<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $list = DB::table('page')
                ->select('name','url')
                ->where('state','=',1)
                ->get();

        return $list;
    }

    public function store(Request $request)
    {
        $empresa = new Page();
        $empresa->name = $request->name;
        $empresa->url = $request->url;
        $empresa->idAdmin = 1;
        $empresa->save();

        $idempresa = DB::table('page')
                    ->latest('id')
                    ->first()
                    ->id;

        return array('status' => true,
        'descripcion' => 'empresa registrada con exito',
        'id' => $idempresa);
    }
}
