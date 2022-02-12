<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $table = 'forms';

    protected $fillable = [
        'id',
        'code',
        'idParameter',
        'idChannel'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
