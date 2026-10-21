<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\FaixaEtaria;
use App\Enums\Genero;

#[Fillable([
    'convidado_id',
    'nomeAcompanhante',
    'faixaEtariaAcompanhante',
    'generoAcompanhante',
    'statusAcompanhante'
])]
class Acompanhante extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'faixaEtariaAcompanhante' => FaixaEtaria::class,
            'generoAcompanhante' => Genero::class,
        ];
    }

    public function convidado(): BelongsTo
    {
        return $this->belongsTo(Convidado::class);
    }
}
