@extends('layouts.app')

@section('meta_title', $portfolio->meta_title ?: $portfolio->title)
@section('meta_description', $portfolio->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($portfolio->description ?? ''), 160))
@section('meta_image', $portfolio->thumbnail ?? '')

@section('content')
    <section class="section-space">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-[1.2fr_0.8fr]">
                <div>
                    <p class="eyebrow">{{ $portfolio->service?->name ?? 'Проект' }}</p>
                    <h1 class="mt-3 text-4xl font-black text-secondary-900 sm:text-5xl">{{ $portfolio->title }}</h1>

                    <div class="mt-8 overflow-hidden rounded-[2rem] bg-secondary-100">
                        @if ($portfolio->thumbnail)
                            <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}" class="h-[30rem] w-full object-cover">
                        @else
                            <div class="flex h-[30rem] items-center justify-center bg-[radial-gradient(circle_at_top,_rgba(255,191,71,0.35),_transparent_55%),linear-gradient(135deg,#2d241f,#12100f)] text-2xl font-bold text-white">
                                {{ $portfolio->title }}
                            </div>
                        @endif
                    </div>

                    @if ($portfolio->description)
                        <div class="content-block mt-10">
                            {!! $portfolio->description !!}
                        </div>
                    @endif

                    @if ($portfolio->images->isNotEmpty())
                        <div class="mt-12 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($portfolio->images as $image)
                                <div class="overflow-hidden rounded-[1.5rem] bg-secondary-100">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->caption ?: $portfolio->title }}" class="h-48 w-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($relatedProjects->isNotEmpty())
                        <div class="mt-14">
                            <h2 class="text-2xl font-bold text-secondary-900">Другие проекты по этому направлению</h2>
                            <div class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($relatedProjects as $relatedProject)
                                    <x-portfolio-card :item="$relatedProject" />
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <aside class="space-y-4">
                    <div class="rounded-[2rem] border border-secondary-200 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-secondary-900">Параметры проекта</h2>
                        <div class="mt-5 space-y-4">
                            @if ($portfolio->location)
                                <div class="info-strip">
                                    <span class="info-strip-label">Локация</span>
                                    <span class="info-strip-value">{{ $portfolio->location }}</span>
                                </div>
                            @endif
                            @if ($portfolio->area)
                                <div class="info-strip">
                                    <span class="info-strip-label">Площадь</span>
                                    <span class="info-strip-value">{{ $portfolio->area }} м²</span>
                                </div>
                            @endif
                            @if ($portfolio->completion_date)
                                <div class="info-strip">
                                    <span class="info-strip-label">Дата завершения</span>
                                    <span class="info-strip-value">{{ $portfolio->completion_date->translatedFormat('F Y') }}</span>
                                </div>
                            @endif
                            @if ($portfolio->client_name)
                                <div class="info-strip">
                                    <span class="info-strip-label">Клиент</span>
                                    <span class="info-strip-value">{{ $portfolio->client_name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-secondary-950 p-6 text-white">
                        <h2 class="text-xl font-bold">Похожий проект для вас</h2>
                        <p class="mt-3 text-sm leading-6 text-secondary-300">Подготовим предварительную смету и предложим сценарий реализации под ваш объект.</p>
                        <div class="mt-5">
                            <x-lead-form :service="$portfolio->service" :source="'calculator'" prefix="portfolio-form" />
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
