<?php

namespace App\Providers;

use App\Models\Autorizacao;
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

        // trás uma coleção de Autorizações Ativas (Ativo = 'SIM') junto com a respectiva Rota
        $autorizacaos = Autorizacao::where('ativo','=','SIM')->with('perfil')->get();
        // dd($autorizacaos);

        // percorre as Autorizações do Usuário logado e cria um Gate dinâmico para cada Rota que fará o controle de autorizações via middleware
        foreach($autorizacaos as $cont => $autorizacao) {
            Gate::define($autorizacao->rota->rota, function (User $user) use ($autorizacao) {
                // dd($autorizacao);
                
                // verifica se dentre os Perfis do Usuário Logado, ele têm autorização nesta Rota
                $hasAutorizacao = $user->hasAutorizacao($autorizacao);

                // tendo autorização, cria o Gate que Autoriza a Rota 
                return $hasAutorizacao;
            });        
        }
        // EUZ ver as abilities carregadas
        // $abilities = Gate::abilities();
        // // dd($abilities);

        // foreach ($abilities as $ability => $callback) {
        //     $rotasAutorizadas[] = $ability;
        // }        
        // print_r($rotasAutorizadas);
        // die();

    }
}
