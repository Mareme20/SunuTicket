<?php if($paginator->hasPages()): ?>
    <nav style="display:flex;align-items:center;justify-content:space-between;width:100%;gap:12px;" aria-label="Pagination">
        <?php if($paginator->onFirstPage()): ?>
            <span class="btn btn-ghost btn-sm" style="opacity:0.4;cursor:not-allowed;pointer-events:none;">
                ← Précédent
            </span>
        <?php else: ?>
            <a class="btn btn-ghost btn-sm" href="<?php echo e($paginator->previousPageUrl()); ?>">
                ← Précédent
            </a>
        <?php endif; ?>

        <span style="font-size:0.85rem;color:var(--text-muted);font-weight:600;">
            Page <?php echo e($paginator->currentPage()); ?>

        </span>

        <?php if($paginator->hasMorePages()): ?>
            <a class="btn btn-ghost btn-sm" href="<?php echo e($paginator->nextPageUrl()); ?>">
                Suivant →
            </a>
        <?php else: ?>
            <span class="btn btn-ghost btn-sm" style="opacity:0.4;cursor:not-allowed;pointer-events:none;">
                Suivant →
            </span>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<?php /**PATH /home/user/Téléchargements/sunuticket/resources/views/pagination/simple.blade.php ENDPATH**/ ?>