<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\TextAnalysisService;

class StabilityTest extends TestCase
{
    public function test_seo_text_does_not_collapse_score()
    {
        $service = new TextAnalysisService();
        // SEO text: Short sentences, repeated keywords.
        $seoText = "Best shoes. Buy now. Cheap prices. Fast shipping. Top quality. Best shoes 2024. Order today. Free returns.";

        $metrics = $service->analyze($seoText);
        $score = $metrics['readability_score'];

        // SEO text should be easy to read (high score).
        $this->assertGreaterThan(50, $score, "SEO text score collapsed ($score) below 50. Likely invariant violation.");
        $this->assertLessThan(100, $score);
    }

    public function test_technical_text_maintains_valid_score()
    {
        $service = new TextAnalysisService();
        // Standard Technical Text (Laravel Docs style)
        // This text is complex but well-structured.
        $techText = "Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling.";

        $metrics = $service->analyze($techText);
        $score = $metrics['readability_score'];

        // 206.835 - (1.015 * ASL) - (84.6 * ASW)
        // With the fix, this should be in valid range 20-70.
        // It scored ~25.46, which is difficult but VALID (not 0).
        $this->assertGreaterThan(20, $score, "Technical text score too low ($score).");
        $this->assertLessThan(80, $score, "Technical text score too high ($score).");
    }

    public function test_bullet_points_safety()
    {
        $service = new TextAnalysisService();
        // Text with newlines but no periods.
        // Robust sentence splitting might count this as 1 sentence if relying only on [.!?], 
        // but max(1) invariant prevents zero division.
        // We just want to ensure it doesn't crash or produce negative scores if clamped.
        $bulletText = "Feature one\nFeature two\nFeature three\nFeature four";

        $metrics = $service->analyze($bulletText);

        // Ensure inputs are valid
        $this->assertGreaterThanOrEqual(1, $metrics['sentence_count']);
        $this->assertGreaterThanOrEqual(1, $metrics['word_count']);

        // Score should be safe (>= 0)
        $this->assertGreaterThanOrEqual(0, $metrics['readability_score']);
    }

    public function test_empty_input_safety()
    {
        $service = new TextAnalysisService();
        $metrics = $service->analyze("");

        $this->assertEquals(0, $metrics['word_count']);
        $this->assertEquals(1, $metrics['sentence_count']); // Invariant: max(1)
        $this->assertEquals(0, $metrics['readability_score']); // 0 words -> 0 score (guarded)
    }
}
