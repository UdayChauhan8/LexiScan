<?php

namespace App\Services;

class TextAnalysisService
{
    /**
     * Analyze the given text and return metrics.
     *
     * @param string $text
     * @return array
     */
    public function analyze(string $text): array
    {
        $cleanText = strip_tags($text);

        $wordCount = str_word_count($cleanText);
        $sentenceCount = $this->countSentences($cleanText);
        $syllableCount = $this->countSyllables($cleanText);

        $avgSentenceLength = $sentenceCount > 0 ? $wordCount / $sentenceCount : 0;

        // Flesch Reading Ease Formula
        // 206.835 - 1.015(total words / total sentences) - 84.6(total syllables / total words)
        $readabilityScore = 0;
        if ($wordCount > 0 && $sentenceCount > 0) {
            $readabilityScore = 206.835 - (1.015 * ($wordCount / $sentenceCount)) - (84.6 * ($syllableCount / $wordCount));
        }

        // Clamp scores
        $readabilityScore = max(0, min(100, $readabilityScore));

        // Simple Content Health Score (Arbitrary logic for MVP: optimal is 60-80 readability, adequate length)
        $healthScore = $this->calculateHealthScore($wordCount, $readabilityScore);

        return [
            'word_count' => $wordCount,
            'sentence_count' => $sentenceCount,
            'avg_sentence_length' => round($avgSentenceLength, 2),
            'readability_score' => round($readabilityScore, 2),
            'content_health_score' => $healthScore,
            'keyword_density' => 0, // Placeholder for specific keyword analysis
        ];
    }

    private function countSentences(string $text): int
    {
        return preg_match_all('/[^\s][.!?]+(?=\s|$)/', $text);
    }

    private function countSyllables(string $text): int
    {
        $count = 0;
        $words = str_word_count($text, 1);
        foreach ($words as $word) {
            $count += $this->countSyllablesInWord($word);
        }
        return $count;
    }

    private function countSyllablesInWord(string $word): int
    {
        $word = strtolower($word);
        if (strlen($word) <= 3)
            return 1;

        $word = preg_replace('/(?:[^laeiouy]es|ed|[^laeiouy]e)$/', '', $word);
        $word = preg_replace('/^y/', '', $word);
        $matches = [];
        preg_match_all('/[aeiouy]{1,2}/', $word, $matches);
        return count($matches[0]);
    }

    private function calculateHealthScore(int $wordCount, float $readability): int
    {
        $score = 70; // Base score

        // Penalty for being too short
        if ($wordCount < 300) {
            $score -= 20;
        } elseif ($wordCount > 1500) {
            $score += 10;
        }

        // Readability sweet spot (60-70 is standard web copy)
        if ($readability >= 60 && $readability <= 70) {
            $score += 10;
        } elseif ($readability < 40) { // Too hard
            $score -= 10;
        }

        return max(0, min(100, $score));
    }
}
