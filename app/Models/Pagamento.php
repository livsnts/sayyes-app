<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'fornecedor_casamento_id',
    'statusPagamento',
    'valorParcela',
    'dataVencimento',
    'dataPagamento',
    'numeroParcela',
    'observacao',
])]

class Pagamento extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'valorParcela'   => 'decimal:2',
            'dataVencimento' => 'date',
            'dataPagamento'  => 'date',
        ];
    }

    public function statusReal(): string 
    {
        if($this->statusPagamento === 'PAGO') {
            return 'PAGO';
        }

        if($this->dataVencimento && $this->dataVencimento->isPast()) {
            return 'ATRASADO';
        }

        return 'PENDENTE';
    }

    public function fornecedorCasamento(): BelongsTo
    {
        return $this->belongsTo(FornecedorCasamento::class);
    }
}
