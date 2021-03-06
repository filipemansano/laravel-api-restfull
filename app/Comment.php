<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = [
        'user_id',
        'film_id',
        'comment'
    ];
    public $timestamps = true;

    public function films(){
        return $this->hasMany(Film::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
