<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function create()
    {
        return view('login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            $usuario = Auth::user();

            if ($usuario->tipoUsuario === 'NOIVO') {
                $casamento = $usuario->casamentos()->where('statusCasamento', 'ATIVO')->first();
                if ($casamento) {
                    return redirect()->route('casamento.show', $casamento);
                }
                return redirect()->route('casamento.create');
            }

            return redirect()->route('inicio');
        }

        return back()->withErrors([
            'login' => 'E-mail ou senha incorretos.',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}
