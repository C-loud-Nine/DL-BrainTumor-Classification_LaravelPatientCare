<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepVerdict extends Model
{
    use HasFactory;

    protected $table = 'repverdict'; // Explicitly define the table name

    protected $fillable = ['report_id', 'verdict'];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
