<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function __construct(){
        $this->middleware('jwt.auth');
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
            'comment' => 'required|string',
            'film_id' => 'required|int'
        ]);
    }

    public function store(Request $request)
    {
        $bodyContent = json_decode($request->getContent(), true);

        $validator = $this->validator($bodyContent);
        
        if ($validator->fails()) { 
            return response()->json(['validation_error'=>$validator->errors()], 400);
        }

        $loggedUser = JWTAuth::toUser(JWTAuth::getToken());
        
        $comment = Comment::create([
            'user_id'       => $loggedUser->id,
            'film_id'       => $bodyContent['film_id'],
            'comment'       => $bodyContent['comment']
        ]);

        return response()->json([ 'id' => $comment->id], 200);
    }
}
