<?php

use App\Http\Controllers\AutorizacaoController;
use App\Http\Controllers\EntidadeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizacaoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RotaController;
use App\Http\Controllers\SistemaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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
     *       2. Descomente abaixo para ver todas autorizações do User logado
     *          dd(FacadesGate::abilities());
     */
    // dd(FacadesGate::abilities());

    // Auth::logout();
    // Auth::loginUsingId(1);
    // Auth::login($user);
    // Auth::check();
    // dd(auth()->id());
    // dd(Auth::id());

    /**
     * todas as rotas da aplicação são acessíveis somente após o Usuário estar autenticado
     */

    // força que o guard 'web' seja empregada na autenticação das rotas listadas dentro do grupo do middleware
    Route::middleware('web')->group(function () {

        // Rotas que não necessitam de autorização
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        // O middleware('AccessControlList') controla as autorizações para todas as rotas da aplicação
        // as autorizações estão gravadas em tabelas Perfils, Autorizacaos, Entidades relacionadas ao Usuário logado
        Route::middleware(['auth', 'AccessControlList'])->group(function () {

            // Gestão de Usuários
            Route::controller(UserController::class)->group(function () {
                Route::get( 'user',             'index')->name('user.index');
                Route::get( 'user/show',        'show')->name('user.show');
                Route::post('user/store',       'store')->name('user.store');
                Route::post('user/destroy',     'destroy')->name('user.destroy');
                Route::post('user/listarPerfis', 'listarPerfis')->name('user.listarPerfis');
                Route::post('user/concederPerfil', 'concederPerfil')->name('user.concederPerfil');
            });        

            // Gestão de Perfis de Acesso dos Usuários
            Route::controller(PerfilController::class)->group(function () {
                Route::get( 'perfil',           'index')->name('perfil.index');
                Route::get( 'perfil/{user_id?}','show')->name('perfil.show');
                Route::post('perfil/store',     'store')->name('perfil.store');
                Route::post('perfil/destroy',   'destroy')->name('perfil.destroy');
                Route::post('perfil/concederEntidade', 'concederEntidade')->name('perfil.concederEntidade');
            });        
    
            // Gestão de Entidades (Tabelas) da aplicação
            Route::controller(EntidadeController::class)->group(function () {
                Route::get( 'entidade',         'index')->name('entidade.index');
                Route::get( 'entidade/show',    'show')->name('entidade.show');
                Route::post('entidade/store',   'store')->name('entidade.store');
                Route::post('entidade/destroy', 'destroy')->name('entidade.destroy');
                Route::get( 'entidade/rotas',   'rotas')->name('entidade.rotas');
                Route::get( 'entidade/list',    'list')->name('entidade.list');
            });        

            // Gestão de Rotas de cada Entidade da Aplicação
            Route::controller(RotaController::class)->group(function () {
                Route::get( 'rota',         'index')->name('rota.index');
                Route::post('rota/store',   'store')->name('rota.store');
                Route::post('rota/destroy', 'destroy')->name('rota.destroy');
            });        
    
            // Gestão de Autorizações de Acesso às Entidades (Tabelas) da Aplicação
            Route::controller(AutorizacaoController::class)->group(function () {
                Route::get( 'autorizacao', 'index')->name('autorizacao.index');
                Route::get( 'autorizacao/{user_id?}','show')->name('autorizacao.show');
                Route::post('autorizacao/destroy', 'destroy')->name('autorizacao.destroy');
                Route::post('autorizacao/store', 'store')->name('autorizacao.store');
                Route::post('autorizacao/authorizar', 'authorizar')->name('autorizacao.authorizar');
            });       

            // SPA Permissões (Autorizações) de Acesso na Entidade: Sistema (tabela acl_sistemas)
            Route::controller(SistemaController::class)->group(function () {
                Route::get( 'sistema',          'index')->name('sistema.index');
                Route::get( 'sistema/{id?}',    'show')->name('sistema.show');
                Route::post('sistema/store',    'store')->name('sistema.store');
                Route::post('sistema/update',   'edit')->name('sistema.update');                
                Route::post('sistema/destroy',  'destroy')->name('sistema.destroy');
            });              
    
            // SPA Permissões (Autorizações) de Acesso na Entidade: Organização (tabela acl_organizacaos)
            Route::controller(OrganizacaoController::class)->group(function () {
                Route::get( 'organizacao',          'index')->name('organizacao.index');
                Route::get( 'organizacao/{id?}',    'show')->name('organizacao.show');
                Route::post('organizacao/store',    'store')->name('organizacao.store');        // insert no registro
                Route::post('organizacao/update',   'update')->name('organizacao.update');      // update no registro        
                Route::post('organizacao/destroy',  'destroy')->name('organizacao.destroy');
            });              

            // API Acesso a Entidade Organização (tabela acl_organizacaos) via API com token
            // como as operacoes e as autorizacoes ocorrem na API da URL basta o index para a view blade
            Route::get('/organizacao', [OrganizacaoController::class, 'index'])->name('organizacao.index');
    
        });

    });     