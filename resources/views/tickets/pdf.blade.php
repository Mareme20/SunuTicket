<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<style>
    @page { margin: 24px; }
    body { font-family: 'DejaVu Sans', sans-serif; color: #0f172a; font-size: 12px; line-height: 1.5; margin: 0; padding: 0; background: #ffffff; }
    .page { page-break-after: always; border: 2px solid #141e4c; border-radius: 12px; overflow: hidden; margin-bottom: 24px; }
    .page:last-child { page-break-after: auto; }
    .band { background: #141e4c; color: #ffffff; padding: 16px 24px; font-size: 16px; font-weight: bold; border-bottom: 3px solid #179b9b; }
    .band-table { width: 100%; }
    .band-title { color: #ffffff; font-size: 16px; font-weight: bold; }
    .band-pill { background: #179b9b; color: #ffffff; padding: 4px 10px; border-radius: 20px; font-size: 11px; text-align: right; }
    .body { padding: 24px; }
    h1 { font-size: 22px; color: #0a1026; margin: 0 0 16px; line-height: 1.2; font-weight: bold; }
    td { vertical-align: top; }
    .label { color: #64748b; font-size: 10px; text-transform: uppercase; font-weight: bold; margin-top: 10px; letter-spacing: 0.5px; }
    .val { font-size: 14px; font-weight: bold; color: #0f172a; margin-top: 2px; }
    .qr { text-align: center; padding-left: 20px; }
    .qr-box { background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px; display: inline-block; }
    .code { font-family: monospace; font-size: 15px; font-weight: bold; letter-spacing: 2px; color: #141e4c; margin-top: 8px; }
    .note { margin-top: 24px; padding-top: 14px; border-top: 1px dashed #cbd5e1; color: #64748b; font-size: 10px; line-height: 1.4; }
</style>
</head>
<body>
@foreach($order->tickets as $ticket)
    <div class="page">
        <div class="band">
            <table class="band-table" width="100%">
                <tr>
                    <td class="band-title">SunuTicket — Pass Officiel</td>
                    <td align="right">
                        <span class="band-pill">Billet {{ $loop->iteration }} sur {{ $order->tickets->count() }}</span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="body">
            <table width="100%">
                <tr>
                    <td>
                        <h1>{{ $order->event->title }}</h1>
                        <div class="label">Date & Heure</div>
                        <div class="val">{{ $order->event->starts_at->translatedFormat('l j F Y \à H\hi') }}</div>

                        <div class="label">Lieu & Ville</div>
                        <div class="val">{{ $order->event->venue }}, {{ $order->event->city }}</div>

                        <div class="label">Titulaire du Billet</div>
                        <div class="val" style="color:#138282;">{{ $ticket->holder_name }}</div>

                        <div class="label">Référence Commande</div>
                        <div class="val" style="font-family:monospace;font-size:12px;">{{ strtoupper(substr($order->reference, 0, 8)) }}</div>
                    </td>
                    <td class="qr" width="220" align="center">
                        <div class="qr-box">
                            <img src="{{ \App\Services\TicketQr::dataUri($ticket, 300) }}" width="190" height="190" alt="Code QR">
                        </div>
                        <div class="code">{{ $ticket->shortCode() }}</div>
                        <div style="font-size:10px;color:#138282;font-weight:bold;margin-top:4px;">CODE UNIQUE VÉRIFIÉ</div>
                    </td>
                </tr>
            </table>
            <div class="note">
                <strong>Consignes d'accès :</strong> Ce billet électronique est strictement personnel et valable pour une seule entrée. Ne partagez jamais la photo de ce code QR. Seule la première personne présentant ce billet au point de contrôle sera admise.
            </div>
        </div>
    </div>
@endforeach
</body>
</html>
