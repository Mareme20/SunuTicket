@if ($paginator->hasPages())
    <nav style="display:flex;align-items:center;justify-content:space-between;width:100%;gap:12px;" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="btn btn-ghost btn-sm" style="opacity:0.4;cursor:not-allowed;pointer-events:none;">
                ← Précédent
            </span>
        @else
            <a class="btn btn-ghost btn-sm" href="{{ $paginator->previousPageUrl() }}">
                ← Précédent
            </a>
        @endif

        <span style="font-size:0.85rem;color:var(--text-muted);font-weight:600;">
            Page {{ $paginator->currentPage() }}
        </span>

        @if ($paginator->hasMorePages())
            <a class="btn btn-ghost btn-sm" href="{{ $paginator->nextPageUrl() }}">
                Suivant →
            </a>
        @else
            <span class="btn btn-ghost btn-sm" style="opacity:0.4;cursor:not-allowed;pointer-events:none;">
                Suivant →
            </span>
        @endif
    </nav>
@endif
