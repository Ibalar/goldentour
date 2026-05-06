@extends('layouts.app')

@section('meta_title', 'Портфолио строительной компании ' . \App\Models\Setting::get('company_name', 'Золотой Тур'))
@section('meta_description', 'Примеры выполненных объектов: строительство, ремонт и отделка помещений с реальными результатами и деталями проектов.')

@section('content')
    <div class="page-header parallaxie" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">Портфолио</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Портфолио</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-gallery">
        <div class="container">
            @if ($services->isNotEmpty())
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-footer-text" style="margin-bottom: 40px;">
                            <p>
                                <span>Направления</span>
                                @foreach ($services as $index => $service)
                                    <a href="{{ route('services.show', $service) }}">{{ $service->name }}</a>@if ($index < $services->count() - 1), @endif
                                @endforeach
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($portfolio->isNotEmpty())
                <div class="row gallery-items page-gallery-box">
                    @foreach ($portfolio as $index => $item)
                        @php
                            $image = $item->thumbnail
                                ? asset('storage/' . $item->thumbnail)
                                : asset('assets/images/project-image-' . (($index % 6) + 1) . '.jpg');
                        @endphp

                        <div class="col-lg-4 col-6">
                            <div class="photo-gallery wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format(min($index * 0.2, 1.6), 1) }}s" @endif>
                                <a href="{{ route('portfolio.show', $item) }}" data-cursor-text="View">
                                    <figure class="image-anime">
                                        <img src="{{ $image }}" alt="{{ $item->title }}">
                                    </figure>
                                </a>

                                <div class="project-item-content" style="padding: 24px 0 0;">
                                    <div class="project-item-content-header">
                                        <div class="project-item-title">
                                            <h2><a href="{{ route('portfolio.show', $item) }}">{{ $item->title }}</a></h2>
                                        </div>
                                        <div class="project-item-btn">
                                            <a href="{{ route('portfolio.show', $item) }}"><img src="{{ asset('assets/images/arrow-white.svg') }}" alt=""></a>
                                        </div>
                                    </div>
                                    <div class="project-item-content-body">
                                        <div class="project-item-tags">
                                            @if ($item->service)
                                                <a href="{{ route('services.show', $item->service) }}">{{ $item->service->name }}</a>
                                            @endif
                                            @if ($item->location)
                                                <a href="{{ route('portfolio.show', $item) }}">{{ $item->location }}</a>
                                            @endif
                                            @if ($item->completion_date)
                                                <a href="{{ route('portfolio.show', $item) }}">{{ $item->completion_date->format('Y') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-footer-text">
                            <p><span>Портфолио</span> Проекты пока не опубликованы. <a href="{{ route('contacts') }}">Свяжитесь с нами для примеров реализованных работ</a></p>
                        </div>
                    </div>
                </div>
            @endif

            @if (method_exists($portfolio, 'links'))
                <div class="row">
                    <div class="col-lg-12">
                        <div style="margin-top: 48px;">
                            {{ $portfolio->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
