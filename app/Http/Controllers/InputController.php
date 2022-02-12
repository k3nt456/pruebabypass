<?php

namespace App\Http\Controllers;

use App\Models\Input;
use Illuminate\Http\Request;

class InputController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {

        $input = Input::all();

        return $input;

    }
}
