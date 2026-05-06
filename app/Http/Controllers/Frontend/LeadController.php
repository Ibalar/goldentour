<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeadRequest;
use App\Models\Lead;
use App\Models\Setting;
use App\Notifications\LeadCreatedNotification;
use App\Notifications\TelegramLeadNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class LeadController extends Controller
{
    public function store(StoreLeadRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['source'] = $data['source'] ?? 'form';
        $data['utm_source'] = session('utm_source');
        $data['utm_medium'] = session('utm_medium');
        $data['utm_campaign'] = session('utm_campaign');
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->userAgent();
        $data['status'] = 'new';

        $lead = Lead::create($data);

        $this->sendNotifications($lead);

        return back()->with('success', 'Спасибо! Ваша заявка принята. Мы свяжемся с вами в ближайшее время.');
    }

    protected function sendNotifications(Lead $lead): void
    {
        try {
            $adminEmail = Setting::get('admin_email', config('mail.from.address', 'admin@example.com'));

            if ($adminEmail) {
                Notification::route('mail', $adminEmail)
                    ->notify(new LeadCreatedNotification($lead->loadMissing('service')));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send email notification', ['message' => $e->getMessage()]);
        }

        try {
            $telegramToken = Setting::get('telegram_bot_token');
            $telegramChatId = Setting::get('telegram_chat_id');

            if ($telegramToken && $telegramChatId) {
                Notification::route('telegram', $telegramChatId)
                    ->notify(new TelegramLeadNotification($lead->loadMissing('service'), (string) $telegramToken));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send telegram notification', ['message' => $e->getMessage()]);
        }
    }
}
