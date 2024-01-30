<?php

namespace App\Http\Middleware;

use App\Mail\VerificarUsuario;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Verifica el rol del usuario
            if ($user->role == 1) {
                $code = Str::random(6);
                $id_user = $user->id;
                $user->second_factory_token = $code;
                $url = URL::temporarySignedRoute('verificar-usuario',now()->addMinutes(5),
                ['id'=>$user->id]);
                Mail::to($user->email)->send(new VerificarUsuario($user,$url));
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('verificarCodigo', ['id' => $user->id]);
            }
        }

        return $next($request);
    }
}
