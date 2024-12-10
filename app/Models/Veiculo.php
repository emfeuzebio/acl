<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    use HasFactory;

    /**
    * Assim, sem nenhuma linha de configuração o Laravel lida com todas as colunas e linhas da tabela com o mesmo nome do Model
    */

    // public $timestamps = false;                                  
    protected $table = 'eve_veiculos';
    protected $fillable = [
            // 'id',
            'descricao', 
            'tipo',
            'marca_modelo',
            'capacidade',
            'motorista',
            'telefone',
            'observacao',
            'ativo',
        ];

    // relacionamento One to Many - nome do relacionamneto: caixa baixa no plural
    // Veiculo é PAI de Viagens. "um para muitos": "Um Veiculo pode ter muitas Viagens"
    public function viagens()
    {
        return $this->hasMany(Viagem::class);
    }    

}
