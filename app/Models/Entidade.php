<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidade extends Model
{
    use HasFactory;

    protected $table = 'acl_entidades';
    public $timestamps = false;                                  
    protected $fillable = ['id','model','tabela','descricao','ativo'];

    // relacionamento One to Many - Uma Entidade pode ter muitas Rotas - nome do relacionamneto: caixa baixa no plural
    public function rotas() {
        return $this->hasMany(Rota::class);
    }    

    // ANTIGO MODELO - Desativar
    public function permissaos() {
        return $this->hasMany(Permissao::class);
    }    


    // public function autorizacoes() {
    //     return $this->hasMany(Autorizacao::class);
    // }    

    // relacionamento Many to Many - Muitos Entidades podem ter muitas Perfis - nome do relacionamneto: caixa baixa no plural
    // public function perfis() {
    //     return $this->belongsToMany(Perfil::class,'acl_autorizacaos');
    // }    

}
