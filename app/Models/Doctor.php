<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'phone', 'specialization', 'room', 'appointment', 'rating', 'rating_count'
    ];

    // Relationship: A doctor belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A doctor has many ratings
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    
}
