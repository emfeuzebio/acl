<?php

namespace App\Providers;

use App\Models\Autorizacao;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    // public function boot()
    // {
    //     // $this->registerPolicies();

    //     // Aqui você pode definir gates ou outras configurações relacionadas a JWT, se necessário.
    //     // O Tymon JWT-Auth não exige muitas alterações aqui, mas você pode adicionar qualquer lógica personalizada.

    //     // Se você precisar usar JWT diretamente, pode usar o JWTAuth
    //     // Exemplo: 
    //     // JWTAuth::parseToken()->authenticate();

    //     // Caso precise configurar algo com o JWT diretamente, pode ser feito aqui
    // }




    /**
     * USAR ESTE PARA O ALC APP
     */
    public function boot(): void
    {
        
        /**
         * NOTA:    não é recomendado recuperar o Auth::User() aqui no AuthServiceProvider
         *          por isso, abaixo pegamos todas as Autorizações Ativas e depois validamos 
         *          se o User Atual têm acesso à elas conforme seus Perfis
         */

        // trás uma coleção de Autorizações Ativas (Ativo = 'SIM') junto com a respectiva Rota
        $autorizacaos = Autorizacao::where('ativo','=','SIM')->with('perfil')->get();

        // percorre as Autorizações do Usuário logado e cria um Gate dinâmico para cada Rota que fará o controle de autorizações via middleware
        foreach($autorizacaos as $cont => $autorizacao) {
            Gate::define($autorizacao->rota->rota, function (User $user) use ($autorizacao) {

                // verifica se dentre os Perfis do Usuário Logado, ele têm autorização nesta Rota
                $hasAutorizacao = $user->hasAutorizacao($autorizacao);
                // dd($hasAutorizacao);

                // tendo autorização, cria o Gate que Autoriza a Rota 
                return $hasAutorizacao;
            });        
        }

        // para lista todos os Gates criados
        // echo 'Quantidade de Rotas Autorizadas: ' . $cont;
        // dd(Gate::abilities());

    }
}
