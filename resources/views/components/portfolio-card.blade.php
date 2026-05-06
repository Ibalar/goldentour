@props(['item'])

<article class="group relative overflow-hidden rounded-[2rem] bg-secondary-900 text-white">
    <a href="{{ route('portfolio.show', $item->slug) }}" class="block">
        <div class="absolute inset-0 z-10 bg-gradient-to-t from-secondary-950 via-secondary-950/50 to-transparent"></div>
        @if ($item->thumbnail)
            <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->title }}" class="h-80 w-full object-cover transition duration-700 group-hover:scale-105">
        @else
            <div class="h-80 w-full bg-[radial-gradient(circle_at_top,_rgba(255,191,71,0.35),_transparent_55%),linear-gradient(135deg,#2d241f,#12100f)]"></div>
        @endif

        <div class="absolute inset-x-0 bottom-0 z-20 p-6">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-300">{{ $item->service?->name ?? 'Проект' }}</p>
            <h3 class="mt-2 text-2xl font-bold">{{ $item->title }}</h3>
            <div class="mt-3 flex flex-wrap gap-3 text-sm text-secondary-200">
                @if ($item->location)
                    <span>{{ $item->location }}</span>
                @endif
                @if ($item->area)
                    <span>{{ $item->area }} м²</span>
                @endif
            </div>
        </div>
    </a>
</article>
