<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'phone', 'specialization', 'room', 'appointment', 'rating'
    ];

    // The 'user' relationship is defined, assuming each doctor belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // You can add additional methods for your business logic if needed
}
