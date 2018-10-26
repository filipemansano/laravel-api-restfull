<?php

namespace App\Http\Controllers;

use App\Film;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use JWTAuth;

class FilmsController extends Controller
{
    private $imgPath;

    public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['index','show']]);
        $this->imgPath = public_path() . "/img/films";
    }

    public function index(){
        $films = Film::all();
        return response()->json($films);
    }

    // get mime type by base64 image
    private function getMimeTypeByBase64($base64){
        $image = base64_decode($base64);
        $f = finfo_open();
        return finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        Validator::extend('is_image',function($attribute, $value, $params, $validator) {
            $result = $this->getMimeTypeByBase64($value);
            return preg_match("/image/", $result);
        },'image is invalid');

        return Validator::make($data, [
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|',
            'release_date'  => 'required|date',
            'rating'        => 'required|int|between:1,5',
            'ticket_price'  => 'required|string',
            'country'       => 'required|string|min:3',
            'photo'         => 'required|string|is_image',
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

        // get image info, and create a randon name
        $imgType = explode("/",$this->getMimeTypeByBase64($bodyContent['photo']));
        $imgToken  = md5(uniqid(rand(), true));
        $imgName = "film-{$imgToken}.{$imgType[1]}";
        $imgPath = "{$this->imgPath}/{$imgName}";

        // save img in disk
        file_put_contents($imgPath, base64_decode($bodyContent['photo']));

        try{

            $film = Film::create([
                'user_id'       => $loggedUser->id,
                'name'          => $bodyContent['name'],
                'description'   => $bodyContent['description'],
                'release_date'  => $bodyContent['release_date'],
                'rating'        => $bodyContent['rating'],
                'ticket_price'  => $bodyContent['ticket_price'],
                'country'       => $bodyContent['country'],
                'photo'         => $imgName,
            ]);

        }catch(\Exception $e){

            if(file_exists($imgPath)){
                unlink($imgPath);
            }

            throw $e;
        }

        return response()->json([ 'id' => $film->id], 200);
    }
}
