<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private const MODEL    = 'gemini-1.5-flash';
    private const BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models/';

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = (string) Setting::get('gemini_api_key', '');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * Analyze a dress image using Gemini and return suggested fields.
     *
     * @return array{name?: string, description?: string, color?: string, brand?: string}
     */
    public function generateDressDescription(UploadedFile $image): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException(
                'Gemini API key is not configured. Please add it in Settings → AI Settings.'
            );
        }

        $imageData = base64_encode(file_get_contents($image->getRealPath()));
        $mimeType  = $image->getMimeType() ?? 'image/jpeg';

        $prompt = <<<'PROMPT'
You are an expert fashion stylist and SEO copywriter for a dress rental website in Nepal.
Analyze this dress image and provide a JSON object with these exact keys:
- "name": A concise, SEO-friendly product name (max 80 characters).
- "description": A detailed, SEO-friendly product description (150–300 words) covering style, silhouette, colour, fabric/texture, suitable occasions, standout design features, and why someone should rent this dress.
- "color": Main colour of the dress.
- "brand": Suggested style category or brand name if recognizable; otherwise an empty string.

Respond with ONLY valid JSON and no additional text.
PROMPT;

        $response = Http::timeout(30)->post(
            self::BASE_URL . self::MODEL . ':generateContent?key=' . $this->apiKey,
            [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'inlineData' => [
                                    'mimeType' => $mimeType,
                                    'data'     => $imageData,
                                ],
                            ],
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'temperature'     => 0.7,
                    'maxOutputTokens' => 1024,
                ],
            ]
        );

        if (! $response->successful()) {
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            throw new \RuntimeException(
                'Gemini API request failed (HTTP ' . $response->status() . '). Please check your API key.'
            );
        }

        $text = (string) $response->json('candidates.0.content.parts.0.text', '');

        // Strip Markdown code fences if Gemini wraps the JSON in them.
        $text = preg_replace('/^```(?:json)?\s*/i', '', trim($text));
        $text = preg_replace('/\s*```$/', '', (string) $text);

        $result = json_decode((string) $text, true);

        return is_array($result) ? $result : ['description' => $text];
    }
}
