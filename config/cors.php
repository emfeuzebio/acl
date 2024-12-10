<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute 
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // 'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'paths' => ['api/*'],                           // Aplica-se a todas as rotas que começam com 'api/'
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],  // Permite apenas esses métodos
    'allowed_origins' => [
        'https://localhost:10000',      // Permite apenas essa origem
        '*',
    ],
    // 'allowed_headers' => ['Content-Type', 'Authorization'],     // Permite apenas esses cabeçalhos
    'allowed_headers' => ['*'],     // Permite apenas esses cabeçalhos
    'exposed_headers' => ['X-Custom-Header'],  // Expor apenas esse cabeçalho
    'max_age' => 3600,  // O tempo em segundos que a resposta de CORS pode ser armazenada em cache
    'supports_credentials' => true,  // Permite o envio de credenciais (cookies, autenticação, etc.)

];

