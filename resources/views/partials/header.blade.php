@php
    $menuServices = collect();
    try {
        $menuServices = \App\Models\Service::active()->showInMenu()->orderBy('name')->get();
    } catch (\Exception $e) {}

    $navItems = [
        ['label' => 'Главная', 'route' => route('home'), 'active' => request()->routeIs('home')],
        ['label' => 'Услуги', 'route' => route('services.index'), 'active' => request()->routeIs('services.*'), 'children' => $menuServices],
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

                <div class="collapse navbar-collapse main-menu">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">
                            @foreach ($navItems as $item)
                                @if (!empty($item['children']) && $item['children']->isNotEmpty())
                                    <li class="nav-item submenu">
                                        <a class="nav-link {{ $item['active'] ? 'active' : '' }}" href="{{ $item['route'] }}">{{ $item['label'] }}</a>
                                        <ul>
                                            @foreach ($item['children'] as $child)
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('services.show', $child) }}">{{ $child->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link {{ $item['active'] ? 'active' : '' }}" href="{{ $item['route'] }}">{{ $item['label'] }}</a>
                                    </li>
                                @endif
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
