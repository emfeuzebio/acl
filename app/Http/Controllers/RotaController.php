<?php

namespace App\Http\Controllers;

use App\Http\Requests\RotaRequest;
use App\Models\Autorizacao;
use App\Models\Rota;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response as HttpResponse;

class RotaController extends Controller
{
    public function index() {

    }

    public function store(RotaRequest $request)
    {

        $exception = DB::transaction(function() use ($request) {
            try {

                // PASSO 1 - vamos inserir ou Salvar a Rota
                $Rota = Rota::updateOrCreate(
                    [
                        'id' => $request->id,
                    ],
                    [
                        'entidade_id' => $request->entidade_id,
                        'rota' => trim(strtolower($request->rota)),
                        'descricao' => trim($request->descricao),
                    ]
                );  

                // PASSO 2 - Sendo um INSERT, vamos inserir a respectiva Rota no Rol de Autorizações das Entidades
                if ($Rota->wasRecentlyCreated) {

                    //EUZ - Aqui, após a insersão da Rota na Entidade, 
                    // há que replicar a mesma para TODAS Entidades já concedidas aos Perfis de Acesso Autorizações
                    $sql = "
                        SELECT DISTINCT acl_autorizacaos.perfil_id, acl_autorizacaos.entidade_id
                        FROM acl_autorizacaos
                        WHERE entidade_id = ?
                    ";
                    $entidades = DB::select($sql,[$request->entidade_id]);

                    foreach($entidades as $entidade) {
                        $result = Autorizacao::create([
                            'perfil_id' => $entidade->perfil_id,
                            'entidade_id' => $request->entidade_id,
                            'rota_id' => $Rota->id,
                            'ativo' => 'SIM',               // padrão é Autorizado SIM
                        ]);  
                    }
                }

            }
            catch(Exception $e) {
                throw new \Exception('EUZ-ENTIDADE-Exception:' . $e);
                // return $e;
            }
            return $Rota;

        });                

        return Response()->json(is_null($exception) ? 'Tudo Certo' : $exception);
    }  

    public function destroy(Request $request)
    {        
        try {

            $sql = "
                SELECT COUNT(*) AS qtd_rotas_autorizadas 
                FROM acl_autorizacaos 
                WHERE rota_id = ?
                AND ativo = 'SIM'
            ";        
            $qtdRotasAutorizadas  = DB::select($sql,[$request->id]);
            // dd($qtdRotasAutorizadas[0]->qtd_rotas_autorizadas);

            // NÃO tendo nenhuma Autorização 'SIM' na Rota, permite a exclusão dela
            if($qtdRotasAutorizadas[0]->qtd_rotas_autorizadas == 0) {
                $Rota = Rota::where('id',$request->id)->delete();
            } else {
                return Response()->json(['message'=>'Exception: Impossível excluir! Esta ROTA possui Autorizações ativas (SIM)'], HttpResponse::HTTP_UNPROCESSABLE_ENTITY); //422
            }

        } catch(Exception $e) {
            throw new \Exception('EUZ-ROTA-Exception:' . $e);
        }
        return Response()->json($Rota);
    }       

}


