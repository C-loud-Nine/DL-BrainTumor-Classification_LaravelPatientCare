<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'type', 'picture', 'location',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relationship: A user has one doctor
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
}
