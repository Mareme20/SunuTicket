<?php

namespace App\Services;

use App\Models\Ticket;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

/**
 * Le QR contient "ST1.<code aléatoire>.<signature HMAC>".
 * - la signature (clé APP_KEY) empêche de fabriquer un faux billet ;
 * - le statut en base (valide → utilisé, une seule fois) empêche de réutiliser une copie.
 */
final class TicketQr
{
    public static function sign(string $code): string
    {
        return substr(hash_hmac('sha256', $code, (string) config('app.key')), 0, 16);
    }

    public static function payload(Ticket $ticket): string
    {
        return 'ST1.'.$ticket->code.'.'.self::sign($ticket->code);
    }

    /** Retourne le code du billet si la signature est authentique, sinon null. */
    public static function parse(string $payload): ?string
    {
        $parts = explode('.', trim($payload));

        if (count($parts) !== 3 || $parts[0] !== 'ST1') {
            return null;
        }

        return hash_equals(self::sign($parts[1]), $parts[2]) ? $parts[1] : null;
    }

    public static function svg(Ticket $ticket, int $size = 240): string
    {
        $writer = new Writer(new ImageRenderer(new RendererStyle($size, 2), new SvgImageBackEnd()));

        return $writer->writeString(self::payload($ticket));
    }

    /** SVG prêt à être inséré dans une page HTML. */
    public static function inline(Ticket $ticket, int $size = 240): string
    {
        return preg_replace('/^<\?xml[^>]*\?>\s*/', '', self::svg($ticket, $size));
    }

    /** Pour les <img> (PDF). */
    public static function dataUri(Ticket $ticket, int $size = 300): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode(self::svg($ticket, $size));
    }
}
