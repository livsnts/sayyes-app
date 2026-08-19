<?php

namespace App\Http\Controllers;

use App\Imports\ConvidadosImport;
use App\Models\Casamento;
use App\Models\Convidado;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ConvidadoController extends Controller
{
    use AuthorizesRequests;

    public function index(Casamento $casamento)
    {
        $this->authorize('view', $casamento);

        $convidados = $casamento->convidados()->withCount('acompanhantes')->orderBy('nomeConvidado')->get();

        $totalConvidados = $convidados->count();
        $confirmados = $convidados->where('statusConvidado', 'CONFIRMADO')->count();
        $pendentes = $convidados->where('statusConvidado', 'PENDENTE')->count();
        $recusados = $convidados->where('statusConvidado', 'RECUSADO')->count();

        return view('convidados.index', compact(
            'casamento',
            'convidados',
            'totalConvidados',
            'confirmados',
            'pendentes',
            'recusados'
        ));
    }

    public function create(Casamento $casamento)
    {
        $this->authorize('view', $casamento);

        $convidados = $casamento->convidados()->withCount('acompanhantes')->orderBy('nomeConvidado')->get();

        $totalConvidados = $convidados->count();
        $confirmados = $convidados->where('statusConvidado', 'CONFIRMADO')->count();
        $pendentes = $convidados->where('statusConvidado', 'PENDENTE')->count();
        $recusados = $convidados->where('statusConvidado', 'RECUSADO')->count();

        return view('convidados.create', compact(
            'casamento',
            'convidados',
            'totalConvidados',
            'confirmados',
            'pendentes',
            'recusados'
        ));
    }

    public function store(Request $request, Casamento $casamento)
    {
        $this->authorize('view', $casamento);

        $request->validate([
            'nomeConvidado' => ['required', 'string', 'max:255'],
            'telefoneConvidado' => ['nullable', 'string'],
            'quantidadeMaxAcompanhantes' => ['required', 'integer', 'min:0'],
            'alergiasConvidado' => ['nullable', 'string'],
        ], [
            'nomeConvidado.required' => 'O nome do convidado é obrigatório.',
            'quantidadeMaxAcompanhantes.required' => 'Informe o número de acompanhantes (0 se não houver).',
            'quantidadeMaxAcompanhantes.integer' => 'O número de acompanhantes deve ser inteiro.',
            'quantidadeMaxAcompanhantes.min' => 'O número de acompanhantes não pode ser negativo.',
        ]);

        $casamento->convidados()->create($request->only([
            'nomeConvidado',
            'telefoneConvidado',
            'quantidadeMaxAcompanhantes',
            'alergiasConvidado',
        ]));

        return redirect()->route('convidado.create', $casamento)->with('sucesso', 'Convidado adicionado com sucesso!');
    }

    public function destroy(Casamento $casamento, Convidado $convidado)
    {
        $this->authorize('view', $casamento);
        abort_unless($convidado->casamento_id === $casamento->id, 404);

        $convidado->delete();

        return back()->with('sucesso', 'Convidado removido com sucesso!');
    }

    public function importar(Request $request, Casamento $casamento)
    {
        $this->authorize('view', $casamento);

        $request->validate([
            'planilha' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ], [
            'planilha.required' => 'Selecione um arquivo.',
            'planilha.mimes' => 'O arquivo deve ser .xlsx, .xls ou .csv.',
        ]);

        Excel::import(new ConvidadosImport($casamento), $request->file('planilha'));

        return redirect()->route('convidado.create', $casamento)->with('sucesso', 'Convidados importados com sucesso!');
    }

    public function edit(Casamento $casamento, Convidado $convidado)
    {
        $this->authorize('view', $casamento);

        return view('convidados.edit', compact('casamento', 'convidado'));
    }

    public function update(Request $request, Casamento $casamento, Convidado $convidado)
    {
        $this->authorize('view', $casamento);

        $request->validate([
            'nomeConvidado' => ['required', 'string', 'max:255'],
            'telefoneConvidado' => ['nullable', 'string'],
            'statusConvidado' => ['required', 'in:PENDENTE,CONFIRMADO,RECUSADO'],
            'quantidadeMaxAcompanhantes' => ['required', 'integer', 'min:0'],
            'alergiasConvidado' => ['nullable', 'string'],
            'observacoesConfirmacao' => ['nullable', 'string'],
        ], [
            'nomeConvidado.required' => 'O nome do convidado é obrigatório.',
            'statusConvidado.required' => 'O status é obrigatório.',
            'statusConvidado.in' => 'Status inválido.',
            'quantidadeMaxAcompanhantes.required' => 'Informe o número de acompanhantes.',
            'quantidadeMaxAcompanhantes.integer' => 'O número de acompanhantes deve ser inteiro.',
            'quantidadeMaxAcompanhantes.min' => 'O número de acompanhantes não pode ser negativo.',
        ]);

        $convidado->update($request->only([
            'nomeConvidado',
            'telefoneConvidado',
            'statusConvidado',
            'quantidadeMaxAcompanhantes',
            'alergiasConvidado',
            'observacoesConfirmacao',
        ]));

        return redirect()->route('convidado.index', $casamento)->with('sucesso', 'Convidado atualizado com sucesso!');
    }

    // Confirmação
    public function confirmar(string $token)
    {
        $convidado = Convidado::where('tokenConfirmacao', $token)->firstOrFail();

        return view('convidados.confirmar', compact('convidado'));
    }

    public function salvarConfirmacao(Request $request, string $token)
    {
        $convidado = Convidado::where('tokenConfirmacao', $token)->firstOrFail();

        $request->validate([
            'statusConvidado' => ['required', 'in:CONFIRMADO,RECUSADO'],
            'observacoesConfirmacao' => ['nullable', 'string'],
            'alergiasConvidado' => ['nullable', 'string'],
        ]);

        $convidado->update([
            'statusConvidado' => $request->statusConvidado,
            'observacoesConfirmacao' => $request->observacoesConfirmacao,
            'alergiasConvidado' => $request->alergiasConvidado,
            'dataConfirmacao' => now(),
        ]);

        return back()->with(
            'sucesso',
            $request->statusConvidado === 'CONFIRMADO'
            ? 'Presença confirmada. Nos vemos no grande dia!'
            : 'Resposta registrada. Obrigado por avisar!'
        );
    }
}
