<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'statusCasamento',
    'nomeCasamento',
    'dataCasamento',
    'orcamentoTotal',
    'localCasamento',
    'descricaoCasamento',
    'imagemCasamento',
    'urlListaDePresentes'
])]

class Casamento extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'dataCasamento' => 'date',
            'orcamentoTotal' => 'decimal:2',
        ];
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function convidados(): HasMany
    {
        return $this->hasMany(Convidado::class);
    }

    public function fornecedores(): HasMany
    {
        return $this->hasMany(FornecedorCasamento::class);
    }

    public function orcamentos(): HasMany
    {
        return $this->hasMany(OrcamentoFornecedor::class);
    }
}
