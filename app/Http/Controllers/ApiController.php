<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Illuminate\Http\Client\Response;
use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
 

class ApiController extends Controller
{
    public function index()
    {
        // dd('index()');

        /**
         * GET > INDEX Listar like *
         * SELECT * WHERE like - SOLUÇÃO simples com autenticação
         * SOLUÇÃO COM TOKEN e Exception que envia token de autenticação
         * FUNCIONANDO trás os registro cuja descricao like '%param%' da API
         * PARANS envia parâmetro descricao por GET para a consulta
         */    
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->withToken('19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08')
              ->get('https://apieventos.voluntary.com.br/api/organizacao');

            // dd([
            //     'body' => $response->body(),
            //     'success' => $response->successful(),
            //     'status' => $response->status(),
            //     'ok' => $response->ok(),
            //     'failed' => $response->failed(),
            //     'headers' => $response->headers(),
            //     'dados' => $response->json(),
            // ]);                

            if ($response->successful()) {

                // $parans['veiculos'] = $response->json();                        // retorna um array de arrais
                $parans['veiculos'] = json_decode($response->body(), false);    // converte para um array de objetos
            } else {
                // Se houver algum erro
                dd('Erro: ' . $response->status());
            }        
        } catch (RequestException $e) {
            dd('Erro de requisição: ' . $e->getMessage());
        }

