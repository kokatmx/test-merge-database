@if ($paginator->hasPages())
    <div class="flex items-center justify-center space-x-1">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 rounded-md text-sm font-medium text-slate-300 bg-white border border-slate-200 cursor-not-allowed">
                &lsaquo;
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-3 py-2 rounded-md text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-emerald-600 transition-colors">
                &lsaquo;
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-2 text-sm text-slate-400">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-2 rounded-md text-sm font-medium text-white bg-emerald-600 border border-emerald-600 shadow-sm">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 rounded-md text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-emerald-600 hover:border-emerald-200 transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-3 py-2 rounded-md text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:text-emerald-600 transition-colors">
                &rsaquo;
            </a>
        @else
            <span class="px-3 py-2 rounded-md text-sm font-medium text-slate-300 bg-white border border-slate-200 cursor-not-allowed">
                &rsaquo;
            </span>
        @endif
    </div>
@endif
