@extends('layouts.app')

@section('meta_title', 'Контакты ' . \App\Models\Setting::get('company_name', 'Золотой Тур'))
@section('meta_description', 'Телефон, email, адрес и форма связи строительной компании ' . \App\Models\Setting::get('company_name', 'Золотой Тур') . '.')

@section('content')
    @php
        $companyPhone = \App\Models\Setting::get('company_phone', '+375 (29) 000-00-00');
        $companyEmail = \App\Models\Setting::get('company_email', 'info@goldentour.local');
        $companyAddress = 'Республика Беларусь, г. Минск, ул. Скрыганова, 6';
        $workHours = \App\Models\Setting::get('company_work_hours', 'Пн-Пт: 09:00 - 18:00');
        $mapQuery = rawurlencode($companyAddress);
    @endphp

    <div class="page-header parallaxie" style="background-image: url('{{ asset('assets/images/page-header-bg.jpg') }}');">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3" data-cursor="-opaque">Контакты</h1>
                        <nav class="wow fadeInUp">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Контакты</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-contact-us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-5">
                    <div class="contact-us-content">
                        <div class="section-title">
                            <h3 class="wow fadeInUp">Свяжитесь с нами</h3>
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Обсудим проект, задачу или следующий шаг без лишней бюрократии</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Позвоните, напишите или оставьте заявку через форму. Уточним исходные данные, подскажем подходящий формат работы и вернемся с понятным ответом в рабочее время.</p>
                        </div>

                        <div class="contact-info-list">
                            <div class="contact-info-item wow fadeInUp">
                                <div class="icon-box">
                                    <img src="{{ asset('assets/images/icon-phone-primary.svg') }}" alt="">
                                </div>
                                <div class="contact-info-item-content">
                                    <h3>Телефон</h3>
                                    <p><a href="tel:{{ preg_replace('/[^0-9]/', '', $companyPhone) }}">{{ $companyPhone }}</a></p>
                                </div>
                            </div>

                            <div class="contact-info-item wow fadeInUp" data-wow-delay="0.2s">
                                <div class="icon-box">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                                <div class="contact-info-item-content">
                                    <h3>Email</h3>
                                    <p><a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a></p>
                                </div>
                            </div>

                            <div class="contact-info-item wow fadeInUp" data-wow-delay="0.4s">
                                <div class="icon-box">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <div class="contact-info-item-content">
                                    <h3>Адрес</h3>
                                    <p>{{ $companyAddress }}</p>
                                </div>
                            </div>

                            <div class="contact-info-item wow fadeInUp" data-wow-delay="0.6s">
                                <div class="icon-box">
                                    <i class="fa-regular fa-clock"></i>
                                </div>
                                <div class="contact-info-item-content">
                                    <h3>Режим работы</h3>
                                    <p>{{ $workHours }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-7">
                    <div class="contact-us-form">
                        <div class="section-title">
                            <h2 class="text-anime-style-3" data-cursor="-opaque">Оставьте заявку</h2>
                            <p class="wow fadeInUp">Опишите задачу в свободной форме. Если деталей пока мало, достаточно имени и телефона, дальше поможем структурировать проект.</p>
                        </div>

                        <div class="contact-form">
                            <form action="{{ route('leads.store') }}" method="POST" class="wow fadeInUp" data-wow-delay="0.2s" data-lead-form>
                                @csrf
                                <input type="hidden" name="source" value="form">

                                <div class="row">
                                    <div class="form-group col-md-6 mb-4">
                                        <input type="text" name="name" class="form-control" placeholder="Ваше имя" required value="{{ old('name') }}">
                                    </div>

                                    <div class="form-group col-md-6 mb-4">
                                        <input type="tel" name="phone" class="form-control" placeholder="+375 (__) ___-__-__" required value="{{ old('phone') }}" data-phone-mask>
                                    </div>

                                    <div class="form-group col-md-12 mb-4">
                                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                    </div>

                                    <div class="form-group col-md-12 mb-5">
                                        <textarea name="message" class="form-control" rows="6" placeholder="Опишите объект, задачу или желаемый результат">{{ old('message') }}</textarea>
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

    <div class="google-map">
        <div class="container-fluid">
            <div class="row no-gutters">
                <div class="col-lg-12">
                    <div class="google-map-iframe">
                        <iframe src="https://www.google.com/maps?q={{ $mapQuery }}&output=embed" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
