@extends('layouts.app')

@section('meta_title', $category->name . ' - услуги Золотой Тур')
@section('meta_description', $category->description ?: ('Услуги категории ' . $category->name))

@section('content')
    @php
        $bgImage = match(true) {
            $category->breadcrumb_image !== null => asset('storage/' . $category->breadcrumb_image),
            $category->parent?->breadcrumb_image !== null => asset('storage/' . $category->parent->breadcrumb_image),
            default => asset('assets/images/page-header-bg.jpg'),
        };
    @endphp

    <div class="page-header parallaxie" style="background-image: url('{{ $bgImage }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">{{ $category->name }}</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Услуги</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-services">
        <div class="container">
            <div class="row services-item-list">
                @forelse ($services as $service)
                    <div class="col-xl-3 col-md-6">
                        <div class="service-item wow fadeInUp" data-wow-delay="{{ ($loop->index % 4) * 0.2 }}s">
                            <div class="service-item-header">
                                <div class="service-item-title">
                                    <h2><a href="{{ route('services.show', $service) }}">{{ $service->name }}</a></h2>
                                </div>
                            </div>
                            <div class="service-image-box">
                                <div class="service-item-image">
                                    <figure class="image-anime">
                                        @if($service->image)
                                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
                                        @else
                                            <img src="{{ asset('assets/images/service-image-' . (($loop->index % 8) + 1) . '.jpg') }}" alt="{{ $service->name }}">
                                        @endif
                                    </figure>
                                </div>
                                <div class="service-item-btn">
                                    <a href="{{ route('services.show', $service) }}">
                                        <img src="{{ asset('assets/images/arrow-primary.svg') }}" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-12">
                        <p class="text-center py-5">В этой категории пока нет активных услуг.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
