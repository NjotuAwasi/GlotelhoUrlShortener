<?php

namespace Tests\Feature;

use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    // we use this to reset the database to the state it was before each run
    use RefreshDatabase;

    /** @test */
    //this test checks if the application can create a short url with a shortcode
    public function it_can_create_a_short_url()
    {
        $response = $this->postJson('/api/shorten', [
            'original_url' => 'https://glotelho.cm'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'short_url',
                'original_url',
                'short_code'
            ]);

        $this->assertDatabaseHas('urls', [
            'original_url' => 'https://glotelho.cm',
        ]);
    }

    /** @test */
    //this test verifies that when the short url is clicked it returns to the original url
    public function it_redirects_to_original_url()
    {
        $url = Url::create([
            'original_url' => 'https://glotelho.cm',
            'short_code' => 'abc123'

        ]);

        $response = $this->get('/abc123');

        $response->assertRedirect('https://glotelho.cm');
    }

    /** @test */
    // this test verifies that starts a returned for the url
    public function it_returns_stats_for_a_short_url()
    {
        $url = Url::create([
            'original_url' => 'https://glotelho.cm',
            'short_code' => 'abc123',
            'click_count' => 5,
        ]);

        $response = $this->getJson('/api/stats/abc123');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'original_url' => 'https://glotelho.cm',
                'short_code' => 'abc123',
                'click_count' => 5,
            ]);
    }

    /** @test */
    //this test makes sure that a valid url is used to call the shorten method
    public function it_requires_a_valid_url()
    {
        $response = $this->postJson('/api/shorten', [
            'original_url' => 'invalid-url'
        ]);

        $response->assertStatus(422); // Validation error
    }
}
