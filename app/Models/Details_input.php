<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details_input extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'details_inputs';

    protected $fillable = [
        'idForms',
        'idInput',
        'value'
    ];
}
