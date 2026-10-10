<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoCode extends Model
{
    protected $fillable = [
        'event_id',
        'code',
        'type',
        'value',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public static function resolveFor(?Event $event, string $code): ?self
    {
        $normalized = strtoupper(trim((string) $code));

        if ($normalized === '') {
            return null;
        }

        $query = static::query()
            ->whereRaw('UPPER(code) = ?', [$normalized])
            ->where('is_active', true);

        if ($event) {
            $query->where(function ($sub) use ($event) {
                $sub->whereNull('event_id')->orWhere('event_id', $event->id);
            });
        }

        $promo = $query->first();

        if (! $promo || ! $promo->isUsable()) {
            return null;
        }

        return $promo;
    }

    public function isUsable(): bool
    {
        $now = now();

        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->lt($now)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function availableUses(): int
    {
        if ($this->usage_limit === null) {
            return PHP_INT_MAX;
        }

        return max(0, $this->usage_limit - (int) $this->used_count);
    }

    public function discountFor(int $amount): int
    {
        $amount = max(0, $amount);

        if ($this->type === 'percent') {
            return (int) round($amount * $this->value / 100);
        }

        return (int) min((float) $this->value, (float) $amount);
    }

    public function label(): string
    {
        return $this->type === 'percent'
            ? $this->value.'%'
            : $this->value.' FCFA';
    }
}
