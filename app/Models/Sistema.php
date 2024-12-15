<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    use HasFactory;

    // public $timestamps = false;                                  
    protected $table = 'acl_sistemas';
    protected $fillable = [
            'id', 
            'organizacao_id', 
            'nome',
            'sigla',
            'descricao', 
            'ativo',
        ];

    // Sistema é FILHO de Organizacao. 
    // Relacionamento "muitos para um": "Um Sistema pertence a uma Organizcao"
    public function organizacao()
    {
        return $this->belongsTo(Organizacao::class);
    }    

    // relacionamento One to Many - nome do relacionamneto: caixa baixa no plural
    // Organizacao é PAI de várias Entidades [Sistema,] . "um para muitos": "Uma Organização pode ter muitos(as) ..."
    // public function sistemas()
    // {
    //     return $this->hasMany(Viagem::class);
    // }        
}
