@php
    $homeRoute = config('breadcrumbs.home_route', 'dashboard');
    $homeDisplay = config('breadcrumbs.home_display', 'icon');
    $homeIcon = config('breadcrumbs.home_icon', 'house');
@endphp

@if (count($breadcrumbs) > 0)
    <div {{ $attributes->merge(['class' => 'mt-1 flex items-center']) }}>
        <flux:breadcrumbs class="text-sm font-medium sm:text-lg">
            @foreach ($breadcrumbs as $breadcrumb)
                @php
                    $isHome = $breadcrumb->title === ucfirst($homeRoute) && $loop->first;
                @endphp

                @if ($breadcrumb->url && !$loop->last)
                    @if ($isHome)
                        <flux:breadcrumbs.item href="{{ $breadcrumb->url }}">
                            @if (in_array($homeDisplay, ['icon', 'both']))
                                <flux:icon icon="{{ $homeIcon }}" class="size-5 !text-orange-500 hover:!text-orange-600" />
                            @endif
                            @if (in_array($homeDisplay, ['text', 'both']))
                                {{ $breadcrumb->title }}
                            @endif
                        </flux:breadcrumbs.item>
                    @else
                        <flux:breadcrumbs.item
                            href="{{ $breadcrumb->url }}"
                            class="!text-sm !text-zinc-500 hover:!text-zinc-700 sm:!text-lg dark:!text-zinc-400 dark:hover:!text-zinc-300"
                        >
                            {{ $breadcrumb->title }}
                        </flux:breadcrumbs.item>
                    @endif
                @else
                    @if ($isHome)
                        <flux:breadcrumbs.item class="!text-orange-500">
                            @if (in_array($homeDisplay, ['icon', 'both']))
                                <flux:icon icon="{{ $homeIcon }}" class="size-5" />
                            @endif
                            @if (in_array($homeDisplay, ['text', 'both']))
                                {{ $breadcrumb->title }}
                            @endif
                        </flux:breadcrumbs.item>
                    @else
                        <flux:breadcrumbs.item class="!text-sm !text-zinc-400 sm:!text-lg dark:!text-zinc-500">
                            {{ $breadcrumb->title }}
                        </flux:breadcrumbs.item>
                    @endif
                @endif
            @endforeach
        </flux:breadcrumbs>
    </div>
@endif