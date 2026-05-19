@extends('layouts.app')

@section('meta_title', $portfolio->meta_title ?: $portfolio->title)
@section('meta_description', $portfolio->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($portfolio->description ?? ''), 160))
@section('meta_image', $portfolio->thumbnail ?? '')

@section('content')
    <div class="page-header parallaxie" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">{{ $portfolio->title }}</h1>
                        <div class="post-single-meta wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('portfolio.index') }}">Портфолио</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $portfolio->title }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-single-post">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if($portfolio->thumbnail)
                        <div class="post-image">
                            <figure class="image-anime reveal">
                                <img src="{{ asset('storage/' . $portfolio->thumbnail) }}" alt="{{ $portfolio->title }}">
                            </figure>
                        </div>
                    @endif

                    <div class="post-content">
                        <div class="post-entry">
                            @if($portfolio->description)
                                <div class="wow fadeInUp">
                                    {!! $portfolio->description !!}
                                </div>
                            @endif
                        </div>

                        @if($portfolio->service || $portfolio->location || $portfolio->area || $portfolio->completion_date || $portfolio->client_name)
                            <div class="post-entry wow fadeInUp" data-wow-delay="0.2s">
                                <h3>Параметры проекта</h3>
                                <div class="row">
                                    @if($portfolio->service)
                                        <div class="col-md-6 col-lg-4">
                                            <p><strong>Услуга:</strong> <a href="{{ route('services.show', $portfolio->service) }}">{{ $portfolio->service->name }}</a></p>
                                        </div>
                                    @endif
                                    @if($portfolio->client_name)
                                        <div class="col-md-6 col-lg-4">
                                            <p><strong>Клиент:</strong> {{ $portfolio->client_name }}</p>
                                        </div>
                                    @endif
                                    @if($portfolio->location)
                                        <div class="col-md-6 col-lg-4">
                                            <p><strong>Локация:</strong> {{ $portfolio->location }}</p>
                                        </div>
                                    @endif
                                    @if($portfolio->area)
                                        <div class="col-md-6 col-lg-4">
                                            <p><strong>Площадь:</strong> {{ $portfolio->area }} м²</p>
                                        </div>
                                    @endif
                                    @if($portfolio->completion_date)
                                        <div class="col-md-6 col-lg-4">
                                            <p><strong>Завершено:</strong> {{ $portfolio->completion_date->translatedFormat('F Y') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $galleryImages = collect($portfolio->gallery ?: [])
            ->map(function ($item) {
                $path = data_get($item, 'image', $item);
                if (! is_string($path) || $path === '') {
                    return null;
                }
                $imageUrl = \Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/storage/', 'storage/'])
                    ? (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://']) ? $path : asset(\Illuminate\Support\Str::startsWith($path, '/') ? ltrim($path, '/') : 'storage/' . ltrim($path, '/')))
                    : asset('storage/' . ltrim($path, '/'));
                return [
                    'image' => $imageUrl,
                    'caption' => data_get($item, 'caption', ''),
                ];
            })
            ->filter()
            ->values();
    @endphp

    @if($galleryImages->isNotEmpty())
        <div class="page-gallery">
            <div class="container">
                <div class="row section-row">
                    <div class="col-lg-12">
                        <div class="section-title section-title-center">
                            <h2 class="text-anime-style-3">Галерея проекта</h2>
                        </div>
                    </div>
                </div>

                <div class="row gallery-items page-gallery-box">
                    @foreach($galleryImages as $index => $galleryItem)
                        <div class="col-lg-4 col-6">
                            <div class="photo-gallery wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format(min($index * 0.2, 1.6), 1) }}s" @endif>
                                <a href="{{ $galleryItem['image'] }}" data-cursor-text="Смотреть">
                                    <figure class="image-anime">
                                        <img src="{{ $galleryItem['image'] }}" alt="{{ $galleryItem['caption'] ?: $portfolio->title }}">
                                    </figure>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($relatedProjects->isNotEmpty())
        <div class="page-blog">
            <div class="container">
                <div class="row section-row">
                    <div class="col-lg-12">
                        <div class="section-title section-title-center">
                            <h2 class="text-anime-style-3">Другие проекты</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach($relatedProjects as $index => $item)
                        <div class="col-xl-4 col-md-6">
                            <div class="post-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format($index * 0.2, 1) }}s" @endif>
                                <div class="post-featured-image">
                                    <a href="{{ route('portfolio.show', $item) }}" data-cursor-text="Смотреть">
                                        <figure>
                                            <img src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/images/project-image-' . (($index % 6) + 1) . '.jpg') }}" alt="{{ $item->title }}">
                                        </figure>
                                    </a>
                                </div>

                                @if($item->service)
                                    <div class="post-item-tags">
                                        <a href="{{ route('services.show', $item->service) }}">{{ $item->service->name }}</a>
                                    </div>
                                @endif

                                <div class="post-item-body">
                                    <div class="post-content-box">
                                        @if($item->completion_date)
                                            <div class="post-item-meta">
                                                <ul>
                                                    <li>{{ $item->completion_date->translatedFormat('F Y') }}</li>
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="post-item-content">
                                            <h2><a href="{{ route('portfolio.show', $item) }}">{{ $item->title }}</a></h2>
                                        </div>
                                    </div>

                                    <div class="post-item-btn">
                                        <a href="{{ route('portfolio.show', $item) }}" class="readmore-btn">Подробнее</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection
