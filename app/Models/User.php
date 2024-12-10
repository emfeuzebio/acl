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
        return $this->hasAnyPerfis($autorizacao->perfil); 
    }

    protected function hasAnyPerfis(Perfil $perfil) {

        // dd($perfil);
        // $userId = Auth::user()->id;
        // dd($userId);

        $sql = "
            SELECT COUNT(*) AS tem_perfil 
            FROM acl_perfil_user
            WHERE acl_perfil_user.user_id = ?
            AND perfil_id = ?    
        ";
        $results = DB::select($sql,[Auth::user()->id, $perfil->id]);
        // dd($results[0]->tem_perfil);

        return ((bool) $results[0]->tem_perfil);

        // if(is_array($perfis) || is_object($perfis) ) {
        //     foreach($perfis as $perfil) {
        //         return !! $perfil-Lintersect($this->perfil-count());
        //     }

        // }
        // return $this->$perfis->contains('rota',$perfis);
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
