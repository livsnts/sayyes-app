<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CategoriaFornecedor;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'casamento_id',
    'fornecedor_casamento_id',
    'fornecedor_confianca_id',
    'categoriaFornecedor',
    'nomeFornecedor',
    'valorOrcado',
    'observacoes',
    'selecionado',
])]

class OrcamentoFornecedor extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'categoriaFornecedor' => CategoriaFornecedor::class,
            'valorOrcado'         => 'decimal:2',
            'selecionado'         => 'boolean',
        ];
    }

    public function casamento(): BelongsTo
    {
        return $this->belongsTo(Casamento::class);
    }

    public function fornecedorCasamento(): BelongsTo
    {
        return $this->belongsTo(FornecedorCasamento::class);
    }

    public function fornecedorConfianca (): BelongsTo
    {
        return $this->belongsTo(FornecedorConfianca::class);
    }
}
