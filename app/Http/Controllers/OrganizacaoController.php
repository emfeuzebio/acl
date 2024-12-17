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

            /**
             * Forma para usar Datatables com serverSide: false e sem data[]
             *  com dataSrc: "" no ajax que não necessida do aoData[]
             */
            // $organizacaos = Organizacao::all();                                          // Recupera as organizações
            // $organizacaos = OrganizacaoResource::collection(Organizacao::all());         // Recupera as organizações aplicando um Resource sobre a coleção add DT_RowId

            $organizacaos = Organizacao::with('sistemas')->where('id','>=', '1')->get();    // Recupera as organizações com a relação 'sistemas', filtrando por id >= 1
            // $organizacaos = Organizacao::with('sistemas')->where('id','=', request()->pessoa_id)->get();    // filtra pelo parametro request()->pessoa_id recebido no GET 

            // Retorna os dados no formato esperado pelo DataTables com data[]
            // return response()->json(['data' => $organizacaos]);            

            // retorna os dados customizados aplicando o OrganizacaoResource() que automaticamente adiciona o data[] poderá retiar o 'sistemas' se não programado
            return OrganizacaoResource::collection($organizacaos);            

            // retorna SEM customizar os dados e SEM o data[] exigindo que a diretiva dataSrc: "" no ajax
            // return response()->json($organizacaos);     

            /**
             * Forma para usar Yajra Datatables com ServerSide TRUE. Mais complexo e difícil combinar com API
             */
            // return DataTables::eloquent(Organizacao::select(['acl_organizacaos.*']))
            //     ->setRowId( function($param) { return $param->id; })
            //     ->addIndexColumn()
            //     ->make(true);
            // return response()->json($organizacaos);     
        }

        return view('acl/OrganizacaoDatatableNoServer');        // Blade com Datatables serverSide: false
        // return view('acl/OrganizacaoDatatable');             // Blade com Yajra Datatables
        // return view('acl/OrganizacaoDatatableApiAxios');     // Consulta API via Axios   
        // return view('acl/OrganizacaoDatatableApiAjax');      // Consulta API via Ajax
        // return view('acl/OrganizacaoDatatableApiFetch');     // Consulta API via Fetch
    }

    public function show(Request $request)
    {        
        $organizacao = Organizacao::where('id', $request->id)->first();
        return response()->json($organizacao);
    }      

    public function destroy(Request $request)
    {        
        $organizacao = Organizacao::where('id', $request->id)->delete();
        return response()->json($organizacao);
    }   

    public function store(OrganizacaoRequest $request)
    {
        $organizacao = Organizacao::updateOrCreate(
            ['id' => $request->id],
            $request->only(['nome', 'sigla', 'descricao', 'ativo'])
        );  

        return response()->json($organizacao);
    }     
}
