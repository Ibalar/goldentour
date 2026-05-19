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

        // Блок "Почему выбирают"
        $whyChooseItems = collect($service->why_choose_items ?: [])->filter()->values();

        // Блок "Что входит"
        $offerList = collect($service->offer_list ?: [])->filter()->values();
        $offerItems = collect($service->offer_items ?: [])->filter()->values();

        // Блок "Процесс работы"
        $processItems = collect($service->process_items ?: [])->filter()->values();

        // Блок FAQ
        $faqItems = collect($service->faq_items ?: [])->filter()->values();

        // Изображения из галереи
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

        // Fallback данные для FAQ
        $defaultFaqItems = collect([
            ['question' => 'Что входит в услугу?', 'answer' => $service->short_description ?: 'Состав услуги зависит от типа объекта, текущего этапа и желаемого результата. Перед стартом фиксируем объем и границы работ.'],
            ['question' => 'Как рассчитывается стоимость?', 'answer' => 'Стоимость зависит от площади, сложности задачи, материалов и сроков. Предварительный ориентир можно получить после короткого брифа или через калькулятор.'],
            ['question' => 'Можно ли адаптировать услугу под объект?', 'answer' => 'Да. Мы масштабируем состав работ под частный дом, квартиру, коммерческий объект или отдельный этап проекта.'],
            ['question' => 'С чего начать запуск работ?', 'answer' => 'Оставьте заявку, и мы уточним исходные данные, после чего предложим оптимальный формат реализации и следующий шаг.'],
        ]);

        $displayFaqItems = $faqItems->isNotEmpty() ? $faqItems : $defaultFaqItems;
    @endphp

    @php
        $bgImage = match(true) {
            $service->breadcrumb_image !== null => asset('storage/' . $service->breadcrumb_image),
            $service->category?->breadcrumb_image !== null => asset('storage/' . $service->category->breadcrumb_image),
            $service->category?->parent?->breadcrumb_image !== null => asset('storage/' . $service->category->parent->breadcrumb_image),
            default => asset('assets/images/page-header-bg.jpg'),
        };
    @endphp
    <div class="page-header parallaxie" style="background-image: url('{{ $bgImage }}');">
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
                <div class="col-lg-8">
                    <div class="service-single-content">

                        <div class="service-entry">
                            @if ($service->short_description)
                                <p class="wow fadeInUp">{{ $service->short_description }}</p>
                            @endif

                            @if ($service->full_description)
                                <div class="content-block wow fadeInUp">
                                    {!! $service->full_description !!}
                                </div>
                            @endif

                            {{-- Блок "Почему выбирают эту услугу" --}}
                            @if($whyChooseItems->isNotEmpty() || $service->why_choose_title)
                                <div class="service-why-choose-box">
                                    <h2 class="text-anime-style-3">{{ $service->why_choose_title ?? 'Почему выбирают эту услугу' }}</h2>
                                    @if($service->why_choose_subtitle)
                                        <p class="wow fadeInUp">{{ $service->why_choose_subtitle }}</p>
                                    @endif

                                    @if($whyChooseItems->isNotEmpty())
                                        <div class="service-why-choose-item-list">
                                            @foreach ($whyChooseItems as $index => $item)
                                                <div class="service-why-choose-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format(($index % 2 === 0 ? 0.4 : 0.2), 1) }}s" @endif>
                                                    <div class="icon-box">
                                                        @if(!empty($item['icon']))
                                                            <img src="{{ asset('assets/images/' . $item['icon']) }}" alt="">
                                                        @else
                                                            <img src="{{ asset('assets/images/icon-about-item-' . (($index % 2) + 1) . '.svg') }}" alt="">
                                                        @endif
                                                    </div>
                                                    <div class="service-why-choose-item-content">
                                                        <h3>{{ $item['title'] ?? \Illuminate\Support\Str::limit($item['description'] ?? '', 38) }}</h3>
                                                        <p>{!! $item['description'] ?? '' !!}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Блок "Что входит" --}}
                            @if($offerList->isNotEmpty() || $offerItems->isNotEmpty() || $service->offer_title)
                                <div class="service-offer-box">
                                    <h2 class="text-anime-style-3">{{ $service->offer_title ?? 'Что входит' }}</h2>
                                    @if($service->offer_subtitle)
                                        <p class="wow fadeInUp">{{ $service->offer_subtitle }}</p>
                                    @else
                                        <p class="wow fadeInUp">Состав работ уточняется под задачу, но обычно включает подготовку, организацию процесса, исполнительную часть и контроль результата. Ниже основные элементы, которые чаще всего входят в услугу.</p>
                                    @endif

                                    @if($offerList->isNotEmpty())
                                        <div class="service-offer-list wow fadeInUp" data-wow-delay="0.2s">
                                            <ul>
                                                @foreach ($offerList as $item)
                                                    <li>{{ $item }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if($offerItems->isNotEmpty())
                                        <div class="service-offer-item-list wow fadeInUp" data-wow-delay="0.4s">
                                            @foreach ($offerItems as $index => $offerItem)
                                                <div class="service-offer-item">
                                                    <div class="service-offer-item-image">
                                                        <figure class="image-anime">
                                                            @if(!empty($offerItem['image']))
                                                                <img src="{{ asset('storage/' . $offerItem['image']) }}" alt="{{ $offerItem['title'] ?? $service->name }}">
                                                            @else
                                                                <img src="{{ $galleryImages->get($index) ?: ($service->image ? asset('storage/' . $service->image) : asset('assets/images/service-image-' . (($index % 4) + 1) . '.jpg')) }}" alt="{{ $service->name }}">
                                                            @endif
                                                        </figure>
                                                    </div>
                                                    <div class="service-offer-item-content">
                                                        <h3>{{ $offerItem['title'] ?? '' }}</h3>
                                                        <p>{{ $offerItem['description'] ?? '' }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Блок "Процесс работы" --}}
                            @if($processItems->isNotEmpty() || $service->process_title)
                                <div class="service-process-box">
                                    <h2 class="text-anime-style-3">{{ $service->process_title ?? 'Процесс работы' }}</h2>
                                    @if($service->process_subtitle)
                                        <p class="wow fadeInUp">{{ $service->process_subtitle }}</p>
                                    @else
                                        <p class="wow fadeInUp">Проект движется по понятной последовательности: сначала фиксируем задачу, затем собираем решение и только после этого запускаем реализацию. Это снижает риск срывов и хаоса на объекте.</p>
                                    @endif

                                    <div class="service-process-image-content">
                                        <div class="service-process-content">
                                            @if($processItems->isNotEmpty())
                                                @foreach ($processItems as $index => $processItem)
                                                    <div class="service-process-item wow fadeInUp" data-wow-delay="{{ number_format(0.2 + ($index * 0.2), 1) }}s">
                                                        <div class="icon-box">
                                                            @if(!empty($processItem['icon']))
                                                                <img src="{{ asset('assets/images/' . $processItem['icon']) }}" alt="">
                                                            @else
                                                                <img src="{{ asset('assets/images/icon-what-we-do-item-' . (($index % 2) + 1) . '.svg') }}" alt="">
                                                            @endif
                                                        </div>
                                                        <div class="service-process-item-content">
                                                            <h3>{{ $processItem['title'] ?? '' }}</h3>
                                                            <p>{{ $processItem['description'] ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                @php
                                                    $defaultProcessItems = [
                                                        ['title' => 'Бриф и анализ задачи', 'description' => 'Уточняем цель проекта, ограничения объекта, сроки и желаемый результат.'],
                                                        ['title' => 'Смета и планирование', 'description' => 'Формируем состав работ, этапность, ориентир по бюджету и график запуска.'],
                                                        ['title' => 'Реализация и контроль', 'description' => 'Организуем исполнение, снабжение и контроль ключевых точек по проекту.'],
                                                    ];
                                                @endphp
                                                @foreach ($defaultProcessItems as $index => $processItem)
                                                    <div class="service-process-item wow fadeInUp" data-wow-delay="{{ number_format(0.2 + ($index * 0.2), 1) }}s">
                                                        <div class="icon-box">
                                                            <img src="{{ asset('assets/images/icon-what-we-do-item-' . (($index % 2) + 1) . '.svg') }}" alt="">
                                                        </div>
                                                        <div class="service-process-item-content">
                                                            <h3>{{ $processItem['title'] }}</h3>
                                                            <p>{{ $processItem['description'] }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <div class="service-process-image">
                                            <figure class="image-anime reveal">
                                                @if($service->process_image)
                                                    <img src="{{ asset('storage/' . $service->process_image) }}" alt="{{ $service->name }}">
                                                @else
                                                    <img src="{{ $galleryImages->get(2) ?: ($service->image ? asset('storage/' . $service->image) : asset('assets/images/service-image-3.jpg')) }}" alt="{{ $service->name }}">
                                                @endif
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                            @endif


                        </div>

                        {{-- Блок FAQ "Что еще важно знать" --}}
                        <div class="page-single-faqs">
                            <div class="section-title">
                                <h2 class="text-anime-style-3" data-cursor="-opaque">{{ $service->faq_title ?? 'Что еще важно знать' }}</h2>
                            </div>

                            <div class="faq-accordion" id="service-accordion">
                                @foreach ($displayFaqItems as $index => $faq)
                                    <div class="accordion-item wow fadeInUp" @if($index > 0) data-wow-delay="{{ number_format($index * 0.2, 1) }}s" @endif>
                                        <h2 class="accordion-header" id="service-heading-{{ $index }}">
                                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#service-collapse-{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="service-collapse-{{ $index }}">
                                                {{ $faq['question'] ?? $faq['q'] ?? '' }}
                                            </button>
                                        </h2>
                                        <div id="service-collapse-{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="service-heading-{{ $index }}" data-bs-parent="#service-accordion">
                                            <div class="accordion-body">
                                                <p>{!! $faq['answer'] ?? $faq['a'] ?? '' !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

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
                                <h3>Нужна помощь или консультация?</h3>
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
                                        <img src="{{ asset('assets/images/sidebar-body-image.png') }}" alt="{{ $service->name }}">
                                    </figure>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Блок тарифных планов --}}
    @php
        $pricingPlans = collect($service->pricing_plans ?: [])->filter()->values();
        $pricingFeatures = collect($service->pricing_features ?: [])->filter()->values();
    @endphp

    @if($pricingPlans->isNotEmpty() && $pricingFeatures->isNotEmpty())
        <div class="page-pricing">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title section-title-center">
                            <h2 class="text-anime-style-3">{{ $service->pricing_title ?? 'Виды работ' }}</h2>
                            @if($service->pricing_subtitle)
                                <p class="wow fadeInUp">{{ $service->pricing_subtitle }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="pricing-table-wrapper wow fadeInUp">
                            <div class="table-responsive">
                                <table class="table pricing-table">
                                    <thead>
                                        <tr>
                                            <th style="min-width: 300px;">{{ $service->pricing_title ?? 'Виды работ' }}</th>
                                            @foreach($pricingPlans as $plan)
                                                <th class="text-center {{ !empty($plan['highlighted']) ? 'highlighted' : '' }}" style="min-width: 200px;">
                                                    <div class="pricing-header">
                                                        <h4>{{ $plan['name'] ?? '' }}</h4>
                                                        @if(!empty($plan['price']))
                                                            <span class="pricing-price">{{ $plan['price'] }}</span>
                                                        @endif
                                                    </div>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pricingFeatures as $feature)
                                            <tr>
                                                <td class="feature-name">{{ $feature['name'] ?? '' }}</td>
                                                @php $featureValues = collect($feature['values'] ?? [])->values(); @endphp
                                                @foreach($pricingPlans as $planIndex => $plan)
                                                    @php
                                                        $value = $featureValues->get($planIndex, '-');
                                                    @endphp
                                                    <td class="text-center {{ !empty($plan['highlighted']) ? 'highlighted' : '' }}">
                                                        @if($value === '+' || $value === 'да' || $value === 'yes')
                                                            <i class="fa-solid fa-check text-success"></i>
                                                        @elseif($value === '-' || $value === 'нет' || $value === 'no')
                                                            <i class="fa-solid fa-minus text-muted"></i>
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
<style>
.page-pricing {
    padding: 100px 0;
    background: #f8f9fa;
}

.pricing-table-wrapper {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 30px rgba(0,0,0,0.08);
    overflow: hidden;
}

.pricing-table {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}

.pricing-table thead th {
    background: #1a1a1a;
    color: #fff;
    font-weight: 600;
    padding: 24px 16px;
    border: none;
    vertical-align: middle;
}

.pricing-table thead th:first-child {
    background: #1a1a1a;
    text-align: left;
    font-size: 1.1rem;
}

.pricing-table thead th.highlighted {
    background: #c9a962;
}

.pricing-header h4 {
    color: #fff;
    font-size: 1rem;
    margin-bottom: 8px;
    font-weight: 600;
}

.pricing-price {
    display: block;
    color: rgba(255,255,255,0.9);
    font-size: 0.9rem;
}

.pricing-table tbody tr:nth-child(even) {
    background: #f8f9fa;
}

.pricing-table tbody td {
    padding: 16px;
    border: none;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
    font-size: 0.95rem;
}

.pricing-table tbody td.feature-name {
    font-weight: 500;
    text-align: left;
    color: #333;
}

.pricing-table tbody td.highlighted {
    background: rgba(201, 169, 98, 0.08);
}

.pricing-table tbody td .fa-check {
    color: #28a745;
    font-size: 1.1rem;
}

.pricing-table tbody td .fa-minus {
    color: #adb5bd;
    font-size: 1.1rem;
}

@media (max-width: 991px) {
    .page-pricing {
        padding: 60px 0;
    }
    .pricing-table thead th,
    .pricing-table tbody td {
        padding: 12px 8px;
        font-size: 0.85rem;
    }
}
</style>
@endpush
