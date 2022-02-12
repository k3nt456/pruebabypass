<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//formulario
Route::get('/formularios/', 'App\Http\Controllers\FormController@index');
Route::post('/formularios', 'App\Http\Controllers\FormController@store');
Route::post('/formularios/{id}', 'App\Http\Controllers\FormController@show');
Route::post('/form/{nombre}','App\Http\Controllers\FormController@showForm');
Route::post('/form2/{nombre}','App\Http\Controllers\FormController@showForm2');

//parametros
Route::post('/parametros', 'App\Http\Controllers\ParameterController@store');

//register
Route::post('/register','App\Http\Controllers\RegisterController@register');

//login
Route::post('/login','App\Http\Controllers\LoginController@login');

//listarURLs
Route::get('/urls','App\Http\Controllers\ParameterController@listUrls');

//listarChannel
Route::get('/channel','App\Http\Controllers\ChannelController@index');

//listarInputs
Route::get('/inputs','App\Http\Controllers\InputController@index');

//listarCampañas
Route::get('/campanas','App\Http\Controllers\ParameterController@index');

//listarUnaCampaña
Route::post('/campanas/','App\Http\Controllers\ParameterController@indexOnly');

//listarEmpresas
Route::get('/empresa','App\Http\Controllers\EmpresaController@index');

//Añadir empresa
Route::post('/insertEmpresa', 'App\Http\Controllers\EmpresaController@store');
