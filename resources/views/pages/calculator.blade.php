@extends('layouts.app')

@section('meta_title', 'Калькулятор стоимости строительства и ремонта')
@section('meta_description', 'Онлайн-калькулятор предварительной стоимости строительных и отделочных работ.')

@section('content')
    @php
        $calculatorServices = $services->map(fn ($service) => [
            'id' => $service->id,
            'slug' => $service->slug,
            'name' => $service->name,
            'price_from' => (float) ($service->price_from ?? 0),
            'area_from' => (int) ($service->area_from ?? 1),
            'duration' => $service->duration,
        ])->values();

        $selectedSlug = request('service');
        $selectedService = $services->firstWhere('slug', $selectedSlug);
        $selectedCategoryName = $selectedService?->category?->name ?? 'Подберите услугу';
    @endphp

    <div class="page-header parallaxie" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">Калькулятор</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Калькулятор</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="page-pricing"
        x-data="calculatorComponent({
            services: {{ \Illuminate\Support\Js::from($calculatorServices) }},
            selectedService: {{ \Illuminate\Support\Js::from($selectedSlug) }},
        })"
    >
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="pricing-item wow fadeInUp">
                        <div class="pricing-item-header">
                            <div class="icon-box">
                                <img src="{{ asset('assets/images/icon-pricing-1.svg') }}" alt="">
                            </div>
                            <div class="pricing-item-content">
                                <p>Шаг 1</p>
                                <h2>Выбор услуги<sub>/категории</sub></h2>
                            </div>
                        </div>
                        <div class="pricing-item-body">
                            <div class="pricing-item-list">
                                <h3>Что нужно указать:</h3>
                                <ul>
                                    <li>Тип работ или нужную услугу</li>
                                    <li>Площадь объекта для расчета</li>
                                    <li>Нужны ли материалы, дизайн или срочный запуск</li>
                                </ul>
                            </div>
                            <div class="pricing-item-btn">
                                <a href="#calculator-form" class="btn-default">Перейти к расчету</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="pricing-item highlighted-box wow fadeInUp" data-wow-delay="0.2s" id="calculator-form">
                        <div class="pricing-item-header">
                            <div class="icon-box">
                                <img src="{{ asset('assets/images/icon-pricing-2.svg') }}" alt="">
                            </div>
                            <div class="pricing-item-content">
                                <p x-text="selectedServiceName !== 'Не выбрана' ? selectedServiceName : '{{ $selectedCategoryName }}'"></p>
                                <h2><span x-text="formattedTotalPrice"></span><sub>/ориентир</sub></h2>
                            </div>
                        </div>
                        <div class="pricing-item-body">
                            <div class="pricing-item-list">
                                <h3>Параметры расчета:</h3>

                                <div class="contact-form">
                                    <div class="row">
                                        <div class="form-group col-md-12 mb-4">
                                            <select class="form-control" x-model="serviceId">
                                                <option value="">Выберите услугу</option>
                                                @foreach ($serviceCategories as $category)
                                                    <optgroup label="{{ $category->name }}">
                                                        @foreach ($category->services as $service)
                                                            <option value="{{ $service->slug }}">{{ $service->name }}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12 mb-4">
                                            <label for="area-range" class="form-label">Площадь: <span x-text="areaLabel"></span></label>
                                            <input id="area-range" type="range" min="10" max="500" step="5" x-model="area" class="w-full">
                                        </div>

                                        <div class="form-group col-md-12 mb-2">
                                            <div class="pricing-item-list">
                                                <ul>
                                                    <li>
                                                        <label class="option-row">
                                                            <input type="checkbox" value="materials" x-model="options">
                                                            <span>Закупка материалов</span>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="option-row">
                                                            <input type="checkbox" value="design" x-model="options">
                                                            <span>Дизайн-проект</span>
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label class="option-row">
                                                            <input type="checkbox" value="urgent" x-model="options">
                                                            <span>Срочный запуск</span>
                                                        </label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pricing-item-btn">
                                <a href="#calculator-request" class="btn-default">Получить точный расчет</a>
                            </div>

                            <p class="mt-3 text-sm text-white-50" x-show="loading" style="display: none;">Пересчитываем стоимость...</p>
                            <p class="mt-3 text-sm text-rose-300" x-show="error" x-text="error" style="display: none;"></p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6">
                    <div class="pricing-item wow fadeInUp" data-wow-delay="0.4s">
                        <div class="pricing-item-header">
                            <div class="icon-box">
                                <img src="{{ asset('assets/images/icon-pricing-3.svg') }}" alt="">
                            </div>
                            <div class="pricing-item-content">
                                <p>Шаг 2</p>
                                <h2>Точный расчет<sub>/заявка</sub></h2>
                            </div>
                        </div>
                        <div class="pricing-item-body">
                            <div class="pricing-item-list">
                                <h3>Что вы получите:</h3>
                                <ul>
                                    <li>Уточнение состава работ по вашему объекту</li>
                                    <li>Ориентир по срокам и этапам</li>
                                    <li>Более точную смету после короткого брифа</li>
                                </ul>
                            </div>
                            <div class="pricing-item-btn">
                                <a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone', '+375290000000')) }}" class="btn-default">Позвонить сейчас</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="pricing-benefit-list wow fadeInUp" data-wow-delay="0.2s">
                        <ul>
                            <li><img src="{{ asset('assets/images/icon-pricing-benefit-1.svg') }}" alt="">Быстрый ориентир по бюджету до выезда на объект</li>
                            <li><img src="{{ asset('assets/images/icon-pricing-benefit-2.svg') }}" alt="">Прозрачный расчет без скрытых опций</li>
                            <li><img src="{{ asset('assets/images/icon-pricing-benefit-3.svg') }}" alt="">Можно начать с услуги, даже если проект еще не собран полностью</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="our-story dark-section parallaxie" style="background-image: url('{{ asset('assets/images/our-story-bg-image.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="our-story-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">Как использовать</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Калькулятор дает ориентир, с которого удобно начинать разговор о проекте</h2>
                        </div>

                        <div class="watch-video-circle">
                            <a href="#calculator-request" data-cursor-text="Start">
                                <img src="{{ asset('assets/images/watch-video-circle.svg') }}" alt="">
                            </a>
                        </div>
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
                        <h3 class="wow fadeInUp">Преимущества расчета</h3>
                        <h2 class="text-effect" data-cursor="-opaque">
                            Помогаем быстро собрать ориентир
                            <span class="feature-title-img-1"><img src="{{ asset('assets/images/icon-feature-title-1.svg') }}" alt=""></span>
                            по бюджету, этапам
                            <span class="feature-title-img-2"><img src="{{ asset('assets/images/icon-feature-title-2.svg') }}" alt=""></span>
                            и дальнейшим действиям
                            <span class="feature-title-img-3"><img src="{{ asset('assets/images/author-1.jpg') }}" alt=""><img src="{{ asset('assets/images/author-2.jpg') }}" alt=""><img src="{{ asset('assets/images/author-3.jpg') }}" alt=""></span>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-md-6 order-1">
                    <div class="feature-item box-1 wow fadeInUp">
                        <div class="feature-item-shape-image">
                            <figure>
                                <img src="{{ asset('assets/images/feature-item-image-1.jpg') }}" alt="">
                            </figure>
                        </div>
                        <div class="feature-item-content-box">
                            <div class="feature-item-content">
                                <h3>Быстрый старт</h3>
                                <p>Не нужно готовить полноценное ТЗ. Для первого ориентира достаточно выбрать услугу и площадь.</p>
                            </div>
                            <div class="feature-item-list">
                                <ul>
                                    <li>Удобно на раннем этапе проекта</li>
                                    <li>Помогает быстро сверить ожидания по бюджету</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 order-xl-2 order-md-3 order-2">
                    <div class="feature-item box-2 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="feature-item-info">
                            <div class="feature-item-info-content">
                                <p>Ваш объект, наш ориентир</p>
                                <h3>Оставьте заявку и получите более точный расчет под реальные условия</h3>
                            </div>
                            <div class="feature-item-btn">
                                <a href="#calculator-request" class="readmore-btn">Отправить заявку</a>
                            </div>
                        </div>
                        <div class="feature-item-image">
                            <figure>
                                <img src="{{ asset('assets/images/feature-item-image-2.png') }}" alt="">
                            </figure>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-md-6 order-xl-3 order-md-2 order-3">
                    <div class="feature-item box-3 wow fadeInUp" data-wow-delay="0.4s">
                        <div class="feature-item-content-box">
                            <div class="feature-item-content">
                                <h2><span class="counter">{{ $services->count() }}</span>+</h2>
                                <h3>Услуг в расчете</h3>
                            </div>
                            <div class="feature-item-counter-info">
                                <p>Калькулятор работает по активным услугам и помогает подобрать направление даже без полного состава проекта.</p>
                            </div>
                        </div>
                        <div class="feature-item-tag-list">
                            <ul>
                                <li>Смета</li>
                                <li>Ориентир</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 order-5">
                    <div class="section-footer-text section-satisfy-img wow fadeInUp" data-wow-delay="0.2s">
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
                        <p>Нужен расчет с учетом объекта? <a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone', '+375290000000')) }}">Обсудите задачу с нами по телефону</a></p>
                        <ul>
                            <li><span class="counter">{{ $serviceCategories->count() }}</span></li>
                            <li>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </li>
                            <li>категорий услуг в каталоге</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-contact-us" id="calculator-request">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-5">
                    <div class="contact-us-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">Точный расчет</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Отправьте заявку, и мы уточним смету под ваш объект</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Предварительный ориентир уже посчитан. Теперь можно оставить контакты, чтобы мы учли реальные условия объекта, материалы, сроки и особенности запуска.</p>
                        </div>

                        <div class="contact-info-list">
                            <div class="contact-info-item wow fadeInUp">
                                <div class="icon-box">
                                    <img src="{{ asset('assets/images/icon-phone-primary.svg') }}" alt="">
                                </div>
                                <div class="contact-info-item-content">
                                    <h3>Услуга</h3>
                                    <p x-text="selectedServiceName"></p>
                                </div>
                            </div>

                            <div class="contact-info-item wow fadeInUp" data-wow-delay="0.2s">
                                <div class="icon-box">
                                    <i class="fa-solid fa-ruler-combined"></i>
                                </div>
                                <div class="contact-info-item-content">
                                    <h3>Площадь</h3>
                                    <p x-text="areaLabel"></p>
                                </div>
                            </div>

                            <div class="contact-info-item wow fadeInUp" data-wow-delay="0.4s">
                                <div class="icon-box">
                                    <i class="fa-regular fa-clock"></i>
                                </div>
                                <div class="contact-info-item-content">
                                    <h3>Срок</h3>
                                    <p x-text="selectedDuration"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="contact-us-form">
                        <div class="section-title">
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Получить точный расчет</h2>
                            <p class="wow fadeInUp">Оставьте контакты и комментарий по объекту. Подготовим следующий, более точный ориентир по смете.</p>
                        </div>

                        <div class="contact-form">
                            <form action="{{ route('leads.store') }}" method="POST" class="wow fadeInUp" data-wow-delay="0.2s" data-lead-form>
                                @csrf
                                <input type="hidden" name="source" value="calculator">
                                <input type="hidden" name="service_id" :value="selectedServiceId">
                                <input type="hidden" name="calculated_area" :value="area">
                                <input type="hidden" name="calculated_price" :value="roundedTotalPrice">

                                <div class="row">
                                    <div class="form-group col-md-6 mb-4">
                                        <input id="calc-name" name="name" type="text" required class="form-control" placeholder="Ваше имя">
                                    </div>

                                    <div class="form-group col-md-6 mb-4">
                                        <input id="calc-phone" name="phone" type="tel" required class="form-control" placeholder="+375 (__) ___-__-__" data-phone-mask>
                                    </div>

                                    <div class="form-group col-md-12 mb-4">
                                        <input id="calc-email" name="email" type="email" class="form-control" placeholder="Email">
                                    </div>

                                    <div class="form-group col-md-12 mb-5">
                                        <textarea id="calc-message" name="message" rows="6" class="form-control" placeholder="Опишите объект, желаемый результат или ограничения по срокам"></textarea>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="contact-form-btn">
                                            <button type="submit" class="btn-default" data-submit-button>
                                                <span data-submit-default>Отправить заявку</span>
                                                <span class="hidden" data-submit-loading>Отправка...</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                                <h2 class="text-anime-style-3" data-cursor="-opaque">Что важно понимать про предварительный расчет</h2>
                                <p class="wow fadeInUp" data-wow-delay="0.2s">Калькулятор не заменяет детальную смету, но помогает быстро сориентироваться по бюджету и понять, с чего начать обсуждение проекта.</p>
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
                                <h3>Позвоните для уточнения</h3>
                                <p><a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone', '+375290000000')) }}">{{ \App\Models\Setting::get('company_phone', '+375 (29) 000-00-00') }}</a></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="faq-accordion" id="calculator-accordion">
                        @php
                            $faqs = [
                                ['q' => 'Насколько точен калькулятор?', 'a' => 'Это предварительный ориентир. На итог влияют реальные размеры объекта, материалы, инженерные решения, доступ на площадку и состав работ.'],
                                ['q' => 'Можно ли посчитать проект без полного ТЗ?', 'a' => 'Да. Именно для этого калькулятор и нужен: он помогает начать разговор о проекте, даже если все детали еще не собраны.'],
                                ['q' => 'Что делать после расчета?', 'a' => 'Оставьте заявку. Мы уточним вводные данные и подготовим более точный ориентир или следующий практический шаг.'],
                                ['q' => 'Можно ли рассчитать только отдельный этап?', 'a' => 'Да. Калькулятор подходит и для отдельных услуг, и для более комплексных сценариев запуска проекта.'],
                            ];
                        @endphp

                        @foreach ($faqs as $index => $faq)
                            <div class="accordion-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format($index * 0.2, 1) }}s" @endif>
                                <h2 class="accordion-header" id="calculator-heading-{{ $index }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#calculator-collapse-{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="calculator-collapse-{{ $index }}">
                                        {{ $faq['q'] }}
                                    </button>
                                </h2>
                                <div id="calculator-collapse-{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="calculator-heading-{{ $index }}" data-bs-parent="#calculator-accordion">
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
