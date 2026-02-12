<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Client\ConnectionException;

class Joke extends Controller
{
    public function __construct(
        private \App\Services\Joke $joke
    ){}

    /**
     * Handles the retrieval of a collection of jokes.
     *
     * @param Request $request The incoming HTTP request containing query parameters.
     * @return AnonymousResourceCollection A collection of jokes wrapped in a JSON resource structure.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $filters = $request->query('filters');
        return \App\Http\Resources\Joke::collection($this->joke->getTenRandomJokes($filters));
    }
}
