<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use App\Services\TicketQr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function page(Event $event)
    {
        $this->authorizeEvent($event);

        return view('organizer.scan', compact('event'));
    }

    /** Retourne la liste des billets valides pour un scan hors ligne local. */
    public function data(Event $event): JsonResponse
    {
        $this->authorizeEvent($event);

        $tickets = $event->tickets()
            ->where('status', 'valid')
            ->orderBy('holder_name')
            ->get(['id', 'code', 'holder_name'])
            ->map(fn ($ticket) => [
                'id' => $ticket->id,
                'code' => $ticket->code,
                'holder_name' => $ticket->holder_name,
                'short_code' => $ticket->shortCode(),
            ])
            ->values();

        return response()->json([
            'event' => ['id' => $event->id, 'title' => $event->title],
            'tickets' => $tickets,
        ]);
    }

    /** Vérifie un billet. La validation est atomique : un même billet ne peut être accepté qu'une seule fois. */
    public function check(Request $request, Event $event): JsonResponse
    {
        $this->authorizeEvent($event);

        $input = trim((string) $request->input('payload', ''));

        if (preg_match('/^[A-Za-z0-9]{8}$/', $input)) {
            // Saisie manuelle du code court imprimé sous le QR (si l'écran du client est abîmé).
            $matches = Ticket::where('event_id', $event->id)->where('code', 'like', strtolower($input).'%')->limit(2)->get();
            $ticket = $matches->count() === 1 ? $matches->first() : null;
        } else {
            $code = TicketQr::parse($input);
            $ticket = $code ? Ticket::where('code', $code)->first() : null;
        }

        if (! $ticket) {
            return $this->verdict(false, 'Billet invalide', 'Ce code n’est pas un billet SunuTicket authentique.');
        }

        if ($ticket->event_id !== $event->id) {
            return $this->verdict(false, 'Autre événement', 'Ce billet est pour : '.$ticket->event->title);
        }

        $updated = Ticket::whereKey($ticket->id)->where('status', 'valid')->update([
            'status' => 'used',
            'used_at' => now(),
            'used_by' => $request->user()->id,
        ]);

        if (! $updated) {
            $ticket->refresh();

            return $this->verdict(false, 'Déjà utilisé', $ticket->holder_name.' — scanné le '.$ticket->used_at->translatedFormat('j F à H\hi'));
        }

        return $this->verdict(true, 'Billet valide', $ticket->holder_name.' — billet '.$ticket->shortCode());
    }

    private function verdict(bool $ok, string $title, string $detail): JsonResponse
    {
        return response()->json(compact('ok', 'title', 'detail'));
    }

    private function authorizeEvent(Event $event): void
    {
        $user = auth()->user();

        abort_unless($user && ($event->user_id === $user->id || $user->isAdmin() || $user->canAccessEvent($event)), 403);
    }
}
