<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OrganizacaoRequest;
use App\Http\Resources\OrganizacaoResource;
use App\Models\Organizacao;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;

class OrganizacaoController extends Controller
{
    
    public function __construct() {

    }

    public function index() {

        // somente Admin e EncPes têm permissão
        // if (Gate::none(['is_admin','is_encpes'], new Organizacao())) {
        //     abort(403, 'Usuário não autorizado!');
        // }        

        if (request()->ajax()) {

            return DataTables::eloquent(Organizacao::select(['acl_organizacaos.*']))
                ->setRowId( function($param) { return $param->id; })
                ->addIndexColumn()
                ->make(true);

            // abaixo funciona para um Datatables com dataSrc: "" no ajax que não necessida do aoData[]  
            // $organizacaos = Organizacao::all();
            // $organizacaos = OrganizacaoResource::collection(Organizacao::all());    // aplica um Resource sobre a coleção add DT_RowId
            // return response()->json($organizacaos);
        }

        return view('acl/OrganizacaoDatatable');
        // return view('acl/OrganizacaoDatatableApiAxios');        
        // return view('acl/OrganizacaoDatatableApiAjax');
        // return view('acl/OrganizacaoDatatableApiFetch');       
    }

    public function show(Request $request)
    {        
        $organizacao = Organizacao::where('id',$request->id)->first();
        return Response()->json($organizacao);
    }      

    public function destroy(Request $request)
    {        
        $organizacao = Organizacao::where(['id'=>$request->id])->delete();
        return Response()->json($organizacao);
    }   

    public function store(OrganizacaoRequest $request)
    {
        $organizacao = Organizacao::where(['id'=>$request->id]);
        $organizacao = Organizacao::updateOrCreate(
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
        return Response()->json($organizacao);
    }     
}
