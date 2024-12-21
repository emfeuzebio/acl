<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

// class User extends Authenticatable
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    public $Roles = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function perfis() {
        return $this->belongsToMany(Perfil::class,'acl_perfil_user');
    }

    public function temPerfis() {
        return $this->hasMany(Perfil::class);
    }

    /**
     * Recebe a Autorização com a Rota
     * Retorna TRUE se dentre os Perfis do Usuário Logado, ele têm autorização nesta Rota
     */
    public function hasAutorizacao(Autorizacao $autorizacao) {

        // vamos carregar os Perfis (Roles) do User a partir do relacionamento (perfis)
        $this->Roles = $this->getRoles();
        // dd($this->Roles);

        // return in_array($autorizacao->perfil->id, $this->Roles);       // Ok
        // return in_array($autorizacao->perfil->id, $this->getRoles());  // Ok
        return in_array($autorizacao->perfil->id, Auth::user()->Roles);   // Ok

        // return $this->hasAnyPerfis($autorizacao->perfil);              // ANTERIOR pesquisava no BD 
    }

    protected function getRoles()
    {
        return $this->perfis->pluck('id')->toArray();                     // Pluck os id das roles (perfis)
    }    

    protected function hasAnyPerfis(Perfil $perfil) {

        // echo 'User(' . Auth::user()->id . ') Perfil (' . $perfil->id . ')';
        // dd($perfil);
        // $userId = Auth::user()->id;
        // dd($userId);

        $sql = "
            SELECT EXISTS (
                SELECT 1
                FROM acl_perfil_user
                WHERE acl_perfil_user.user_id = ?
                AND acl_perfil_user.perfil_id = ?
            ) AS tem_perfil
        ";
        // $results = DB::select($sql,[Auth::user()->id, $perfil->id]);
        // echo $results;
        // dd($results[0]->tem_perfil);
        $results = DB::selectOne($sql,[Auth::user()->id, $perfil->id]); // retorna objeto row()

        // return ((bool) $results[0]->tem_perfil);
        return ((bool) $results->tem_perfil);   // retorna boolean
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // public function getAutorizacoes()
    // {

    //     $sql = "
    //         SELECT
    //                 acl_perfil_user.user_id
    //             , acl_perfil_user.perfil_id
    //             , acl_rotas.rota
    //         FROM acl_perfil_user
    //             INNER JOIN acl_autorizacaos ON acl_autorizacaos.perfil_id = acl_perfil_user.perfil_id
    //             INNER JOIN acl_rotas        ON acl_rotas.id = acl_autorizacaos.rota_id
    //         WHERE acl_perfil_user.user_id = ?
    //             AND acl_autorizacaos.ativo = 'SIM'
    //         ORDER BY 
    //                 acl_perfil_user.user_id
    //             , acl_perfil_user.perfil_id
    //             , acl_rotas.rota
    //     ";
    //     $autorizacaosDoUser = DB::select($sql, [Auth::user()->id]);
    //     print_r($autorizacaosDoUser);
    //     // die();

    //     return $autorizacaosDoUser;
    // }


}
