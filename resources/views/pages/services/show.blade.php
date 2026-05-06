@extends('layouts.app')

@section('meta_title', $service->meta_title ?: $service->name)
@section('meta_description', $service->meta_description ?: ($service->short_description ?: 'Описание услуги строительной компании ' . \App\Models\Setting::get('company_name', 'Золотой Тур') . '.'))
@section('meta_keywords', $service->meta_keywords ?? '')
@section('meta_image', $service->image ?? '')
@section('meta_type', 'service')

@push('head')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service->name,
        'description' => $service->short_description,
        'provider' => [
            '@type' => 'ConstructionBusiness',
            'name' => \App\Models\Setting::get('company_name', 'Золотой Тур'),
        ],
        'offers' => [
            '@type' => 'Offer',
            'price' => (string) ($service->price_from ?? 0),
            'priceCurrency' => 'RUB',
        ],
    ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Главная',
                'item' => url('/'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Услуги',
                'item' => route('services.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $service->name,
            ],
        ],
    ], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT) !!}
    </script>
@endpush

@section('content')
    @php
        $otherServices = $relatedServices->isNotEmpty()
            ? $relatedServices
            : \App\Models\Service::active()->whereKeyNot($service->getKey())->orderBy('name')->take(5)->get();

        $highlights = collect($service->features ?: [])
            ->filter()
            ->take(4)
            ->values();

        $offerItems = $highlights->isNotEmpty()
            ? $highlights->take(2)
            : collect([
                'Формируем понятный состав работ и последовательность этапов.',
                'Собираем ориентир по бюджету, срокам и организационным решениям.',
            ]);

        $galleryImages = collect($service->gallery ?: [])
            ->map(function ($image) {
                $path = data_get($image, 'image', $image);

                if (! is_string($path) || $path === '') {
                    return null;
                }

                if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '/storage/', 'storage/'])) {
                    return \Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])
                        ? $path
                        : asset(\Illuminate\Support\Str::startsWith($path, '/') ? ltrim($path, '/') : $path);
                }

                return asset('storage/' . ltrim($path, '/'));
            })
            ->filter()
            ->values();

        $faqItems = [
            [
                'q' => 'Что входит в услугу?',
                'a' => $service->short_description ?: 'Состав услуги зависит от типа объекта, текущего этапа и желаемого результата. Перед стартом фиксируем объем и границы работ.',
            ],
            [
                'q' => 'Как рассчитывается стоимость?',
                'a' => 'Стоимость зависит от площади, сложности задачи, материалов и сроков. Предварительный ориентир можно получить после короткого брифа или через калькулятор.',
            ],
            [
                'q' => 'Можно ли адаптировать услугу под объект?',
                'a' => 'Да. Мы масштабируем состав работ под частный дом, квартиру, коммерческий объект или отдельный этап проекта.',
            ],
            [
                'q' => 'С чего начать запуск работ?',
                'a' => 'Оставьте заявку, и мы уточним исходные данные, после чего предложим оптимальный формат реализации и следующий шаг.',
            ],
        ];

        $processItems = [
            ['title' => 'Бриф и анализ задачи', 'text' => 'Уточняем цель проекта, ограничения объекта, сроки и желаемый результат.'],
            ['title' => 'Смета и планирование', 'text' => 'Формируем состав работ, этапность, ориентир по бюджету и график запуска.'],
            ['title' => 'Реализация и контроль', 'text' => 'Организуем исполнение, снабжение и контроль ключевых точек по проекту.'],
        ];
    @endphp

    <div class="page-header parallaxie" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">{{ $service->name }}</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Услуги</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $service->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-service-single">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="page-single-sidebar">
                        <div class="page-category-list wow fadeInUp">
                            <h3>Другие услуги</h3>
                            <ul>
                                @foreach ($otherServices as $otherService)
                                    <li><a href="{{ route('services.show', $otherService) }}">{{ $otherService->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="sidebar-cta-box wow fadeInUp" data-wow-delay="0.25s">
                            <div class="sidebar-cta-title">
                                <h3>Нужна помощь по проекту?</h3>
                            </div>

                            <div class="sidebar-cta-body">
                                <div class="sidebar-cta-body-content">
                                    <ul>
                                        <li><img src="{{ asset('assets/images/icon-phone-primary.svg') }}" alt=""><a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone', '+79990000000')) }}">{{ \App\Models\Setting::get('company_phone', '+7 (999) 000-00-00') }}</a></li>
                                        <li><i class="fa-regular fa-envelope"></i><a href="mailto:{{ \App\Models\Setting::get('company_email', 'info@goldentour.local') }}">{{ \App\Models\Setting::get('company_email', 'info@goldentour.local') }}</a></li>
                                    </ul>
                                </div>
                               <div class="sidebar-cta-body-image">
                                    <figure>
                                        <img src="{{ $service->image ? asset('storage/' . $service->image) : asset('assets/images/service-image-1.jpg') }}" alt="{{ $service->name }}">
                                    </figure>
                               </div>
                            </div>
                        </div>

                        <div class="sidebar-cta-box wow fadeInUp" data-wow-delay="0.35s">
                            <div class="sidebar-cta-title">
                                <h3>Быстрая заявка</h3>
                            </div>
                            <div class="sidebar-cta-body">
                                <div class="w-100">
                                    <x-lead-form :service="$service" prefix="service-sidebar" source="service_page" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="service-single-content">
                        <div class="page-single-image">
                            <figure class="image-anime reveal">
                                <img src="{{ $service->image ? asset('storage/' . $service->image) : asset('assets/images/service-image-1.jpg') }}" alt="{{ $service->name }}">
                            </figure>
                        </div>

                        <div class="service-entry">
                            @if ($service->short_description)
                                <p class="wow fadeInUp">{{ $service->short_description }}</p>
                            @endif

                            <p class="wow fadeInUp">Услуга адаптируется под тип объекта, текущий этап и формат участия команды. До старта фиксируем состав работ, последовательность действий, ориентир по срокам и ключевые контрольные точки.</p>

                            <div class="service-why-choose-box">
                                <h2 class="text-anime-style-3">Почему выбирают эту услугу</h2>
                                <p class="wow fadeInUp">Мы не ограничиваемся одной операцией. Смотрим на задачу как на часть всего проекта, поэтому заранее учитываем смету, организацию работ, материалы и реальные ограничения объекта.</p>

                                <div class="service-why-choose-item-list">
                                    @foreach (($highlights->isNotEmpty() ? $highlights : collect([
                                        'Понятный состав работ без скрытых этапов.',
                                        'Прозрачный ориентир по срокам и бюджету.',
                                        'Адаптация решения под реальный сценарий объекта.',
                                        'Контроль качества и коммуникации на всех этапах.',
                                    ])) as $index => $highlight)
                                        <div class="service-why-choose-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format(($index % 2 === 0 ? 0.4 : 0.2), 1) }}s" @endif>
                                            <div class="icon-box">
                                                <img src="{{ asset('assets/images/icon-about-item-' . (($index % 2) + 1) . '.svg') }}" alt="">
                                            </div>
                                            <div class="service-why-choose-item-content">
                                                <h3>{{ \Illuminate\Support\Str::limit($highlight, 38) }}</h3>
                                                <p>{{ $highlight }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="service-offer-box">
                                <h2 class="text-anime-style-3">Что входит</h2>
                                <p class="wow fadeInUp">Состав работ уточняется под задачу, но обычно включает подготовку, организацию процесса, исполнительную часть и контроль результата. Ниже основные элементы, которые чаще всего входят в услугу.</p>

                                <div class="service-offer-list wow fadeInUp" data-wow-delay="0.2s">
                                    <ul>
                                        @foreach (($highlights->isNotEmpty() ? $highlights : collect([
                                            'Подготовка исходных данных и анализ объекта',
                                            'Согласование состава и очередности работ',
                                            'Подбор материалов и организационных решений',
                                            'Контроль качества и сопровождение исполнения',
                                        ])) as $highlight)
                                            <li>{{ $highlight }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                <div class="service-offer-item-list wow fadeInUp" data-wow-delay="0.4s">
                                    @foreach ($offerItems as $index => $offerItem)
                                        <div class="service-offer-item">
                                            <div class="service-offer-item-image">
                                                <figure class="image-anime">
                                                    <img src="{{ $galleryImages->get($index) ?: ($service->image ? asset('storage/' . $service->image) : asset('assets/images/service-image-' . (($index % 4) + 1) . '.jpg')) }}" alt="{{ $service->name }}">
                                                </figure>
                                            </div>
                                            <div class="service-offer-item-content">
                                                <h3>{{ $index === 0 ? 'Подготовка и оценка' : 'Планирование и реализация' }}</h3>
                                                <p>{{ $offerItem }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="service-process-box">
                                <h2 class="text-anime-style-3">Процесс работы</h2>
                                <p class="wow fadeInUp">Проект движется по понятной последовательности: сначала фиксируем задачу, затем собираем решение и только после этого запускаем реализацию. Это снижает риск срывов и хаоса на объекте.</p>

                                <div class="service-process-image-content">
                                    <div class="service-process-content">
                                        @foreach ($processItems as $index => $processItem)
                                            <div class="service-process-item wow fadeInUp" data-wow-delay="{{ number_format(0.2 + ($index * 0.2), 1) }}s">
                                                <div class="icon-box">
                                                    <img src="{{ asset('assets/images/icon-what-we-do-item-' . (($index % 2) + 1) . '.svg') }}" alt="">
                                                </div>
                                                <div class="service-process-item-content">
                                                    <h3>{{ $processItem['title'] }}</h3>
                                                    <p>{{ $processItem['text'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="service-process-image">
                                        <figure class="image-anime reveal">
                                            <img src="{{ $galleryImages->get(2) ?: ($service->image ? asset('storage/' . $service->image) : asset('assets/images/service-image-3.jpg')) }}" alt="{{ $service->name }}">
                                        </figure>
                                    </div>
                                </div>
                            </div>

                            @if ($service->full_description)
                                <div class="content-block wow fadeInUp">
                                    {!! $service->full_description !!}
                                </div>
                            @endif
                        </div>

                        <div class="page-single-faqs">
                            <div class="section-title">
                                <h2 class="text-anime-style-3" data-cursor="-opaque">Что еще важно знать</h2>
                            </div>

                            <div class="faq-accordion" id="service-accordion">
                                @foreach ($faqItems as $index => $faq)
                                    <div class="accordion-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format($index * 0.2, 1) }}s" @endif>
                                        <h2 class="accordion-header" id="service-heading-{{ $index }}">
                                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#service-collapse-{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="service-collapse-{{ $index }}">
                                                {{ $faq['q'] }}
                                            </button>
                                        </h2>
                                        <div id="service-collapse-{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="service-heading-{{ $index }}" data-bs-parent="#service-accordion">
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
        </div>
    </div>
@endsection
