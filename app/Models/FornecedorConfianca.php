<?php

namespace App\Models;
use App\Enums\CategoriaFornecedor;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'nomeFornecedorConfianca',
    'categoriaFornecedorConfianca',
    'telefoneFornecedorConfianca',
    'instagramFornecedorConfianca',
])]

class FornecedorConfianca extends Model
{
    use HasFactory;

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'categoriaFornecedorConfianca' => CategoriaFornecedor::class,
        ];
    }

    public function fornecedorCasamentos(): HasMany
    {
        return $this->hasMany(FornecedorCasamento::class);
    }

    public function orcamentos(): HasMany
    {
        return $this->hasMany(OrcamentoFornecedor::class);
    }
}
