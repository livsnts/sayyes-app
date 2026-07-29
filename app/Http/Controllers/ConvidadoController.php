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

        $convidados = $casamento->convidados()->orderBy('nomeConvidado')->get();

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

        $convidados = $casamento->convidados()->orderBy('nomeConvidado')->get();

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
}
