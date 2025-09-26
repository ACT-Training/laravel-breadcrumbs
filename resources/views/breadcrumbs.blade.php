@if (count($breadcrumbs) > 0)
    <div {{ $attributes->merge(['class' => 'mt-1 flex items-center']) }}>
        <flux:breadcrumbs class="text-sm font-medium sm:text-lg">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb->url && !$loop->last)
                    @if ($breadcrumb->title === 'Dashboard' && $loop->first)
                        <flux:breadcrumbs.item href="{{ $breadcrumb->url }}">
                            <flux:icon icon="house" class="size-5 !text-orange-500 hover:!text-orange-600" />
                            {{ $breadcrumb->title }}
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
                    @if ($breadcrumb->title === 'Dashboard' && $loop->first)
                        <flux:breadcrumbs.item class="!text-orange-500">
                            <flux:icon icon="house" class="size-5" />
                            {{ $breadcrumb->title }}
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