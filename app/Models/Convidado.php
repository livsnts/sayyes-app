<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Enums\FaixaEtaria;
use App\Enums\Genero;

#[Fillable([
    'casamento_id',
    'statusConvidado',
    'nomeConvidado',
    'telefoneConvidado',
    'quantidadeMaxAcompanhantes',
    'dataConfirmacao',
    'observacoesConfirmacao',
    'faixaEtariaConvidado',
    'generoConvidado',
])]

class Convidado extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Convidado $convidado) {
            if (empty($convidado->tokenConfirmacao)) {
                $convidado->tokenConfirmacao = (string) Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'dataConfirmacao' => 'datetime',
            'faixaEtariaConvidado' => FaixaEtaria::class,
            'generoConvidado' => Genero::class,
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

    public function linkConfirmacaoWhatsapp(string $nomeCasamento): string
    {
        $numero = preg_replace('/\D/', '', $this->telefoneConvidado);

        if (strlen($numero) <= 11) {
            $numero = '55' . $numero;
        }

        $link = route('convidado.confirmar', $this->tokenConfirmacao);
        $mensagem = "Olá, {$this->nomeConvidado}! Você foi convidado(a) para o {$nomeCasamento}. Confirme sua presença por aqui: {$link}";

        return 'https://wa.me/' . $numero . '?text=' . urlencode($mensagem);
    }


    // Faixas etárias das pessoas deste convite (convidado + acompanhantes)
    // com o status X. Faixa não preenchida vem como null.
    public function faixasEtariasComStatus(array $status): Collection
    {
        $faixas = collect();

        if (in_array($this->statusConvidado, $status, true)) {
            $faixas->push($this->faixaEtariaConvidado);
        }

        foreach ($this->acompanhantes->whereIn('statusAcompanhante', $status) as $acompanhante) {
            $faixas->push($acompanhante->faixaEtariaAcompanhante);
        }

        return $faixas;
    }


    // Resume uma lista de faixas em adultos, crianças (4 a 12) e bebês (0 a 3).
    // Faixa não preenchida conta como adulto.
    public static function resumoFaixas(Collection $faixas): array
    {
        $bebes = $faixas->filter(fn($faixa) => $faixa === FaixaEtaria::Bebe)->count();
        $criancas = $faixas->filter(
            fn($faixa) => $faixa !== null && $faixa !== FaixaEtaria::Bebe && $faixa->ehCrianca()
        )->count();

        return [
            'adultos' => $faixas->count() - $bebes - $criancas,
            'criancas' => $criancas,
            'bebes' => $bebes,
        ];
    }
}
