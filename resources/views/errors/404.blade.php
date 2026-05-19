@extends('layouts.app')

@section('meta_title', 'Страница не найдена — 404')
@section('meta_description', 'Запрашиваемая страница не существует или была удалена.')

@section('content')
    <div class="page-header parallaxie" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">Страница не найдена</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                                <li class="breadcrumb-item active" aria-current="page">404</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="error-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="error-page-image wow fadeInUp">
                        <img src="{{ asset('assets/images/404-error-img.png') }}" alt="Ошибка 404">
                    </div>
                    <div class="error-page-content">
                        <div class="section-title">
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Упс! Страница не найдена</h2>
                        </div>
                        <div class="error-page-content-body">
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Запрашиваемая страница не существует или была удалена.</p>
                            <a class="btn-default wow fadeInUp" data-wow-delay="0.4s" href="{{ route('home') }}">Вернуться на главную</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
