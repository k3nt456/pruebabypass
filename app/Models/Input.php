<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'inputs';

    protected $fillable = [
        'name'
    ];

}
