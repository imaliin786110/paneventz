<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiManager
{
    /**
     * Determine if a live AI provider is configured in .env
     */
    public static function isConfigured(): bool
    {
        return !empty(static::getGeminiKey()) || !empty(static::getOpenAiKey());
    }

    public static function getActiveProvider(): string
    {
        if (!empty(static::getGeminiKey())) {
            return 'Gemini AI';
        }
        if (!empty(static::getOpenAiKey())) {
            return 'OpenAI';
        }
        return 'Local Editorial Engine';
    }

    protected static function getGeminiKey(): ?string
    {
        return env('GEMINI_API_KEY') ?: config('services.gemini.key');
    }

    protected static function getOpenAiKey(): ?string
    {
        return env('OPENAI_API_KEY') ?: config('services.openai.key');
    }

    /**
     * Generate structured JSON with AI or fallback
     */
    public static function generateJson(string $prompt, array $defaultFallback = [], string $systemInstruction = ''): array
    {
        $geminiKey = static::getGeminiKey();
        if ($geminiKey) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$geminiKey}";
                
                $response = Http::timeout(30)->post($url, [
                    'systemInstruction' => [
                        'parts' => [['text' => $systemInstruction ?: 'You are an editorial wedding journalism expert and luxury SEO director. Output strictly valid JSON without markdown wrapping.']]
                    ],
                    'contents' => [
                        [
                            'parts' => [['text' => $prompt]]
                        ]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature'      => 0.4,
                    ]
                ]);

                if ($response->successful()) {
                    $jsonText = $response->json('candidates.0.content.parts.0.text');
                    $decoded = json_decode($jsonText, true);
                    if (is_array($decoded)) {
                        return $decoded;
                    }
                } else {
                    Log::warning('Gemini AI call returned non-200: ' . $response->body());
                }
            } catch (\Throwable $e) {
                Log::error('Gemini AI API exception: ' . $e->getMessage());
            }
        }

        $openAiKey = static::getOpenAiKey();
        if ($openAiKey) {
            try {
                $response = Http::withToken($openAiKey)->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                    'model'           => 'gpt-4o-mini',
                    'response_format' => ['type' => 'json_object'],
                    'messages'        => [
                        ['role' => 'system', 'content' => $systemInstruction ?: 'You are an editorial wedding journalism and luxury SEO specialist. Respond ONLY in valid JSON.'],
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature'     => 0.4,
                ]);

                if ($response->successful()) {
                    $content = $response->json('choices.0.message.content');
                    $decoded = json_decode($content, true);
                    if (is_array($decoded)) {
                        return $decoded;
                    }
                } else {
                    Log::warning('OpenAI call returned non-200: ' . $response->body());
                }
            } catch (\Throwable $e) {
                Log::error('OpenAI API exception: ' . $e->getMessage());
            }
        }

        // Return fallback structured response
        return $defaultFallback;
    }
}
