<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTipoUsuario
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next,
    string ...$tipos): Response
    {
        if(! in_array(auth()->user()->tipoUsuario, $tipos)){
            abort(403, 'Acesso negado :(');
        }
        return $next($request);
    }
}
