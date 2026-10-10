<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PromoCodeController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $this->authorizeEvent($event);

        $data = $request->validate([
            'code' => ['required', 'string', 'max:80', 'regex:/^[A-Za-z0-9-]+$/'],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'integer', 'min:1', 'max:1000000'],
            'usage_limit' => ['nullable', 'integer', 'min:1', 'max:50000'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active' => ['boolean'],
        ]);

        $data['code'] = strtoupper(trim((string) $data['code']));
        $data['event_id'] = $event->id;
        $data['is_active'] = (bool) ($request->boolean('is_active', true));

        $promoCode = PromoCode::firstOrCreate([
            'event_id' => $event->id,
            'code' => $data['code'],
        ], [
            'type' => $data['type'],
            'value' => $data['value'],
            'usage_limit' => $data['usage_limit'] ?? null,
            'used_count' => 0,
            'starts_at' => $data['starts_at'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'is_active' => $data['is_active'],
        ]);

        $promoCode->update([
            'type' => $data['type'],
            'value' => $data['value'],
            'usage_limit' => $data['usage_limit'] ?? null,
            'starts_at' => $data['starts_at'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'is_active' => $data['is_active'],
        ]);

        return Redirect::route('organizer.events.show', $event)->with('status', 'Code promo enregistré.');
    }

    public function destroy(Event $event, PromoCode $promoCode)
    {
        $this->authorizeEvent($event);

        abort_unless($promoCode->event_id === $event->id, 404);

        $promoCode->delete();

        return Redirect::route('organizer.events.show', $event)->with('status', 'Code promo supprimé.');
    }

    private function authorizeEvent(Event $event): void
    {
        $user = auth()->user();

        abort_unless($user && ($event->user_id === $user->id || $user->isAdmin() || $user->canAccessEvent($event)), 403);
    }
}
