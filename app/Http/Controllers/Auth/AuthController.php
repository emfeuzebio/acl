<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * IMPORTANTE
     * Sistema JWT precisa que o token seja enviado sempre no Headers da requisićão
     *  Authorization: bearer 'xxxx'. 
     * Se ele estiver vencido qualquer método irá retornar: "message": "Unauthenticated." e
     *  somente após o novo login os métodos irão responder 
     * O arquivo config/jwt contém todas as configuraćões do JWT
     */
    
    /**
     * chatgpt.com
     * laravel 10 autenticaćão JWT completa com AutoController como implementar
     */

    /**
     * Construtor cuida das autorizaćões de acesso aos métodos deste Controller
     */
    public function __construct()
    {
        // nesse caso ['login', 'register'] não dependem de estar autenticado
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    // Método não usado
    public function index(Request $request)
    {
        // dd("AuthController->index()");
    }

    /**
     * Método para realiza o Login e cria o token JWT - Consulta a tabela Users
     */
    public function login(Request $request)
    {
        /**
         * chatgpt
         * laravel 10 como é um padrão de lista de abilities que o $abilities = Gate::abilities() retorna dentro de um token jwt ?
         */

        // Validação dos dados recebidos na requisição
        $credentials = $request->only('email', 'password');
        // dd($credentials);

        // Tenta gerar o token com as credenciais do usuário fornecidas
        try {
            // JWTAuth::attempt($credentials) tenta localizar o usuário no BD, se não conseguir gera um token retorno 401 ou 500 caso der erro
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);  // 401
            }
        } catch (JWTException $error) {
            return response()->json(['error' => 'Could not create token.' . $error], Response::HTTP_INTERNAL_SERVER_ERROR);   // 500
        }

        // pega o Usuário Logado acima
        $user = Auth::user();

        // assim estou pegando as abilities do user vindas do ACL
        $abilities = Gate::abilities();
        $simplifiedAbilities = array_keys($abilities);  // transforma num array padrão abilities ex. "abilities": ["user.index","user.show"]
        // dd($abilities);
        // dd($simplifiedAbilities);

        $payload = [
            "sub" => $user->id,                             // ID do usuário
            'iss' => 'http://example.org',                  // emissor do token
            'aud' => 'http://apieventos.voluntary.com.br',  // Audience (público-alvo) do token
            'iat' => 1356999524,                            // Data de emissão
            'nbf' => 1357000000,                            // Data de expiração
            'exp' => now()->addMinutes(15)->timestamp,      // Define a expiração para 10 minutos
            'user' => $user,                                // inserido o User no Payload
            'abilities' => $simplifiedAbilities,            // ex. "abilities": ["user.index","user.show"]
            // 'roles' => ["admin","sgtte","SuperAdmin"]
        ];

        // Cria o token JWT com o payload personalizado usando as definicoes do config/jwt.php 
        $token = JWTAuth::claims($payload)->fromUser($user);

        // Retorna o token gerado. Se env for local envia user e payload em claro para dev
        return response()->json([
            'token' => $token,
            'user' => getenv('APP_ENV') == 'local' ? $user : '',        // sendo local envia o usuário por fora do payload só para ver
            'payload' => getenv('APP_ENV') == 'local' ? $payload : '',  // sendo local envia o payload por fora do payload só para ver
        ], Response::HTTP_OK);  // 200        

    }

    /**
     * Método para fazer Logout do usuário (invalidar o token).
     */
    public function logout(Request $request)
    {
        /**
         * o logou somente é executado se o token trazido pela requisićão for válido
         * do contrário retorna: {"message":"Unauthenticated."} e sequer processar as linhas abaixo
         * dd("AuthController->logout()");
         */

        try {
            // Recupera o token do header Authorization
            $token = JWTAuth::getToken();

            // Invalida o token
            JWTAuth::invalidate($token);
            Auth::logout();

            // Resposta de sucesso
            return response()->json(['message' => 'Logout realizado com sucesso'], Response::HTTP_OK);  // 200   
        } catch (JWTException $error) {
            // Caso o token não seja válido ou ocorra outro erro
            return response()->json(['error' => 'Não foi possível realizar o logout. ' . $error], Response::HTTP_INTERNAL_SERVER_ERROR);   // 500
        }
    }    

    /**
     * Método para cria um novo usuário - insere um novo registro na tabela Users
     */
    public function register(Request $request)
    {
        // dd("AuthController->register()");
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        /**
         * Caso o usuário já exista o validate acima retorna a mensagem:
         * {"message":"The email has already been taken.","errors":{"email":["The email has already been taken."]}}
         */

        try {
            // insere o novo Usuário na tabela Users
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Um usuário é criado na tabela Users
            return response()->json(['message' => 'Usuário registrado com sucesso'], Response::HTTP_CREATED);  // 201

        } catch (JWTException $error) {
            return response()->json(['error' => 'Não foi possível registrar o Usuário, ' . $error], Response::HTTP_INTERNAL_SERVER_ERROR);   // 500
        }
    }

    /**
     * Método para recupera os dados do usuário logado (autenticado) - consulta a tabela Users
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    /**
     * Método para listar todos os usuários
     */
    public function listUsers()
    {
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);  // 401
        }

        // retorna a lista de todos os usuários do banco
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Método para fazer o refresh (novo token sem login) do token
     * 
     * A cada vez que o token JWT expirar, o usuário pode enviar uma requisição para o endpoint /api/refresh 
     * para obter um novo token SEM precisar realizar o login novamente.
     */
    public function refresh(Request $request)
    {
        try {
            // Tentar obter o token refresh
            $token = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json(compact('token'));
        } catch (JWTException $error) {
            return response()->json(['error' => 'Could not refresh token.' . $error], Response::HTTP_INTERNAL_SERVER_ERROR);   // 500
        }
    }    

}

