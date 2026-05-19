<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(public Lead $lead)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage())
            ->subject('Новая заявка с сайта Золотой Тур')
            ->greeting('Здравствуйте!')
            ->line('Поступила новая заявка с сайта.')
            ->line('Имя: ' . $this->lead->name)
            ->line('Телефон: ' . $this->lead->phone);

        if ($this->lead->email) {
            $message->line('Email: ' . $this->lead->email);
        }

        if ($this->lead->service) {
            $message->line('Услуга: ' . $this->lead->service->name);
        }

        if ($this->lead->message) {
            $message->line('Сообщение: ' . $this->lead->message);
        }

        if ($this->lead->calculated_price) {
            $message->line('Рассчитанная цена: ' . number_format((float) $this->lead->calculated_price, 0, ',', ' ') . ' BYN');
        }

        $message->line('Источник: ' . match ($this->lead->source) {
            'calculator' => 'Калькулятор',
            'direct' => 'Прямой звонок',
            'phone' => 'Обратный звонок',
            default => 'Форма на сайте',
        });

        return $message->action('Открыть в админке', url('/admin/resource/lead-resource/' . $this->lead->id . '/edit'));
    }
}
