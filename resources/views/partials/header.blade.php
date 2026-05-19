@php
    $menuCategories = collect();
    try {
        $menuCategories = \App\Models\ServiceCategory::active()
            ->whereNull('parent_id')
            ->with([
                'activeChildren' => fn ($q) => $q->orderBy('sort_order')->with([
                    'activeChildren' => fn ($q) => $q->orderBy('sort_order')->with([
                        'activeChildren' => fn ($q) => $q->orderBy('sort_order')->with([
                            'activeServices' => fn ($q) => $q->orderBy('name'),
                        ]),
                        'activeServices' => fn ($q) => $q->orderBy('name'),
                    ]),
                    'activeServices' => fn ($q) => $q->orderBy('name'),
                ]),
                'activeServices' => fn ($q) => $q->orderBy('name'),
            ])
            ->orderBy('sort_order')
            ->get();
    } catch (\Exception $e) {}

    $navItems = [
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
                            {{-- Главная --}}
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Главная</a>
                            </li>

                            {{-- Услуги с иерархией --}}
                            @if($menuCategories->isNotEmpty())
                                <li class="nav-item submenu">
                                    <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Услуги</a>
                                    <ul>
                                        @foreach($menuCategories as $category)
                                            @include('partials.header-service-menu-item', ['category' => $category])
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}" href="{{ route('services.index') }}">Услуги</a>
                                </li>
                            @endif

                            {{-- Остальные пункты --}}
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
