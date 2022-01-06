<?php

namespace App\Http\Controllers;

class ResponseController extends Controller
{
    public function __construct()
    {
        //
    }

    public function price()
    {
        return response()->json(['price' => 100]);
    }
}
