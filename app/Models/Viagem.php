<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Viagem extends Model
{
    use HasFactory;

    protected $table = 'eve_viagens';

    // Viagem é FILHO de Veiculo. Relacionamento "muitos para um": "Um Veículo pertence a muitas Viagens"
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }    

    // Viagem é FILHO de Trajeto. Relacionamento "muitos para um": "Um Trajeto pertence a muitas Viagens"
    public function trajeto()
    {
        return $this->belongsTo(Trajeto::class);
    }    
}
