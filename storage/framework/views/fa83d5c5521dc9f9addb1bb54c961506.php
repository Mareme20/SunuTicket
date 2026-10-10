<?php $__env->startSection('title', $event->title.' · Billetterie'); ?>

<?php $__env->startSection('content'); ?>

<nav class="breadcrumbs" aria-label="Fil d'Ariane">
    <a href="<?php echo e(route('home')); ?>">Accueil</a>
    <span>/</span>
    <a href="<?php echo e(route('home')); ?>">Événements</a>
    <span>/</span>
    <span style="color:var(--text-main);font-weight:600;"><?php echo e(Str::limit($event->title, 40)); ?></span>
</nav>

<article class="detail-layout">
    <!-- Colonne Principale -->
    <div class="detail-main">
        <?php if($event->posterUrl()): ?>
            <div class="detail-poster-wrap">
                <img class="detail-poster" src="<?php echo e($event->posterUrl()); ?>" alt="Affiche : <?php echo e($event->title); ?>">
            </div>
        <?php else: ?>
            <div class="detail-poster-wrap" style="aspect-ratio: 16/9; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg, var(--navy-900), var(--teal-800)); color:#fff;">
                <div style="text-align:center;">
                    <div style="font-family:var(--font-heading);font-size:4rem;font-weight:800;color:var(--teal-300);">
                        <?php echo e(mb_strtoupper(mb_substr($event->title, 0, 1))); ?>

                    </div>
                    <div style="font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.7);font-size:0.85rem;">
                        SunuTicket
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="detail-body">
            <div class="detail-badge-row">
                <span class="detail-badge">
                    <span>📍 <?php echo e($event->city); ?></span>
                </span>
                <?php if($onSale): ?>
                    <span class="detail-badge" style="background:var(--teal-50);color:var(--teal-700);border-color:var(--teal-100);">
                        <span><?php echo e($remaining); ?> place<?php echo e($remaining > 1 ? 's' : ''); ?> restante<?php echo e($remaining > 1 ? 's' : ''); ?></span>
                    </span>
                <?php endif; ?>
            </div>

            <h1 class="detail-title"><?php echo e($event->title); ?></h1>

            <!-- Métadonnées pratiques -->
            <ul class="detail-meta-list">
                <li class="detail-meta-item">
                    <div class="detail-meta-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="detail-meta-text">
                        <span class="detail-meta-label">Date & Heure</span>
                        <span class="detail-meta-val"><?php echo e($event->starts_at->translatedFormat('l j F Y \à H\hi')); ?></span>
                    </div>
                </li>

                <li class="detail-meta-item">
                    <div class="detail-meta-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="detail-meta-text">
                        <span class="detail-meta-label">Lieu de l'événement</span>
                        <span class="detail-meta-val"><?php echo e($event->venue); ?>, <?php echo e($event->city); ?></span>
                    </div>
                </li>

                <?php if($event->organizer): ?>
                    <li class="detail-meta-item">
                        <div class="detail-meta-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="detail-meta-text">
                            <span class="detail-meta-label">Organisé par</span>
                            <a href="<?php echo e(route('organizers.show', $event->organizer)); ?>" class="detail-meta-val" style="text-decoration:none;color:var(--navy-950);font-weight:600;">
                                <?php echo e($event->organizer->name); ?>

                            </a>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Bouton Partager sur WhatsApp pour motiver les amis -->
            <?php
                $shareText = rawurlencode("🔥 Tu viens avec moi ? Regarde cet événement : " . $event->title . " (" . $event->starts_at->translatedFormat('j M') . " à " . $event->venue . ") ! Prends ton pass ici : " . url()->current());
            ?>
            <div style="background:linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);border:1.5px solid #bbf7d0;border-radius:var(--radius-lg);padding:18px 22px;display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:30px;flex-wrap:wrap;">
                <div>
                    <strong style="color:#166534;font-size:1rem;display:block;margin-bottom:2px;">Vous sortez à plusieurs ?</strong>
                    <span style="color:#15803d;font-size:0.88rem;">Envoyez le lien à vos amis sur WhatsApp pour qu'ils réservent leur place.</span>
                </div>
                <a class="btn btn-whatsapp" href="https://api.whatsapp.com/send?text=<?php echo e($shareText); ?>" target="_blank">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.588-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                    </svg>
                    <span>Partager sur WhatsApp</span>
                </a>
            </div>

            <h3 class="detail-section-title">Présentation de l'événement</h3>
            <?php if($event->description): ?>
                <div class="detail-prose"><?php echo nl2br(e($event->description)); ?></div>
            <?php else: ?>
                <p style="color:var(--text-muted);font-style:italic;">Retrouvez toutes les informations sur place ou auprès de l'organisateur.</p>
            <?php endif; ?>

            <!-- Mode d'emploi concret du jour J -->
            <div style="margin-top:36px;border-top:1px solid var(--border);padding-top:24px;">
                <h4 style="color:var(--navy-950);font-size:1.1rem;margin-bottom:14px;">💡 Comment ça se passe pour entrer ?</h4>
                <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:16px;">
                    <div style="background:var(--bg-surface);padding:16px;border-radius:var(--radius-md);border:1px solid var(--border);">
                        <strong style="color:var(--teal-700);display:block;margin-bottom:4px;">1. Billet instantané</strong>
                        <span style="font-size:0.86rem;color:var(--text-muted);">Dès votre paiement Wave/OM validé, votre billet avec code QR s'affiche sur votre écran et arrive dans vos emails.</span>
                    </div>
                    <div style="background:var(--bg-surface);padding:16px;border-radius:var(--radius-md);border:1px solid var(--border);">
                        <strong style="color:var(--teal-700);display:block;margin-bottom:4px;">2. Zéro papier</strong>
                        <span style="font-size:0.86rem;color:var(--text-muted);">Pas besoin d'imprimer : prenez une capture d'écran ou gardez l'email ouvert sur votre smartphone.</span>
                    </div>
                    <div style="background:var(--bg-surface);padding:16px;border-radius:var(--radius-md);border:1px solid var(--border);">
                        <strong style="color:var(--teal-700);display:block;margin-bottom:4px;">3. Entrée directe</strong>
                        <span style="font-size:0.86rem;color:var(--text-muted);">L'équipe à la porte scanne votre QR code en 1 seconde chrono, et vous profitez de votre soirée !</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Encart d'achat (Sticky Widget) -->
    <aside class="checkout-box">
        <?php if($onSale): ?>
            <div class="checkout-header">
                <h2>Prendre mes places</h2>
                <p>Paiement instantané Wave ou Orange Money</p>
            </div>

            <form id="buy" class="checkout-form" method="post" action="<?php echo e(route('checkout.store', $event)); ?>"
                  data-percent="<?php echo e(config('sunuticket.commission_percent')); ?>"
                  data-default-price="<?php echo e($event->price); ?>">
                <?php echo csrf_field(); ?>

                <?php $ticketTypes = $event->ticketTypes(); ?>

                <?php if(count($ticketTypes) > 1): ?>
                    <div class="form-group">
                        <label for="ticket_type">Choisir votre tarif</label>
                        <select id="ticket_type" class="form-control" name="ticket_type">
                            <?php $__currentLoopData = $ticketTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticketType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ticketType['name']); ?>" data-price="<?php echo e($ticketType['price']); ?>" <?php if(old('ticket_type', $ticketTypes[0]['name']) === $ticketType['name']): echo 'selected'; endif; ?>>
                                    <?php echo e($ticketType['name']); ?> — <?php echo e(fcfa($ticketType['price'])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['ticket_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="ticket_type" value="<?php echo e($ticketTypes[0]['name'] ?? 'Standard'); ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="buyer_name">Votre prénom et nom</label>
                    <input id="buyer_name" class="form-control" name="buyer_name" value="<?php echo e(old('buyer_name')); ?>" placeholder="Ex : Fatou Ndiaye" autocomplete="name" required>
                    <?php $__errorArgs = ['buyer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="buyer_phone">Numéro de téléphone (Wave ou Orange Money)</label>
                    <input id="buyer_phone" class="form-control" name="buyer_phone" value="<?php echo e(old('buyer_phone')); ?>" type="tel" inputmode="tel" autocomplete="tel" placeholder="Ex : 77 123 45 67" required>
                    <?php $__errorArgs = ['buyer_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="buyer_email">Adresse email (pour recevoir vos billets)</label>
                    <input id="buyer_email" class="form-control" name="buyer_email" value="<?php echo e(old('buyer_email')); ?>" type="email" autocomplete="email" placeholder="fatou@gmail.com" required>
                    <?php $__errorArgs = ['buyer_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="quantity">Nombre de places</label>
                    <select id="quantity" class="form-control" name="quantity" style="font-size:1.05rem;font-weight:700;">
                        <?php $__currentLoopData = range(1, $maxQty); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($n); ?>" <?php if((int) old('quantity', 1) === $n): echo 'selected'; endif; ?>>
                                <?php echo e($n); ?> place<?php echo e($n > 1 ? 's' : ''); ?> (<?php echo e(fcfa(($ticketTypes[0]['price'] ?? $event->price) * $n)); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="promo_code">Code promo (optionnel)</label>
                    <input id="promo_code" class="form-control" name="promo_code" value="<?php echo e(old('promo_code')); ?>" placeholder="Ex : TERANGA10">
                    <?php $__errorArgs = ['promo_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="err"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Récapitulatif simple et transparent -->
                <dl class="checkout-summary">
                    <div class="summary-line">
                        <dt>Tarif places</dt>
                        <dd id="sum-sub"><?php echo e(fcfa($event->price)); ?></dd>
                    </div>
                    <div class="summary-line">
                        <dt>Frais de service (<?php echo e(config('sunuticket.commission_percent')); ?>%)</dt>
                        <dd id="sum-fee">—</dd>
                    </div>
                    <div class="summary-total">
                        <dt>Total à payer</dt>
                        <dd id="sum-total">—</dd>
                    </div>
                </dl>

                <button class="btn btn-primary btn-block btn-lg" style="box-shadow: 0 8px 24px rgba(23,155,155,0.45);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Valider et Payer</span>
                </button>

                <div class="payment-methods">
                    <span class="payment-pill pill-wave">
                        <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#00a4e4;"></span>
                        Wave
                    </span>
                    <span class="payment-pill pill-orange">
                        <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#ff6600;"></span>
                        Orange Money
                    </span>
                </div>

                <p class="checkout-guarantee">
                    <span>🔒 Paiement crypté sécurisé par PayDunya</span>
                </p>
            </form>
        <?php elseif($event->starts_at->isPast()): ?>
            <div class="checkout-header" style="background:var(--navy-950);">
                <h2>Événement terminé</h2>
                <p>La date de cet événement est déjà passée.</p>
            </div>
            <div class="checkout-form" style="text-align:center;padding:32px 24px;">
                <p style="color:var(--text-muted);margin-bottom:20px;">Les ventes sont closes pour cette édition.</p>
                <a class="btn btn-navy btn-block" href="<?php echo e(route('home')); ?>">Voir les prochains événements</a>
            </div>
        <?php else: ?>
            <div class="checkout-header" style="background:linear-gradient(135deg, #991b1b, #ef4444);">
                <h2>Guichet complet</h2>
                <p>Toutes les places ont été réservées !</p>
            </div>
            <div class="checkout-form" style="text-align:center;padding:32px 24px;">
                <p style="color:var(--text-muted);margin-bottom:20px;">Désolé, il n'y a plus de places disponibles pour cet événement.</p>
                <a class="btn btn-navy btn-block" href="<?php echo e(route('home')); ?>">Découvrir d'autres sorties</a>
            </div>
        <?php endif; ?>
    </aside>
</article>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    var f = document.getElementById('buy');
    if (!f) return;
    var pct = +f.dataset.percent;
    var q = f.querySelector('[name=quantity]');
    var typeField = f.querySelector('[name=ticket_type]');
    var defaultPrice = +f.dataset.defaultPrice;

    function fmt(n) { return n.toLocaleString('fr-FR').replace(/[\u202f\u00a0]/g, ' ') + ' FCFA'; }

    function getPrice() {
        if (!typeField) {
            return defaultPrice;
        }

        var selected = typeField.options[typeField.selectedIndex];
        return +(selected && selected.dataset.price ? selected.dataset.price : defaultPrice);
    }

    function update() {
        var price = getPrice();
        var n = +q.value || 1, sub = price * n, fee = Math.round(sub * pct / 100);
        document.getElementById('sum-sub').textContent = fmt(sub);
        document.getElementById('sum-fee').textContent = fmt(fee);
        document.getElementById('sum-total').textContent = fmt(sub + fee);
    }

    q.addEventListener('change', update);
    if (typeField) {
        typeField.addEventListener('change', update);
    }
    update();
})();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/user/Téléchargements/sunuticket/resources/views/events/show.blade.php ENDPATH**/ ?>