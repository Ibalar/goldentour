@php
    $navItems = [
        ['label' => 'Главная', 'route' => route('home'), 'active' => request()->routeIs('home')],
        ['label' => 'Услуги', 'route' => route('services.index'), 'active' => request()->routeIs('services.*')],
        ['label' => 'Портфолио', 'route' => route('portfolio.index'), 'active' => request()->routeIs('portfolio.*')],
        ['label' => 'О компании', 'route' => route('about'), 'active' => request()->routeIs('about')],
        ['label' => 'Контакты', 'route' => route('contacts'), 'active' => request()->routeIs('contacts')],
    ];
@endphp

<header class="main-header">
    <div class="header-sticky">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="Золотой Тур">
                </a>

                <div class="main-menu d-none d-lg-flex align-items-center">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">
                            @foreach ($navItems as $item)
                                <li class="nav-item">
                                    <a class="nav-link {{ $item['active'] ? 'active' : '' }}" href="{{ $item['route'] }}">{{ $item['label'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="header-btn">
                        <a href="{{ route('calculator') }}" class="btn-default btn-highlighted">Рассчитать стоимость</a>
                    </div>
                </div>

                <div class="navbar-toggle"></div>
            </div>
        </nav>
        <div class="responsive-menu"></div>
    </div>
</header>
