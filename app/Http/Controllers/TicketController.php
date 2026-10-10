<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\TicketPdf;
use App\Support\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /** Page des billets de l'acheteur. L'accès se fait par le lien secret (référence UUID) reçu par email. */
    public function show(Order $order)
    {
        abort_unless($order->status === 'paid', 404);
        $order->load('event', 'tickets');

        return view('tickets.show', compact('order'));
    }

    public function lookup()
    {
        return view('tickets.lookup');
    }

    public function searchByPhone(Request $request)
    {
        $phone = Phone::normalize($request->input('phone', ''));
        $orders = [];

        if ($phone !== '' && preg_match(Phone::RULE, $phone)) {
            $orders = Order::where('buyer_phone', $phone)
                ->where('status', 'paid')
                ->with('event', 'tickets')
                ->latest('paid_at')
                ->get();
        }

        return view('tickets.lookup', compact('orders', 'phone'));
    }

    public function calendar(Order $order)
    {
        abort_unless($order->status === 'paid', 404);
        $order->loadMissing('event');

        $event = $order->event;
        $start = $event->starts_at->utc()->format('Ymd\THis\Z');
        $end = $event->starts_at->copy()->addHour()->utc()->format('Ymd\THis\Z');
        $body = "Billet acheté sur SunuTicket\n\n".
            $order->buyer_name.'\n'
            .$event->title.'\n'
            .$event->venue.', '.$event->city;

        $ics = "BEGIN:VCALENDAR\n"
            ."VERSION:2.0\n"
            ."PRODID:-//SunuTicket//FR\n"
            ."BEGIN:VEVENT\n"
            ."UID:".$order->reference."@sunuticket\n"
            ."DTSTAMP:".now()->utc()->format('Ymd\THis\Z')."\n"
            ."DTSTART:{$start}\n"
            ."DTEND:{$end}\n"
            ."SUMMARY:".str_replace(["\r", "\n"], [' ', ' '], $event->title)."\n"
            ."LOCATION:".str_replace(["\r", "\n"], [' ', ' '], $event->venue.', '.$event->city)."\n"
            ."DESCRIPTION:".str_replace(["\r", "\n"], [' ', ' '], $body)."\n"
            ."END:VEVENT\n"
            ."END:VCALENDAR\n";

        return response($ics, 200)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="'.Str::slug($event->title).'.ics"');
    }

    public function pdf(Order $order)
    {
        abort_unless($order->status === 'paid', 404);

        return TicketPdf::make($order)->download('billets-'.Str::slug($order->event->title).'.pdf');
    }
}
