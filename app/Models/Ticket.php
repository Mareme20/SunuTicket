<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = ['order_id', 'event_id', 'code', 'holder_name', 'status', 'used_at', 'used_by'];

    protected function casts(): array
    {
        return ['used_at' => 'datetime'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /** Les 8 premiers caractères du code, affichés sous le QR pour une saisie manuelle de secours. */
    public function shortCode(): string
    {
        return strtoupper(substr($this->code, 0, 8));
    }
}
