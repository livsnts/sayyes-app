<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ModeloConvidadosExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['nome', 'telefone', 'acompanhantes'];
    }

    public function array(): array
    {
        return [
            ['Maria da Silva', '(11) 94002-8922', 1],
            ['João Pereira', '(21) 98564-1235', 0],
        ];
    }
}