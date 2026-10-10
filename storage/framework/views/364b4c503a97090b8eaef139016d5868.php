<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
</head>
<body style="margin:0;padding:32px 16px;background:#f8fafc;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;color:#0f172a;line-height:1.6;">
    <div style="max-width:540px;margin:0 auto;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 16px rgba(15,23,42,0.08);border:1px solid #e2e8f0;">
        <!-- En-tête officiel SunuTicket -->
        <div style="background:#141e4c;padding:24px 28px;text-align:center;border-bottom:3px solid #179b9b;">
            <h2 style="color:#ffffff;margin:0;font-size:22px;letter-spacing:-0.5px;">SunuTicket Sénégal</h2>
            <p style="color:#ccfbf1;margin:4px 0 0;font-size:12px;text-transform:uppercase;letter-spacing:1px;font-weight:600;">Billetterie Officielle</p>
        </div>

        <div style="padding:32px 28px;">
            <h1 style="margin:0 0 12px;font-size:20px;color:#0a1026;line-height:1.3;">
                Merci <?php echo e($order->buyer_name); ?>, vos billets sont prêts !
            </h1>
            <p style="margin:0 0 20px;color:#64748b;font-size:14px;">
                Votre paiement a été validé avec succès. Vous trouverez vos billets avec code QR en pièce jointe (PDF) ainsi qu'en accès direct sur votre téléphone.
            </p>

            <!-- Récapitulatif de l'événement -->
            <div style="background:#f1f5f9;border-radius:12px;padding:20px;margin-bottom:24px;border:1px solid #e2e8f0;">
                <h3 style="margin:0 0 10px;color:#141e4c;font-size:16px;"><?php echo e($order->event->title); ?></h3>
                <p style="margin:0 0 4px;font-size:14px;color:#334155;">
                    📅 <strong><?php echo e($order->event->starts_at->translatedFormat('l j F Y \à H\hi')); ?></strong>
                </p>
                <p style="margin:0 0 4px;font-size:14px;color:#334155;">
                    📍 <?php echo e($order->event->venue); ?>, <?php echo e($order->event->city); ?>

                </p>
                <p style="margin:0;font-size:14px;color:#334155;">
                    🎟️ <strong><?php echo e($order->quantity); ?> place(s)</strong> · Montant payé : <strong><?php echo e(fcfa($order->total)); ?></strong>
                </p>
            </div>

            <!-- Bouton d'accès au billet -->
            <div style="text-align:center;margin:28px 0;">
                <a href="<?php echo e(route('tickets.show', $order)); ?>" style="display:inline-block;background:#179b9b;color:#ffffff;text-decoration:none;padding:14px 28px;border-radius:10px;font-weight:bold;font-size:15px;box-shadow:0 4px 12px rgba(23,155,155,0.35);">
                    Afficher mes billets électroniques
                </a>
            </div>

            <p style="margin:24px 0 0;padding-top:16px;border-top:1px dashed #e2e8f0;color:#64748b;font-size:12px;line-height:1.5;">
                <strong>Consignes :</strong> Chaque code QR n’est valable qu’une seule fois. Ne partagez pas ce lien ou votre billet en photo. Présentez l'écran de votre téléphone directement au point de contrôle à l’entrée.
            </p>
        </div>

        <div style="background:#f8fafc;padding:16px 28px;text-align:center;font-size:11px;color:#94a3b8;border-top:1px solid #e2e8f0;">
            &copy; <?php echo e(date('Y')); ?> SunuTicket · Paiements sécurisés Wave & Orange Money par PayDunya
        </div>
    </div>
</body>
</html>
<?php /**PATH /home/user/Téléchargements/sunuticket/resources/views/emails/tickets.blade.php ENDPATH**/ ?>