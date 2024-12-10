<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\JwtController;
use App\Http\Controllers\OrganizacaoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VeiculoController;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Http\Controllers\AuthController;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Gate as AccessGate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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


// Route::post('/login', function(Request $request) {

//     $credentials = $request->only(["email","password"]);
//     // dd($credentials);

//     // abordagem 1 - verificação passo a passo
//     // $user = User::whereEmail($credentials['email'])->first();
//     // dd($user);
//     // $autenticated = Hash::check($credentials['password'], $user->password);
//     // dd($autenticated);
//     // if (! $autenticated ) {
//     //     return response()->json(['error' => 'Você não está autenticado a acessar este recurso.'], Response::HTTP_UNAUTHORIZED);
//     // }

//     // abordagem 2 - mágicas do Laravel que previne ataques. 
//     // Esta aobrdagem cria um session mas não será usada. Em API se usa Tokens
//     if (Auth::attempt($credentials) === false) {
//         return response()->json(['error' => 'Você não está autenticado'], Response::HTTP_UNAUTHORIZED);
//     }

//     $user = Auth::user();
//     // dd($user);
//     // $token = $user->CreateToken('token');

//     $user->tokens()->delete();          // Revoke all tokens...


//     // $token = $request->user()->CreateToken('APItoken');                     // cria o token sem restrição de autorizações, default ['*']
//     $token = $request->user()->CreateToken('APItoken',['veiculo.index','veiculo.show']); // cria o token com autorizações específicas
//     return response()->json($token->plainTextToken);
// });


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });



// esta passar a ser a rota padrão de autenticação em substituição ao Sanctum
// Route::middleware('auth:api')->get('/user', function(Request $request) {

//     // die('entrou!');
//     // return $request->user();
// });

Route::middleware('auth:api')->get('/user', function(Request $request) {

    // die('entrou!');
    // die($request);
    // dd(Auth::guard());
    // dd(Auth::user());           // mostra o User autenticado
    // dd(auth()->check());        // verifica se usuário está autenticado

    $key = getenv('JWT_SECRET');        // se quiser buscar no .ENV
    $jwt = request()->bearerToken();    // pega o token do Request retirando o prefixo 'Bearer '
    // dd($jwt);

    // echo "Encode:\n" . print_r($token, true) . "\n";
    // echo "Encode:\n" . print_r($token, true) . "\n";

    // return type is stdClass
    // $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    // echo "Decode:\n" . print_r($decoded, true) . "\n";
    // die();


    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        // echo "Encode:\n" . print_r($decoded, true) . "\n";
        // echo json_encode($decoded);
        return response()->json($decoded, Response::HTTP_OK);                               // 200

    } catch (Throwable $e) {
        echo json_encode($e->getMessage());
        return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);    // 500
    }
    // die();


    // return $request->user();
});


