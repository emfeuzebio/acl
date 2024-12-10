<?php

namespace App\Http\Controllers;

use App\Http\Requests\AutorizacaoRequest;
use App\Models\Autorizacao;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AutorizacaoController extends Controller
{
    public function index() {

    }

    public function store(AutorizacaoRequest $request)
    {
        $Autorizacao = Autorizacao::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'perfil_id' => $request->perfil_id,
                'entidade_id' => $request->entidade_id,
                'rota_id' => $request->rotal_id,
                'ativo' => $request->ativo,
            ]
        );  
        return Response()->json($Autorizacao);
    }  

    public function authorizar(Request $request)
    {
        $Autorizacao = Autorizacao::find($request->id);
        $Autorizacao->ativo = $request->ativo;
        $ret = $Autorizacao->save();
        // dd($ret);
        
        return Response()->json($Autorizacao);
    }

    public function destroy(Request $request)
    {        
        $Autorizacao = Autorizacao::where('id',$request->id)->delete();
        return Response()->json($Autorizacao);
    }       

}


