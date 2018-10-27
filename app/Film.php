<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $table = 'films';

    protected $fillable = [
        'user_id',
        'name',
        'slug_name',
        'description',
        'release_date',
        'rating',
        'ticket_price',
        'country',
        'photo'
    ];

    public $timestamps = true;

    public function genres(){
        return $this->belongsToMany(Genre::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
       
}
