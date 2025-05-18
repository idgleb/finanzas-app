@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">

        {{-- Mobile Prev/Next --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="my-custom-prev cursor-default px-4 py-2 text-gray-500 border rounded-md">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="my-custom-prev px-4 py-2 text-blue-700 border rounded-md">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="my-custom-next px-4 py-2 ml-3 text-blue-700 border rounded-md">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="my-custom-next cursor-default px-4 py-2 ml-3 text-gray-500 border rounded-md">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop Numbers --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <span class="inline-flex shadow-sm rounded-md">
                    {{-- Prev arrow --}}
                    @if ($paginator->onFirstPage())
                        <span class="my-custom-prev cursor-default px-2 py-2 rounded-l-md text-gray-500 border">
                            ‹
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="my-custom-prev px-2 py-2 rounded-l-md text-blue-700 border">
                            ‹
                        </a>
                    @endif

                    {{-- Page Links --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="my-custom-page cursor-default px-4 py-2 -ml-px text-gray-700 border">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="my-custom-page-active px-4 py-2 -ml-px text-white border">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="my-custom-page px-4 py-2 -ml-px text-blue-700 border">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next arrow --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="my-custom-next px-2 py-2 -ml-px rounded-r-md text-blue-700 border">
                            ›
                        </a>
                    @else
                        <span class="my-custom-next cursor-default px-2 py-2 -ml-px rounded-r-md text-gray-500 border">
                            ›
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
