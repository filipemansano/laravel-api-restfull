<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use JWTException;

class LoginController extends Controller
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
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
    }

    public function login(Request $request){

        $bodyContent = json_decode($request->getContent(), true);

        $validator = $this->validator($bodyContent);

        if ($validator->fails()) { 
            return response()->json(['validation_error'=>$validator->errors()], 401);
        }

        $user = User::where('email', $bodyContent['email'])
            ->first();

        $validCredentials = Hash::check($bodyContent['password'], $user->getAuthPassword());

        if (!$validCredentials) {
            return response()->json(['msg' => 'User or password is invalid'], 401); 
        }

        $credentials = ['email' => $bodyContent['email'], 'password' => $bodyContent['password']];

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['msg' => 'We cant find an account with this credentials. Please make sure you entered the right information and you have verified your email address.'], 404);
            }

        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['msg' => 'Failed to login, please try again.'], 500);
        }

        return response()->json(['token' => $token], 200);
    }
}
