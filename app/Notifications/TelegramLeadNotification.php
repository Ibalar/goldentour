<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Notifications\Channels\TelegramChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TelegramLeadNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Lead $lead,
        public string $token,
    ) {
    }

    public function via(object $notifiable): array
    {
        return [TelegramChannel::class];
    }

    public function toTelegram(object $notifiable): array
    {
        $text = "Новая заявка с сайта\n\n";
        $text .= "*Имя:* {$this->lead->name}\n";
        $text .= "*Телефон:* {$this->lead->phone}\n";

        if ($this->lead->email) {
            $text .= "*Email:* {$this->lead->email}\n";
        }

        if ($this->lead->service) {
            $text .= "*Услуга:* {$this->lead->service->name}\n";
        }

        if ($this->lead->calculated_price) {
            $price = number_format((float) $this->lead->calculated_price, 0, ',', ' ');
            $text .= "*Расчет:* {$price} ₽\n";
        }

        $text .= '*Источник:* ' . match ($this->lead->source) {
            'calculator' => 'Калькулятор',
            'direct' => 'Прямой звонок',
            'phone' => 'Обратный звонок',
            default => 'Форма на сайте',
        };

        return [
            'token' => $this->token,
            'text' => $text,
            'button' => [
                'text' => 'Открыть в админке',
                'url' => url('/admin/resource/lead-resource/' . $this->lead->id . '/edit'),
            ],
        ];
    }
}
