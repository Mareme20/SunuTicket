<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

final class TicketPdf
{
    public static function make(Order $order)
    {
        $order->loadMissing('event', 'tickets');

        return Pdf::loadView('tickets.pdf', ['order' => $order])->setPaper('a4');
    }
}
