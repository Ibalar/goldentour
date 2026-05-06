@extends('layouts.app')

@section('meta_title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($page->content ?? ''), 160))

@section('content')
    <section class="section-space">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-heading">
                <p class="eyebrow">Страница</p>
                <h1 class="section-title">{{ $page->title }}</h1>
            </div>

            <div class="content-block rounded-[2rem] bg-white p-8 shadow-sm">
                {!! $page->content !!}
            </div>
        </div>
    </section>
@endsection
