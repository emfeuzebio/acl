<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    use HasFactory;

    protected $table = 'acl_permissaos';
    public $timestamps = false;                              //desabilita colunas timestamps                    
    protected $fillable = ['id','entidade_id','rota','descricao','ativo'];       //colunas  

    // public function permissaos() {
    //     return $this->belongsToMany(permissaos::class,'acl_perfil_permissaos');
    // }

    // public function entidade() {
    //     return $this->hasMany(Entidade::class);
    // }    

}