<footer class="main-footer dark-section">
    <div class="footer-scrolling-ticker">
        <div class="scrolling-ticker-box">
            <div class="scrolling-content">
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Строительство домов</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Капитальный ремонт</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Отделка помещений</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Проектирование</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Комплектация объекта</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Строительство домов</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Капитальный ремонт</span>
            </div>
            <div class="scrolling-content">
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Строительство домов</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Капитальный ремонт</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Отделка помещений</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Проектирование</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Комплектация объекта</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Строительство домов</span>
                <span><img src="{{ asset('assets/images/icon-asterisk.svg') }}" alt="">Капитальный ремонт</span>
            </div>
        </div>
    </div>

    <div class="footer-box">
        <div class="container">
            <div class="row">
                <div class="col-xl-4">
                    <div class="about-footer">
                        <div class="footer-logo">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="Золотой Тур">
                        </div>

                        <div class="footer-working-hours">
                            <h3>Режим работы:</h3>
                            <ul>
                                <li>{{ \App\Models\Setting::get('company_work_hours', 'Пн-Пт: 09:00 - 18:00') }}</li>
                                <li>Консультации по заявкам в течение рабочего дня</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="footer-links-box">
                        <div class="footer-links footer-location-info">
                            <h3>Адрес</h3>
                            <p>{{ \App\Models\Setting::get('company_address', 'Республика Беларусь, г. Минск, ул. Скрыганова, 6') }}</p>
                        </div>

                        <div class="footer-links footer-contact-links">
                            <h3>Связаться с нами</h3>
                            <ul>
                                <li><img src="{{ asset('assets/images/icon-phone-white.svg') }}" alt=""> Телефон: <a href="tel:{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('company_phone', '+375290000000')) }}">{{ \App\Models\Setting::get('company_phone', '+375 (29) 000-00-00') }}</a></li>
                                <li><img src="{{ asset('assets/images/icon-mail-white.svg') }}" alt=""> Email: <a href="mailto:{{ \App\Models\Setting::get('company_email', 'info@goldentour.local') }}">{{ \App\Models\Setting::get('company_email', 'info@goldentour.local') }}</a></li>
                            </ul>
                        </div>

                        <div class="footer-links footer-newsletter-form">
                            <h3>Быстрый запрос</h3>
                            <p>Оставьте заявку через калькулятор или страницу контактов, если нужен расчет или консультация.</p>
                            <a href="{{ route('calculator') }}" class="btn-default btn-highlighted">Перейти в калькулятор</a>
                        </div>

                        <div class="footer-links footer-social-links">
                            <h3>Разделы сайта</h3>
                            <ul>
                                <li><a href="{{ route('services.index') }}">Услуги</a></li>
                                <li><a href="{{ route('portfolio.index') }}">Проекты</a></li>
                                <li><a href="{{ route('about') }}">О компании</a></li>
                                <li><a href="{{ route('contacts') }}">Контакты</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="footer-copyright">
                        <div class="footer-copyright-text">
                            <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::get('company_name', 'Золотой Тур') }}. Все права защищены.</p>
                        </div>

                        <div class="footer-copyright-text">
                            <p>Разработка сайта <a href="https://webart.by" target="_blank">WebArt.BY</a> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
