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
        
        $this->middleware('jwt.auth')->only('create');
        $this->imgPath = public_path() . "/img/films";
    }

    public function index(Request $request, $pag){

        $pag = ($pag < 1) ? 1 : intval($pag);

        $film = Film::skip($pag - 1)->take(1)->first();

        if(is_null($film)){
            return response()->json(['msg' => 'film not exists'], 404);
        }

        $total = Film::count();

        return response()->json([
            'total' => $total,
            'current' => $pag,
            'film'  => $film
        ]);
    }

    public function search(Request $request, $slug){

        $film = Film::where("slug_name", $slug)->with("comments","comments.user")->first();

        if(is_null($film)){
            return response()->json(['msg' => 'film not exists'], 404);
        }

        return response()->json($film);
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
    private function validator(array $data)
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

    public function create(Request $request)
    {
        $bodyContent = json_decode($request->getContent(), true);

        $validator = $this->validator($bodyContent);
        
        if ($validator->fails()) { 
            return response()->json(['validation_error'=>$validator->errors()], 400);
        }

        // remove all non-ascii character, replace one or plus spaces to - and set string to lower case
        $slugName = strtolower(preg_replace("/\s+/","-",preg_replace('/[~\'`^]/', null, iconv ('UTF-8', 'ASCII//TRANSLIT', $bodyContent['name']))));
        $film = Film::where('slug_name', $slugName)->first();

        if(!is_null($film)){
            return response()->json([ 'msg' => 'name is already exists.'], 400);
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
                'slug_name'     => $slugName,
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

        return response()->json([ 'id' => $film->id, 'slug_name' => $slugName], 200);
    }
}
