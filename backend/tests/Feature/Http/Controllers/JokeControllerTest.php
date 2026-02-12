<?php

namespace Tests\Feature\Http\Controllers;

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
}
