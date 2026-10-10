<?php $__env->startSection('title', $event->title.' · Gestion'); ?>

<?php $__env->startSection('content'); ?>

<nav class="breadcrumbs" aria-label="Fil d'Ariane">
    <a href="<?php echo e(route('home')); ?>">Accueil</a>
    <span>/</span>
    <a href="<?php echo e(route('organizer.dashboard')); ?>">Tableau de bord</a>
    <span>/</span>
    <span style="color:var(--text-main);font-weight:600;"><?php echo e(Str::limit($event->title, 35)); ?></span>
</nav>

<div class="dashboard-header">
    <div>
        <h1 style="font-size:2rem;color:var(--navy-950);"><?php echo e($event->title); ?></h1>
        <p style="color:var(--text-muted);font-size:0.95rem;margin:4px 0 0;">
            📅 <?php echo e($event->starts_at->translatedFormat('l j F Y \à H\hi')); ?> · 📍 <?php echo e($event->venue); ?>, <?php echo e($event->city); ?>

        </p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <a class="btn btn-ghost" href="<?php echo e(route('organizer.events.export', $event)); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 3v12"></path>
                <path d="M7 18l5 5 5-5"></path>
                <path d="M5 21h14"></path>
            </svg>
            <span>Exporter CSV</span>
        </a>
        <a class="btn btn-primary" href="<?php echo e(route('organizer.scan', $event)); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
            </svg>
            <span>Scanner les billets</span>
        </a>
        <a class="btn btn-ghost" href="<?php echo e(route('events.show', $event)); ?>" target="_blank">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                <polyline points="15 3 21 3 21 9"></polyline>
                <line x1="10" y1="14" x2="21" y2="3"></line>
            </svg>
            <span>Page publique</span>
        </a>
        <a class="btn btn-ghost" href="<?php echo e(route('organizer.events.edit', $event)); ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 20h9"></path>
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
            </svg>
            <span>Modifier</span>
        </a>
    </div>
</div>

<!-- Statistiques Clés de l'événement -->
<dl class="stat-grid">
    <div class="stat-card">
        <div class="stat-info">
            <dt class="stat-label">Billets vendus</dt>
            <dd class="stat-value"><?php echo e($stats['sold']); ?> <span style="font-size:1.1rem;color:var(--text-muted);font-weight:600;">/ <?php echo e($event->capacity); ?></span></dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                <path d="M7 7h.01M7 12h.01M7 17h.01M12 7h.01M12 12h.01M12 17h.01M17 7h.01M17 12h.01M17 17h.01"></path>
            </svg>
        </div>
    </div>

    <div class="stat-card featured">
        <div class="stat-info">
            <dt class="stat-label">Recettes encaissées</dt>
            <dd class="stat-value"><?php echo e(fcfa($stats['revenue'])); ?></dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"></line>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <dt class="stat-label">Entrées scannées</dt>
            <dd class="stat-value"><?php echo e($stats['entered']); ?></dd>
        </div>
        <div class="stat-icon" style="background:var(--green-bg);color:#065f46;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <dt class="stat-label">Places restantes</dt>
            <dd class="stat-value"><?php echo e($stats['remaining']); ?></dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
    </div>
</dl>

<div class="section-header" style="margin-top:40px;">
    <div>
        <h2>Codes promo</h2>
        <p class="section-subtitle">Créez des remises événementielles simples et visibles pour vos acheteurs.</p>
    </div>
</div>

<div class="card-surface" style="padding:22px; margin-bottom:28px;">
    <form method="post" action="<?php echo e(route('organizer.promo-codes.store', $event)); ?>" class="grid grid-2" style="gap:16px; align-items:end;">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="promo_code">Code promo</label>
            <input id="promo_code" class="form-control" name="code" value="<?php echo e(old('code')); ?>" placeholder="TERANGA10" maxlength="80" required>
        </div>

        <div class="form-group">
            <label for="promo_type">Type</label>
            <select id="promo_type" class="form-control" name="type" required>
                <option value="percent">Pourcentage</option>
                <option value="fixed">Montant fixe</option>
            </select>
        </div>

        <div class="form-group">
            <label for="promo_value">Valeur</label>
            <input id="promo_value" class="form-control" type="number" name="value" value="<?php echo e(old('value', 10)); ?>" min="1" max="1000000" required>
        </div>

        <div class="form-group">
            <label for="promo_limit">Limite d’usage</label>
            <input id="promo_limit" class="form-control" type="number" name="usage_limit" value="<?php echo e(old('usage_limit')); ?>" min="1" placeholder="Illimité">
        </div>

        <div class="form-group">
            <label for="promo_starts">Début</label>
            <input id="promo_starts" class="form-control" type="datetime-local" name="starts_at" value="<?php echo e(old('starts_at')); ?>">
        </div>

        <div class="form-group">
            <label for="promo_expires">Fin</label>
            <input id="promo_expires" class="form-control" type="datetime-local" name="expires_at" value="<?php echo e(old('expires_at')); ?>">
        </div>

        <div class="form-group" style="display:flex;align-items:center;gap:10px; padding-top:20px;">
            <input id="promo_active" type="checkbox" name="is_active" value="1" checked>
            <label for="promo_active" style="margin:0;">Actif</label>
        </div>

        <div style="display:flex; justify-content:flex-end; align-items:flex-end;">
            <button type="submit" class="btn btn-primary">Ajouter le code</button>
        </div>
    </form>
