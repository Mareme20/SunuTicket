<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Order;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('orders:expire', function () {
    $expired = Order::where('status', 'pending')
        ->where('created_at', '<', now()->subMinutes(config('sunuticket.hold_minutes')))
        ->update(['status' => 'expired']);

    $this->info($expired.' commande(s) en attente expirée(s).');
})->purpose('Expire pending orders after the configured hold window');

Schedule::command('orders:expire')->everyMinute();
