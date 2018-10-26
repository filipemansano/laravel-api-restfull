<?php

namespace App\Http\Controllers;

use App\Film;

use Illuminate\Http\Request;

class FilmsController extends Controller
{
    public function index()
    {
        $films = Film::get();
        return response()->json($films);
    }
}
