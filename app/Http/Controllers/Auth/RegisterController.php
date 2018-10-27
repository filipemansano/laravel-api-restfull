<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function create(Request $request){

        $bodyContent = json_decode($request->getContent(), true);

        $validator = $this->validator($bodyContent);

        if ($validator->fails()) { 
            return response()->json(['validation_error'=>$validator->errors()], 401);
        }
        
        $user = User::create([
            'name' => $bodyContent['name'],
            'email' => $bodyContent['email'],
            'password' => bcrypt($bodyContent['password']),
        ]);
        
        return response()->json(['user_id' => $user->id], 200); 
    }
}
