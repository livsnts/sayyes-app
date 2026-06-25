<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CategoriaFornecedor;
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
    'valorTotalFornecedorCasamento',
    'contratoFornecedorCasamento',
    'observacoesFornecedorCasamento',
    'totalParcelas',
])]
class FornecedorCasamento extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'categoriaFornecedorCasamento' => CategoriaFornecedor::class,
            'valorTotalFornecedorCasamento' => 'decimal:2'
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
