@extends('layouts.app')

@section('meta_title', \App\Models\Setting::get('site_title', 'Золотой Тур'))
@section('meta_description', \App\Models\Setting::get('site_description', 'Строительство и ремонт под ключ.'))

@section('content')
    <div class="hero dark-section parallaxie" style="background-image: url('{{ \App\Models\Setting::get('hero_image') ? asset('storage/' . \App\Models\Setting::get('hero_image')) : asset('assets/images/hero-bg-image.jpg') }}');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-8 col-md-10">
                    <div class="hero-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">Строительство, ремонт и комплектация объекта под ключ</h3>
                            <h1 class="text-anime-style-3" data-cursor="-opaque">
                                {{ \App\Models\Setting::get('hero_title', 'Строим и запускаем объект без хаоса на каждом этапе') }}
                            </h1>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">
                                {{ \App\Models\Setting::get('hero_subtitle', 'Берем на себя организацию работ, смету, материалы и контроль исполнения. Клиент получает понятный процесс, сроки и одну точку ответственности.') }}
                            </p>
                        </div>

                        <div class="hero-content-body wow fadeInUp" data-wow-delay="0.4s">
                            <div class="hero-btn">
                                <a href="{{ route('calculator') }}" class="btn-default btn-highlighted mx-3">Рассчитать стоимость</a>
                                <a href="{{ route('portfolio.index') }}" class="btn-default mx-3">Смотреть проекты</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-2">
                    <div class="year-experience-circle">
                        <img src="{{ asset('assets/images/year-experience-circle-transperant.svg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-info-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="hero-info-list">
                        <div class="hero-info-item box-1">
                            <div class="hero-info-content-box">
                                <div class="hero-info-item-content">
                                    <ul>
                                        <li>Под ключ</li>
                                    </ul>
                                    <h3>Формируем маршрут проекта от заявки до сдачи объекта</h3>
                                </div>
                                <div class="hero-info-btn">
                                    <a href="{{ route('about') }}" class="readmore-btn">О компании</a>
                                </div>
                            </div>
                            <div class="hero-info-image">
                                <figure class="image-anime reveal">
                                    <img src="{{ asset('assets/images/hero-info-image-1.jpg') }}" alt="">
                                </figure>
                            </div>
                        </div>

                        <div class="hero-info-item box-2">
                            <figure class="image-anime reveal">
                                <img src="{{ asset('assets/images/hero-info-image-2.jpg') }}" alt="">
                            </figure>
                        </div>

                        <div class="hero-info-item box-3">
                            <div class="hero-info-header">
                                <div class="icon-box">
                                    <img src="{{ asset('assets/images/icon-hero-info-1.svg') }}" alt="">
                                </div>

                                <div class="satisfy-client-images">
                                    <div class="satisfy-client-image">
                                        <figure class="image-anime">
                                            <img src="{{ asset('assets/images/author-1.jpg') }}" alt="">
                                        </figure>
                                    </div>
                                    <div class="satisfy-client-image">
                                        <figure class="image-anime">
                                            <img src="{{ asset('assets/images/author-2.jpg') }}" alt="">
                                        </figure>
                                    </div>
                                    <div class="satisfy-client-image">
                                        <figure class="image-anime">
                                            <img src="{{ asset('assets/images/author-3.jpg') }}" alt="">
                                        </figure>
                                    </div>
                                </div>
                            </div>

                            <div class="hero-info-item-content">
                                <h3>{{ max($stats['projects'], 50) }}+ реализованных проектов</h3>
                                <p>Понятная смета, контроль этапов и реальный производственный подход к каждому объекту.</p>
                            </div>

                            <div class="hero-info-btn">
                                <a href="{{ route('contacts') }}" class="readmore-btn">Связаться с нами</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="about-us">
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <div class="about-us-image-box wow fadeInUp">
                        <div class="about-us-image-box-1">
                            <div class="about-us-image">
                                <figure class="image-anime">
                                    <img src="{{ asset('assets/images/about-us-image-1.jpg') }}" alt="">
                                </figure>
                            </div>
                        </div>

                        <div class="about-us-image-box-2">
                            <div class="about-us-image">
                                <figure class="image-anime">
                                    <img src="{{ asset('assets/images/about-us-image-2.jpg') }}" alt="">
                                </figure>
                            </div>

                            <div class="year-experience-circle">
                                <img src="{{ asset('assets/images/year-experience-circle-accent.svg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="about-us-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">О компании</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Собираем объект как управляемый проект, а не как набор случайных задач</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Мы работаем в логике производства: сначала фиксируем задачу, бюджет и ограничения объекта, затем собираем этапы, смету и график. Это позволяет управлять сроками, качеством и коммуникацией, а не разбираться с последствиями в конце.</p>
                        </div>

                        <div class="about-us-body wow fadeInUp" data-wow-delay="0.4s">
                            <div class="about-body-item">
                                <div class="icon-box">
                                    <img src="{{ asset('assets/images/icon-about-item-1.svg') }}" alt="">
                                </div>
                                <div class="about-body-item-content">
                                    <h3>Понятная смета</h3>
                                    <p>Разбиваем бюджет по этапам и пакетам работ до старта.</p>
                                </div>
                            </div>

                            <div class="about-body-item">
                                <div class="icon-box">
                                    <img src="{{ asset('assets/images/icon-about-item-2.svg') }}" alt="">
                                </div>
                                <div class="about-body-item-content">
                                    <h3>Контроль исполнения</h3>
                                    <p>Следим за последовательностью, качеством и реальным темпом работ.</p>
                                </div>
                            </div>
                        </div>

                        <div class="about-us-footer wow fadeInUp" data-wow-delay="0.6s">
                            <div class="about-us-footer-content">
                                <div class="about-footer-content-list">
                                    <ul>
                                        <li>Одна точка ответственности за проект.</li>
                                        <li>Фиксация этапов, сроков и состава работ.</li>
                                        <li>Подход, ориентированный на конечный результат, а не на процесс ради процесса.</li>
                                    </ul>
                                </div>
                                <div class="about-us-btn">
                                    <a href="{{ route('about') }}" class="btn-default">Подробнее о нас</a>
                                </div>
                            </div>

                            <div class="about-us-video-box">
                                <div class="about-video-image">
                                    <figure class="image-anime">
                                        <img src="{{ asset('assets/images/about-intro-video-image.jpg') }}" alt="">
                                    </figure>
                                </div>
                                <div class="video-play-button">
                                    <a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone', '+375290000000')) }}">
                                        <i class="fa-solid fa-phone"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="our-services">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <div class="section-title section-title-center">
                        <h3 class="wow fadeInUp">Наши услуги</h3>
                        <h2 class="text-anime-style-3" data-cursor="-opaque">Ключевые направления, с которых обычно начинается проект</h2>
                    </div>
                </div>
            </div>

            <div class="row services-item-list">
                @foreach ($services->take(4) as $index => $service)
                    <div class="col-xl-3 col-md-6">
                        <div class="service-item wow fadeInUp {{ $index === 0 ? 'active' : '' }}" @if($index > 0) data-wow-delay="{{ number_format($index * 0.2, 1) }}s" @endif>
                            <div class="service-item-header">
                                <div class="service-item-title">
                                    <h2><a href="{{ route('services.show', $service) }}">{{ $service->name }}</a></h2>
                                    <h3>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}.</h3>
                                </div>
                                <div class="service-item-content">
                                    <p>{{ \Illuminate\Support\Str::limit($service->short_description ?: 'Производственный цикл с понятным составом работ и сроками.', 90) }}</p>
                                </div>
                            </div>
                            <div class="service-image-box">
                                <div class="service-item-image">
                                    <figure class="image-anime">
                                        <img src="{{ $service->image ? asset('storage/' . $service->image) : asset('assets/images/service-image-' . (($index % 4) + 1) . '.jpg') }}" alt="{{ $service->name }}">
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
                @endforeach
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="section-footer-text">
                        <p><span>Подбор</span> Не нашли нужное направление? <a href="{{ route('contacts') }}">Свяжитесь с нами для консультации</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="what-we-do">
        <div class="container">
            <div class="row align-items-end">
                <div class="col-xl-7">
                    <div class="what-we-do-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">Почему нам доверяют</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Берем на себя организацию, снабжение и управление этапами</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">На длинных объектах важна не только строительная экспертиза, но и дисциплина проекта. Мы выстраиваем процесс так, чтобы заказчик понимал, что происходит сейчас, что будет дальше и за что он платит.</p>
                        </div>

                        <div class="what-we-do-item-list wow fadeInUp" data-wow-delay="0.4s">
                            <div class="what-we-do-item">
                                <div class="icon-box">
                                    <img src="{{ asset('assets/images/icon-what-we-do-item-1.svg') }}" alt="">
                                </div>
                                <div class="what-we-do-item-body">
                                    <h3>Управление объектом</h3>
                                    <p>Координируем процесс, подрядчиков, материалы и ключевые точки принятия решений.</p>
                                    <ul>
                                        <li>Фокус на сроках, качестве и прозрачности коммуникации.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="what-we-do-item">
                                <div class="icon-box">
                                    <img src="{{ asset('assets/images/icon-what-we-do-item-2.svg') }}" alt="">
                                </div>
                                <div class="what-we-do-item-body">
                                    <h3>Проектный подход</h3>
                                    <p>Собираем объект как последовательную систему этапов, а не как стихийный набор задач.</p>
                                    <ul>
                                        <li>Так снижается риск срыва сроков и бюджетных сюрпризов.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="what-we-do-btn wow fadeInUp" data-wow-delay="0.6s">
                            <a href="{{ route('calculator') }}" class="btn-default">Получить ориентир по бюджету</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5">
                    <div class="what-we-do-image wow fadeInUp" data-wow-delay="0.2s">
                        <figure>
                            <img src="{{ asset('assets/images/what-we-do-image.png') }}" alt="">
                        </figure>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="section-footer-text section-satisfy-img wow fadeInUp" data-wow-delay="0.4s">
                    <div class="satisfy-client-images">
                        <div class="satisfy-client-image">
                            <figure class="image-anime">
                                <img src="{{ asset('assets/images/author-1.jpg') }}" alt="">
                            </figure>
                        </div>
                        <div class="satisfy-client-image add-more">
                            <img src="{{ asset('assets/images/icon-phone-primary.svg') }}" alt="">
                        </div>
                    </div>
                    <p>От идеи до сдачи объекта — <a href="{{ route('portfolio.index') }}">смотрите примеры реализованных проектов</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="our-projects">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-xl-6">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">Наши проекты</h3>
                        <h2 class="text-anime-style-3" data-cursor="-opaque">Показываем реальные кейсы, а не только обещания</h2>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="section-btn wow fadeInUp" data-wow-delay="0.2s">
                        <a href="{{ route('portfolio.index') }}" class="btn-default">Все проекты</a>
                    </div>
                </div>
            </div>

            <div class="row project-item-boxes">
                @foreach ($portfolio->take(4) as $index => $item)
                    <div class="col-lg-6">
                        <div class="project-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format($index * 0.2, 1) }}s" @endif>
                            <div class="project-image">
                                <a href="{{ route('portfolio.show', $item) }}" data-cursor-text="View">
                                    <figure class="image-anime">
                                        <img src="{{ $item->thumbnail ? asset('storage/' . $item->thumbnail) : asset('assets/images/project-image-' . (($index % 6) + 1) . '.jpg') }}" alt="{{ $item->title }}">
                                    </figure>
                                </a>
                            </div>
                            <div class="project-item-content">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="our-testimonials">
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <div class="our-testimonial-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">Отзывы клиентов</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Нас рекомендуют после завершения работ, а не только на этапе продажи</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Публикуем отзывы клиентов, которые прошли с нами через реальные строительные или ремонтные задачи.</p>
                        </div>

                        <div class="testimonial-btn wow fadeInUp" data-wow-delay="0.4s">
                            <a href="{{ route('contacts') }}" class="btn-default">Оставить заявку</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="testimonial-slider-box">
                        <div class="swiper testimonial-slider">
                            <div class="swiper-wrapper">
                                @foreach ($reviews as $review)
                                    <div class="swiper-slide">
                                        <div class="testimonial-item">
                                            <div class="testimonial-header">
                                                <div class="author-image">
                                                    <figure class="image-anime">
                                                        <img src="{{ asset('assets/images/author-1.jpg') }}" alt="{{ $review->author_name }}">
                                                    </figure>
                                                </div>
                                                <div class="author-content">
                                                    <h3>{{ $review->author_name }}</h3>
                                                    <p>{{ $review->service?->name ?? 'Клиент компании' }}</p>
                                                </div>
                                            </div>
                                            <div class="testimonial-content">
                                                <p>{{ $review->text }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="testimonial-pagination"></div>
                        </div>

                        <div class="section-footer-text wow fadeInUp" data-wow-delay="0.4s">
                            <p><span><img src="{{ asset('assets/images/icon-phone-primary.svg') }}" alt=""></span> Нужен расчет или консультация? <a href="{{ route('calculator') }}">Начните с калькулятора</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
