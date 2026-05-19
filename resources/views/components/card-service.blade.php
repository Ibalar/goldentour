@props(['service'])

<article class="group overflow-hidden rounded-[2rem] border border-secondary-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-secondary-900/10">
    <a href="{{ route('services.show', $service->slug) }}" class="block">
        <div class="relative h-56 overflow-hidden bg-secondary-100">
            @if ($service->image)
                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
            @else
                <div class="service-card-placeholder">
                    <span>{{ $service->category?->name ?? 'Услуга' }}</span>
                </div>
            @endif

            @if ($service->price_from)
                <div class="absolute bottom-4 right-4 rounded-full bg-white/90 px-4 py-2 text-sm font-semibold text-secondary-900 shadow-lg">
                    от {{ number_format((float) $service->price_from, 0, ',', ' ') }} BYN
                </div>
            @endif
        </div>
    </a>

    <div class="space-y-4 p-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary-600">{{ $service->category?->name ?? 'Услуга' }}</p>
            <h3 class="mt-2 text-2xl font-bold text-secondary-900">
                <a href="{{ route('services.show', $service->slug) }}" class="transition hover:text-primary-700">{{ $service->name }}</a>
            </h3>
        </div>

        @if ($service->short_description)
            <p class="line-clamp-3 text-sm leading-6 text-secondary-600">{{ $service->short_description }}</p>
        @endif

        @if ($service->features)
            <ul class="space-y-2">
                @foreach (array_slice($service->features, 0, 3) as $feature)
                    <li class="flex items-start gap-2 text-sm text-secondary-700">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary-500"></span>
                        <span>{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <x-button href="{{ route('services.show', $service->slug) }}" variant="outline" size="sm" class="w-full">Подробнее</x-button>
    </div>
</article>
