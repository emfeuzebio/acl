<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        if (request()->ajax()) {

            // Usar este quando usar o DataTables com serverSide: true. 
            // return DataTables::eloquent(User::with('perfis'))
            //     ->filter(function ($query) { $query->where('id', '>=', "1");}, true)
            //     ->setRowId( function($param) { return $param->id; })
            //     // ->rawColumns(['perfis'])
            //     ->addIndexColumn()
            //     ->make(true);

            $users = User::where('id','>=', '1')->with('perfis')->get();    // Recupera os Usuários com a relação 'perfis', filtrando por id >= 1

            $autorizacoes = $this->getAbilities();  

            // Adicionar a coluna 'autorizacoes' na Collection a cada User: function (User $user) use ($autorizacao) 
            $users->each(function ($user) use ($autorizacoes)  {  // Collection
                $user->setAttribute('autorizacoes', $autorizacoes);
            });          

            return [
                'data' => $users,
                'autorizacoes' => $autorizacoes,
            ];
        }
        return view('acl/UserDatatable');
    }    

    public function listarPerfis(Request $request) {

        $perfis = Perfil::orderBy('id')->get();
        // print_r($perfis);
        // $perfis = $user->perfis()->where('status', 'ativo')->orderBy('nome')->get();

        foreach($perfis as $key => $perfil) {
            $resp = DB::select('SELECT COUNT(*) AS qtd FROM acl_perfil_user WHERE user_id = ? AND perfil_id = ? ',[$request->id, $perfil->id]);
            $perfis[$key]['concedido'] = $resp[0]->qtd > 0 ? 'checked' : '';
        }

        return Response()->json($perfis);        
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

        return Response()->json($User);
    }   
    
    public function destroy(Request $request)
    {        
        $User = User::where(['id'=>$request->id])->delete();
        return Response()->json($User);
    }   

    /**
     * Retorna um array com todas as habilidades (ações/rotas autorizadas) do User logado as quais foram definidas com Gate::define()
     */
    protected function getAbilities()
    {
        $rotasAutorizadas = [];

        // recupera um Collection de closures (Funcções Anônimas) nesse caso que espera um User como parâmetro: "user.index" => Closure(User $user)
        foreach (Gate::abilities() as $ability => $callback) {
            if (Gate::allows($ability)) {
                $rotasAutorizadas[] = $ability;  // Adiciona a ability ao array se o usuário tiver permissão
                // if (stripos($ability, 'organizacao') !== false) {
                //     $rotasAutorizadas[] = $ability;  // Adiciona a ability ao array se o usuário tiver permissão
                // }
            }
        }        

        return $rotasAutorizadas;
    }        


    
}
