<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SistemaRequest;
use App\Models\Organizacao;
use App\Models\Sistema;
use Yajra\DataTables\Facades\DataTables;

class SistemaController extends Controller
{
    protected $Organizacao = null;

    public function __construct() {

        $this->Organizacao = new Organizacao();
    }

    public function index() {

        if (request()->ajax()) {

            return DataTables::eloquent(Sistema::select(['acl_sistemas.*']))
                ->setRowId( function($param) { return $param->id; })
                ->addIndexColumn()
                ->make(true);

            // abaixo funciona para um Datatables com dataSrc: "" no ajax que não necessida do aoData[]  
            // $organizacaos = Organizacao::all();
            // $organizacaos = OrganizacaoResource::collection(Organizacao::all());    // aplica um Resource sobre a coleção add DT_RowId
            // return response()->json($organizacaos);
        }

        // aqui precisamos pegar a lista de Organizacoes e enviar para o view fazer o combobox
        $organizacaos = $this->Organizacao->all()->sortBy('nome');

        return view('acl/SistemaDatatable', ['organizacaos' => $organizacaos]);
    }

    public function show(Request $request)
    {        
        // $where = array('id'=>$request->id);
        // $sistema = Sistema::where($where)->first();
        $sistema = Sistema::where('id',$request->id)->first();
        return Response()->json($sistema);
    }    

    public function destroy(Request $request)
    {        
        $sistema = Sistema::where(['id'=>$request->id])->delete();
        return Response()->json($sistema);
    }   

    public function store(SistemaRequest $request)
    {
        $sistema = Sistema::where(['id'=>$request->id]);
        $sistema = sistema::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'organizacao_id' => $request->organizacao_id,
                'nome' => $request->nome,
                'sigla' => $request->sigla,
                'descricao' => $request->descricao,
                'ativo' => $request->ativo,
            ]
        );  
        return Response()->json($sistema);
    }     

}
