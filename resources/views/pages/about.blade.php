@extends('layouts.app')

@section('meta_title', 'О компании ' . \App\Models\Setting::get('company_name', 'Золотой Тур'))
@section('meta_description', 'История компании, подход к работе, команда и принципы ' . \App\Models\Setting::get('company_name', 'Золотой Тур') . '.')

@section('content')
    @php
        $approachItems = [
            [
                'title' => 'Наша миссия',
                'text' => 'Собирать строительные и ремонтные проекты как управляемую систему, где у клиента есть понятный результат, сроки и одна точка ответственности.',
                'items' => [
                    'Понятная структура работ и сметы',
                    'Прозрачная коммуникация по этапам',
                    'Реалистичный подход к срокам и ресурсам',
                ],
                'tags' => ['Система', 'Контроль'],
                'shape' => 'assets/images/approach-item-shape-1.svg',
                'image' => 'assets/images/about-us-image-1.jpg',
                'box' => 'box-1',
            ],
            [
                'title' => 'Наше видение',
                'text' => 'Строительный проект не должен быть хаотичным. Мы выстраиваем процесс так, чтобы качество, бюджет и организационные решения работали вместе.',
                'items' => [
                    'Единый производственный подход',
                    'Связка проектирования, снабжения и исполнения',
                    'Фокус на результате, а не на имитации процесса',
                ],
                'tags' => ['Практика', 'Результат'],
                'shape' => 'assets/images/approach-item-shape-2.svg',
                'image' => 'assets/images/about-us-image-2.jpg',
                'box' => 'box-2',
            ],
            [
                'title' => 'Наши ценности',
                'text' => 'Для нас важны дисциплина проекта, качество коммуникации и честная фиксация решений до начала работ, а не после появления проблем.',
                'items' => [
                    'Ответственность за взятые обязательства',
                    'Фиксация договоренностей и этапов',
                    'Практичные решения без лишнего шума',
                ],
                'tags' => ['Качество', 'Ответственность'],
                'shape' => 'assets/images/approach-item-shape-3.svg',
                'image' => 'assets/images/about-intro-video-image.jpg',
                'box' => 'box-3',
            ],
        ];

        $featureItems = [
            [
                'title' => 'Проектное мышление',
                'text' => 'Каждый объект рассматриваем как последовательность управляемых этапов с понятными решениями и контрольными точками.',
                'items' => [
                    'Сначала уточняем задачу и ограничения',
                    'Затем собираем смету, график и состав работ',
                ],
                'image' => 'assets/images/feature-item-image-1.jpg',
                'box' => 'box-1',
            ],
            [
                'title' => 'Прозрачная организация',
                'text' => 'Клиент понимает, что происходит на объекте, на каком этапе находится проект и какие решения приняты по бюджету и реализации.',
                'items' => [
                    'Понятная отчетность по ходу работ',
                    'Снижение рисков переделок и срывов',
                ],
                'image' => 'assets/images/feature-item-image-2.png',
                'box' => 'box-1',
            ],
        ];

        $faqItems = [
            [
                'q' => 'Берете ли вы проект под ключ?',
                'a' => 'Да. Мы можем вести проект от подготовки и сметы до реализации, комплектации и сдачи результата.',
            ],
            [
                'q' => 'Можно ли подключиться только на отдельный этап?',
                'a' => 'Да. Мы работаем как с полным циклом, так и с отдельными задачами: проектирование, отделка, ремонт, снабжение или управление объектом.',
            ],
            [
                'q' => 'Как строится коммуникация по проекту?',
                'a' => 'Фиксируем состав работ, сроки и ответственных, после чего ведем проект через понятные этапы и регулярные обновления статуса.',
            ],
            [
                'q' => 'Какой первый шаг для старта?',
                'a' => 'Достаточно оставить заявку или позвонить. Мы уточним исходные данные и предложим следующий практический шаг без лишней бюрократии.',
            ],
        ];
    @endphp

    <div class="page-header parallaxie" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">О компании</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                                <li class="breadcrumb-item active" aria-current="page">О компании</li>
                            </ol>
                        </nav>
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
                            <h3 class="wow fadeInUp">О нас</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Работаем как производственная команда, а не как случайный набор подрядчиков</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Собираем проект вокруг задачи клиента: планирование, смета, материалы, исполнение и контроль качества. Такой подход позволяет управлять сроками, бюджетом и результатом, а не реагировать на проблемы постфактум.</p>
                        </div>

                        <div class="about-us-body wow fadeInUp" data-wow-delay="0.4s">
                            <div class="about-body-item">
                                <div class="icon-box">
                                    <img src="{{ asset('assets/images/icon-about-item-1.svg') }}" alt="">
                                </div>
                                <div class="about-body-item-content">
                                    <h3>Понятная организация</h3>
                                    <p>До старта фиксируем состав работ, приоритеты объекта и логику запуска каждого этапа.</p>
                                </div>
                            </div>

                            <div class="about-body-item">
                                <div class="icon-box">
                                    <img src="{{ asset('assets/images/icon-about-item-2.svg') }}" alt="">
                                </div>
                                <div class="about-body-item-content">
                                    <h3>Контроль исполнения</h3>
                                    <p>Следим за качеством, очередностью работ и рабочей коммуникацией между всеми участниками проекта.</p>
                                </div>
                            </div>
                        </div>

                        <div class="about-us-footer wow fadeInUp" data-wow-delay="0.6s">
                            <div class="about-us-footer-content">
                                <div class="about-footer-content-list">
                                    <ul>
                                        <li>{{ $stats['years'] }}+ лет практического опыта в организации строительных задач.</li>
                                        <li>{{ $stats['projects'] }}+ реализованных проектов с разным масштабом и сценариями работ.</li>
                                        <li>{{ $stats['services'] }} активных услуг, которые можно адаптировать под конкретный объект.</li>
                                    </ul>
                                </div>

                                <div class="about-us-btn">
                                    <a href="{{ route('contacts') }}" class="btn-default">Связаться с нами</a>
                                </div>
                            </div>

                            <div class="about-us-video-box">
                                <div class="about-video-image">
                                    <figure class="image-anime">
                                        <img src="{{ asset('assets/images/about-intro-video-image.jpg') }}" alt="">
                                    </figure>
                                </div>
                                <div class="video-play-button">
                                    <a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone', '+375290000000')) }}" data-cursor-text="Call">
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

    <div class="our-approach dark-section parallaxie" style="background-image: url('{{ asset('assets/images/working-process-bg-silver.jpg') }}');">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <div class="section-title section-title-center">
                        <h3 class="wow fadeInUp">Наш подход</h3>
                        <h2 class="text-anime-style-3" data-cursor="-opaque">Стратегия проекта строится вокруг результата, а не вокруг хаотичного процесса</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach ($approachItems as $index => $item)
                    <div class="col-xl-4 col-md-6">
                        <div class="approach-item {{ $item['box'] }} wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format($index * 0.2, 1) }}s" @endif>
                            <div class="icon-box">
                                <img src="{{ asset($item['shape']) }}" alt="">
                            </div>
                            <div class="approach-item-image">
                                <figure>
                                    <img src="{{ asset($item['image']) }}" alt="">
                                </figure>
                            </div>
                            <div class="approach-item-content">
                                <h3>{{ $item['title'] }}</h3>
                                <p>{{ $item['text'] }}</p>
                                <ul>
                                    @foreach ($item['items'] as $listItem)
                                        <li>{{ $listItem }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="approach-item-list">
                                <ul>
                                    @foreach ($item['tags'] as $tag)
                                        <li>{{ $tag }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-lg-12">
                    <div class="section-footer-text wow fadeInUp" data-wow-delay="0.4s">
                        <p>Нужен понятный запуск проекта? <a href="{{ route('calculator') }}">Получите ориентир по бюджету</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="our-features">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <div class="section-title section-title-center">
                        <h3 class="wow fadeInUp">Наши сильные стороны</h3>
                        <h2 class="text-effect" data-cursor="-opaque">
                            Соединяем дисциплину реализации
                            <span class="feature-title-img-1"><img src="{{ asset('assets/images/icon-feature-title-1.svg') }}" alt=""></span>
                            с понятной коммуникацией
                            <span class="feature-title-img-2"><img src="{{ asset('assets/images/icon-feature-title-2.svg') }}" alt=""></span>
                            и командной работой
                            <span class="feature-title-img-3"><img src="{{ asset('assets/images/author-1.jpg') }}" alt=""><img src="{{ asset('assets/images/author-2.jpg') }}" alt=""><img src="{{ asset('assets/images/author-3.jpg') }}" alt=""></span>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach ($featureItems as $index => $feature)
                    <div class="col-xl-6 col-md-6 order-{{ $index + 1 }}">
                        <div class="feature-item {{ $feature['box'] }} wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format($index * 0.2, 1) }}s" @endif>
                            <div class="feature-item-shape-image">
                                <figure>
                                    <img src="{{ asset($feature['image']) }}" alt="">
                                </figure>
                            </div>
                            <div class="feature-item-content-box">
                                <div class="feature-item-content">
                                    <h3>{{ $feature['title'] }}</h3>
                                    <p>{{ $feature['text'] }}</p>
                                </div>
                                <div class="feature-item-list">
                                    <ul>
                                        @foreach ($feature['items'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @if ($team->isNotEmpty())
        <div class="our-team dark-section parallaxie" style="background-image: url('{{ asset('assets/images/dark-section-bg-image.png') }}');">
            <div class="container">
                <div class="row section-row">
                    <div class="col-lg-12">
                        <div class="section-title section-title-center">
                            <h3 class="wow fadeInUp">Команда</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Люди, которые ведут проект и отвечают за реальную реализацию</h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach ($team as $index => $member)
                        <div class="col-xl-3 col-md-6">
                            <div class="team-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format(min($index * 0.2, 0.6), 1) }}s" @endif>
                                <div class="team-item-image">
                                    <a href="{{ route('about') }}" data-cursor-text="View">
                                        <figure>
                                            <img src="{{ $member->photo ? asset('storage/' . $member->photo) : asset('assets/images/author-' . (($index % 5) + 1) . '.jpg') }}" alt="{{ $member->full_name }}">
                                        </figure>
                                    </a>
                                </div>

                                <div class="team-item-content">
                                    <h3><a href="{{ route('about') }}">{{ $member->full_name }}</a></h3>
                                    <p>{{ $member->position }}</p>
                                </div>

                                <div class="team-social-list">
                                    <ul>
                                        @if ($member->phone)
                                            <li><a href="tel:{{ preg_replace('/[^0-9]/', '', $member->phone) }}"><i class="fa-solid fa-phone"></i></a></li>
                                        @endif
                                        @if ($member->email)
                                            <li><a href="mailto:{{ $member->email }}"><i class="fa-regular fa-envelope"></i></a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if ($reviews->isNotEmpty())
        <div class="our-testimonials">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5">
                        <div class="our-testimonial-content">
                            <div class="section-title">
                                <h3 class="wow fadeInUp">Отзывы</h3>
                                <h2 class="text-anime-style-3" data-cursor="-opaque">Реальная обратная связь после завершения работ, а не только на этапе переговоров</h2>
                                <p class="wow fadeInUp" data-wow-delay="0.2s">Публикуем отзывы клиентов, которые прошли с нами через проектирование, организацию работ и сдачу результата.</p>
                            </div>

                            <div class="testimonial-btn wow fadeInUp" data-wow-delay="0.4s">
                                <a href="{{ route('contacts') }}" class="btn-default">Оставить заявку</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7">
                        <div class="testimonial-slider-box">
                            <div class="testimonial-slider">
                                <div class="swiper">
                                    <div class="swiper-wrapper" data-cursor-text="Drag">
                                        @foreach ($reviews as $index => $review)
                                            <div class="swiper-slide">
                                                <div class="testimonial-item">
                                                    <div class="testimonial-company-logo">
                                                        <img src="{{ asset('assets/images/company-logo-' . (($index % 3) + 1) . '.svg') }}" alt="">
                                                    </div>
                                                    <div class="testimonial-content">
                                                        <p>{{ $review->text }}</p>
                                                    </div>
                                                    <div class="testimonial-author">
                                                        <div class="testimonial-author-image">
                                                            <figure class="image-anime">
                                                                <img src="{{ asset('assets/images/author-' . (($index % 5) + 1) . '.jpg') }}" alt="{{ $review->author_name }}">
                                                            </figure>
                                                        </div>
                                                        <div class="testimonial-author-content">
                                                            <h3>{{ $review->author_name }}</h3>
                                                            <p>{{ $review->service?->name ?? 'Клиент компании' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="testimonial-pagination"></div>
                                </div>
                            </div>

                            <div class="section-footer-text section-footer-contact wow fadeInUp" data-wow-delay="0.2s">
                                <p><span><img src="{{ asset('assets/images/icon-phone-primary.svg') }}" alt=""></span> Нужен расчет или консультация? <a href="{{ route('calculator') }}">Начните с калькулятора</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="our-faqs">
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <div class="our-faqs-content">
                        <div class="faqs-title-box">
                            <div class="section-title">
                                <h3 class="wow fadeInUp">Частые вопросы</h3>
                                <h2 class="text-anime-style-3" data-cursor="-opaque">Коротко о том, как мы подходим к работе и запуску проекта</h2>
                                <p class="wow fadeInUp" data-wow-delay="0.2s">Если у вас нет готового технического задания, это не проблема. Начнем с исходных данных, задачи и желаемого результата, а затем предложим следующий шаг.</p>
                            </div>

                            <div class="our-faqs-btn wow fadeInUp" data-wow-delay="0.4s">
                                <a href="{{ route('contacts') }}" class="btn-default">Связаться с нами</a>
                            </div>
                        </div>

                        <div class="faq-contact-box wow fadeInUp" data-wow-delay="0.6s">
                            <div class="icon-box">
                                <img src="{{ asset('assets/images/icon-phone-primary.svg') }}" alt="">
                            </div>
                            <div class="faq-contact-box-content">
                                <h3>Позвоните нам</h3>
                                <p><a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone', '+375290000000')) }}">{{ \App\Models\Setting::get('company_phone', '+375 (29) 000-00-00') }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="faq-accordion" id="about-accordion">
                        @foreach ($faqItems as $index => $faq)
                            <div class="accordion-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format($index * 0.2, 1) }}s" @endif>
                                <h2 class="accordion-header" id="about-heading-{{ $index }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#about-collapse-{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="about-collapse-{{ $index }}">
                                        {{ $faq['q'] }}
                                    </button>
                                </h2>
                                <div id="about-collapse-{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="about-heading-{{ $index }}" data-bs-parent="#about-accordion">
                                    <div class="accordion-body">
                                        <p>{{ $faq['a'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
