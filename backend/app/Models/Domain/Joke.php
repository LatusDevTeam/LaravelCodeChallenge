<?php

namespace App\Models\Domain;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Joke extends Model
{
    use HasFactory;

    protected $table = null;
    public $timestamps = false;

    protected $fillable = ['id', 'type', 'setup', 'punchline'];
}
