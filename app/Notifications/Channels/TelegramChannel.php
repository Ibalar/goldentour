<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class TelegramChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toTelegram')) {
            return;
        }

        $message = $notification->toTelegram($notifiable);
        $chatId = method_exists($notifiable, 'routeNotificationFor')
            ? $notifiable->routeNotificationFor('telegram', $notification)
            : null;

        if (!is_array($message) || empty($message['token']) || empty($chatId) || empty($message['text'])) {
            return;
        }

        $response = Http::asForm()->post("https://api.telegram.org/bot{$message['token']}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $message['text'],
            'parse_mode' => 'Markdown',
            'reply_markup' => isset($message['button']) ? json_encode([
                'inline_keyboard' => [[[
                    'text' => $message['button']['text'],
                    'url' => $message['button']['url'],
                ]]],
            ], JSON_UNESCAPED_UNICODE) : null,
        ]);

        $response->throw();
    }
}
