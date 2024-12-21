<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizacaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */ 
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "nome" => $this->nome,
            "sigla" => $this->sigla,
            "descricao" => $this->descricao,
            "ativo" => $this->ativo,
            'sistemas' => $this->sistemas,              // inclui o relacionamento 'sistemas'
            "criado_em" => $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : null,
            "atualizado_em" => $this->updated_at ? $this->updated_at->format('d/m/Y H:i:s') : null,
            // "atualizado_em" => $this->updated_at ? Carbon::parse($this->updated_at)->format('d/m/Y H:i:s') : null,
            // 'sistemas' => SistemaResource::collection($this->whenLoaded('sistemas')), // Aplica um recurso para a relação 'sistemas'
            // "DT_RowId" => $this->id,
            // "created_at" => $this->created_at,
            // "updated_at" => $this->updated_at,
            // 'routes' => $this->getAuthorizedRoutes(),
            // 'autorizacoes' => $this->getAuthorizedActions(), // ATENCAO MUITO LENTO, para cada linha um getAuthorizedActions() que faz 45 consultas ao BD
        ];
    }

    /**
     * recupera um array com todas abilities autorizadas do Usuário logado
     */
    // protected function getAuthorizedActions()
    // {
    //     $authorizedActions = [];
    //     $permissions = [];

    //     // retorna um array de todas as habilidades registradas no sistema, ou seja, 
    //     // as permissões que foram definidas com o método Gate::define()
    //     $abilities = Gate::abilities();

    //     // extrai no array apenas o nome das abilities 
    //     foreach ($abilities as $ability => $callback) {
    //         $permissions[] = $ability;
    //     }        

    //     // cria um array com apenas as abilities autorizadas
    //     foreach ($permissions as $permission) {
    //         if (Gate::allows($permission, $this)) {
    //             $authorizedActions[] = $permission;
    //         }
    //     }

    //     // de todas abilities autorizadas, extrair aqui apenas as que correspondem a Entidade
    //     // FAZER
    //     // dÁ PARA SIMPLIFICAR todos o código ao percorrer as abilities já filtar as autorizadas e as que correspondem a Entidade


    //     return $authorizedActions;
    // }    
}
