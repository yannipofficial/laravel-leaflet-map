<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'color'
    ];

    /**
     * Get the place of the user.
     */
    public function places()
    {
        return $this->hasMany(Place::class, 'cat');
    }
}
