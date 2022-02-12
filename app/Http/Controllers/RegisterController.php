<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register (Request $request)
    {

        $rut = $request->rut;
        $idNomb = $request->user_nick;

        $IDCompany = DB::table('company_admin')
                    ->select('id')
                    ->where('rut', '=',$rut)
                    ->first();

        $IDUser = DB::table('users')
                  ->select('user_nick')
                  ->where('user_nick', '=', $idNomb)
                  ->first();

            if(is_null($IDCompany)){
                $mensajeRUT = array('acceso' => false,
                'descripcion' => 'rut invalido');

                    return $mensajeRUT;

            }if(!is_null($IDUser)){
                $mensajeUser = array('acceso' => false,
                'descripcion' => 'nombre de usuario en uso');

                    return $mensajeUser;

            }else{
                $register = new User();
                $register->user_nick = $idNomb;
                $register->nombre = $request->nombre;
                $register->password = Hash::make($request->password);
                $register->idAdmin = (int)$IDCompany->id;
                $register->idtipousuario = 1;
                $register->save();


            $mensajeRegis = array('acceso' => true,
                'descripcion' => 'usuario registrado con exito');

                    return $mensajeRegis;
        }
    }
}
