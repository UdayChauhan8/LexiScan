<?php

namespace Tests\Feature;

use App\Models\Analysis;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_generate_public_link()
    {
        $user = User::factory()->create();
        $analysis = Analysis::create([
            'user_id' => $user->id,
            'content_raw' => 'Some content',
            'word_count' => 10,
        ]);

        $response = $this->actingAs($user)->post(route('reports.store', $analysis));

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', [
            'analysis_id' => $analysis->id,
        ]);

        $this->assertNotNull($analysis->refresh()->report->public_link_token);
    }

    public function test_public_user_can_view_report()
    {
        $user = User::factory()->create();
        $analysis = Analysis::create([
            'user_id' => $user->id,
            'content_raw' => 'Public content visible here.',
            'word_count' => 10,
            'readability_score' => 85.5
        ]);

        $token = Str::uuid();
        $analysis->report()->create(['public_link_token' => $token]);

        $response = $this->get(route('reports.show', $token));

        $response->assertStatus(200);
        $response->assertSee('Public content visible here.');
        $response->assertSee('85.5');
    }

    public function test_user_can_revoke_link()
    {
        $user = User::factory()->create();
        $analysis = Analysis::create([
            'user_id' => $user->id,
            'content_raw' => 'Content',
            'word_count' => 5,
        ]);

        $analysis->report()->create(['public_link_token' => Str::uuid()]);

        $response = $this->actingAs($user)->delete(route('reports.destroy', $analysis));

        $response->assertRedirect();
        $this->assertDatabaseMissing('reports', [
            'analysis_id' => $analysis->id,
        ]);
    }
}
