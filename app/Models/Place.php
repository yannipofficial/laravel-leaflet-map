<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $fillable = [
        'cat',
        'userID',
        'name',
        'description',
        'lat',
        'long'
    ];

    /**
     * Get the comments for the place.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'placeID');
    }



    /**
     * Get the user for the place.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    /**
     * Get users that have marked this place as favorite
     */
    public function Likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'placeID', 'userID');
    }
}
