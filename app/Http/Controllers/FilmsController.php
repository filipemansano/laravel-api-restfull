<?php

namespace App\Http\Controllers;

use App\Film;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use JWTAuth;

class FilmsController extends Controller
{
    public function index()
    {
        $films = Film::all();
        return response()->json($films);
    }

    public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['index','show']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|',
            'release_date'  => 'required|date',
            'rating'        => 'required|int|between:1,5',
            'ticket_price'  => 'required|string',
            'country'       => 'required|string|min:3',
            'photo'         => 'required|string',
        ]);
    }

    public function store(Request $request)
    {
        $bodyContent = json_decode($request->getContent(), true);

        $validator = $this->validator($bodyContent);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 400);
        }

        $loggedUser = JWTAuth::toUser(JWTAuth::getToken());

        $film = Film::create([
            'user_id'       => $loggedUser->id,
            'name'          => $bodyContent['name'],
            'description'   => $bodyContent['description'],
            'release_date'  => $bodyContent['release_date'],
            'rating'        => $bodyContent['rating'],
            'ticket_price'  => $bodyContent['ticket_price'],
            'country'       => $bodyContent['country'],
            'photo'         => $bodyContent['photo'],
        ]);

        return response()->json([ 'id' => $film->id], 400);
    }
}
