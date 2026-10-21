<?php

namespace App\Exports;

use App\Imports\ConvidadosImport;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModeloConvidadosExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        $colunas = ['nome', 'telefone', 'faixa_etaria', 'genero'];

        for ($i = 1; $i <= ConvidadosImport::MAX_ACOMPANHANTES; $i++) {
            array_push(
                $colunas,
                "acompanhante_{$i}_nome",
                "acompanhante_{$i}_faixa_etaria",
                "acompanhante_{$i}_genero"
            );
        }

        return $colunas;
    }

    public function array(): array
    {
        $totalColunas = count($this->headings());

        $linhas = [
            [
                'Maria da Silva',
                '(11) 94002-8922',
                '13 anos ou mais',
                'Feminino',
                'Carlos da Silva',
                '13 anos ou mais',
                'Masculino',
                'Pedro da Silva',
                '4 a 7 anos',
                'Masculino',
            ],
            ['João Pereira', '(21) 98564-1235', '13 anos ou mais', 'Masculino'],
        ];
        return array_map(fn($linha) => array_pad($linha, $totalColunas, ''), $linhas);
    }
}