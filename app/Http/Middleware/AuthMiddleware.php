<?php
// namespace: carpeta lógica o espaci donde está la clase
namespace App\Http\Middleware;
// para importar clases sin tener que escribir su ruta completa
use Closure;
use Illuminate\Http\Request;
// Class that will protect routes
class AuthMiddleware {
    // Method executed before the request reaches the controller
    public function handle(Request $request, Closure $next) {
        // No user in the session = user no authenticated
        if (!session()->has('usuario')) {
            return redirect('/');
        } // return to login page
        return $next($request);
    } // User Auth: Pass request to the next middleware or controller
}
// Middleware check session before protected routes are accessed.
// It can be reused (e.g.: in every controller method)