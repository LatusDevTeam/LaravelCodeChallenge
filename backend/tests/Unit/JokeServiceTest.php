<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\Joke;
use App\Models\Domain\Joke as JokeModel;
use Illuminate\Http\Client\ConnectionException;

class JokeServiceTest extends TestCase
{
    public function test_get_ten_random_jokes_returns_models(): void
    {
        // Prepare a deterministic 10-item payload (matches domain model shape)
        $payload = [];
        for ($i = 1; $i <= 10; $i++) {
            $payload[] = [
                'id' => $i,
                'type' => 'programming',
                'setup' => "Setup {$i}",
                'punchline' => "Punchline {$i}",
            ];
        }

        // Fake the external API for any endpoint on the official-joke-api domain
        Http::fake([
            'https://official-joke-api.appspot.com/*' => Http::response($payload, 200),
        ]);

        // Instantiate the service and execute
        $service = new Joke();
        $jokes = $service->getTenRandomJokes();

        // Basic shape assertions
        $this->assertIsArray($jokes, 'Service should return an array');
        $this->assertCount(10, $jokes, 'Service should return 10 jokes');

        // Each item must be a domain Joke model with correct attributes
        foreach ($jokes as $index => $joke) {
            $this->assertInstanceOf(JokeModel::class, $joke, "Item {$index} should be a Joke model");

            $expected = $payload[$index];
            $this->assertEquals($expected['id'], $joke->id);
            $this->assertEquals($expected['type'], $joke->type);
            $this->assertEquals($expected['setup'], $joke->setup);
            $this->assertEquals($expected['punchline'], $joke->punchline);
        }

        // Ensure exactly one HTTP request was made to the external API
        Http::assertSentCount(1);

        // Verify the request targeted the expected route on the configured domain
        Http::assertSent(function ($request) {
            return str_starts_with($request->url(), Joke::DOMAIN)
                && str_contains($request->url(), '/jokes/programming/ten');
        });
    }

    public function test_get_ten_random_jokes_handles_empty_response(): void
    {
        // Fake the API returning an empty array
        Http::fake([
            'https://official-joke-api.appspot.com/*' => Http::response([], 200),
        ]);

        $service = new Joke();
        $jokes = $service->getTenRandomJokes();

        $this->assertIsArray($jokes);
        $this->assertCount(0, $jokes, 'Service should return an empty array when API returns no jokes');

        Http::assertSentCount(1);
    }

    public function test_get_ten_random_jokes_throws_on_connection_exception(): void
    {
        // Simulate a connection failure when calling the external API by using a fake that throws
        Http::fake([
            'https://official-joke-api.appspot.com/*' => function ($request, $options = null) {
                throw new ConnectionException('Connection failed');
            },
        ]);

        $this->expectException(ConnectionException::class);

        $service = new Joke();
        $service->getTenRandomJokes();
    }
}
