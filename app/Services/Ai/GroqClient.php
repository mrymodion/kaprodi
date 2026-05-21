<?php

namespace App\Services\Ai;

use CodeIgniter\HTTP\CURLRequest;
use Config\Services;
use RuntimeException;

/**
 * Klien Groq AI (OpenAI-compatible Chat Completions).
 * Konfigurasi: .env -> groq.apiKey, groq.baseUrl, groq.modelLong, groq.modelFast.
 */
class GroqClient
{
    private CURLRequest $http;
    private string $apiKey;
    private string $baseUrl;
    private string $modelLong;
    private string $modelFast;
    private int    $timeout;

    public function __construct()
    {
        $this->apiKey    = env('groq.apiKey', '');
        $this->baseUrl   = rtrim(env('groq.baseUrl', 'https://api.groq.com/openai/v1'), '/');
        $this->modelLong = env('groq.modelLong', 'llama-3.3-70b-versatile');
        $this->modelFast = env('groq.modelFast', 'llama-3.1-8b-instant');
        $this->timeout   = (int) env('groq.timeout', 60);
        $this->http      = Services::curlrequest(['timeout' => $this->timeout]);
    }

    public function isConfigured(): bool
    {
        return $this->apiKey !== '';
    }

    /**
     * @param array{role: string, content: string}[] $messages
     */
    public function chat(array $messages, bool $fast = false, float $temperature = 0.3): string
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('GROQ_API_KEY belum di-set di .env');
        }
        $payload = [
            'model'       => $fast ? $this->modelFast : $this->modelLong,
            'messages'    => $messages,
            'temperature' => $temperature,
        ];
        $res = $this->http->setHeader('Authorization', 'Bearer ' . $this->apiKey)
            ->setHeader('Content-Type', 'application/json')
            ->post($this->baseUrl . '/chat/completions', ['body' => json_encode($payload)]);

        if ($res->getStatusCode() >= 400) {
            throw new RuntimeException('Groq error ' . $res->getStatusCode() . ': ' . $res->getBody());
        }
        $data = json_decode($res->getBody(), true);
        return $data['choices'][0]['message']['content'] ?? '';
    }

    public function generateLed(string $prompt, array $context = []): string
    {
        $sys = 'Anda adalah penulis LED akademik untuk akreditasi LAM INFOKOM. '
             . 'Gunakan bahasa Indonesia formal, faktual, sertakan tren tahunan dan rujukan tabel LKPS.';
        $userContent = $prompt . "\n\nData konteks (JSON):\n" . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return $this->chat([
            ['role' => 'system', 'content' => $sys],
            ['role' => 'user',   'content' => $userContent],
        ], fast: false, temperature: 0.4);
    }
}
