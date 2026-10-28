<?php

namespace App\Http\Controllers;

use App\Enums\CategoriaFornecedor;
use App\Enums\TipoValorFornecedor;
use App\Models\Casamento;
use App\Models\FornecedorCasamento;
use App\Models\FornecedorConfianca;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FornecedorCasamentoController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, Casamento $casamento)
    {

        $this->authorize('view', $casamento);

        $query = $casamento->fornecedores();

        if ($request->filled('categoria')) {
            $query->where('categoriaFornecedorCasamento', $request->categoria);
        }

        $fornecedores = $query->orderBy('nomeFornecedorCasamento')->get();

        $pessoas = $casamento->contagemPessoas();

        // ignora o filtro de categoria
        $totalContratado = $casamento->fornecedores()
            ->where('statusFornecedorCasamento', 'CONTRATADO')
            ->sum('valorTotalFornecedorCasamento');

        $disponivel = $casamento->orcamentoTotal - $totalContratado;

        // sugestões herdadas dos fornecedores de confiança do assessor da equipe
        $sugestoes = collect();
        if ($casamento->herdaFornecedoresAssessor) {
            // pega os membros da equipe que são assessores e devolve só os ids deles, depois busca os fornecedores de confiança cujo dono (user_id) está nessa lista
            $sugestoes = FornecedorConfianca::whereIn('user_id', $casamento->assessores()->pluck('users.id'))
                ->whereNotIn('id', $casamento->fornecedores()->whereNotNull('fornecedor_confianca_id')->pluck('fornecedor_confianca_id'))
                ->orderBy('nomeFornecedorConfianca')
                ->get();
        }

        return view('fornecedores-casamento.index', compact(
            'casamento',
            'fornecedores',
            'pessoas',
            'totalContratado',
            'disponivel',
            'sugestoes'
        ));

    }

    public function store(Request $request, Casamento $casamento)
    {
        $this->authorize('view', $casamento);

        $dados = $this->validar($request);

        // só aceita o vínculo se o casamento herda E o fornecedor é de um assessor da equipe
        $fornecedorConfiancaId = null;
        if ($casamento->herdaFornecedoresAssessor && $request->filled('fornecedor_confianca_id')) {
            $fornecedorConfiancaId = FornecedorConfianca::whereIn('user_id', $casamento->assessores()->pluck('users.id'))
                ->find($request->fornecedor_confianca_id)?->id;
        }

        $casamento->fornecedores()->create($this->montarDados($dados) + [
            'fornecedor_confianca_id' => $fornecedorConfiancaId,
        ]);

        return redirect()->route('fornecedor-casamento.index', $casamento)
            ->with('sucesso', 'Fornecedor adicionado com sucesso!');
    }

    public function update(Request $request, Casamento $casamento, FornecedorCasamento $fornecedor)
    {
        $this->authorize('view', $casamento);
        abort_unless($fornecedor->casamento_id === $casamento->id, 404);

        $fornecedor->update($this->montarDados($this->validar($request)));

        return redirect()->route('fornecedor-casamento.index', $casamento)
            ->with('sucesso', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Casamento $casamento, FornecedorCasamento $fornecedor)
    {
        $this->authorize('view', $casamento);
        abort_unless($fornecedor->casamento_id === $casamento->id, 404);

        $fornecedor->delete();

        return back()->with('sucesso', 'Fornecedor removido com sucesso!');
    }

    private function validar(Request $request): array
    {
        // converte preço com máscara em decimal antes de validar
        foreach (['valorTotal', 'valorAdulto', 'valorCrianca'] as $campo) {
            $request->merge([$campo => $this->converterValor($request->input($campo))]);
        }

        return $request->validate([
            'nomeFornecedorCasamento' => ['required', 'string', 'max:255'],
            'categoriaFornecedorCasamento' => ['required', Rule::enum(CategoriaFornecedor::class)],
            'statusFornecedorCasamento' => ['required', Rule::in(array_keys(FornecedorCasamento::STATUS))],
            'tipoValorFornecedorCasamento' => ['required', Rule::enum(TipoValorFornecedor::class)],
            'valorTotal' => ['required', 'numeric', 'min:0'],
            'valorAdulto' => ['required_if:tipoValorFornecedorCasamento,POR_PESSOA', 'nullable', 'numeric', 'min:0'],
            'valorCrianca' => ['required_if:tipoValorFornecedorCasamento,POR_PESSOA', 'nullable', 'numeric', 'min:0'],
            'observacoesFornecedorCasamento' => ['nullable', 'string', 'max:1000'],
        ], [
            'nomeFornecedorCasamento.required' => 'O nome do fornecedor é obrigatório.',
            'categoriaFornecedorCasamento.required' => 'Escolha a categoria do fornecedor.',
            'valorTotal.required' => 'Informe o valor total.',
            'valorTotal.numeric' => 'O valor total deve ser um número.',
            'valorAdulto.required_if' => 'Informe o valor por adulto.',
            'valorCrianca.required_if' => 'Informe o valor por criança (pode ser R$ 0,00).',
            'valorAdulto.numeric' => 'O valor por adulto deve ser um número.',
            'valorCrianca.numeric' => 'O valor por criança deve ser um número.',
        ]);
    }

    private function converterValor(?string $valor): ?string
    {
        if (blank($valor)) {
            return null;
        }

        return str_replace(['R$ ', '.', ','], ['', '', '.'], $valor);
    }

    // guarda só os campos do tipo escolhido; os outros ficam nulos
    private function montarDados(array $dados): array
    {
        $porPessoa = $dados['tipoValorFornecedorCasamento'] === TipoValorFornecedor::PorPessoa->value;

        return [
            'nomeFornecedorCasamento' => $dados['nomeFornecedorCasamento'],
            'categoriaFornecedorCasamento' => $dados['categoriaFornecedorCasamento'],
            'statusFornecedorCasamento' => $dados['statusFornecedorCasamento'],
            'tipoValorFornecedorCasamento' => $dados['tipoValorFornecedorCasamento'],
            'valorTotalFornecedorCasamento' => $dados['valorTotal'],
            'valorAdultoFornecedorCasamento' => $porPessoa ? $dados['valorAdulto'] : null,
            'valorCriancaFornecedorCasamento' => $porPessoa ? $dados['valorCrianca'] : null,
            'observacoesFornecedorCasamento' => $dados['observacoesFornecedorCasamento'] ?? null,
        ];
    }


}




