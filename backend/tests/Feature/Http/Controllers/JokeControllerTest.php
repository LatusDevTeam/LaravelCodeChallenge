<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Domain\Joke;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class JokeControllerTest extends TestCase
{
    /**
     * Tests the jokes index endpoint returns 10 jokes with expected structure.
     */
    public function test_jokes_endpoint_returns_ten_jokes(): void
    {
        $fakeJokes = [];
        for ($i = 1; $i <= 10; $i++) {
            $fakeJokes[] = [
                'id' => $i,
                'type' => 'programming',
                'setup' => "setup $i",
                'punchline' => "punchline $i",
            ];
        }

        Joke::factory()->count(10)->make();

        // Fake the external API used by App\Services\Joke
        Http::fake([
            'https://official-joke-api.appspot.com/*' => Http::response($fakeJokes, Response::HTTP_OK),
        ]);

        $response = $this->get('api/jokes');

        $response->assertStatus(Response::HTTP_OK);
        // Resource collections are wrapped in a `data` key, assert count there
        $response->assertJsonCount(10, 'data');
        $response->assertJsonStructure([
            // top-level resource collection has `data` => [ ... ]
            'data' => [
                ['id', 'type', 'setup', 'punchline'],
            ],
        ]);

        // Some extra sanity checks
        $response->assertJsonFragment(['id' => 1, 'setup' => 'setup 1']);
    }

    /**
     * Tests the jokes endpoint respects the limit filter parameter.
     */
    public function test_jokes_endpoint_respects_limit_filter(): void
    {
        $fakeJokes = [];
        for ($i = 1; $i <= 10; $i++) {
            $fakeJokes[] = [
                'id' => $i,
                'type' => 'programming',
                'setup' => "setup $i",
                'punchline' => "punchline $i",
            ];
        }

        Http::fake([
            'https://official-joke-api.appspot.com/*' => Http::response($fakeJokes, Response::HTTP_OK),
        ]);

        // Request with limit=3
        $response = $this->get('api/jokes?filters[limit]=3');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                ['id', 'type', 'setup', 'punchline'],
            ],
        ]);
    }

    /**
     * Tests the jokes endpoint returns default count when no limit is specified.
     */
    public function test_jokes_endpoint_returns_default_limit_without_filter(): void
    {
        $fakeJokes = [];
        for ($i = 1; $i <= 10; $i++) {
            $fakeJokes[] = [
                'id' => $i,
                'type' => 'programming',
                'setup' => "setup $i",
                'punchline' => "punchline $i",
            ];
        }

        Http::fake([
            'https://official-joke-api.appspot.com/*' => Http::response($fakeJokes, Response::HTTP_OK),
        ]);

        // Request without any filters
        $response = $this->get('api/jokes');

        $response->assertStatus(Response::HTTP_OK);
        // Default limit is 10
        $response->assertJsonCount(10, 'data');
    }

    /**
     * Tests the jokes endpoint handles limit=1 (edge case).
     */
    public function test_jokes_endpoint_handles_limit_of_one(): void
    {
        $fakeJokes = [];
        for ($i = 1; $i <= 10; $i++) {
            $fakeJokes[] = [
                'id' => $i,
                'type' => 'programming',
                'setup' => "setup $i",
                'punchline' => "punchline $i",
            ];
        }

        Http::fake([
            'https://official-joke-api.appspot.com/*' => Http::response($fakeJokes, Response::HTTP_OK),
        ]);

        $response = $this->get('api/jokes?filters[limit]=1');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonFragment(['id' => 1, 'setup' => 'setup 1']);
    }

    /**
     * Tests the jokes endpoint handles limit exceeding available jokes.
     */
    public function test_jokes_endpoint_handles_limit_exceeding_available(): void
    {
        $fakeJokes = [];
        for ($i = 1; $i <= 5; $i++) {
            $fakeJokes[] = [
                'id' => $i,
                'type' => 'programming',
                'setup' => "setup $i",
                'punchline' => "punchline $i",
            ];
        }

        Http::fake([
            'https://official-joke-api.appspot.com/*' => Http::response($fakeJokes, Response::HTTP_OK),
        ]);

        // Request limit=10 but only 5 jokes available
        $response = $this->get('api/jokes?filters[limit]=10');

        $response->assertStatus(Response::HTTP_OK);
        // Should return only 5 (what's available)
        $response->assertJsonCount(5, 'data');
    }
}
