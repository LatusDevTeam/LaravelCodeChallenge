<?php

namespace App\Services;

use App\Models\Domain\Joke as JokeModel;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Joke
{
    const DOMAIN = 'https://official-joke-api.appspot.com';

    /**
     * @return array
     * @throws ConnectionException
     */
    public function getTenRandomJokes(): array
    {
        $resourcePath  = '/jokes/programming/ten/';

        $jokes = [];
        foreach (Http::get(self::DOMAIN . $resourcePath)->json() as $joke) {
            $jokes[] = new JokeModel($joke);
        }

        return $jokes;
    }
}
