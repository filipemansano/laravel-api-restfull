<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'genres';
    protected $fillable = [
        'user_id',
        'film_id',
        'comment'
    ];
    public $timestamps = true;

    public function films(){
        return $this->hasMany(Film::class);
    }
}
