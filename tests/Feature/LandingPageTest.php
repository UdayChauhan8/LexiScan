<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_landing_page_loads_correctly(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Check for key Landing Page elements
        $response->assertSee('LexiScan');
        $response->assertSee('Clarify your'); // Hero Text Part 1
        $response->assertSee('message');      // Hero Text Part 2
        $response->assertSee('Readability Scores');   // Feature
        $response->assertSee('Log');
        $response->assertSee('in');
        $response->assertSee('Get Started');
    }
}
