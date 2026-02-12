<?php

namespace App\Services;

use App\Models\Domain\Joke as JokeModel;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Joke
{
    const DOMAIN = 'https://official-joke-api.appspot.com';

    /**
     * Retrieves up to ten random programming jokes based on the provided filters.
     *
     * @param array $filters An associative array containing filter options. It may include 'limit' to specify the maximum
     *                       number of jokes to retrieve, defaulting to 10 if not provided.
     * @return array An array of JokeModel objects representing the retrieved jokes.
     */
    public function getTenRandomJokes(array $filters): array
    {
        $limit = $filters['limit'] ?? 10;
        $resourcePath  = '/jokes/programming/ten/';

        $jokes = [];
        foreach (Http::get(self::DOMAIN . $resourcePath)->json() as $joke) {
            if (count($jokes) >= $limit) {
                break;
            }

            $jokes[] = new JokeModel($joke);
        }

        return $jokes;
    }
}
