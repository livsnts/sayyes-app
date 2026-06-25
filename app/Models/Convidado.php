<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'casamento_id',
    'statusConvidado',
    'nomeConvidado',
    'telefoneConvidado',
    'quantidadeMaxAcompanhantes',
    'dataConfirmacao',
    'observacoesConfirmacao',
    'alergiasConvidado',
])]

class Convidado extends Model
{
    use HasFactory;

    protected static function booted(): void 
    {
        static::creating(function (Convidado $convidado) {
            if(empty($convidado->tokenConfirmacao)){
                $convidado->tokenConfirmacao = (string) Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'dataConfirmacao' => 'datetime',
        ];
    }

    public function casamento(): BelongsTo
    {
        return $this->belongsTo(Casamento::class);
    }

    public function acompanhantes(): HasMany
    {
        return $this->hasMany(Acompanhante::class);
    }
}
