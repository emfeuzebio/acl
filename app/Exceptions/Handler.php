<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        // captura e trata os erros de Rotas não autorizadas
        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            
            $route = $request->route();
            $routeName = $route ? $route->getName() : 'Rota sem nome';  // pega o nome da rota
            
            if ($request->wantsJson()) {
                // Se for uma requisição Ajax (JSON), vamos enviar uma resposta JSON
                return response()->json(['message' => 'Rota: ' . $routeName . ' <b> não autorizada.</b>'], Response::HTTP_FORBIDDEN); // 403
            }

            // Caso contrário, vamos redirecionar o usuário para a página Home ou uma personalizada
            return redirect()->route('home')->with('error', "Rota: '" . $routeName . "' não autorizada.");
        });   
    }

}
