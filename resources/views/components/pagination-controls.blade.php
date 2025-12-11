@props([
    'paginator',
    'paginationOptions' => [10, 20, 30, 50],
    'showPerPageSelector' => true,
    'showInfo' => true,
])

<div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">

    @if($showInfo)
    <!-- Información de registros -->
    <div class="text-sm text-gray-600 dark:text-gray-400">
        Mostrando
        <span class="font-semibold text-gray-800 dark:text-white">{{ $paginator->firstItem() ?? 0 }}</span>
        a
        <span class="font-semibold text-gray-800 dark:text-white">{{ $paginator->lastItem() ?? 0 }}</span>
        de
        <span class="font-semibold text-gray-800 dark:text-white">{{ $paginator->total() }}</span>
        registros
    </div>
    @endif

    <!-- Selector de resultados por página -->
    @if($showPerPageSelector && count($paginationOptions) > 1)
    <div class="flex items-center gap-2">
        <label for="per-page-select" class="text-sm text-gray-600 dark:text-gray-400">
            Mostrar:
        </label>
        <select
            id="per-page-select"
            onchange="changePerPage(this.value)"
            class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg
                   bg-white dark:bg-gray-700 text-gray-800 dark:text-white
                   focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500
                   transition-colors duration-200"
        >
            @foreach($paginationOptions as $option)
                <option value="{{ $option }}" {{ request('per_page', 10) == $option ? 'selected' : '' }}>
                    {{ $option }} resultados
                </option>
            @endforeach
        </select>
    </div>
    @endif

    <!-- Links de paginación -->
    <div class="flex items-center gap-1">
        {{ $paginator->links() }}
    </div>

</div>

<script>
    function changePerPage(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', value);
        url.searchParams.set('page', '1'); // Resetear a la primera página
        window.location.href = url.toString();
    }
</script>

<style>
    /* Estilos para los links de paginación de Laravel */
    nav[role="navigation"] {
        display: flex;
        align-items: center;
    }

    nav[role="navigation"] ul {
        display: flex;
        gap: 0.25rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    nav[role="navigation"] ul li a,
    nav[role="navigation"] ul li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    /* Light mode */
    html:not(.dark) nav[role="navigation"] ul li a {
        background-color: #ffffff;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    html:not(.dark) nav[role="navigation"] ul li a:hover {
        background-color: #f3f4f6;
        border-color: #10b981;
    }

    html:not(.dark) nav[role="navigation"] ul li span {
        background-color: #f9fafb;
        color: #9ca3af;
        border: 1px solid #e5e7eb;
    }

    html:not(.dark) nav[role="navigation"] ul li .active {
        background-color: #10b981;
        color: #ffffff;
        border-color: #10b981;
        font-weight: 600;
    }

    /* Dark mode */
    .dark nav[role="navigation"] ul li a {
        background-color: #374151;
        color: #e5e7eb;
        border: 1px solid #4b5563;
    }

    .dark nav[role="navigation"] ul li a:hover {
        background-color: #4b5563;
        border-color: #10b981;
    }

    .dark nav[role="navigation"] ul li span {
        background-color: #1f2937;
        color: #6b7280;
        border: 1px solid #374151;
    }

    .dark nav[role="navigation"] ul li .active {
        background-color: #10b981;
        color: #ffffff;
        border-color: #10b981;
        font-weight: 600;
    }

    /* Disabled state */
    nav[role="navigation"] ul li .disabled {
        cursor: not-allowed;
        opacity: 0.5;
    }
</style>
