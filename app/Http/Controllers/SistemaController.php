<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SistemaRequest;
use App\Models\Organizacao;
use App\Models\Sistema;
use Yajra\DataTables\Facades\DataTables;

class SistemaController extends Controller
{
    
    public function __construct() {

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
        $organizacaos = [];

        return view('acl/SistemaDatatable', ['$organizacaos' => $organizacaos]);
    }

    public function show(Request $request)
    {        
        $where = array('id'=>$request->id);
        $sistema = Organizacao::where($where)->first();
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
                'nome' => $request->nome,
                'sigla' => $request->sigla,
                'descricao' => $request->descricao,
                'ativo' => $request->ativo,
            ]
        );  
        return Response()->json($sistema);
    }     

}
