<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autorizacao extends Model
{
    use HasFactory;

    protected $table = 'acl_autorizacaos';
    public $timestamps = false;
    protected $fillable = ['id','perfil_id','entidade_id','rota_id','ativo'];

    public function rotas() {
        return $this->belongsToMany(Rota::class,'acl_rotas');
    }

    public function rota() {
        return $this->belongsTo(Rota::class);
    }    

    public function perfil() {
        return $this->belongsTo(Perfil::class);
    }    


    // public function acao() {
    //     return $this->hasOne(Rota::class);
    // }    

    // public function rotas() {
    //     return $this->hasMany(Rota::class);
    // }    

    // public function perfis() {
    //     return $this->hasMany(Perfil::class);
    // }    

    // public function entidade() {
    //     return $this->hasOne(Entidade::class);
    // }    
   
    // public function permissaos() {
    //     return $this->belongsToMany(Permissao::class,'acl_perfil_permissao');
    // }

    // public function permissaos() {
    //     return $this->hasMany(Permissao::class);
    // }    

    // public function permissaos() {
    //     return $this->belongsToMany(Permissao::class,'acl_perfil_permissao');
    // }

}
