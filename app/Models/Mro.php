<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mro extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'ratings' => 'array',
        'expiry_date' => 'date',
    ];
}
