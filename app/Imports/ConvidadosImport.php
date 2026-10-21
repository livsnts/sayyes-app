<?php

namespace App\Imports;

use App\Models\Casamento;
use App\Models\Convidado;
use App\Enums\FaixaEtaria;
use App\Enums\Genero;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ConvidadosImport implements ToCollection, WithHeadingRow
{

    public const MAX_ACOMPANHANTES = 4;
    public function __construct(public Casamento $casamento)
    {
    }

    public function collection(Collection $linhas): void
    {
        foreach ($linhas as $linha) {
            if (empty($linha['nome'])) {
                continue;
            }

            $acompanhantes = [];

            for ($i = 1; $i <= self::MAX_ACOMPANHANTES; $i++) {
                if (blank($linha["acompanhante_{$i}_nome"] ?? null)) {
                    continue;
                }

                $acompanhantes[] = [
                    'nomeAcompanhante' => $linha["acompanhante_{$i}_nome"],
                    'faixaEtariaAcompanhante' => $this->converterEnum(FaixaEtaria::class, $linha["acompanhante_{$i}_faixa_etaria"] ?? null),
                    'generoAcompanhante' => $this->converterEnum(Genero::class, $linha["acompanhante_{$i}_genero"] ?? null),
                ];
            }

            $convidado = $this->casamento->convidados()->create([
                'nomeConvidado' => $linha['nome'],
                'telefoneConvidado' => $linha['telefone'] ?? null,
                'faixaEtariaConvidado' => $this->converterEnum(FaixaEtaria::class, $linha['faixa_etaria'] ?? null),
                'generoConvidado' => $this->converterEnum(Genero::class, $linha['genero'] ?? null),
                'quantidadeMaxAcompanhantes' => count($acompanhantes),
            ]);

            $convidado->acompanhantes()->createMany($acompanhantes);
        }
    }

    private function converterEnum(string $enum, mixed $texto): ?string
    {
        $texto = mb_strtolower(trim((string) $texto));

        foreach ($enum::cases() as $caso) {
            if (mb_strtolower($caso->value) === $texto) {
                return $caso->value;
            }
        }

        return null;
    }
}
