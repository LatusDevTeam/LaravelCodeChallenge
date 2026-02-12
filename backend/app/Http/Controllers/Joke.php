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
     * @return AnonymousResourceCollection
     * @throws ConnectionException
     */
    public function index(): AnonymousResourceCollection
    {
        return \App\Http\Resources\Joke::collection($this->joke->getTenRandomJokes());
    }
}
