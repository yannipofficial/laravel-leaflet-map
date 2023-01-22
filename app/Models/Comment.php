<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;


    protected $fillable = [
        'placeID',
        'userID',
        'commentBody'
    ];

    /**
     * Get the place that owns the comment.
     */
    public function place()
    {
        return $this->belongsTo(Place::class, 'placeID');
    }

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
