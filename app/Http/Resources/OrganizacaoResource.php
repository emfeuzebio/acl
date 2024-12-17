<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            "DT_RowId" => $this->id,
            // "created_at" => $this->created_at,
            // "updated_at" => $this->updated_at,
            "criado_em" => $this->created_at ? $this->created_at->format('d/m/Y H:i:s') : null,
            "atualizado_em" => $this->updated_at ? $this->updated_at->format('d/m/Y H:i:s') : null,
            // "atualizado_em" => $this->updated_at ? Carbon::parse($this->updated_at)->format('d/m/Y H:i:s') : null,
            'sistemas' => $this->sistemas,
            // 'sistemas' => SistemaResource::collection($this->whenLoaded('sistemas')), // Aplica um recurso para a relação 'sistemas'
        ];
    }
}
