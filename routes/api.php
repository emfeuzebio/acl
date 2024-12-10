<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\JwtController;
use App\Http\Controllers\OrganizacaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VeiculoController;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Gate as AccessGate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Basta isto abaixo para todas as rotas da API VeiculoController estarem prontas
 * O apiResource() habilitadas as 5 rotas: GET, POST, PUT, PATCH e DELETE
 * Para vê-las no terminal: php artisan route:list
 * 
 * FUNCIONANDO Ok
 * 
 */ 
Route::apiResource('/veiculo', VeiculoController::class);

// FUNCIONANDO Ok
Route::post('login', [AuthController::class, 'login']);

// FUNCIONANDO Ok
Route::post('register', [AuthController::class, 'register']);

// Os métodos ACIMA NÃO dependem do usuário estar Logado


/**
 * Os métodos ABAIXO dependem do usuário estar Logado
 * estão implementados separadamente e testados todos Ok
 */

// FUNCIONANDO Ok
// Route::post('logout', [AuthController::class, 'logout']);
// Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']); // Rota para dados do usuário autenticado

// FUNCIONANDO Ok
// Route::get('me', [AuthController::class, 'me']);
// Route::middleware('auth:api')->get('me', [AuthController::class, 'me']); // Rota para dados do usuário autenticado

// FUNCIONANDO ambos Ok
// Route::get('users', [AuthController::class, 'listUsers']);
// Route::middleware('auth:api')->get('users', [AuthController::class, 'listUsers']);

// Teste FUNCIONANDO Ok
// Route::middleware('auth:api')->post('refresh', [AuthController::class, 'refresh']);


/**
 * Reunimos os métodos ACIMA num grupo por dependerem do usuário estar Logado
 * 
 * Força que o guard 'api' seja empregada na autenticação das rotas listadas dentro do grupo do middleware
 * O AuthController implementar toda autenticaćão com JWT para acesso api
 */
Route::middleware('auth:api')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');            // efetua logou do usuário
        Route::get('me', 'me');                     // recupera dados do usuário logado
        Route::get('users', 'listUsers');           // recupera lista dos usuários
        Route::post('refresh', 'refresh');          // retorna novo token SEM precisar precisar de login
    });

});

