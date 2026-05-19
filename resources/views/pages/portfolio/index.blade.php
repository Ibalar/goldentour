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

    <div class="page-blog">
        <div class="container">
            <div class="row">
                @forelse ($portfolio as $index => $item)
                    <div class="col-xl-4 col-md-6">
                        <div class="post-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format(($index % 3) * 0.2, 1) }}s" @endif>
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
                                    <div class="post-item-meta">
                                        <ul>
                                            @if($item->completion_date)
                                                <li>{{ $item->completion_date->translatedFormat('F Y') }}</li>
                                            @endif
                                            @if($item->location)
                                                <li>{{ $item->location }}</li>
                                            @endif
                                        </ul>
                                    </div>

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
                @empty
                    <div class="col-lg-12">
                        <p class="text-center py-5">Проекты пока не опубликованы.</p>
                    </div>
                @endforelse
            </div>

            @if(method_exists($portfolio, 'links'))
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-pagination wow fadeInUp">
                            {{ $portfolio->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