        return view('acl/ApiTestes', $parans);    
    }
    // Classe de apenas um método que é executado direto: __invoke()
    public function __invoke()
    {
        dd('__invoke()');

        /**
         * DETROY > DELETE ID
         * SOLUÇÃO COM TOKEN e Exception que envia token de autenticação
         * FUNCIONANDO trás um registro pelo ID da API
         * PARANS envia parâmetro ID por GET para a consulta
         */
        // try {
        //     $response = Http::withHeaders([
        //         'Accept' => 'application/json',
        //     ])->withToken('19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08')
        //       ->delete('https://apieventos.voluntary.com.br/api/veiculo/22');

        //     if ($response->successful()) {
        //         // $parans['veiculos'] = $response->json();                        // retorna um array de arrays
        //         $parans['veiculos'] = json_decode($response->body(), false);       // converte para um array de objetos
        //         // dd($parans['veiculos']);
        //     } else {
        //         // Se houver algum erro
        //         dd('Erro: ' . $response->status(), $response->body());
        //     }        
        // } catch (RequestException $e) {
        //     dd('Erro de requisição: ' . $e->getMessage());
        // }




        /**
         * GET > INDEX Listar Todos
         * SELECT * SOLUÇÃO simples sem autenticação ou parâmetros
         * FUNCIONANDO trás a lista de veículos da API
         * Condição é que o routes/api não exija autenticação
         */
        // $response = Http::get('https://apieventos.voluntary.com.br/api/veiculo');
        // dd([
        //     'body' => $response->body(),
        //     'status' => $response->status(),
        //     'ok' => $response->ok(),
        //     'failed' => $response->failed(),
        //     'headers' => $response->headers(),
        //     'dados' => $response->json(),
        //     // 'response' => $response,
        // ]);    
        // return view('acl/ApiTestes');

        

        /**
         * PATCH > UPDATE Parcial ID
         * UPDATE PARCIAL um registro com Http::PATCH - Funciona
         *  Aplica apenas as validações do campo enviado contidas no EntidadeRequest que foi customizado
         */
        // $formDados = [
        //     "descricao" => "C4 Pallas preto do Marcus 3 lugares",
        //     "capacidade" => "4",
        //     "marca_modelo" => "Chevrolet/Meriva GL 1.6 Preto",
        // ];

        // $response = Http::withHeaders([
        //             'Accept' => 'application/json',
        //         ])->withToken('19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08')
        //             ->patch('https://apieventos.voluntary.com.br/api/veiculo/22', 
        //             $formDados
        //         );


        /**
         * POST
         * INSERT TOTAL um registro com Http::POST - Funciona
         *  Aplica todas validações contidas no EntidadeRequest
         */
        // $formDados = [
        //     "descricao" => "Honda/HRV branco do Julião 3 lugares",
        //     "tipo" => "Automóvel",
        //     "marca_modelo" => "Honda/HRV 1.6 Branco",
        //     "capacidade" => 3,
        //     "motorista" => "JULIÃO TAVARES",
        //     "telefone" => "(61) 98000-2000",
        //     "observacao" => "SUV com bom bagageiro para 3 passageiros", 
        //     "ativo" => "SIM",
        // ];

        // $response = Http::withHeaders([
        //             'Accept' => 'application/json',
        //         ])->withToken('19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08')
        //             ->post('https://apieventos.voluntary.com.br/api/veiculo', 
        //             $formDados
        //         );        
  
        
        
        /**
         * PUT UPDATE Total ID
         * UPDATE TOTAL um registro com Http::PUT - Funciona
         *  Aplica todas validações contidas no EntidadeRequest
         */
        // $formDados = [
        //     "descricao" => "Honda/HRV verde do Julião 3 lugares",
        //     "tipo" => "Automóvel",
        //     "marca_modelo" => "Honda/HRV 1.6 Branco",
        //     "capacidade" => '3',
        //     "motorista" => "JULIÃO TAVARES",
        //     "telefone" => "(61) 98000-2000",
        //     "observacao" => "SUV com bom bagageiro para 3 passageiros", 
        //     "ativo" => "SIM",
        // ];

        // $response = Http::withHeaders([
        //             'Accept' => 'application/json',
        //         ])->withToken('19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08')
        //             ->put('https://apieventos.voluntary.com.br/api/veiculo/25', 
        //             $formDados
        //         );        

        // dd([
        //     // 'body' => $response->body(),
        //     'status' => $response->status(),
        //     'ok' => $response->ok(),
        //     'failed' => $response->failed(),
        //     'headers' => $response->headers(),
        //     'dados' => $response->json(),
        // ]);            
        
        /**
         * SHOW > GET ID
         * SOLUÇÃO COM TOKEN e Exception que envia token de autenticação
         * FUNCIONANDO trás um registro pelo ID da API
         * PARANS envia parâmetro ID por GET para a consulta
         */
        // try {
        //     $response = Http::withHeaders([
        //         'Accept' => 'application/json',
        //     ])->withToken('19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08')
        //       ->get('https://apieventos.voluntary.com.br/api/veiculo/1');

        //     if ($response->successful()) {

        //         // Ver aqui 
        //         // a API retornou uma string com um array de objetos no $response->body()
        //         // o $response->json() retorna um aray de array
        //         // $parans['veiculos'] = $response->json();                        // retorna um array de arrays
        //         $parans['veiculos'] = json_decode($response->body(), false);       // converte para um array de objetos
        //         // dd($parans['veiculos']);

        //     } else {
        //         // Se houver algum erro
        //         dd('Erro: ' . $response->status());
        //     }        
        // } catch (RequestException $e) {
        //     dd('Erro de requisição: ' . $e->getMessage());
        // }

        
        /**
         * GET > INDEX Listar like *
         * SELECT * WHERE like - SOLUÇÃO simples com autenticação
         * SOLUÇÃO COM TOKEN e Exception que envia token de autenticação
         * FUNCIONANDO trás os registro cuja descricao like '%param%' da API
         * PARANS envia parâmetro descricao por GET para a consulta
         */    
        // try {
        //     $response = Http::withHeaders([
        //         'Accept' => 'application/json',
        //     ])->withToken('19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08')
        //       ->get('https://apieventos.voluntary.com.br/api/veiculo?descricao=prata&=tipo=2');

        //     // dd([
        //     //     // 'body' => $response->body(),
        //     //     'success' => $response->successful(),
        //     //     'status' => $response->status(),
        //     //     'ok' => $response->ok(),
        //     //     'failed' => $response->failed(),
        //     //     'headers' => $response->headers(),
        //     //     'dados' => $response->json(),
        //     // ]);                

        //     if ($response->successful()) {

        //         // $parans['veiculos'] = $response->json();                        // retorna um array de arrais
        //         $parans['veiculos'] = json_decode($response->body(), false);    // converte para um array de objetos
        //     } else {
        //         // Se houver algum erro
        //         dd('Erro: ' . $response->status());
        //     }        
        // } catch (RequestException $e) {
        //     dd('Erro de requisição: ' . $e->getMessage());
        // }

        // return view('acl/ApiTestes', $parans);      


        /**
         * GET > INDEX Listar like *
         * SELECT * WHERE like - SOLUÇÃO simples com autenticação
         * SOLUÇÃO COM TOKEN e Exception que envia token de autenticação
         * FUNCIONANDO trás a lista de veículos da API
         * PARANS não envia parâmetros para a consulta
         */
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Connection' => 'close',
            ])->withToken('19|HUm7ao29lyhtDjo9BGKwL8DbbbNl22i79cIvURn07c5bfa08')
              ->get('https://apieventos.voluntary.com.br/api/veiculo');

            if ($response->successful()) {

                // Ver aqui 
                // a API retornou uma string com um array de objetos no $response->body()
                // o $response->json() retorna um aray de array
                // $parans['veiculos'] = $response->json();                        // retorna um array de arrais
                $parans['veiculos'] = json_decode($response->body(), false);    // converte para um array de objetos
            } else {
                // Se houver algum erro
                dd('Erro: ' . $response->status());
            }        
        } catch (RequestException $e) {
            dd('Erro de requisição: ' . $e->getMessage());
        }

        return view('acl/ApiTestes', $parans);   
                


        /**
         * SOLUÇÃO simples sem autenticação ou parâmetros
         */
        // $response = Http::get('https://api.github.com/users/emfeuzebio/repos');
    
    }
}
