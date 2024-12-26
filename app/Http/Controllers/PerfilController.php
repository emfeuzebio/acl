<?php

namespace App\Http\Controllers;

use App\Http\Requests\PerfilRequest;
use App\Models\Autorizacao;
use App\Models\Entidade;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Perfil;
use Illuminate\Support\Str;
use App\Models\Permissao;
use App\Models\Rota;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerfilController extends Controller
{
    public function index() {

        // $Entidade = Entidade::with('permissaos')->where('id','=','1')->first();
        // $Entidade = Entidade::where('id','=','1')->first();
        // dd($Entidade);
        // dd($Entidade->permissaos);
        // foreach($Entidade->permissaos as $permissao) {
        //     dd($permissao);
        // }

        // busca a Entidade e sua rotas
            // $Entidade = Entidade::with('rotas')->where('id','=','1')->first();
            // foreach($Entidade->rotas as $rota) {
            //     echo '<pre>';
            //     print_r($rota->rota) . PHP_EOL;
            //     echo '</pre>';
            //     // dd($rota);
            // }
            // die('');

        // busca a Entidade e sua rotas
        // $Entidade = Autorizacao::with('rotas')->get();
        // $Entidade = Autorizacao::all();
        // dd($Entidade);

        // foreach($Entidade->rotas as $rota) {
        //     echo '<pre>';
        //     print_r($rota->rota) . PHP_EOL;
        //     echo '</pre>';
        //     // dd($rota);
        // }
        // die('');

        // $data = DataTables::eloquent(Perfil::with('entidades'))
        //     ->filter(function ($query) { $query->where('id', '>=', "1");}, true)        
        //     ->setRowId( function($param) { return $param->id; })
        //     // ->addColumn('entidades', function ($param) {return $this->getEntidades($param->id); })
        //     ->rawColumns(['entidades'])
        //     ->addIndexColumn()
        //     ->make(true);        
        // dd($data);

        if(request()->ajax()) {

            return DataTables::eloquent(Perfil::with('entidades'))
            // return DataTables::eloquent(Perfil::select(['acl_perfils.*']))
                ->filter(function ($query) { $query->where('id', '>=', "1");}, true)        
                ->setRowId( function($param) { return $param->id; })
                ->addColumn('entidades', function ($param) {return $this->getEntidades($param->id); })
                ->rawColumns(['entidades'])
                ->addIndexColumn()
                ->make(true);        
        }
        return view('acl/PerfilsDatatable');
    }


    protected function getEntidades($perfil_id) {

        // $entidades = Entidade::where('id',$perfil_id)->get();
        // $entidadesLabels  = '';
        // foreach($entidades as $entidade) {
        //     $entidadesLabels .= '<button class="btn btn-success btn-xs btnPerfil" data-perfil-id="' . $entidade->id . '" data-toggle="tooltip" title="Ver os detalhes deste Perfil de Acesso">' . $entidade->id . ' ' . $entidade->nome . '</button> ';
        // }

        $sql = "
            SELECT DISTINCT 
                  acl_autorizacaos.entidade_id AS id
                , acl_entidades.model
                , acl_entidades.id
            FROM acl_autorizacaos
                INNER JOIN acl_entidades ON acl_entidades.id = acl_autorizacaos.entidade_id
            WHERE perfil_id = ?
            ORDER BY acl_entidades.id ASC
        ";        
        $entidades = DB::select($sql,[$perfil_id]);
        
        return $entidades;
    }    


    public function destroy(Request $request)
    {        
        $Perfil = Perfil::where('id',$request->id)->delete();
        return Response()->json($Perfil);
    }        


    public function concederEntidade(Request $request)
    {        
        $EntidadePerfil = ['sucesso' => false];

        if($request->operacao == 'inserir') {    

            // PASSO 1 - Vamos busca os dados a Entidade que foi inserida
            $Entidade = Entidade::with('rotas')->where('id','=',$request->entidade_id)->first();

            // PASSO 2 - Vamos conceder (inserir) as Autorizações para cada Rota da Entidade com 'NÃO' como padrão 
            foreach($Entidade->rotas as $rota) {
                $result = Autorizacao::create([
                    'perfil_id' => $request->perfil_id,
                    'entidade_id' => $request->entidade_id,
                    'rota_id' => $rota->id,
                    'ativo' => ( Str::contains($rota->rota, '.index') ? 'SIM' : 'NÃO'),
                ]);  
            }

            $EntidadePerfil = ['sucesso' => $result];
        }

        if($request->operacao == 'excluir') {

            $result = DB::transaction(function() use ($request) {

                // PASSO 1 - vamos excluir a Entidade do Perfil
                //DB::table('acl_entidade_perfil')->where('perfil_id',$request->perfil_id)->where('entidade_id',$request->entidade_id)->delete();

                // PASSO 2 - Vamos excluir todas as Autorizações concedidas nas Rotas
                DB::table('acl_autorizacaos')->where('perfil_id',$request->perfil_id)->where('entidade_id',$request->entidade_id)->delete();
            });

            $EntidadePerfil = ['sucesso' => $result];
        }
        
        return Response()->json($EntidadePerfil);
    }        

    public function show(Request $request)
    {        
        // $perfil = Perfil::where('id',$request->id)->first();                // trás apenas o Perfil
        // $perfil = Perfil::with('autorizacoes')->find($request->id);         // trás o Perfil com todas autorizações relacionadas

        $perfil = Perfil::with('autorizacoes.rota')->find($request->id);    // recupera o Perfil com suas Autorizacoes e as Rotas associadas
        return Response()->json($perfil);
    }       

    public function store(PerfilRequest $request)
    {
        $exception = DB::transaction(function() use ($request) {
            try {

                // PASSO 1 - vamos inserir ou Salvar o Perfil
                $Perfil = Perfil::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'nome' => $request->nome,
                        'descricao' => $request->descricao,
                        'ativo' => $request->ativo,
                    ]
                );  

                // PASSO 2 - vamos inserir todas as respectivas Autorizações das Entidades Básicas no novo Perfil de Acesso 
                // vamos buscar todas as Autorizações das Entidades Básicas do Perfil 1-Administrador necessárias das Entidades Padrão (ID de 1 a 9) para passar ao novo Perfil
                $sql = "
                    SELECT *
                    FROM acl_autorizacaos
                    WHERE perfil_id = 1
                    AND entidade_id BETWEEN 1 AND 9
                ";
                $autorizacaosBasicas = DB::select($sql);

    
                // Se for INSERT de um Novo Perfil de Acesso
                // PASSO 2 - vamos inserir todas as respectivas Autorizações das Entidades Básicas no novo Perfil de Acesso 
                if ($Perfil->wasRecentlyCreated) {

                    // criar as Autorizações de Cada Rota (Ação) Básica e concede ao novo Perfil de Acesso
                    foreach($autorizacaosBasicas as $key => $autorizacao) {
                        Autorizacao::create([
                            'perfil_id' => $Perfil->id,
                            'entidade_id' => $autorizacao->entidade_id,
                            'rota_id' => $autorizacao->rota_id,
                            'ativo' => 'SIM',
                        ]);  
                    }

                    // PASSO 3 - Vamos conceder acesso ao Super Usuário ID=1 no novo Perfil de Acesso
                    DB::select('INSERT INTO acl_perfil_user SET user_id = 1, perfil_id = ? ', [$Perfil->id]);
                } else {

                    /**
                     * Mesmo sendo um UPDATE do Perfil
                     * Vamos tentar criar as Autorizações de Cada Rota (Ação) Básica
                     */
                    // criar as Autorizações de Cada Rota (Ação) Básica e concede ao novo Perfil de Acesso
                    foreach($autorizacaosBasicas as $key => $autorizacao) {

                        Autorizacao::insertOrIgnore([
                            'perfil_id' => $Perfil->id,
                            'entidade_id' => $autorizacao->entidade_id,
                            'rota_id' => $autorizacao->rota_id,
                            'ativo' => 'SIM',
                        ]);  
                    }
                    
                }                           
            }
            catch(Exception $e) {
                throw new Exception('EUZ-PERFIL-Exception:' . $e);
            }
        });

        return Response()->json(is_null($exception) ? ['id' => $request->id] : $exception);
    }


}


