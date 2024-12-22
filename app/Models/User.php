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

    public $Roles = [];

    public function __construct() {
    }    

    public function index() {
    }

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
    public function hasAnyPerfis($roleIds) {

        // vamos carregar os Perfis (Roles) do User a partir do relacionamento (perfis)
        $this->Roles = $this->getRoles();

        // echo '<pre>Verifica se alguns dos Perfis da Rota: <br/>';
        // print_r($roleIds);
        // echo 'Estão na lista de Perfis do User: <br/>';
        // print_r(Auth::user()->Roles);
        // echo '</pre>';
        // die();

        // Verifica se pelo menos um elemento de $roleIds está presente em $roles
        if (count(array_intersect(Auth::user()->Roles, $roleIds)) > 0) {
            return true;
        } 
    }

    protected function getRoles()
    {
        return $this->perfis->pluck('id')->toArray();                     // Pluck os id das roles (perfis)
    }    

    protected function hasAnyPerfisOLD(Perfil $perfil) {

        $sql = "
            SELECT EXISTS (
                SELECT 1
                FROM acl_perfil_user
                WHERE acl_perfil_user.user_id = ?
                AND acl_perfil_user.perfil_id = ?
            ) AS tem_perfil
        ";
        // echo $results;
        $results = DB::selectOne($sql,[Auth::user()->id, $perfil->id]); // retorna uma row() objeto

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

}