</div>

<?php if($event->promoCodes()->exists()): ?>
    <div class="table-card" style="margin-bottom:28px;">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Type</th>
                        <th>Valeur</th>
                        <th>Utilisation</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $event->promoCodes()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($promo->code); ?></strong></td>
                            <td><?php echo e($promo->type === 'percent' ? 'Pourcentage' : 'Fixe'); ?></td>
                            <td><?php echo e($promo->label()); ?></td>
                            <td><?php echo e($promo->used_count); ?>/<?php echo e($promo->usage_limit ?? '∞'); ?></td>
                            <td><?php echo e($promo->starts_at?->format('d/m/Y H:i') ?? '—'); ?></td>
                            <td><?php echo e($promo->expires_at?->format('d/m/Y H:i') ?? '—'); ?></td>
                            <td>
                                <?php if($promo->is_active && $promo->isUsable()): ?>
                                    <span class="badge-pill paid">Actif</span>
                                <?php else: ?>
                                    <span class="badge-pill pending">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="<?php echo e(route('organizer.promo-codes.destroy', [$event, $promo])); ?>" onsubmit="return confirm('Supprimer ce code promo ?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-ghost" style="padding:8px 12px;">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Liste des Acheteurs -->
<div class="section-header" style="margin-top:40px;">
    <div>
        <h2>Participants & Commandes</h2>
        <p class="section-subtitle">Liste des personnes ayant acheté des billets pour cet événement.</p>
    </div>
</div>

<?php if($orders->isEmpty()): ?>
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <h3>Aucune vente enregistrée pour le moment</h3>
        <p>Partagez le lien de votre billetterie sur vos réseaux sociaux pour lancer les ventes :</p>
        <div style="display:inline-flex;align-items:center;gap:10px;background:var(--bg-surface);padding:8px 16px;border-radius:var(--radius-md);border:1px solid var(--border);">
            <a href="<?php echo e(route('events.show', $event)); ?>" target="_blank" style="font-weight:600;word-break:break-all;">
                <?php echo e(route('events.show', $event)); ?>

            </a>
        </div>
    </div>
<?php else: ?>
    <div class="table-card">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Billets</th>
                        <th>Entrées validées</th>
                        <th>Montant</th>
                        <th>Date d'achat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <strong style="color:var(--navy-950);"><?php echo e($order->buyer_name); ?></strong>
                            </td>
                            <td><?php echo e(\App\Support\Phone::pretty($order->buyer_phone)); ?></td>
                            <td><?php echo e($order->buyer_email); ?></td>
                            <td>
                                <span class="badge-pill" style="background:var(--teal-50);color:var(--teal-700);">
                                    <?php echo e($order->quantity); ?> place<?php echo e($order->quantity > 1 ? 's' : ''); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($order->used_count == $order->quantity): ?>
                                    <span class="badge-pill paid">Tous entrés (<?php echo e($order->used_count); ?>/<?php echo e($order->quantity); ?>)</span>
                                <?php elseif($order->used_count > 0): ?>
                                    <span class="badge-pill pending"><?php echo e($order->used_count); ?> / <?php echo e($order->quantity); ?></span>
                                <?php else: ?>
                                    <span style="color:var(--text-muted);font-size:0.85rem;">0 / <?php echo e($order->quantity); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo e(fcfa($order->subtotal)); ?></strong></td>
                            <td style="color:var(--text-muted);"><?php echo e($order->paid_at?->translatedFormat('j M Y, H\hi')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-wrap">
        <?php echo e($orders->links('pagination.simple')); ?>

    </div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/user/Téléchargements/sunuticket/resources/views/organizer/events/show.blade.php ENDPATH**/ ?>