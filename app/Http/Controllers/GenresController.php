<?php

namespace App\Http\Controllers;

use App\Genre;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    public function index(Request $request){
        return response()->json(Genre::all());
    }
}
