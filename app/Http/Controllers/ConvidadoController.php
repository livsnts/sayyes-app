<?php

namespace App\Http\Controllers;

use App\Imports\ConvidadosImport;
use App\Models\Casamento;
use App\Models\Convidado;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ModeloConvidadosExport;
use App\Enums\FaixaEtaria;
use App\Enums\Genero;
use Illuminate\Validation\Rule;

class ConvidadoController extends Controller
{
    use AuthorizesRequests;

    public function index(Casamento $casamento, Request $request)
    {
        $this->authorize('view', $casamento);

        $query = $casamento->convidados()->withCount('acompanhantes')->with('acompanhantes');

        if ($request->filled('status')) {
            $query->where('statusConvidado', $request->status);
        }

        if ($request->filled('busca')) {
            $query->where('nomeConvidado', 'like', '%' . $request->busca . '%');
        }

        $convidadosFiltrados = $query->orderBy('nomeConvidado')->get();

        $todosConvidados = $casamento->convidados()->with('acompanhantes')->get();
        $estatisticas = $this->estatisticasConvidados($todosConvidados);

        return view('convidados.index', compact('casamento', 'convidadosFiltrados', 'estatisticas'));

    }

    private function estatisticasConvidados($convidados): array
    {
        // soma o convidado principal (se tiver o status) + acompanhantes com o status
        $pessoasComStatus = fn(string $status) => $convidados->sum(
            fn($c) => ($c->statusConvidado === $status ? 1 : 0)
                + $c->acompanhantes->where('statusAcompanhante', $status)->count()
        );

        $faixasComStatus = fn(array $status) => Convidado::resumoFaixas(
            $convidados->flatMap(fn(Convidado $c) => $c->faixasEtariasComStatus($status))
        );


        return [
            'totalConvidados' => $convidados->count(),
            'totalPessoas' => $convidados->sum(fn($c) => 1 + $c->acompanhantes->count()),
            'confirmados' => $convidados->where('statusConvidado', 'CONFIRMADO')->count(),
            'totalPessoasConfirmadas' => $pessoasComStatus('CONFIRMADO'),
            'pendentes' => $convidados->where('statusConvidado', 'PENDENTE')->count(),
            'totalPessoasPendentes' => $pessoasComStatus('PENDENTE'),
            'recusados' => $convidados->where('statusConvidado', 'RECUSADO')->count(),
            'totalPessoasRecusadas' => $pessoasComStatus('RECUSADO'),

            'faixas' => [
                '' => $faixasComStatus(['PENDENTE', 'CONFIRMADO', 'RECUSADO']),
                'CONFIRMADO' => $faixasComStatus(['CONFIRMADO']),
                'PENDENTE' => $faixasComStatus(['PENDENTE']),
                'RECUSADO' => $faixasComStatus(['RECUSADO']),
            ],
        ];
    }

    public function create(Casamento $casamento)
    {
        $this->authorize('view', $casamento);

        $convidados = $casamento->convidados()->withCount('acompanhantes')->latest()->get();

        $todosConvidados = $casamento->convidados()->with('acompanhantes')->get();
        $estatisticas = $this->estatisticasConvidados($todosConvidados);

        return view('convidados.create', compact('casamento', 'convidados', 'estatisticas'));
    }

    public function store(Request $request, Casamento $casamento)
    {
        $this->authorize('view', $casamento);

        $request->validate([
            'nomeConvidado' => ['required', 'string', 'max:255'],
            'telefoneConvidado' => ['nullable', 'string'],
            'faixaEtariaConvidado' => ['nullable', Rule::enum(FaixaEtaria::class)],
            'generoConvidado' => ['nullable', Rule::enum(Genero::class)],
            'acompanhantes' => ['nullable', 'array'],
            'acompanhantes.*.nome' => ['required', 'string', 'max:255'],
            'acompanhantes.*.faixaEtaria' => ['nullable', Rule::enum(FaixaEtaria::class)],
            'acompanhantes.*.genero' => ['nullable', Rule::enum(Genero::class)],
        ], [
            'nomeConvidado.required' => 'O nome do convidado é obrigatório.',
            'acompanhantes.*.nome.required' => 'Informe o nome de todos os acompanhantes adicionados.',
            'acompanhantes.*.faixaEtaria.enum' => 'A faixa etária do acompanhante é inválida.',
            'acompanhantes.*.genero.enum' => 'O gênero do acompanhante é inválido.',
        ]);

        $acompanhantes = $request->input('acompanhantes', []);

        $convidado = $casamento->convidados()->create([
            'nomeConvidado' => $request->nomeConvidado,
            'telefoneConvidado' => $request->telefoneConvidado,
            'faixaEtariaConvidado' => $request->faixaEtariaConvidado,
            'generoConvidado' => $request->generoConvidado,
            'quantidadeMaxAcompanhantes' => count($acompanhantes),
        ]);

        foreach ($acompanhantes as $acompanhante) {
            $convidado->acompanhantes()->create([
                'nomeAcompanhante' => $acompanhante['nome'],
                'faixaEtariaAcompanhante' => $acompanhante['faixaEtaria'] ?? null,
                'generoAcompanhante' => $acompanhante['genero'] ?? null,
            ]);
        }

        return redirect()->route('convidado.create', $casamento)->with('sucesso', 'Convidado adicionado com sucesso!');
    }

