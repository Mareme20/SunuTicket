<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'reference', 'event_id', 'buyer_name', 'buyer_phone', 'buyer_email', 'ticket_type',
        'promo_code', 'discount_amount', 'quantity', 'unit_price', 'subtotal', 'commission',
        'total', 'gateway_fee', 'admin_net', 'status', 'paydunya_token', 'paid_at',
    ];

    protected function casts(): array
    {
        return ['paid_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            $order->reference ??= (string) Str::uuid();
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function statusLabel(): string
    {
        return ['pending' => 'En attente', 'paid' => 'Payé', 'failed' => 'Échoué'][$this->status] ?? $this->status;
    }
}
