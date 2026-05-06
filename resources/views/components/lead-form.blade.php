@props(['service' => null, 'source' => 'form', 'prefix' => 'lead'])

<form action="{{ route('leads.store') }}" method="POST" class="space-y-4" data-lead-form>
    @csrf
    <input type="hidden" name="source" value="{{ $source }}">

    @if ($service)
        <input type="hidden" name="service_id" value="{{ $service->id }}">
    @endif

    <div>
        <label for="{{ $prefix }}-name" class="form-label">Ваше имя *</label>
        <input id="{{ $prefix }}-name" name="name" type="text" required class="form-input" placeholder="Иван Иванов" value="{{ old('name') }}">
    </div>

    <div>
        <label for="{{ $prefix }}-phone" class="form-label">Телефон *</label>
        <input id="{{ $prefix }}-phone" name="phone" type="tel" required class="form-input" placeholder="+375 (__) ___-__-__" value="{{ old('phone') }}" data-phone-mask>
    </div>

    <div>
        <label for="{{ $prefix }}-email" class="form-label">Email</label>
        <input id="{{ $prefix }}-email" name="email" type="email" class="form-input" placeholder="example@email.com" value="{{ old('email') }}">
    </div>

    <div>
        <label for="{{ $prefix }}-message" class="form-label">Сообщение</label>
        <textarea id="{{ $prefix }}-message" name="message" rows="4" class="form-input min-h-28" placeholder="Опишите ваш проект...">{{ old('message') }}</textarea>
    </div>

    <x-button type="submit" class="w-full" data-submit-button>
        <span data-submit-default>Отправить заявку</span>
        <span class="hidden" data-submit-loading>Отправка...</span>
    </x-button>

    <p class="text-center text-xs leading-5 text-secondary-500">
        Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности.
    </p>
</form>