    public function destroy(Casamento $casamento, Convidado $convidado)
    {
        $this->authorize('view', $casamento);
        abort_unless($convidado->casamento_id === $casamento->id, 404);

        $convidado->delete();

        return back()->with('sucesso', 'Convidado removido com sucesso!');
    }

    public function modelo(Casamento $casamento)
    {
        $this->authorize('view', $casamento);

        return Excel::download(new ModeloConvidadosExport, 'modelo-convidados.xlsx');
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
            'faixaEtariaConvidado' => ['nullable', Rule::enum(FaixaEtaria::class)],
            'generoConvidado' => ['nullable', Rule::enum(Genero::class)],
            'observacoesConfirmacao' => ['nullable', 'string'],
            'acompanhantes' => ['nullable', 'array'],
            'acompanhantes.*.id' => ['nullable', 'integer'],
            'acompanhantes.*.nome' => ['required', 'string', 'max:255'],
            'acompanhantes.*.faixaEtaria' => ['nullable', Rule::enum(FaixaEtaria::class)],
            'acompanhantes.*.genero' => ['nullable', Rule::enum(Genero::class)],
            'acompanhantes.*.status' => ['nullable', 'in:PENDENTE,CONFIRMADO,RECUSADO']
        ], [
            'nomeConvidado.required' => 'O nome do convidado é obrigatório.',
            'statusConvidado.required' => 'O status é obrigatório.',
            'statusConvidado.in' => 'Status inválido.',
            'acompanhantes.*.nome.required' => 'Informe o nome de todos os acompanhantes adicionados.',
        ]);

        $acompanhantes = $request->input('acompanhantes', []);

        $convidado->update([
            'nomeConvidado' => $request->nomeConvidado,
            'telefoneConvidado' => $request->telefoneConvidado,
            'statusConvidado' => $request->statusConvidado,
            'faixaEtariaConvidado' => $request->faixaEtariaConvidado,
            'generoConvidado' => $request->generoConvidado,
            'observacoesConfirmacao' => $request->observacoesConfirmacao,
            'quantidadeMaxAcompanhantes' => count($acompanhantes),
        ]);

        $idsMantidos = [];

        foreach ($acompanhantes as $dados) {
            $acompanhante = $convidado->acompanhantes()->updateOrCreate(
                ['id' => $dados['id'] ?? null],
                [
                    'nomeAcompanhante' => $dados['nome'],
                    'faixaEtariaAcompanhante' => $dados['faixaEtaria'] ?? null,
                    'generoAcompanhante' => $dados['genero'] ?? null,
                    'statusAcompanhante' => $dados['status'] ?? 'PENDENTE',
                ]
            );

            $idsMantidos[] = $acompanhante->id;
        }

        $convidado->acompanhantes()->whereNotIn('id', $idsMantidos)->delete();

        return redirect()->route('convidado.index', $casamento)->with('sucesso', 'Convidado atualizado com sucesso!');
    }

    // Confirmação
    public function confirmar(string $token)
    {
        $convidado = Convidado::with(['casamento', 'acompanhantes']) // eager loading
            ->where('tokenConfirmacao', $token)
            ->firstOrFail();

        return view('convidados.confirmar', compact('convidado'));
    }

    public function salvarConfirmacao(Request $request, string $token)
    {
        $convidado = Convidado::with('acompanhantes')
            ->where('tokenConfirmacao', $token)
            ->firstOrFail();

        $request->validate([
            'convidadoVai' => ['nullable', 'boolean'],
            'acompanhantesQueVao' => ['nullable', 'array'],
            'acompanhantesQueVao.*' => ['integer'],
            'observacoesConfirmacao' => ['nullable', 'string', 'max:1000'],
        ]);

        $idsQueVao = $request->input('acompanhantesQueVao', []);

        $principalVai = $request->boolean('convidadoVai');

        $convidado->update([
            'statusConvidado' => $principalVai ? 'CONFIRMADO' : 'RECUSADO',
            'observacoesConfirmacao' => $request->observacoesConfirmacao,
            'dataConfirmacao' => now(),
        ]);

        // percorre só os acompanhantes DESSE convidado
        foreach ($convidado->acompanhantes as $acompanhante) {
            $acompanhante->update([
                'statusAcompanhante' => in_array($acompanhante->id, $idsQueVao) ? 'CONFIRMADO' : 'RECUSADO',
            ]);
        }

        $totalConfirmados = ($principalVai ? 1 : 0) + $convidado->acompanhantes->where('statusAcompanhante', 'CONFIRMADO')->count();

        return back()->with(
            'sucesso',
            $totalConfirmados > 0
            ? 'Presença confirmada. Nos vemos no grande dia!'
            : 'Resposta registrada. Obrigado por avisar!'
        );
    }
}
