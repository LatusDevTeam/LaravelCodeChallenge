<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HealthController extends Controller
{
    const STATUS_OK = 'OK';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['status' => self::STATUS_OK]);
    }
}
