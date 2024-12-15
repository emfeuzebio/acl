<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response as HttpResponse;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        // dd('UserController->__construct()');
    }
    
    public function index() {

        // dd('UserController->index()');

        if(request()->ajax()) {

            return DataTables::eloquent(User::with('perfis'))
                ->filter(function ($query) { $query->where('id', '>=', "1");}, true)        
                ->setRowId( function($param) { return $param->id; })
                // ->rawColumns(['perfis'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('acl/UserDatatable');
    }    

    public function listarPerfis(Request $request) {

        $Perfis = Perfil::orderBy('id')->get();

        foreach($Perfis as $key => $perfil) {
            $resp = DB::select('SELECT COUNT(*) AS qtd FROM acl_perfil_user WHERE user_id = ? AND perfil_id = ? ',[$request->id, $perfil->id]);
            $Perfis[$key]['concedido'] = $resp[0]->qtd > 0 ? 'checked' : '';
        }

        return Response()->json($Perfis);        
    }

    public function concederPerfil(Request $request) {

        $result = FALSE;

        if($request->operacao == 'inserir') {    

            // https://laravel.com/docs/5.2/queries#inserts
            // https://laraveldaily.com/post/eloquent-create-query-builder-insert
            
            // Vamos conceder (inserir) o Perfil de Acesso no Usuário: opções de implementação
            // $result = DB::select('INSERT INTO acl_perfil_user SET user_id = ?, perfil_id = ? ', [$request->user_id, $request->perfil_id]);
            // $result = DB::table('acl_perfil_user')->insert(['user_id' => $request->user_id, 'perfil_id' => $request->perfil_id]);
            $result = DB::insert('INSERT INTO acl_perfil_user ( user_id, perfil_id) VALUES ( ?, ? )', [$request->user_id, $request->perfil_id]);
        }

        if($request->operacao == 'excluir') {

            // vamos excluir o Perfil de Acesso deste Usuário
            $result = DB::table('acl_perfil_user')->where('perfil_id',$request->perfil_id)->where('user_id',$request->user_id)->delete();
        }
        
        return Response()->json(['sucesso' => $result]);
    }

    public function show(Request $request)
    {        
        $User = User::where('id',$request->id)->first();
        return Response()->json($User);
    }     
    
    public function store(UserRequest $request)
    {
        $User = User::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => trim($request->name),
                'email' => trim($request->email),
                'password' => Hash::make($request->password),
            ]
        );  

        // PASSO 2 - Em sendo a criação de um Novo Usuário - INSERT 
        if ($User->wasRecentlyCreated) {
        
            // Vamos conceder o Perfil de Acesso padrão (3-Usuário) ao Usuário recém criado
            $result = DB::insert('INSERT INTO acl_perfil_user ( user_id, perfil_id) VALUES ( ?, ? )', [$User->id, 3]);
        }         

        //AQUI inserir Perfil Padrão
        return Response()->json($User);
    }   
    
    public function destroy(Request $request)
    {        
        $User = User::where(['id'=>$request->id])->delete();
        return Response()->json($User);
    }      

    
}
