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

        // Invariant: English words have at least one syllable.
        // If technical text or edge cases result in undercounting, enforce floor.
        if ($syllableCount < $wordCount) {
            $syllableCount = $wordCount;
        }

        $avgSentenceLength = $sentenceCount > 0 ? $wordCount / $sentenceCount : 0;

        // Flesch Reading Ease Formula
        // 206.835 - 1.015(total words / total sentences) - 84.6(total syllables / total words)
        $readabilityScore = 0;

        // Ensure we don't divide by zero
        if ($wordCount > 0 && $sentenceCount > 0) {
            $readabilityScore = 206.835 - (1.015 * ($wordCount / $sentenceCount)) - (84.6 * ($syllableCount / $wordCount));
        }

        // Clamp scores strictly for display, but calculation should be robust enough to generally land > 0.
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
        // Robust splitting: split by punctuation . ! ?
        // PREG_SPLIT_NO_EMPTY ensures we don't get empty strings
        $sentences = preg_split('/[.!?]+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);

        // Invariant: Text must have at least 1 sentence if it exists, to avoid DBZ.
        // If text is empty, wordCount is 0, so DBZ is protected in analyze().
        // But for consistency:
        return max(1, count($sentences));
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
        // 1. Normalize
        $word = preg_replace('/[^a-z]/', '', strtolower($word));
        if ($word === '') {
            return 0;
        }

        $syllableCount = 0;

        // 2. Handle specific suffixes that usually add a syllable
        // We strip these and add their syllable count, then process the root.
        $suffixes = [
            'tial' => 1,
            'cial' => 1,
            'tion' => 1,
            'sion' => 1,
            'cian' => 1,
            'ment' => 1,
            'ness' => 1,
            'less' => 1,
            'ship' => 1,
            'ful' => 1,
            'ing' => 1,
            'ly' => 1,
            'ism' => 2,
        ];

        foreach ($suffixes as $suffix => $count) {
            if (substr($word, -strlen($suffix)) === $suffix) {
                $syllableCount += $count;
                $word = substr($word, 0, -strlen($suffix));
                break;
            }
        }

        if ($word === '') {
            return max(1, $syllableCount);
        }

        // 3. Count vowel groups in the remaining root
        $matchCount = preg_match_all('/[aeiouy]+/', $word, $matches);
        $rootCount = ($matchCount !== false) ? count($matches[0]) : 0;

        // 4. Handle silent endings on the root
        // Silent 'e'
        if (substr($word, -1) === 'e') {
            $rootCount--;
        }
        // Silent 'es' (unless preceded by s, z, x, sh, ch)
        elseif (substr($word, -2) === 'es') {
            if (!preg_match('/(sh|ch|[szx])es$/', $word)) {
                $rootCount--;
            }
        }
        // Silent 'ed' (unless preceded by t, d)
        elseif (substr($word, -2) === 'ed') {
            if (!preg_match('/[td]ed$/', $word)) {
                $rootCount--;
            }
        }

        // 5. Handle 'le' logic
        if (preg_match('/[^aeiouy]les?$/', $word)) {
            $rootCount++;
        }

        // 6. Combine and Min Check
        return $syllableCount + max(1, $rootCount);
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
