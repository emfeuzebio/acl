<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'acl_perfils';                            
    public $timestamps = false;                                  
    protected $fillable = ['id','nome','descricao','ativo'];     

    // relacionamento Many to Many - Muitas Rotas podem ter muitos Perfis (autorizados) - nome do relacionamneto: caixa baixa no plural
    public function rotas() {
        return $this->belongsToMany(Rota::class,'acl_autorizacaos');
    }

    public function entidades() {
        return $this->hasMany(Autorizacao::class);
    }       




    // public function entidades() {
    //     return $this->hasMany(Autorizacao::class,'acl_entidade_perfil');
    //     // return $this->belongsToMany(Autorizacao::class,'acl_autorizacaos');
    // }


    // public function permissaos() {
    //     return $this->belongsToMany(Permissao::class,'acl_perfil_permissao');
    // }

    // public function entidades() {
    //     return $this->belongsToMany(Entidade::class,'acl_entidade_perfil');
    //     // return $this->belongsToMany(Autorizacao::class,'acl_autorizacaos');
    // }


}
