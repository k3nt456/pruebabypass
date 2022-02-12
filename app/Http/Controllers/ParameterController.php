<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Input;
use App\Models\Page;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ParameterController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function generateCode($longitud)
    {
        $key     = '';
        $pattern = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max     = strlen($pattern) - 1;

        for ($i = 0; $i < $longitud; $i++) $key .= $pattern[mt_rand(0, $max)];
        return $key;
    }

    public function index()
    {
        $list = DB::table('parameters')
            ->select('id', 'name', 'idinputs', 'idchannel', 'url')
            ->where('status', '=', 1)
            ->get();

        return $list;
    }

    public function indexOnly(Request $request)
    {
        $row = $this->BuscarCampana($request->idCampaign);

        return $row;
    }

    public function BuscarCampana($idCampaign)
    {

        try {
            $idPage = Page::where('name', $idCampaign)->first();

            if (is_null($idPage)) {
                return "Campaña no encontrada";
            } else {

                $parameter = Parameter::where('idPage', $idPage->id)->first();
                // dd($parameter);
                if (is_null($idPage)) {
                    return "Parameter no encontrado";
                } else {
                    $array = array(
                        'idparameter' => $parameter['id'],
                        'name' => $parameter['name'],
                        'url' => $parameter['url'],
                        'channels' => $this->listUrls($idCampaign, $parameter['id'], $parameter['url'], $parameter['idchannel'], $parameter['idinputs']),
                        'idinputs' => json_decode($parameter['idinputs']),
                    );
                    return $array;
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {


            $auth = [];

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'idinputs' => ['required', 'string'],
                'idchannel' => ['required', 'string'],
                'url' => ['required', 'string'],
                'idEmpresa' => ['required', 'integer'],
            ]);

            if ($validator->fails()) {

                $auth['message'] = 'No se proporciono el acceso.';
            } else {

                $idInputs = json_decode($request->idinputs);
                $idChannels = json_decode($request->idchannel);

                $arrayInputs = Input::select('id', 'name')->whereIn('id', $idInputs)->get();
                $arrayChannel = Channel::select('id', 'name')->whereIn('id', $idChannels)->get();

                $page = $this->getPage($request->idEmpresa);

                if (is_null($page)) {
                    $auth['message'] = 'Pagina no encontrada';
                } else {

                    $idPage     = (int)$request->idEmpresa;
                    $namePage   = (string)$page->name;

                    do {

                        $code = $this->verifyCode($namePage);

                        if (is_null($code[1])) {
                            $par = DB::select("CALL sp_insert_parameters(?,?,?,?,?,?);", array($request->name, $code[0], $arrayInputs, $arrayChannel, $request->url, $idPage));
                            $idParameter = DB::select('SELECT LAST_INSERT_ID() as id;');
                            break;
                        }
                    } while (0);
                }
            }

            $parameter = Parameter::where('parameters.id', $idParameter[0]->id)
                ->join('page', 'parameters.idPage', 'page.id')
                ->select('parameters.*', 'page.name as page')
                ->first();

            $links = $this->listUrls($parameter->page, $parameter->id, $parameter->url, $parameter->idchannel, $parameter->idinputs);
            
            DB::commit();
            return $links;
        } catch (\Throwable $th) {
            DB::rollBack();
            // return array(
            //     'status' => false,
            //     'descripcion' => 'Parametros incorrectos'
            // );
            throw $th;
        }
    }

    public function getPage($id)
    {

        $idPage = Page::where('id', '=', $id)->first();

        return $idPage;
    }

    public function verifyCode($namePage)
    {

        $cod2dig    = $this->generateCode(2);
        $abrev_page = strtoupper(substr((string)$namePage, 0, 2) . $cod2dig);
        $abrevArray = Parameter::select('abrev')->where('abrev', '=', $abrev_page)->where('status', '=', 1)->first();

        $array = [$abrev_page, $abrevArray];

        return $array;
    }

    public function listUrls($Campaign, $idParameter, $url, $idChannel, $idInputs)
    {
        try {

            $channel = json_decode($idChannel);
            $inputs = json_decode($idInputs);

            $links = [];
            $inputsCod = "";
            for($i = 0; $i<count($inputs); $i++){
                $inputsCod = $inputsCod . base64_encode($inputs[$i]->name) . "-";
            }

            for ($i = 0; $i < count($channel); $i++) {

                $channelCod = base64_encode($channel[$i]->name);
                $links[$i] = array(
                    'channel' => $channel[$i]->name,
                    'link' => "$url?idParameter=$idParameter&&name=$Campaign&&channel=$channelCod&&idinputs=$inputsCod",
                );
            }
            return $links;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
