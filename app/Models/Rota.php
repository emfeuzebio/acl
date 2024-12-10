<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rota extends Model
{
    use HasFactory;

    protected $table = 'acl_rotas';
    public $timestamps = false;
    protected $fillable = ['id','entidade_id','rota','descricao'];

    // relacionamento Many to One - Muitas Rotas podem pertencer a uma Entidade - nome do relacionamento: caixa baixa no singular
    // trás todos os Perfis autorizados = 'SIM' na Rota  
    public function perfis() {
        return $this->belongsToMany(Perfil::class,'acl_autorizacaos')->where('acl_autorizacaos.ativo','=','SIM');
    }

    public function autorizacoes() {
        return $this->hasMany(Autorizacao::class);
    }    


    // public function autorizacoes() {
    //     return $this->belongsToMany(Autorizacao::class,'acl_autorizacaos');
    // }

    // relacionamento Many to One - Muitas Rotas podem pertencer a uma Entidade - nome do relacionamento: caixa baixa no singular
    // public function entidade() {
    //     return $this->belongsTo(Entidade::class);
    //         // ->where('ativo','=','SIM')
    // }    



    // public function permissaos() {
    //     return $this->belongsToMany(Permissao::class,'acl_perfil_permissao');
    // }

}
