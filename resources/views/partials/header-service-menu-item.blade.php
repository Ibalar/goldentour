@php
    $hasChildren = $category->activeChildren->isNotEmpty();
    $hasServices = $category->activeServices->isNotEmpty();
@endphp

@if($hasChildren || $hasServices)
    <li class="nav-item submenu">
        <a class="nav-link" href="{{ route('services.category', $category) }}">{{ $category->name }}</a>
        <ul>
            @if($hasChildren)
                @foreach($category->activeChildren as $child)
                    @include('partials.header-service-menu-item', ['category' => $child])
                @endforeach
            @endif
            @if($hasServices)
                @foreach($category->activeServices as $service)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services.show', $service) }}">{{ $service->name }}</a>
                    </li>
                @endforeach
            @endif
        </ul>
    </li>
@else
    <li class="nav-item">
        <a class="nav-link" href="{{ route('services.category', $category) }}">{{ $category->name }}</a>
    </li>
@endif
