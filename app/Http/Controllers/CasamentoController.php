<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Casamento;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CasamentoController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = auth()->user()->casamentos();

        if ($request->filled('status')) {
            $query->where('statusCasamento', $request->status);
        }

        if ($request->filled('nome')) {
            $query->where('nomeCasamento', 'like', '%' . $request->nome . '%');
        }

        if ($request->filled('data_de')) {
            $query->where('dataCasamento', '>=', $request->data_de);
        }

        if ($request->filled('data_ate')) {
            $query->where('dataCasamento', '<=', $request->data_ate);
        }

        $casamentos = $query->orderBy('dataCasamento')->get();

        return view('casamento.index', compact('casamentos'));
    }

    public function create()
    {
        if (auth()->user()->tipoUsuario === 'NOIVO') {
            $casamento = auth()->user()->casamentos()->first();
            if ($casamento) {
                return redirect()->route('casamento.show', $casamento);
            }
        }
        return view('casamento.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->tipoUsuario === 'NOIVO' && auth()->user()->casamentos()->where('statusCasamento', 'ATIVO')->exists()) {
            return back()->with('erro', 'Você já possui um casamento cadastrado.');
        }

        if ($request->orcamentoTotal) {
            $valor = str_replace(['R$ ', '.', ','], ['', '', '.'], $request->orcamentoTotal);
            $request->merge(['orcamentoTotal' => $valor]);
        }

        $request->validate([
            'nomeCasamento' => ['required', 'string', 'max:255'],
            'dataCasamento' => ['required', 'date', 'after:today'],
            'localCasamento' => ['nullable', 'string', 'max:255'],
            'orcamentoTotal' => ['required', 'numeric', 'min:0'],
            'descricaoCasamento' => ['nullable', 'string', 'max:1000'],
            'urlListaDePresentes' => ['nullable', 'url'],
            'imagemCasamento' => ['nullable', 'image', 'max:2048'],
        ], [
            'nomeCasamento.required' => 'O nome do casamento é obrigatório.',
            'dataCasamento.required' => 'A data é obrigatória.',
            'dataCasamento.after' => 'A data deve ser no futuro.',
            'urlListaDePresentes.url' => 'Informe uma URL válida.',
            'imagemCasamento.image' => 'O arquivo deve ser uma imagem.',
            'imagemCasamento.max' => 'A imagem deve ter no máximo 2MB.',
            'orcamentoTotal.numeric' => 'O orçamento deve ser um número.',
            'orcamentoTotal.required' => 'Você precisa inserir uma previsão de orçamento. Caso ainda não tenha uma, adicione um valor aproximado.',
        ]);

        $imagemPath = null;
        if ($request->hasFile('imagemCasamento')) {
            $imagemPath = $request->file('imagemCasamento')->store('casamentos', 'public');
        }

        $casamento = Casamento::create([
            'nomeCasamento' => $request->nomeCasamento,
            'dataCasamento' => $request->dataCasamento,
            'localCasamento' => $request->localCasamento,
            'orcamentoTotal' => $request->orcamentoTotal,
            'descricaoCasamento' => $request->descricaoCasamento,
            'urlListaDePresentes' => $request->urlListaDePresentes,
            'imagemCasamento' => $imagemPath,
            'statusCasamento' => 'ATIVO',
        ]);

        $casamento->usuarios()->attach(auth()->id());

        return redirect()->route('casamento.show', $casamento);
    }

    public function show(Casamento $casamento)
    {
        $this->authorize('view', $casamento);
        return view('casamento.show', compact('casamento'));
    }

    public function edit(Casamento $casamento)
    {
        $this->authorize('update', $casamento);

        session()->flashInput([
            'statusCasamento' => $casamento->statusCasamento,
            'nomeCasamento' => $casamento->nomeCasamento,
            'dataCasamento' => $casamento->dataCasamento->format('Y-m-d'),
            'localCasamento' => $casamento->localCasamento,
            'orcamentoTotal' => number_format($casamento->orcamentoTotal, 2, ',', '.'),
            'descricaoCasamento' => $casamento->descricaoCasamento,
            'urlListaDePresentes' => $casamento->urlListaDePresentes,
        ]);

        return view('casamento.edit', compact('casamento'));
    }

    public function update(Request $request, Casamento $casamento)
    {
        $this->authorize('update', $casamento);

        if ($request->orcamentoTotal) {
            $valor = str_replace(['R$ ', '.', ','], ['', '', '.'], $request->orcamentoTotal);
            $request->merge(['orcamentoTotal' => $valor]);
        }

        $request->validate([
            'statusCasamento' => ['required', 'in:ATIVO,REALIZADO,CANCELADO,INATIVO'],
            'nomeCasamento' => ['required', 'string', 'max:255'],
            'dataCasamento' => ['required', 'date', 'after:today'],
            'localCasamento' => ['nullable', 'string', 'max:255'],
            'orcamentoTotal' => ['required', 'numeric', 'min:0'],
            'descricaoCasamento' => ['nullable', 'string', 'max:1000'],
            'urlListaDePresentes' => ['nullable', 'url'],
            'imagemCasamento' => ['nullable', 'image', 'max:2048'],
        ]);

        $dados = $request->only([
            'nomeCasamento',
            'dataCasamento',
            'localCasamento',
            'orcamentoTotal',
            'descricaoCasamento',
            'urlListaDePresentes',
            'statusCasamento',
        ]);

        if ($request->hasFile('imagemCasamento')) {
            $dados['imagemCasamento'] = $request->file('imagemCasamento')->store('casamentos', 'public');
        }

        $casamento->update($dados);

        return redirect()->route('casamento.show', $casamento)->with('sucesso', 'Casamento atualizado com sucesso!');
    }

    public function equipe(Casamento $casamento)
    {
        $this->authorize('view', $casamento);
        $membros = $casamento->usuarios()->get();
        return view('casamento.equipe', compact('casamento', 'membros'));
    }

    public function buscarUsuario(Request $request, Casamento $casamento)
    {
        $this->authorize('view', $casamento);

        $usuario = User::where('email', $request->email)->first();

        if (!$usuario) {
            return response()->json(['encontrado' => false]);
        }

        if ($casamento->usuarios()->where('user_id', $usuario->id)->exists()) {
            return response()->json(['encontrado' => false, 'ja_membro' => true]);
        }

        return response()->json([
            'encontrado' => true,
            'id' => $usuario->id,
            'name' => $usuario->name,
            'telefone' => $usuario->telefoneUsuario,
            'tipo' => $usuario->tipoUsuario,
            'sexo' => $usuario->sexoUsuario,
        ]);
    }

    public function adicionarMembro(Request $request, Casamento $casamento)
    {
        $this->authorize('view', $casamento);

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        if ($casamento->usuarios()->where('user_id', $request->user_id)->exists()) {
            return back()->with('erro', 'Este usuário já é membro do casamento.');
        }

        $casamento->usuarios()->attach($request->user_id);

        return back()->with('sucesso', 'Membro adicionado com sucesso!');
    }

}
