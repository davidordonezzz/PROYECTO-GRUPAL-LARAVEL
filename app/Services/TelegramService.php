<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{

    protected $botToken;
    protected $adminChatId;
    protected $apiUrl;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
        $this->adminChatId = env('TELEGRAM_ADMIN_CHAT_ID');
        $this->apiUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    public function notifyAdmin($message)
    {
        try {
            $response = Http::post("{$this->apiUrl}/sendMessage", [
                'chat_id' => $this->adminChatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Error enviando mensaje a Telegram: ' . $e->getMessage());
            return false;
        }
    }
}
