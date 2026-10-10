<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'venue', 'city', 'category',
        'starts_at', 'price', 'ticket_types', 'capacity', 'poster_path', 'is_published',
    ];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'is_published' => 'boolean', 'ticket_types' => 'array'];
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function promoCodes(): HasMany
    {
        return $this->hasMany(PromoCode::class);
    }

    /** Liste des tarifs de l'événement, avec un tarif standard par défaut pour compatibilité. */
    public function ticketTypes(): array
    {
        $types = is_array($this->ticket_types) ? $this->ticket_types : [];

        if ($types === []) {
            return [[
                'name' => 'Standard',
                'price' => (int) ($this->price ?? 0),
            ]];
        }

        return array_values(array_map(function ($type) {
            $normalized = is_array($type) ? $type : [];

            return [
                'name' => (string) ($normalized['name'] ?? 'Standard'),
                'price' => (int) ($normalized['price'] ?? $this->price ?? 0),
                'capacity' => array_key_exists('capacity', $normalized) ? (int) $normalized['capacity'] : null,
            ];
        }, $types));
    }

    public function ticketType(?string $name): ?array
    {
        if ($name === null || trim($name) === '') {
            return $this->ticketTypes()[0] ?? null;
        }

        foreach ($this->ticketTypes() as $ticketType) {
            if (strtolower((string) $ticketType['name']) === strtolower(trim($name))) {
                return $ticketType;
            }
        }

        return null;
    }

    public function priceFor(?string $name = null): int
    {
        $ticketType = $this->ticketType($name);

        return $ticketType['price'] ?? (int) ($this->price ?? 0);
    }

    /** Événements publiés et pas encore commencés. */
    public function scopeOnSale(Builder $query): Builder
    {
        return $query->where('is_published', true)->where('starts_at', '>', now());
    }

    /** Billets déjà émis (payés). Utilise withCount('tickets') s'il a été chargé. */
    public function soldCount(): int
    {
        return (int) ($this->tickets_count ?? $this->tickets()->count());
    }

    /** Places bloquées par des paiements en cours (non expirés). */
    public function heldCount(): int
    {
        return (int) $this->orders()
            ->where('status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(config('sunuticket.hold_minutes')))
            ->sum('quantity');
    }

    public function remaining(): int
    {
        return max(0, $this->capacity - $this->soldCount() - $this->heldCount());
    }

    public function posterUrl(): ?string
    {
        return $this->poster_path ? '/storage/'.ltrim($this->poster_path, '/') : null;
    }
}
