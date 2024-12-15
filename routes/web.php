<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AutorizacaoController;
use App\Http\Controllers\EntidadeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JwtController;
use App\Http\Controllers\OrganizacaoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RotaController;
use App\Http\Controllers\SistemaController;
use App\Http\Controllers\UserController;
use App\Http\Requests\OrganizacaoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

    /**
     * NOTA: 1. funcionamento: No AuthServiceProvider são percorridas todas as Autorizações do Usuário logado 
     *       e é criado um Gate dinâmico para cada Rota que será usada no controle de autorizações 
     *       via middleware logo abaixo.
     * 
     *       2. Decomente abaixo para ver todas autorizações do User logado
     *          dd(FacadesGate::abilities());
     */
    // dd(FacadesGate::abilities());



    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

        // Auth::logout();
        // Auth::loginUsingId(1);
        // Auth::login($user);
        // Auth::check();

        // dd(auth()->id());
        // dd(Auth::id());

    /**
     * todas as rotas da aplicação são acessíveis somente após o Usuário estar autenticado
     */

    // middleware('auth') emprega o autenticador 'guard' padrão 
    //Route::middleware('auth')->group(function () {

    // força que o guard 'web' seja empregada na autenticação das rotas listadas dentro do grupo do middleware
    Route::middleware('web')->group(function () {

        // dd(Auth::user()->id);
        // dd(Auth::id());

        // Rotas que não necessitam de autorização
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        // O middleware('AccessControlList') controla as autorizações para todas as rotas da aplicação
        // as autorizações estão gravadas em tabelas Perfils, Permissaos, Entidades relacionadas ao Usuário logado
        Route::middleware('AccessControlList')->group(function () {

            // Gestão de Usuários
            Route::controller(UserController::class)->group(function () {
                Route::get('/user', 'index')->name('user.index');
                Route::get('user/show', 'show')->name('user.show');
                // Route::post('user/edit', 'edit')->name('user.edit');
                Route::post('user/store', 'store')->name('user.store');
                Route::post('user/destroy', 'destroy')->name('user.destroy');
                Route::post('user/listarPerfis', 'listarPerfis')->name('user.listarPerfis');
                Route::post('user/concederPerfil', 'concederPerfil')->name('user.concederPerfil');
            });        

            // Perfis de Acesso dos Usuários
            Route::controller(PerfilController::class)->group(function () {
                Route::get('/perfil', 'index')->name('perfil.index');
                Route::get('/perfil/{user_id?}','show')->name('perfil.show');
                // Route::post('perfil/edit', 'edit')->name('perfil.edit');
                Route::post('perfil/store', 'store')->name('perfil.store');
                Route::post('perfil/destroy', 'destroy')->name('perfil.destroy');
                Route::post('perfil/concederEntidade', 'concederEntidade')->name('perfil.concederEntidade');
            });        
    
            // Entidades (Tabelas) da aplicação
            Route::controller(EntidadeController::class)->group(function () {
                Route::get('/entidade', 'index')->name('entidade.index');
                Route::get('entidade/show', 'show')->name('entidade.show');
                // Route::post('entidade/edit', 'edit')->name('entidade.edit');
                Route::post('entidade/store', 'store')->name('entidade.store');
                Route::post('entidade/destroy', 'destroy')->name('entidade.destroy');
                Route::get('entidade/rotas', 'rotas')->name('entidade.rotas');
                Route::get('entidade/list', 'list')->name('entidade.list');
            });        

            // Rotas de cada Entidade da Aplicação
            Route::controller(RotaController::class)->group(function () {
                Route::get('/rota', 'index')->name('rota.index');
                Route::post('rota/store', 'store')->name('rota.store');
                Route::post('rota/destroy', 'destroy')->name('rota.destroy');
            });        
    
            // Permissões (Autorizações) de Acesso às Entidades (Tabelas) da Aplicação
            Route::controller(AutorizacaoController::class)->group(function () {
                Route::get('/autorizacao', 'index')->name('autorizacao.index');
                Route::get('/autorizacao/{user_id?}','show')->name('autorizacao.show');
                Route::post('autorizacao/destroy', 'destroy')->name('autorizacao.destroy');
                Route::post('autorizacao/store', 'store')->name('autorizacao.store');
                Route::post('autorizacao/authorizar', 'authorizar')->name('autorizacao.authorizar');
            });       

            // SPA Permissões (Autorizações) de Acesso na Entidade: Sistema (tabela acl_sistemas)
            Route::controller(SistemaController::class)->group(function () {
                Route::get('/sistema',          'index')->name('sistema.index');
                Route::get('/sistema/{id?}',    'show')->name('sistema.show');
                Route::post('sistema/store',    'store')->name('sistema.store');
                Route::post('sistema/update',   'edit')->name('sistema.update');                
                Route::post('sistema/destroy',  'destroy')->name('sistema.destroy');
            });              
    
            // API bastaria este mas não funciona. 
            // Acesso a Entidade Organização (tabela acl_organizacaos) via API com token
            // Route::get('/organizacao', function () {
            //     view('acl/OrganizacaoDatatableApiAxios');
            // })->name('organizacao.index');

            // API Acesso a Entidade Organização (tabela acl_organizacaos) via API com token
            // como as operacoes e as autorizacoes ocorrem na API da URL basta o index para a view blade
            Route::get('/organizacao', [OrganizacaoController::class, 'index'])->name('organizacao.index');
    
        });

    });     