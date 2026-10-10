<?php $__env->startSection('title', $user->name.' · Organisateur'); ?>

<?php $__env->startSection('content'); ?>
    <section style="padding: 24px 0 18px;">
        <nav class="breadcrumbs" aria-label="Fil d'Ariane">
            <a href="<?php echo e(route('home')); ?>">Accueil</a>
            <span>/</span>
            <span style="color:var(--text-main);font-weight:600;"><?php echo e($user->name); ?></span>
        </nav>
    </section>

    <section style="padding: 12px 0 36px;">
        <div style="background:linear-gradient(135deg, rgba(15,23,42,0.96), rgba(20,184,166,0.76)); border:1px solid rgba(255,255,255,0.08); border-radius:var(--radius-xl); padding: 28px; box-shadow:var(--shadow-lg); color:#fff;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;">
                <div>
                    <span style="display:inline-block;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.18);padding:8px 12px;border-radius:var(--radius-full);font-size:0.75rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">
                        Organisateur
                    </span>
                    <h1 style="margin:14px 0 8px;font-size:clamp(2rem,4vw,3rem);line-height:1.1;"><?php echo e($user->name); ?></h1>
                    <p style="margin:0;color:rgba(255,255,255,0.8);font-size:1.02rem;max-width:620px;">
                        Découvrez les événements publiés par cet organisateur et réservez directement sur SunuTicket.
                    </p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <div style="min-width:120px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.12); border-radius:var(--radius-md); padding:14px 16px;">
                        <div style="font-size:0.7rem; text-transform:uppercase; letter-spacing:0.08em; color:rgba(255,255,255,0.7);">Événements</div>
                        <div style="font-size:1.6rem; font-weight:800; margin-top:6px;"><?php echo e($events->count()); ?></div>
                    </div>
                    <div style="min-width:120px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.12); border-radius:var(--radius-md); padding:14px 16px;">
                        <div style="font-size:0.7rem; text-transform:uppercase; letter-spacing:0.08em; color:rgba(255,255,255,0.7);">Contact</div>
                        <div style="font-size:0.9rem; font-weight:700; margin-top:6px;"><?php echo e($user->phone ?? 'Disponible sur demande'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="section-header">
            <div>
                <h2>Événements publiés</h2>
                <p class="section-subtitle">Les prochaines sorties proposées par <?php echo e($user->name); ?>.</p>
            </div>
        </div>

        <?php if($events->isEmpty()): ?>
            <div class="empty-state">
                <div class="empty-icon">🎫</div>
                <h3>Aucun événement publié pour le moment</h3>
                <p>Cette billetterie n’a pas encore de rendez-vous en ligne.</p>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="event-card">
                        <a href="<?php echo e(route('events.show', $event)); ?>" style="text-decoration:none;color:inherit;display:block;">
                            <div class="event-media">
                                <?php if($event->posterUrl()): ?>
                                    <img src="<?php echo e($event->posterUrl()); ?>" alt="Affiche : <?php echo e($event->title); ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="event-media-fallback">
                                        <div class="fallback-pattern"></div>
                                        <span class="fallback-letter" aria-hidden="true"><?php echo e(mb_strtoupper(mb_substr($event->title, 0, 1))); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="event-date-badge">
                                    <span class="event-date-day"><?php echo e($event->starts_at->format('d')); ?></span>
                                    <span class="event-date-month"><?php echo e($event->starts_at->translatedFormat('M')); ?></span>
                                </div>
                            </div>
                        </a>

                        <div class="event-content">
                            <div class="event-location">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0Z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <span><?php echo e($event->venue); ?>, <?php echo e($event->city); ?></span>
                            </div>

                            <a href="<?php echo e(route('events.show', $event)); ?>" style="text-decoration:none;color:inherit;">
                                <h3 class="event-title"><?php echo e($event->title); ?></h3>
                            </a>

                            <div class="event-footer">
                                <div class="event-price-wrap">
                                    <span class="price-label">Tarif</span>
                                    <span class="price-amount"><?php echo e($event->price == 0 ? 'Gratuit' : fcfa($event->price)); ?></span>
                                </div>
                                <a class="btn btn-primary btn-sm" href="<?php echo e(route('events.show', $event)); ?>">Réserver</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/user/Téléchargements/sunuticket/resources/views/organizers/show.blade.php ENDPATH**/ ?>