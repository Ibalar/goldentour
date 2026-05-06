@props(['review'])

<article class="rounded-[2rem] border border-secondary-200 bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <div class="flex text-primary-500">
            @for ($i = 1; $i <= 5; $i++)
                <svg class="h-5 w-5 {{ $i <= $review->rating ? 'fill-current' : 'text-secondary-200' }}" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endfor
        </div>

        @if ($review->is_verified)
            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Проверен</span>
        @endif
    </div>

    <p class="mt-5 text-base leading-7 text-secondary-700">"{{ $review->text }}"</p>

    <div class="mt-6 flex items-center gap-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-primary-100 font-bold text-primary-700">
            {{ mb_substr($review->author_name, 0, 1) }}
        </div>
        <div>
            <p class="font-semibold text-secondary-900">{{ $review->author_name }}</p>
            @if ($review->service)
                <p class="text-sm text-secondary-500">{{ $review->service->name }}</p>
            @endif
        </div>
    </div>

    @if ($review->admin_reply)
        <div class="mt-6 rounded-2xl bg-secondary-50 p-4 text-sm leading-6 text-secondary-700">
            <span class="font-semibold text-secondary-900">Ответ компании:</span>
            {{ $review->admin_reply }}
        </div>
    @endif
</article>
