<?php

namespace App\Models\Domain;

use Illuminate\Database\Eloquent\Model;

class Joke extends Model
{
    protected $table = null;
    public $timestamps = false;

    protected $fillable = ['id', 'type', 'setup', 'punchline'];
}
