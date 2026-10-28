<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CategoriaFornecedor;
use App\Enums\TipoValorFornecedor;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'casamento_id',
    'fornecedor_confianca_id',
    'statusFornecedorCasamento',
    'nomeFornecedorCasamento',
    'categoriaFornecedorCasamento',
    'tipoValorFornecedorCasamento',
    'valorTotalFornecedorCasamento',
    'valorAdultoFornecedorCasamento',
    'valorCriancaFornecedorCasamento',
    'valorTotalFornecedorCasamento',
    'contratoFornecedorCasamento',
    'observacoesFornecedorCasamento',
    'totalParcelas',
])]
class FornecedorCasamento extends Model
{
    use HasFactory;

    public const STATUS = [
        'EM_PESQUISA' => 'Em pesquisa',
        'EM_NEGOCIACAO' => 'Em negociação',
        'CONTRATADO' => 'Contratado',
        'CANCELADO' => 'Cancelado',
    ];

    protected function casts(): array
    {
        return [
            'categoriaFornecedorCasamento' => CategoriaFornecedor::class,
            'tipoValorFornecedorCasamento' => TipoValorFornecedor::class,
            'valorTotalFornecedorCasamento' => 'decimal:2',
            'valorAdultoFornecedorCasamento' => 'decimal:2',
            'valorCriancaFornecedorCasamento' => 'decimal:2',
        ];
    }

    public function casamento(): BelongsTo
    {
        return $this->belongsTo(Casamento::class);
    }

    public function fornecedorConfianca(): BelongsTo
    {
        return $this->belongsTo(FornecedorConfianca::class);
    }

    public function orcamento(): HasOne
    {
        return $this->hasOne(OrcamentoFornecedor::class);
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class);
    }
}
