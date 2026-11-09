<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Casamento;
use App\Models\FornecedorCasamento;
use App\Models\Pagamento;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PagamentoController extends Controller
{
    use AuthorizesRequests;

    public function gerar(Request $request, Casamento $casamento, FornecedorCasamento $fornecedor)
    {
        $this->autorizar($casamento, $fornecedor);

        if ($fornecedor->pagamentos()->exists()) {
            return back()->withErrors(['parcelas' => 'Este fornecedor já possui parcelas. Use "Refazer parcelas" para gerar de novo.']);
        }

        $dados = $request->validate([
            'totalParcelas' => ['required', 'integer', 'min:1', 'max:60'],
            'primeiroVencimento' => ['required', 'date'],
        ], [
            'totalParcelas.required' => 'Informe o número de parcelas.',
            'totalParcelas.min' => 'O número de parcelas deve ser de pelo menos 1.',
            'totalParcelas.max' => 'O número de parcelas deve ser de no máximo 60.',
            'primeiroVencimento.required' => 'Informe a data do primeiro vencimento.',
            'primeiroVencimento.date' => 'Data de vencimento inválida.',
        ]);

        $quantidade = (int) $dados['totalParcelas'];

        // trabalha em centavos pra n perder dinheiro no arredondamento
        $totalCentavos = (int) round($fornecedor->valorTotalFornecedorCasamento * 100);
        $parcelaCentavos = intdiv($totalCentavos, $quantidade);
        $sobraCentavos = $totalCentavos - ($parcelaCentavos * $quantidade);

        $primeiroVencimento = Carbon::parse($dados['primeiroVencimento']);

        DB::transaction(function () use ($fornecedor, $quantidade, $parcelaCentavos, $sobraCentavos, $primeiroVencimento) {
            for ($numero = 1; $numero <= $quantidade; $numero++) {
                // a última parcela absorve a sobra dos centavos
                $centavos = $numero === $quantidade ? $parcelaCentavos + $sobraCentavos : $parcelaCentavos;

                $fornecedor->pagamentos()->create([
                    'numeroParcela' => $numero,
                    'valorParcela' => $centavos / 100,
                    'statusPagamento' => 'PENDENTE',
                    'dataVencimento' => $primeiroVencimento->copy()->addMonthsNoOverflow($numero - 1),
                ]);
            }

            $fornecedor->update(['totalParcelas' => $quantidade]);
        });

        return redirect()->route('fornecedor-casamento.index', $casamento)
            ->with('sucesso', 'Parcelas geradas com sucesso!');
    }

    // garante que o usuário pode ver o casamento e que fornecedor/parcela pertencem a ele
    private function autorizar(Casamento $casamento, FornecedorCasamento $fornecedor, ?Pagamento $pagamento = null): void
    {
        $this->authorize('view', $casamento);
        abort_unless($fornecedor->casamento_id === $casamento->id, 404);

        if ($pagamento) {
            abort_unless($pagamento->fornecedor_casamento_id === $fornecedor->id, 404);
        }
    }

    public function pagar(Request $request, Casamento $casamento, FornecedorCasamento $fornecedor, Pagamento $pagamento)
    {
        $this->autorizar($casamento, $fornecedor, $pagamento);

        $dados = $request->validate([
            'dataPagamento' => ['nullable', 'date', 'before_or_equal:today'],
        ], [
            'dataPagamento.date' => 'Data de pagamento inválida.',
            'dataPagamento.before_or_equal' => 'A data de pagamento não pode ser no futuro.',
        ]);

        $pagamento->update([
            'statusPagamento' => 'PAGO',
            'dataPagamento' => $dados['dataPagamento'] ?? now()->toDateString(),
        ]);

        return back()->with('sucesso', 'Parcela marcada como paga!');
    }

    public function desfazer(Casamento $casamento, FornecedorCasamento $fornecedor, Pagamento $pagamento)
    {
        $this->autorizar($casamento, $fornecedor, $pagamento);

        $pagamento->update([
            'statusPagamento' => 'PENDENTE',
            'dataPagamento' => null,
        ]);

        return back()->with('sucesso', 'Pagamento desfeito.');
    }

    public function reiniciar(Casamento $casamento, FornecedorCasamento $fornecedor)
    {
        $this->autorizar($casamento, $fornecedor);

        $fornecedor->pagamentos()->delete();

        return back()->with('sucesso', 'Parcelas removidas. Você pode gerar novas.');
    }    
}
