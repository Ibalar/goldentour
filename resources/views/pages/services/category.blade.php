@extends('layouts.app')

@section('meta_title', $category->name . ' - услуги Золотой Тур')
@section('meta_description', $category->description ?: ('Услуги категории ' . $category->name))

@section('content')
    <section class="section-space">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-heading">
                <p class="eyebrow">Категория услуг</p>
                <h1 class="section-title">{{ $category->name }}</h1>
                @if ($category->description)
                    <p class="section-subtitle">{{ $category->description }}</p>
                @endif
            </div>

            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($category->activeServices as $service)
                    <x-card-service :service="$service" />
                @empty
                    <p class="col-span-full rounded-[2rem] border border-dashed border-secondary-300 bg-white p-10 text-center text-secondary-500">В этой категории пока нет активных услуг.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