Route::post('/login', function(Request $request) {

    $credentials = $request->only(["email","password"]);
    // dd($credentials);
    // dd(Auth::guard());
    // dd(Auth::user());        // mostra o User autenticado
    // dd(auth()->user());      // mostra o User autenticado
    // dd(auth()->check());    // verifica se usuário está autenticado
    // dd($request->user());       // mostra o User autenticado
    // dd(JWTAuth::parseToken()->authenticate());

    // Tenta gerar o token com as credenciais fornecidas
    try {
        // JWTAuth::attempt($credentials) tenta localizar o usuário no BD
        // se conseguir gera um token
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);  // 401
        }
    } catch (JWTException $e) {
        return response()->json(['error' => 'Could not create token'], Response::HTTP_INTERNAL_SERVER_ERROR);   // 500
    }

    // pega o Usuário Logado acima
    $user = Auth::user();

    // Recupera as permissões (abilities) do usuário
    // $abilities = $user->getPermissionsViaRoles()->pluck('name')->toArray();   
    // $abilities = 'organizacao.index,organizacao.show,organizacao.edit'; 

    /**
     * Ver a resposta do chatGPT
     * lá ha controler para implemntar este métodos: login  user e outros
     * class AuthController extends Controller
     * 
     * No chatGPT
     * 
     * 1 Tem exemplo de middlewarw e outros para validar as abilities do user contido no payloas do token
     * 
     * class CheckAbility{}
     * 
     * 2 Estudar o abilities do SPATIE 
     *      * 
     * Implementar algo similiar no ACL como o SPATIE e ter o mesmo método: $user->getPermissionsViaRoles()->pluck('name')->toArray();
     * 
     * A expiração do token por na tabela preferencias do ACL
     * 
     * 
     * 
     * 
     * 
     * 
     * 
     */




    // assim estou pegando as abilities do user vindas do ACL
    $abilities = FacadesGate::abilities();



    $key = getenv('JWT_SECRET'); 

    $payload = [
        'iss' => 'http://example.org',
        'aud' => 'http://example.com',
        'iat' => 1356999524,
        'nbf' => 1357000000,
        'exp' => now()->addMinutes(15)->timestamp,  // Define a expiração para 10 minutos
        'user' => $user,                            // inserido o User no Payload
        'abilities' => $abilities,
        'roles' => ["admin","sgtte","user","SuperAdmin"]
    ];
    
    $headers = [
        'x-forwarded-for' => 'www.google.com'
    ];

    // Encode headers in the JWT string
    // $token = JWT::encode($payload, $key, 'HS256', null, $headers);    

    // Cria o token JWT com o payload personalizado
    $token = JWTAuth::claims($payload)->fromUser($user);
    

    // Retorna o token gerado, junto com o usuário no payload
    return response()->json([
        'token' => $token,
        'user' => $user,            // usuário por fora do payload só para ver, após testar comente, isto está dentro do token
        'payload' => $payload       // payload por fora do payload só para ver, após testar comente, isto está dentro do token 
    ], Response::HTTP_OK);  // 200
    

});






// Route::apiResource('/user', UserController::class);

// Route::apiResource('/jwt', JwtController::class);

// die('parou!');




/**
 * Este middleware controla todo o acessoa API pois assim o sanctum
 * limita o acesso a todos os recursos podendo acessar apenas usuários já autenticados.
 * o sanctum encontra o usuário a partir do token e controla o acesso.
 */
// Route::middleware('auth:sanctum')->group(function() {

    // todas as rotas da API VeiculoController estão controladas aqui e a autorização deve ser feito no controller: php artisan route:list
    // Route::apiResource('/veiculo', VeiculoController::class);

    /**
     * o controle de autorizações das Rotas API do VeiculoController abaixo estão sendo controladas pelo middleware
     * usando a regra: pelo menos uma das habilidades listada para ter acesso
     */
    // Route::apiResource('/veiculo', VeiculoController::class)->middleware(['auth:sanctum','ability:veiculo.index,veiculo.show']);

//  });

/**
 * Basta isto abaixo para todas as rotas da API VeiculoController estarem prontas
 * Para vê-las no terminal: php artisan route:list
 */ 
// Route::apiResource('/veiculo', VeiculoController::class);
// Route::apiResource('/organizacao', OrganizacaoController::class);
// Route::apiResource('/api', ApiController::class);


// Rota para controlador invocável usado para experimentos
// Route::get('jwt', JwtController::class);

// Route::apiResource('/jwt', JwtController::class);




// Route::post('entidade/store', 'store')->name('entidade.store');
// Route::get('/home', [HomeController::class, 'index'])->name('home');


/**
 * Interface padrão HTTP
 * 
 * Métodos & Recursos
 * 
 *  GET      /entidades      pegar todas as Entidades (listar)
 *  GET      /entidades/:id  pegar uma Entidade específica
 *  POST     /entidades      inserir nova Entidades
 *  PUT      /entidades/:id  atualizar todas as propriedades de uma Entidade 
 *  PATCH    /entidades/:id  atualizar alguma das propriedades de uma Entidade 
 *  DELETE   /entidades/:id  remover uma Entidades
 * 
 * Cabeçalhos:
 * 
 *  Requisição
 *      Accept: application/json
 *  Resposta
 *      Content-Type: application/json
 * 
 * Códigos de resposta (status):
 *  2xx - requisição foi processada com sucesso
 *  3xx - indica ao cliente uma ação a ser tomada para que a requisição possa ser concluída
 *  4xx - indica erros na requisição causada pelo cliente
 *  5xx - indica que a requisição não foi concluída por erros no servidor
 * 
 * REST - Respresentational State Transfer
 * 
 */