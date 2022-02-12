<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\RegistersUsers;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','create']]);
    }

    public function login(Request $request)
    {
        $rut = $request->rut;
        $IDCompany = DB::table('company_admin')
                        ->select('id')
                        ->where('rut', '=',$rut)
                        ->first();


        if(is_null($IDCompany)){
            $mensajeRUT = array('acceso' => false,
            'descripcion' => 'rut invalido');

                return $mensajeRUT;

        }else{
            $idComp = $IDCompany->id;
            $credentials = array('idAdmin' => $idComp,
        'user_nick' =>$request->user_nick,
        'password'=>$request->password);

        if ($token = Auth::attempt($credentials)) {
            $eltoken = $this->respondWithToken($token);
            $mensajeSI = array('acceso' => true,
                    'descripcion' => 'acceso permitido',
                    'token' => $eltoken);
        }else{
            $mensajeNO = array('acceso' => false,
                    'descripcion' => 'acceso restringido');
            return $mensajeNO;
        }
     }

        return $mensajeSI;
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
           // 'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
