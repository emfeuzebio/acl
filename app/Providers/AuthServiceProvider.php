<?php

namespace App\Providers;

use App\Models\Autorizacao;
use App\Models\Rota;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;

use function PHPUnit\Framework\callback;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * USAR ESTE PARA O ALC APP
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * NOTA:    Auth::User() não está disponível aqui. O User ainda não foi autentidado
         *          Não é recomendado recuperar o Auth::User() aqui no AuthServiceProvider
         *          por isso, abaixo pegamos todas as Autorizações Ativas e depois validamos 
         *          se o User Atual têm acesso à elas conforme seus Perfis
         */
        // recupera uma lista de Rotas cujas Autorizações estão Ativas (Ativo = 'SIM')
        // observe que a mesma Rota pode repetir pois cada Perfil pode ter a mesma Rota
        $sql = "
            SELECT 
                  acl_rotas.rota AS name
                , acl_autorizacaos.perfil_id AS role_id
            FROM acl_rotas
                INNER JOIN acl_autorizacaos ON acl_autorizacaos.rota_id = acl_rotas.id
            WHERE acl_autorizacaos.ativo = 'SIM'
            ORDER BY acl_rotas.rota    
        ";
        $abilities = collect(DB::select($sql));     // transforma numa coleção chamada abilities

        // agrupa os registros da Coleção pelo nome da Rota, isso faz com que a coluna role_id passe a ter uma array de itens
        $groupedAbilities = $abilities->groupBy('name');

        // Iterar sobre o grupo de abilities
        foreach ($groupedAbilities as $name => $group) {
            Gate::define($name, function ($user) use ($group) {

                // Para cada grupo de abilities com o mesmo nome, obter todos os role_id's
                $roleIds = $group->pluck('role_id')->toArray();

                // tendo o User pelo menos um de seus Perfis (Roles) entre a lista de $roleIds desta ability, cria o Gate que Autoriza a Rota 
                return $user->hasAnyPerfis($roleIds);                
            });
        }        
        // EUZ ver as abilities carregadas
        // $abilities = Gate::abilities();
        // dd($abilities);

    }
}
