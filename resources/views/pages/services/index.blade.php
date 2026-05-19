@extends('layouts.app')

@section('meta_title', 'Услуги строительной компании ' . \App\Models\Setting::get('company_name', 'Золотой Тур'))
@section('meta_description', 'Строительство, ремонт, проектирование и комплексные услуги под ключ с понятной сметой, этапами и сроками.')

@section('content')
    @php
        $services = $categories->flatMap(function ($category) {
            return $category->activeServices->map(function ($service) use ($category) {
                $service->setRelation('category', $service->category ?? $category);

                return $service;
            });
        })->values();

        $serviceCount = $services->count();
    @endphp

    <div class="page-header parallaxie" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">Услуги</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Услуги</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-services">
        <div class="container">
            @if ($services->isNotEmpty())
                <div class="row services-item-list">
                    @foreach ($services as $index => $service)
                        <div class="col-xl-3 col-md-6">
                            <div class="service-item wow fadeInUp {{ $index === 0 ? 'active' : '' }}" @if($index > 0) data-wow-delay="{{ number_format(min($index * 0.2, 1.4), 1) }}s" @endif>
                                <div class="service-item-header">
                                    <div class="service-item-title">
                                        <h2><a href="{{ route('services.show', $service) }}">{{ $service->name }}</a></h2>
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
            @else
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-footer-text">
                            <p><span>Каталог</span> Активные услуги пока не добавлены. <a href="{{ route('contacts') }}">Свяжитесь с нами для консультации</a></p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="our-story dark-section parallaxie" style="background-image: url('{{ asset('assets/images/our-story-bg-image.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="our-story-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">Как мы работаем</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Подбираем решение под задачу, бюджет и этап проекта, а не продаем одну и ту же схему всем подряд</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Поможем определить состав работ, порядок запуска и ориентир по стоимости до старта.</p>
                        </div>

                        <div class="watch-video-circle">
                            <a href="{{ route('calculator') }}" data-cursor-text="Рассчитать">
                                <img src="{{ asset('assets/images/watch-video-circle.svg') }}" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="our-faqs">
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <div class="our-faqs-content">
                        <div class="faqs-title-box">
                            <div class="section-title">
                                <h3 class="wow fadeInUp">Частые вопросы</h3>
                                <h2 class="text-anime-style-3" data-cursor="-opaque">Что важно уточнить перед выбором услуги</h2>
                                <p class="wow fadeInUp" data-wow-delay="0.2s">Если задача еще не сформулирована до конца, начните с консультации. Мы поможем определить нужную услугу, этапы и ориентир по смете.</p>
                            </div>

                            <div class="our-faqs-btn wow fadeInUp" data-wow-delay="0.4s">
                                <a href="{{ route('calculator') }}" class="btn-default">Перейти в калькулятор</a>
                            </div>
                        </div>

                        <div class="faq-contact-box wow fadeInUp" data-wow-delay="0.6s">
                            <div class="icon-box">
                                <img src="{{ asset('assets/images/icon-phone-primary.svg') }}" alt="">
                            </div>
                            <div class="faq-contact-box-content">
                                <h3>Нужна консультация</h3>
                                <p><a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone', '+79990000000')) }}">{{ \App\Models\Setting::get('company_phone', '+7 (999) 000-00-00') }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="faq-accordion" id="services-accordion">
                        @php
                            $faqs = [
                                ['q' => 'Как понять, какая услуга подходит под мою задачу?', 'a' => 'Опишите объект, сроки и желаемый результат. Мы поможем определить, нужен ли вам отдельный этап работ или комплексное решение под ключ.'],
                                ['q' => 'Можно ли заказать только часть работ?', 'a' => 'Да. Мы можем подключиться на конкретный этап: проектирование, черновые работы, отделку, комплектацию или управление объектом.'],
                                ['q' => 'Когда можно получить ориентир по стоимости?', 'a' => 'Предварительный диапазон обычно можно определить после короткого брифа. Точный расчет зависит от объема, материалов и условий объекта.'],
                                ['q' => 'Работаете ли вы с нестандартными задачами?', 'a' => 'Да. Если задача выходит за рамки типовой услуги, мы собираем индивидуальный состав работ и предлагаем подходящий формат реализации.'],
                            ];
                        @endphp

                        @foreach ($faqs as $index => $faq)
                            <div class="accordion-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format($index * 0.2, 1) }}s" @endif>
                                <h2 class="accordion-header" id="services-heading-{{ $index }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#services-collapse-{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="services-collapse-{{ $index }}">
                                        {{ $faq['q'] }}
                                    </button>
                                </h2>
                                <div id="services-collapse-{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="services-heading-{{ $index }}" data-bs-parent="#services-accordion">
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
