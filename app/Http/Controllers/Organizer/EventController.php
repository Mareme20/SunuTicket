<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function create()
    {
        $event = new Event(['capacity' => 100, 'price' => 2000, 'category' => 'concert', 'is_published' => true]);

        return view('organizer.events.form', compact('event'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(creating: true));

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }
        unset($data['poster']);

        if (isset($data['ticket_types'])) {
            $data['ticket_types'] = $this->normalizeTicketTypes($data['ticket_types']);
        }

        $event = Event::create($data + ['user_id' => $request->user()->id]);

        return redirect()->route('organizer.events.show', $event)->with('status', 'Événement créé et publié.');
    }

    public function show(Event $event)
    {
        $this->authorizeEvent($event);

        $orders = $event->orders()
            ->where('status', 'paid')
            ->withCount(['tickets as used_count' => fn ($q) => $q->where('status', 'used')])
            ->latest('paid_at')
            ->simplePaginate(25);

        $stats = [
            'sold' => $event->soldCount(),
            'remaining' => $event->remaining(),
            'revenue' => (int) $event->orders()->where('status', 'paid')->sum('subtotal'),
            'entered' => $event->tickets()->where('status', 'used')->count(),
        ];

        return view('organizer.events.show', compact('event', 'orders', 'stats'));
    }

    public function edit(Event $event)
    {
        $this->authorizeEvent($event);

        return view('organizer.events.form', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $data = $request->validate($this->rules(creating: false, sold: $event->soldCount()));

        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }
        unset($data['poster']);

        if (array_key_exists('ticket_types', $data)) {
            $data['ticket_types'] = $this->normalizeTicketTypes($data['ticket_types'] ?? []);
        }

        $event->update($data);

        return redirect()->route('organizer.events.show', $event)->with('status', 'Événement mis à jour.');
    }

    public function exportCsv(Event $event)
    {
        $this->authorizeEvent($event);

        $orders = $event->orders()->where('status', 'paid')->latest('paid_at')->get();

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, ['Nom', 'Téléphone', 'Email', 'Quantité', 'Montant', 'Date achat', 'Référence']);

        foreach ($orders as $order) {
            fputcsv($csv, [
                $order->buyer_name,
                $order->buyer_phone,
                $order->buyer_email,
                $order->quantity,
                $order->subtotal,
                $order->paid_at?->format('Y-m-d H:i:s') ?? '',
                $order->reference,
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return response($content, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="participants-'.strtolower(str_replace(' ', '-', $event->title)).'.csv"');
    }

    private function authorizeEvent(Event $event): void
    {
        $user = auth()->user();

        abort_unless($user && ($event->user_id === $user->id || $user->isAdmin() || $user->canAccessEvent($event)), 403);
    }

    private function normalizeTicketTypes(mixed $ticketTypes): array
    {
        if (is_string($ticketTypes)) {
            $decoded = json_decode($ticketTypes, true);
            $ticketTypes = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($ticketTypes) || $ticketTypes === []) {
            return [];
        }

        return array_values(array_filter(array_map(function ($ticketType) {
            if (! is_array($ticketType)) {
                return null;
            }

            $name = trim((string) ($ticketType['name'] ?? ''));
            $price = (int) ($ticketType['price'] ?? 0);

            if ($name === '' || $price <= 0) {
                return null;
            }

            return ['name' => $name, 'price' => $price];
        }, $ticketTypes)));
    }

    private function rules(bool $creating, int $sold = 0): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:3000'],
            'venue' => ['required', 'string', 'max:150'],
            'city' => ['required', 'string', 'max:80'],
            'starts_at' => array_filter(['required', 'date', $creating ? 'after:now' : null]),
            'category' => ['nullable', 'string', 'max:40'],
            'price' => ['required', 'integer', 'min:0', 'max:1000000'],
            'ticket_types' => ['nullable', 'string'],
            'capacity' => ['required', 'integer', 'min:'.max(1, $sold), 'max:100000'],
            'poster' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'is_published' => ['boolean'],
        ];
    }
}
