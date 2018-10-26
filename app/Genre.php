<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'genres';
    protected $fillable = ['name'];
    public $timestamps = true;

    public function films(){
        return $this->hasMany(Film::class);
    }
}
