<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // Add 'type' to the $fillable property so it can be mass-assigned
    protected $fillable = [
        'scanner_name',
        'scanner_id',
        'user_name',
        'user_id',
        'report_class',
        'confidence',
        'report_image',
        'type', // Add this line for the 'type' field
    ];
}
