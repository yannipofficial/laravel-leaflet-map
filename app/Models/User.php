<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Return user role
     *
     * @return UserRole
     */
    public function role()
    {
        return UserRole::from($this->role);
    }

    /**
     * Checks if user is an admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        
        return UserRole::from($this->role) === UserRole::Admin;
    }

    /**
     * Get the places of the user.
     */
    public function places()
    {
        return $this->hasMany(Place::class, 'userID');
    }

    /**
     * Get favorite places of the user.
     */
    public function FavoritePlaces()
    {
        return $this->belongsToMany(Place::class, 'likes', 'userID', 'placeID');
    }
}
