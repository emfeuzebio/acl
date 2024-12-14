<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntidadeRequest;
use Illuminate\Http\Response as HttpResponse;
use App\Models\Entidade;
use App\Models\Rota;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class EntidadeController extends Controller
{
    public function index() {

        if(request()->ajax()) {

            // return DataTables::eloquent(Entidade::with('entidades'))
            return DataTables::eloquent(Entidade::with('rotas'))
                ->filter(function ($query) { $query->where('id', '>=', "1");}, true)        
                ->setRowId( function($param) { return $param->id; })
                // ->addColumn('entidades', function ($param) {return $this->getEntidades($param->id); })
                ->rawColumns(['entidades'])
                ->addIndexColumn()
                ->make(true);        
        }
        return view('acl/EntidadeDatatable');
    }

    public function rotas(Request $request)
    {        
        $Rotas = Rota::where('entidade_id',$request->id)->get();
        // echo '<pre>';
        // print_r($Rotas->rota) . PHP_EOL;
        // echo '</pre>';
        return Response()->json($Rotas);
    }   

    public function edit(Request $request)
    {        
        $Entidade = Entidade::where('id',$request->id)->first();
        return Response()->json($Entidade);
    }       

    public function store(EntidadeRequest $request)
    {
        // dd($request->all());

        // As Estidades Básica (id=[1-5]) não podem ser nem Editadas nem Excluídas
        $id = (int) $request->id;
        if($id >= 1 && $id <= 5) {
            return Response()->json(['message'=>'Exception: NÃO é permitido editar uma Entidade Básica. ID (' . $request->id . ')'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY); //422
        }

        $exception = DB::transaction(function() use ($request) {
            try {

                // PASSO 1 - vamos inserir ou Salvar a Entidade
                $Entidade = Entidade::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'model' => trim(ucwords(strtolower(str_replace(' ', '', $request->model)))),
                        'tabela' => trim(strtolower(str_replace(' ', '', $request->tabela))),
                        'descricao' => trim($request->descricao),
                        'ativo' => $request->ativo,
                        // 'ativo' => ($request->ativo == 'on' ? 'SIM' : 'NÃO'),
                    ]
                ); 
                // dd($Entidade);
    
                // PASSO 2 - Sendo um INSERT, vamos inserir as respectivas Rotas Padrão filhas na Entidade
                if ($Entidade->wasRecentlyCreated) {           
                    // $user->roles()->attach($request->input('roles'));
                    $acoes = ['index','show','update','store','destroy'];
                    $descricoes = ['Listar','Ver','Atualizar','Salvar','Excluir'];
    
                    foreach($acoes as $key => $acao) {
                        Rota::create([
                            'entidade_id' => $Entidade->id,
                            'rota' => strtolower($Entidade->model) . '.' . strtolower($acao),
                            'descricao' => $descricoes[$key] . ' ' . $Entidade->model,
                        ]);  
                    }                            
                }                                     
            }
            catch(Exception $e) {
                throw new \Exception('EUZ-ROTA-Exception:' . $e);
                // return $e;
            }
            return $Entidade;

        });

        return Response()->json(is_null($exception) ? 'Tudo Certo' : $exception);
    }        


    public function destroy(Request $request)
    {       
        // As Estidades Básica (id=[1-5]) não podem ser editadas ou excluídas 
        if($request->id <= 5) {
            return Response()->json(['message'=>'Exception: NÃO é permitido excluir uma Entidade Básica'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY); //422
        }

        try {

            $sql = "
                SELECT COUNT(*) AS qtd_rotas_autorizadas 
                FROM acl_autorizacaos
                WHERE entidade_id = ?
                  AND ativo = 'SIM'
            ";        
            $qtdRotasAutorizadas  = DB::select($sql,[$request->id]);

            // NÃO tendo nenhuma Autorização 'SIM' em nenhuma Rota da Entidade, permite a exclusão da Entidade
            if($qtdRotasAutorizadas[0]->qtd_rotas_autorizadas == 0) {
                $Entidade = Entidade::where('id',$request->id)->delete();
            } else {
                return Response()->json(['message'=>'Exception: Impossível excluir! Esta ENTIDADE possui Autorizações ativas (SIM)'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY); //422
            }

        } catch(Exception $e) {
            throw new \Exception('EUZ-ROTA-Exception:' . $e);
        }        

        // $Entidade = Entidade::where('id',$request->id)->delete();
        return Response()->json($Entidade);
    }          


    public function list(Request $request)
    {        
        // $EntidadesList = Autorizacao::all();
        // $Entidades = Autorizacao::all();

        // $Entidades = collect($EntidadesList)
        //     ->pluck("perfil_id","entidade_id")
        //     ->unique("perfil_id");
            // ->flatten(1)
            // ->unique("id");


        // $Entidades = $Entidades->unique();
        // dd($Entidades);
        // print_r($Entidades);    
        // die();
        // >toQuery()

        $Entidades = Entidade::orderBy('id')->get();
        foreach($Entidades as $key => $entidade) {
            // $resp = DB::select('SELECT id FROM acl_entidade_perfil WHERE perfil_id = ? AND entidade_id = ? ',[$request->id, $entidade->id]);
            $resp = DB::select('SELECT COUNT(*) AS qtd FROM acl_autorizacaos WHERE perfil_id = ? AND entidade_id = ? ',[$request->id, $entidade->id]);
            // print_r($resp);
            $Entidades[$key]['concedido'] = $resp[0]->qtd > 0 ? 'checked' : '';
        }

        return Response()->json($Entidades);

        // $Entidades = Perfil::with('entidades')->where('id',$request->id)->get();
        // $Entidades = Perfil::where('id',$request->id)->get();
        // $Entidades = Perfil::with('entidadesAutorizadas')->where('id',$request->id)->get();
        // dd($Entidades);
        // print_r($EntidadeInseridas);

        // $pgrad = DB::table('pgrads')->where('sigla', $DGPUser->pgrad_sigla)->first();
        // $sql = "SELECT * FROM acl_entidade_perfil";

        // $sql = "
        //     SELECT 
        //           acl_entidade_perfil.id
        //         , acl_entidades.model
        //         , acl_entidades.descricao
        //         , acl_entidades.ativo
        //         , acl_entidades.id AS entidade_id
        //         , acl_entidade_perfil.perfil_id
        //         , CASE WHEN	acl_entidade_perfil.perfil_id = ? THEN 'checked' ELSE '' END AS concedido
        //     FROM acl_entidades
        //     LEFT JOIN acl_entidade_perfil ON acl_entidades.id = acl_entidade_perfil.entidade_id
        // ";
        // $Entidades = DB::select($sql,[$request->id]);
        // $Entidades = Perfil::all();
        // dump($Entidades);

        // $sql = "
        //     SELECT * FROM acl_entidades
        // ";
        // $Entidades = DB::select($sql,[$request->id]);
    }    


    public function show(Request $request)
    {        

        $sql = "
            SELECT acl_autorizacaos.*, acl_rotas.rota, acl_rotas.descricao
            FROM acl_autorizacaos 
                INNER JOIN acl_rotas ON acl_rotas.id = acl_autorizacaos.rota_id
            WHERE   acl_autorizacaos.perfil_id = ?
                AND acl_autorizacaos.entidade_id = ?
        ";

        // $Entidade = Entidade::with('permissaos')->where('id',$request->id)->first();
        // $Entidade = Entidade::with('autorizacoes')->where('id',$request->id)->first();
        $Entidade = Entidade::where('id',$request->id)->first();
        $Autorizacoes = DB::select($sql,[$request->perfil_id, $request->id]);
        $Entidade['autorizacoes'] = $Autorizacoes;

        return Response()->json($Entidade);
    }    
    
    // protected function getEntidades($perfil_id) {

    //     $entidades = Entidade::where('id',$perfil_id)->get();

    //     $entidadesLabels  = '';
    //     foreach($entidades as $entidade) {
    //         $entidadesLabels .= '<button class="btn btn-success btn-xs btnPerfil" data-perfil-id="' . $entidade->id . '" data-toggle="tooltip" title="Ver os detalhes deste Perfil de Acesso">' . $entidade->id . ' ' . $entidade->nome . '</button> ';
    //     }

    //     return $entidadesLabels;
    // }
    
}


