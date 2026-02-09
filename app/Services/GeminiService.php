<?php

namespace App\Services;

use Gemini\Client;
use Gemini\Resources\GenerativeModel;
use Illuminate\Support\Facades\Log;
use Gemini;

class GeminiService
{
    private $client;

    public function __construct()
    {
        $apiKey = trim(env('GEMINI_API_KEY'), "'\"");
        if ($apiKey) {
            $this->client = Gemini::client($apiKey);
        }
    }

    /**
     * Send text to Gemini to get an improved version.
     *
     * @param string $text
     * @param string $category
     * @param string|null $keyword
     * @return array
     */
    public function improveContent(string $text, string $category, ?string $keyword = null): array
    {
        if (!$this->client) {
            Log::error('Gemini API Key missing in Service');
            return [
                'error' => 'API Key is missing. Please configure GEMINI_API_KEY in .env',
            ];
        }

        // Construct the prompt based on category
        $prompt = $this->constructPrompt($text, $category, $keyword);

        try {
            // "limit: 0" error on 2.0-flash suggests it's not free.
            // Switching to flash-latest which likely maps to 1.5-flash (free tier eligible)
            $response = $this->client->generativeModel(model: 'models/gemini-flash-latest')->generateContent($prompt);

            $rawText = $response->text();

            // Clean up Markdown code blocks if present
            $cleanJson = str_replace(['```json', '```'], '', $rawText);

            $result = json_decode($cleanJson, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Gemini JSON Parse Error', ['error' => json_last_error_msg(), 'raw' => $rawText]);
                // Fallback
                return [
                    'rewritten_text' => $text,
                    'improvements_made' => ['Model returned invalid format.'],
                    'seo_keywords_added' => [],
                    'new_score_est' => 0
                ];
            }

            return $result;

        } catch (\Exception $e) {
            $message = $e->getMessage();

            // Log the error specifics even for rate limits to confirm the "limit: 0" theory
            Log::error('Gemini Service Exception', [
                'message' => $message,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Handle Rate Limiting gracefully
            if (str_contains(strtolower($message), 'quota') || str_contains($message, '429')) {
                return ['error' => 'AI usage limit reached (Free Tier). Please wait 60 seconds and try again.'];
            }

            return ['error' => 'An unexpected error occurred: ' . $message];
        }
    }

    private function constructPrompt(string $text, string $category, ?string $keyword): string
    {
        $targetScore = match ($category) {
            'beginner' => '80-95',
            'lifestyle' => '65-75',
            'marketing' => '55-65', // Sweet spot for SEO
            'business' => '45-60',
            'technical' => '30-50',
            default => '60-70'
        };

        $keywordInstruction = $keyword
            ? "Ensure the keyword '{$keyword}' and 3-5 related LSI keywords are naturally integrated."
            : "Identify a primary topic and integrate relevant LSI keywords.";

        // Use Heredoc for cleaner multi-line string
        return <<<EOT
You are an expert Content Editor and SEO Strategist.
Your task is to rewrite the following text to match the "{$category}" style guide.

Target Audience: {$category}
Target Flesch Reading Ease Score: {$targetScore}
SEO Instruction: {$keywordInstruction}

Analyze the input text and provide a rewritten version that improves flow, fixes passive voice, and hits the target readability score.

Return ONLY a JSON object with this exact structure (no markdown formatting):
{
    "rewritten_text": "The full rewritten content here...",
    "improvements_made": [
        "Bullet point 1 of what you changed",
        "Bullet point 2"
    ],
    "seo_keywords_added": ["keyword1", "keyword2"],
    "new_score_est": 65
}

Input Text:
"{$text}"
EOT;
    }
}
