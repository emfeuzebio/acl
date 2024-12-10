<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trajeto extends Model
{
    use HasFactory;

    protected $table = 'eve_trajetos';
    protected $fillable = [
            'evento_id',
            'nome', 
            'tipo',
            'origem',
            'destino',
        ];

    // relacionamento One to Many - nome do relacionamneto: caixa baixa no plural
    // Trajeto é PAI de Viagens. "um para muitos": "Um Trajeto pode ter muitas Viagens"
    public function viagens()
    {
        return $this->hasMany(Viagem::class);
    }       
}
