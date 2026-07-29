<?php

namespace App\Imports;

use App\Models\Casamento;
use App\Models\Convidado;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class ConvidadosImport implements ToCollection, WithHeadingRow
{
    public function __construct(public Casamento $casamento) {}

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            if (empty($row['nome'])) {
                continue;
            }

            $this->casamento->convidados()->create([
                'nomeConvidado'              => $row['nome'],
                'telefoneConvidado'          => $row['telefone'] ?? null,
                'quantidadeMaxAcompanhantes' => $row['acompanhantes'] ?? 0,
            ]);
        }
    }
}