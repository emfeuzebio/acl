<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizacao extends Model
{
    use HasFactory;

    // public $timestamps = false;                                  
    protected $table = 'acl_organizacaos';
    protected $fillable = [
            'id', 
            'nome',
            'sigla',
            'descricao', 
            'ativo',
        ];

    // relacionamento One to Many - nome do relacionamneto: caixa baixa no plural
    // Organizacao é PAI de várias Entidades [Sistema,] . "um para muitos": "Uma Organização pode ter muitos(as) ..."
    public function sistemas()
    {
        return $this->hasMany(Sistema::class);
    }        
}
