@if (count($breadcrumbs) > 0)
    <nav {{ $attributes->merge(['class' => $classes['wrapper'] ?? 'breadcrumbs']) }} aria-label="Breadcrumb">
        <ol class="{{ $classes['list'] ?? 'breadcrumb-list' }}">
            @foreach ($breadcrumbs as $breadcrumb)
                <li class="{{ $classes['item'] ?? 'breadcrumb-item' }}">
                    @if ($breadcrumb->url && !$loop->last)
                        <a href="{{ $breadcrumb->url }}" class="{{ $classes['link'] ?? 'breadcrumb-link' }}">
                            {{ $breadcrumb->title }}
                        </a>
                    @else
                        <span class="{{ $classes['active'] ?? 'breadcrumb-active' }}" aria-current="page">
                            {{ $breadcrumb->title }}
                        </span>
                    @endif

                    @if (!$loop->last)
                        <span class="{{ $classes['separator'] ?? 'breadcrumb-separator' }}" aria-hidden="true">
                            {{ $separator }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif