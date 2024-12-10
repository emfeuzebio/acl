<?php

namespace App\Http\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

use stdClass;
use Throwable;

// use Psr\Http\Message\RequestInterface;
// use Exception;
// use Illuminate\Http\Client\PendingRequest;
// use Illuminate\Http\Client\RequestException;

// use Firebase\JWT\JWT;
 

class JwtController extends Controller
{

    /**
     * https://github.com/firebase/php-jwt
     * https://www.youtube.com/watch?v=B-7e-ZpIWAs
     * https://www.youtube.com/watch?v=sHyoMWnnLGU
     * https://www.youtube.com/watch?v=Wv02i0yNVVs     * 
     */
    private $key = 'example_key';
    
    public function index()
    {

        /**
         * Usar a requisição abaixo para testar a chamada decode
         */
        // fetch('http://localhost:10000/api/jwt/', {
        //     method: 'GET',
        //     headers: { 
        //         'Content-Type': 'application/json', 
        //         'Authorization': 'eyJ0eXAiOiJKV1QiLCJ4LWZvcndhcmRlZC1mb3IiOiJ3d3cuZ29vZ2xlLmNvbSIsImFsZyI6IkhTMjU2In0.eyJpc3MiOiJodHRwOi8vZXhhbXBsZS5vcmciLCJhdWQiOiJodHRwOi8vZXhhbXBsZS5jb20iLCJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwiYWJpbGl0aWVzIjoib3JnYW5pemFjYW8uaW5kZXgsb3JnYW5pemFjYW8uc2hvdyxvcmdhbml6YWNhby5lZGl0Iiwicm9sZXMiOlsiYWRtaW4iLCJzZ3R0ZSIsInVzZXIiXX0.y953ztv7RFNQRaOVKNiKSPQoV2cxPcSMIAK-6Ar8n1w'
        //     }
        // })
        // .then(response => response.text())
        // .then(data => console.log(data));


        $key = 'example_key';
        $key = getenv('JWT_KEY');       // se quiser buscar no .ENV

        $jwt = $_SERVER['HTTP_AUTHORIZATION'];

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
            return response()->json($decoded, Response::HTTP_OK);

        } catch (Throwable $e) {
            echo json_encode($e->getMessage());
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        die();

    }

    public function show()
    {

        $key = 'example_key';
        $key = getenv('JWT_KEY');       // se quiser buscar no .ENV

        $payload = [
            'iss' => 'http://example.org',
            'aud' => 'http://example.com',
            'iat' => 1356999524,
            'nbf' => 1357000000,
            'abilities' => 'organizacao.index,organizacao.show,organizacao.edit',
            'roles' => ["admin","sgtte","user"]
        ];
        
        $headers = [
            'x-forwarded-for' => 'www.google.com'
        ];
        
        // Encode headers in the JWT string
        $token = JWT::encode($payload, $key, 'HS256', null, $headers);
        // echo $token; 
        return response()->json($token, Response::HTTP_OK);

        /**
         * a página que ira solicitar o login do usuário deverá enviar a tupla email e password
         * o método acima dererá autenticar o usuário, recuperar suas roles e autorizações,
         * incluir no payload e gerar o token
         * 
         * a página que soliciou irá receber o token e
         * deverá guardar este na session do navegar com js usado:
         * 
         *      sessionStorage.setItem('session', data);         // para gravar o token na session do navegador
         * 
         *      sessionStorage.getItem('session');               // para recuperar o token da session do navegador
         * 
         *      conat authSession = sessionStorage.getItem('session');
         *      const {data} = await axios.get('http://localhost/api/jwt', {
         *              headers: {
         *                "Autorization": 'Bearer ' + authSession
         *          }
         *      })
         * 
         *      console.log(data);
         *         * 
         * 
         * 
         */

        //  sessionStorage.setItem('session', 'eyJ0eXAiOiJKV1QiLCJ4LWZvcndhcmRlZC1mb3IiOiJ3d3cuZ29vZ2xlLmNvbSIsImFsZyI6IkhTMjU2In0.eyJpc3MiOiJodHRwOi8vZXhhbXBsZS5vcmciLCJhdWQiOiJodHRwOi8vZXhhbXBsZS5jb20iLCJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwiYWJpbGl0aWVzIjoib3JnYW5pemFjYW8uaW5kZXgsb3JnYW5pemFjYW8uc2hvdyxvcmdhbml6YWNhby5lZGl0Iiwicm9sZXMiOlsiYWRtaW4iLCJzZ3R0ZSIsInVzZXIiXX0.y953ztv7RFNQRaOVKNiKSPQoV2cxPcSMIAK-6Ar8n1w');

    }    

    public function __invoke()
    {
        // dd('__invoke()');


        // $key = 'example_key';
        // $payload = [
        //     'iss' => 'http://example.org',
        //     'aud' => 'http://example.com',
        //     'iat' => 1356999524,
        //     'nbf' => 1357000000,
        //     'abilities' => 'organizacao.index,organizacao.show,organizacao.edit',
        //     'roles' => ["admin","sgtte","user"]
        // ];
        
        // $headers = [
        //     'x-forwarded-for' => 'www.google.com'
        // ];
        
        // // Encode headers in the JWT string
        // $jwt = JWT::encode($payload, $key, 'HS256', null, $headers);
        // echo $jwt; 

    }
        
}