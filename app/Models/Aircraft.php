<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    use HasFactory;

    protected $table = 'aircraft';

    protected $guarded = [];

    protected $casts = [
        'issue_date' => 'date',
    ];
}
