<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Details_input;
use App\Models\Form;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use function GuzzleHttp\json_decode;
use function PHPUnit\Framework\isNull;

class FormController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {
            $forms = Form::select('forms.*', 'parameters.name', 'channel.name as channel')
                ->join('parameters', 'forms.idParameter', 'parameters.id')
                ->join('channel', 'forms.idChannel', 'channel.id')
                ->orderBy('parameters.name', 'asc')
                ->orderBy('code', 'asc')
                ->get();

            $formulario = [];
            for ($i = 0; $i < count($forms); $i++) {

                $formulario[$i] = array(
                    'codigo'    => $forms[$i]['code'],
                    'idCampaña' => $forms[$i]['idParameter'],
                    'campaña'   => $forms[$i]['name'],
                    'idCanal'   => $forms[$i]['idChannel'],
                    'canal'     => $forms[$i]['channel'],
                    'inputs'    => $this->viewDetailInputs($forms[$i]['id'])
                );
            }

            return $formulario;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function viewDetailInputs($idForm)
    {

        try {
            $detailInput = Details_input::select('inputs.name', 'value')
                ->join('inputs', 'details_inputs.idInput', '=', 'inputs.id')
                ->where('idForms', '=', $idForm)
                ->get();

            for ($i = 0; $i < count($detailInput); $i++) {

                $query[$i] = array(
                    'input' => $detailInput[$i]['name'],
                    'value' => $detailInput[$i]['value'],
                );
            }

            return $query;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function viewDetailInputs2($idForm)
    {

        try {
            $detailInput = Details_input::select('inputs.name', 'value')
                ->join('inputs', 'details_inputs.idInput', '=', 'inputs.id')
                ->where('idForms', '=', $idForm)
                ->get();

            for ($i = 0; $i < count($detailInput); $i++) {

                $query[$i] = array(
                    $detailInput[$i]['name'] => $detailInput[$i]['value']
                );
            }

            return $query;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {


            $auth = [];

            $validatorParameter = Validator::make($request->all(), [
                'idParameter' => ['required', 'integer'],
                'channel'     => ['required', 'string']
            ]);

            if ($validatorParameter->fails()) {
                $auth['message'] = 'Ingresa el parametro correcto';
            } else {

                $idInputs = Parameter::select('idinputs')->where('id', '=', $request->idParameter)->first();
                $idChannel = Channel::select('id')->where('name', '=', $request->channel)->first();

                if (is_null($idInputs)) {
                    $auth['message'] = 'Ingresa una campaña valida';
                }

                if (is_null($idChannel)) {
                    $auth['message'] = 'No es un canal valido';
                } else {
                    $inputs = json_decode($idInputs->idinputs);

                    for ($i = 0; $i < count($inputs); $i++) {

                        $validatorInputs = Validator::make($request->all(), [
                            $inputs[$i]->name => ['required', 'string'],
                        ]);
                    }

                    if ($validatorInputs->fails()) {

                        $auth['message'] = 'Ingresa parametros correctos';
                    } else {
                        $idChannel = (int)$idChannel->id;
                        DB::select("CALL sp_insert_form($request->idParameter, $idChannel);");
                        $idParameter = DB::select('SELECT LAST_INSERT_ID() as id;');
                        $id = (int)$idParameter[0]->id;

                        for ($i = 0; $i < count($inputs); $i++) {

                            $valueInput[$i] = $inputs[$i]->name;
                            $idInput[$i] = (int)$inputs[$i]->id;
                        }
                        $i = 0;
                        foreach ($valueInput as $inputs => $value) {
                            DB::select("CALL sp_insert_details_inputs(?,?,?);", array($id, $idInput[$i], $request->$value));
                            $i++;
                            $auth['message'] = "Formulario ingresado con exito";
                        }
                    }
                }
            }

            $lastForm = DB::table('forms')
                          ->where('idParameter', '=', $request->idParameter)->orderBy('id', 'desc')->first()->code;
                        //->latest('code')
                        //->first()
                       // ->code;
            //dd($lastForm->code);
            return array('idForm' => $lastForm,
                    'message' => 'Formulario ingresado con exito'
                );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try {

            $formulario = [];
            $idParameter = Parameter::select('id')->where('id', '=', $id)->first();


            if (is_null($idParameter)) {

                $formulario['message'] = 'Ingresa una campaña valida';

            } else {

                $forms = Form::select('forms.*', 'parameters.name', 'channel.name as channel')
                    ->join('parameters', 'forms.idParameter', 'parameters.id')
                    ->join('channel', 'forms.idChannel', 'channel.id')
                    ->where('idParameter', '=', $idParameter->id)
                    ->orderBy('parameters.name', 'asc')
                    ->orderBy('code', 'asc')
                    ->get();

                $formulario = [];
                for ($i = 0; $i < count($forms); $i++) {

                    $formulario[$i] = array(
                        'codigo'    => $forms[$i]['code'],
                        'idCampaña' => $forms[$i]['idParameter'],
                        'campaña'   => $forms[$i]['name'],
                        'idCanal'   => $forms[$i]['idChannel'],
                        'canal'     => $forms[$i]['channel'],
                        'inputs'    => $this->viewDetailInputs($forms[$i]['id'])
                    );
                }
            }


            return $formulario;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function showForm($nombre)
    {

        try {

            $formulario = [];
            $idFormulario = Form::select('id')
                            ->where('code', '=', $nombre)
                            ->first()
                            ->id;


            if (is_null($idFormulario)) {

                $formulario['message'] = 'Ingresa formulario valido';

            } else {

                $forms = Form::select('forms.*')
                    ->join('details_inputs', 'forms.id', 'details_inputs.idForms')
                    ->where('forms.id', '=', $idFormulario)
                    ->orderBy('forms.code', 'asc')
                    ->orderBy('code', 'asc')
                    ->get();

                $formulario = [];
                for ($i = 0; $i < 1; $i++) {

                    $formulario[$i] = array(
                        'codigo'    => $forms[$i]['code'],
                        'inputs'    => $this->viewDetailInputs($forms[$i]['id'])
                    );
                }
            }


            return $formulario;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function showForm2($nombre)
    {

        try {

            $formulario = [];
            $idFormulario = Form::select('id')
                            ->where('code', '=', $nombre)
                            ->first()
                            ->id;


            if (is_null($idFormulario)) {

                $formulario['message'] = 'Ingresa formulario valido';

            } else {

                $forms = Form::select('forms.*')
                    ->join('details_inputs', 'forms.id', 'details_inputs.idForms')
                    ->where('forms.id', '=', $idFormulario)
                    ->orderBy('forms.code', 'asc')
                    ->orderBy('code', 'asc')
                    ->get();

                $formulario = [];
                for ($i = 0; $i < 1; $i++) {

                    $formulario[$i] = array(
                        'codigo'    => $forms[$i]['code'],
                        'inputs'    => $this->viewDetailInputs2($forms[$i]['id'])
                    );
                }
            }


            return $formulario;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


}
