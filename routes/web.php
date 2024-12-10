<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AutorizacaoController;
use App\Http\Controllers\EntidadeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JwtController;
use App\Http\Controllers\OrganizacaoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RotaController;
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
                Route::post('user/edit', 'edit')->name('user.edit');
                Route::post('user/store', 'store')->name('user.store');
                Route::post('user/destroy', 'destroy')->name('user.destroy');
                Route::post('user/listarPerfis', 'listarPerfis')->name('user.listarPerfis');
                Route::post('user/concederPerfil', 'concederPerfil')->name('user.concederPerfil');
            });        

            // Perfis de Acesso dos Usuários
            Route::controller(PerfilController::class)->group(function () {
                Route::get('/perfil', 'index')->name('perfil.index');
                Route::get('/perfil/{user_id?}','show')->name('perfil.show');
                Route::post('perfil/edit', 'edit')->name('perfil.edit');
                Route::post('perfil/store', 'store')->name('perfil.store');
                Route::post('perfil/destroy', 'destroy')->name('perfil.destroy');
                Route::post('perfil/concederEntidade', 'concederEntidade')->name('perfil.concederEntidade');
            });        
    
            // Entidades (Tabelas) da aplicação
            Route::controller(EntidadeController::class)->group(function () {
                Route::get('/entidade', 'index')->name('entidade.index');
                Route::post('entidade/edit', 'edit')->name('entidade.edit');
                Route::post('entidade/store', 'store')->name('entidade.store');
                Route::post('entidade/destroy', 'destroy')->name('entidade.destroy');
                Route::post('entidade/rotas', 'rotas')->name('entidade.rotas');
                Route::post('entidade/show', 'show')->name('entidade.show');
                Route::post('entidade/list', 'list')->name('entidade.list');
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
    
            // Permissões (Autorizações) de Acesso na Entidade: Organização (tabela acl_organizacaos) 
            Route::controller(OrganizacaoController::class)->group(function () {
                Route::get('/organizacao', 'index')->name('organizacao.index');
                Route::get('/organizacao/{organizacao_id?}','show')->name('organizacao.show');
                Route::post('organizacao/edit', 'edit')->name('organizacao.edit');                
                Route::post('organizacao/destroy', 'destroy')->name('organizacao.destroy');
                Route::post('organizacao/store', 'store')->name('organizacao.store');
            });        
    
        });

    });     