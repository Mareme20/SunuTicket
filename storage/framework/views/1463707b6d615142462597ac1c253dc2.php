<?php $__env->startSection('title', 'Tableau de bord Organisateur'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-header">
    <div>
        <h1>Tableau de bord</h1>
        <p style="color:var(--text-muted);font-size:0.95rem;margin:4px 0 0;">
            Bienvenue, <strong><?php echo e(auth()->user()->name); ?></strong>. Suivez vos ventes et gérez vos accès.
        </p>
    </div>
    <a class="btn btn-primary" href="<?php echo e(route('organizer.events.create')); ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        <span>Créer un événement</span>
    </a>
</div>

<!-- Cartes Statistiques KPI -->
<dl class="stat-grid">
    <div class="stat-card">
        <div class="stat-info">
            <dt class="stat-label">Billets vendus</dt>
            <dd class="stat-value"><?php echo e(number_format($totals['tickets'], 0, ',', ' ')); ?></dd>
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
            <dd class="stat-value"><?php echo e(fcfa($totals['revenue'])); ?></dd>
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
            <dt class="stat-label">Événements actifs</dt>
            <dd class="stat-value"><?php echo e($totals['upcoming']); ?></dd>
        </div>
        <div class="stat-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-info">
            <dt class="stat-label">Entrées scannées</dt>
            <dd class="stat-value"><?php echo e(number_format($totals['entered'], 0, ',', ' ')); ?></dd>
        </div>
        <div class="stat-icon" style="background:var(--green-bg);color:#065f46;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
    </div>
</dl>

<?php if($nextEvent || $attentionEvents->isNotEmpty()): ?>
    <div class="ops-grid organizer-ops-grid">
        <?php if($nextEvent): ?>
            <?php
                $nextPct = $nextEvent->capacity ? min(100, round($nextEvent->tickets_count / $nextEvent->capacity * 100)) : 0;
            ?>
            <section class="ops-panel">
                <div class="ops-panel-head">
                    <h2>Prochain événement</h2>
                    <span class="badge-pill paid"><?php echo e($nextEvent->starts_at->diffForHumans()); ?></span>
                </div>
                <div class="organizer-focus">
                    <strong><?php echo e($nextEvent->title); ?></strong>
                    <span><?php echo e($nextEvent->starts_at->translatedFormat('j F Y \à H\hi')); ?> · <?php echo e($nextEvent->city); ?></span>
                    <div class="progress-bar-wrap" title="<?php echo e($nextPct); ?>% des places vendues">
                        <div class="progress-bar-fill" style="width: <?php echo e($nextPct); ?>%;"></div>
                    </div>
                    <span><?php echo e($nextEvent->tickets_count); ?> / <?php echo e($nextEvent->capacity); ?> billets vendus</span>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:12px;">
                        <a class="btn btn-primary btn-sm" href="<?php echo e(route('organizer.scan', $nextEvent)); ?>">Scanner</a>
                        <a class="btn btn-ghost btn-sm" href="<?php echo e(route('organizer.events.show', $nextEvent)); ?>">Voir les ventes</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <section class="ops-panel <?php echo e($attentionEvents->isNotEmpty() ? 'ops-panel-warnings' : ''); ?>">
            <div class="ops-panel-head">
                <h2>À surveiller</h2>
                <span class="badge-pill <?php echo e($attentionEvents->isEmpty() ? 'paid' : 'pending'); ?>"><?php echo e($attentionEvents->count()); ?></span>
            </div>
            <?php $__empty_1 = true; $__currentLoopData = $attentionEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $soldRate = $event->capacity ? round($event->tickets_count / $event->capacity * 100) : 0;
                    $reason = ! $event->is_published
                        ? 'Non publié'
                        : ($soldRate >= 80 ? 'Presque complet' : 'Date proche');
                ?>
                <div class="ops-row">
                    <div>
                        <strong><?php echo e($event->title); ?></strong>
                        <span><?php echo e($reason); ?> · <?php echo e($event->starts_at->translatedFormat('j M H\hi')); ?></span>
                    </div>
                    <time><?php echo e($soldRate); ?>%</time>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="ops-empty">Aucune alerte sur vos prochains événements.</p>
            <?php endif; ?>
        </section>
    </div>
<?php endif; ?>

<div class="section-header" style="margin-top:40px;">
    <div>
        <h2>Mes événements</h2>
        <p class="section-subtitle">Gérez vos billetteries en temps réel et validez les entrées au scanner.</p>
    </div>
</div>

<?php if($events->isEmpty()): ?>
    <div class="empty-state">
        <div class="empty-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        </div>
        <h3>Vous n'avez pas encore d'événement</h3>
        <p>Publiez votre premier concert, soirée ou conférence pour commencer à vendre des billets avec Wave et Orange Money.</p>
        <a class="btn btn-primary" href="<?php echo e(route('organizer.events.create')); ?>">Créer mon premier événement</a>
    </div>
<?php else: ?>
    <div class="org-event-list">
        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $pct = $event->capacity ? min(100, round($event->tickets_count / $event->capacity * 100)) : 0; ?>
            <div class="org-event-item">
                <div class="org-event-info">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
                        <a class="org-event-title" href="<?php echo e(route('organizer.events.show', $event)); ?>">
                            <?php echo e($event->title); ?>

                        </a>
                        <?php if($event->is_published): ?>
                            <span class="badge-pill paid" style="font-size:0.72rem;">En ligne</span>
                        <?php else: ?>
                            <span class="badge-pill pending" style="font-size:0.72rem;">Non publié</span>
                        <?php endif; ?>
                    </div>

                    <div class="org-event-meta">
                        <span><?php echo e($event->starts_at->translatedFormat('j F Y \à H\hi')); ?></span>
                        <span>·</span>
                        <span><?php echo e($event->venue); ?>, <?php echo e($event->city); ?></span>
                    </div>

                    <!-- Barre de progression des ventes -->
                    <div class="progress-bar-wrap" title="<?php echo e($pct); ?>% des places vendues">
                        <div class="progress-bar-fill" style="width: <?php echo e($pct); ?>%;"></div>
                    </div>

                    <div class="org-event-stats">
                        <strong style="color:var(--teal-700);"><?php echo e($event->tickets_count); ?> / <?php echo e($event->capacity); ?></strong> billets vendus (<?php echo e($pct); ?>%)
                        <span>·</span>
                        <span style="color:var(--text-muted);">Entrées :</span>
                        <strong><?php echo e($event->used_tickets_count); ?></strong>
                        <span>·</span>
                        <span style="color:var(--text-muted);">Recettes :</span>
                        <strong><?php echo e(fcfa((int) $event->revenue)); ?></strong>
                    </div>
                </div>

                <div class="org-event-actions">
                    <a class="btn btn-primary btn-sm" href="<?php echo e(route('organizer.scan', $event)); ?>" title="Scanner les billets à l'entrée">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                            <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                            <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                            <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                        </svg>
                        <span>Scanner</span>
                    </a>

                    <a class="btn btn-ghost btn-sm" href="<?php echo e(route('organizer.events.show', $event)); ?>">
                        <span>Détails & Ventes</span>
                    </a>

                    <a class="btn btn-ghost btn-sm" href="<?php echo e(route('organizer.events.edit', $event)); ?>">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                        <span>Modifier</span>
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/user/Téléchargements/sunuticket/resources/views/organizer/dashboard.blade.php ENDPATH**/ ?>