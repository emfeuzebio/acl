<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissaoRequest;
use App\Models\Permissao;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissaoController extends Controller
{
    public function index() {

        // if(request()->ajax()) {

        //     return DataTables::eloquent(Entidade::with('entidades'))
        //         ->filter(function ($query) { $query->where('id', '>=', "1");}, true)        
        //         ->setRowId( function($param) { return $param->id; })
        //         // ->addColumn('entidades', function ($param) {return $this->getEntidades($param->id); })
        //         ->rawColumns(['entidades'])
        //         ->addIndexColumn()
        //         ->make(true);        
        // }
        // return view('acl/PerfilsDatatable');
    }

    public function show(Request $request)
    {        
        // $Entidade = Entidade::with('permissaos')->where('id',$request->id)->first();
        // return Response()->json($Entidade);
    }    

    public function store(PermissaoRequest $request)
    {
        $Permissao = Permissao::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'entidade_id' => $request->entidade_id,
                'rota' => trim(strtolower($request->rota)),
                'descricao' => trim($request->descricao),
                'ativo' => $request->ativo,
            ]
        );  
        return Response()->json($Permissao);
    }  

    public function destroy(Request $request)
    {        
        $Permissao = Permissao::where('id',$request->id)->delete();
        return Response()->json($Permissao);
    }       

}


