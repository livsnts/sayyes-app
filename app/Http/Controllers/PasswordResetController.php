<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    public function requestForm()
    {
        session()->forget('errors');
        return view('auth.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::ResetLinkSent) {
            return back()->with('sucesso', 'Enviamos o link de redefinição de senha para o seu e-mail.');
        }

        $mensagens = [
            Password::InvalidUser => 'Não encontramos um usuário com esse e-mail.',
            Password::ResetThrottled => 'Aguarde alguns instantes antes de tentar novamente.',
        ];

        return back()->withErrors([
            'email' => $mensagens[$status] ?? 'Não foi possível enviar o link. Tente novamente.',
        ]);
    }

    public function resetForm(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate(
            [
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required', 'confirmed', PasswordRule::min(8)->max(14)->mixedCase()->numbers()->symbols()],
            ],
            [
                'password.required' => 'A senha é obrigatória.',
                'password.confirmed' => 'As senhas não coincidem.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'password.max' => 'A senha deve ter no máximo 14 caracteres.',
                'password.mixed' => 'A senha deve conter letras maiúsculas e minúsculas.',
                'password.numbers' => 'A senha deve conter pelo menos um número.',
                'password.symbols' => 'A senha deve conter pelo menos um símbolo.',
            ]
        );

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('sucesso', 'Senha redefinida com sucesso! Faça login.')
            : back()->withErrors(['email' => 'Link inválido ou expirado. Solicite um novo.']);
    }
}