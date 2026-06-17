<?php

namespace App\Http\Controllers;
use App\Rules\CpfValido;
use Illuminate\Validation\Rules\Password;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'telefone' => ['required', 'string'],
                'cpf' => ['required', 'string', 'unique:users,cpfUsuario', new CpfValido],
                'cidade' => ['required', 'string'],
                'tipoUsuario' => ['required', 'in:NOIVA,NOIVO,ASSESSOR'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'confirmed', Password::min(8)->max(14)->mixedCase()->numbers()->symbols()],
            ],
            [
                'name.required' => 'O nome é obrigatório.',
                'telefone.required' => 'O telefone é obrigatório.',
                'cpf.required' => 'O CPF é obrigatório.',
                'cpf.unique' => 'Este CPF já está cadastrado.',
                'cidade.required' => 'A cidade é obrigatória.',
                'tipoUsuario.required' => 'Selecione o tipo de usuário.',
                'tipoUsuario.in' => 'Tipo de usuário inválido.',
                'email.required' => 'O e-mail é obrigatório.',
                'email.email' => 'Informe um e-mail válido.',
                'email.unique' => 'Este e-mail já está cadastrado.',
                'password.required' => 'A senha é obrigatória.',
                'password.confirmed' => 'As senhas não coincidem.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'password.max' => 'A senha deve ter no máximo 14 caracteres.',
            ]
        );

        $sexo = match ($request->tipoUsuario) {
            'NOIVA' => 'F',
            'NOIVO' => 'M',
            default => null,
        };

        $usuario = User::create([
            'name' => $request->name,
            'telefoneUsuario' => $request->telefone,
            'cpfUsuario' => $request->cpf,
            'cidadeUsuario' => $request->cidade,
            'tipoUsuario' => in_array($request->tipoUsuario, ['NOIVA', 'NOIVO']) ? 'NOIVO' : 'ASSESSOR',
            'email' => $request->email,
            'password' => $request->password,
        ]);

        Auth::login($usuario);

        return redirect()->route('inicio');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('inicio');
        }

        return back()->withErrors([
            'email' => 'E-mail ou senha incorretos.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
