<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RelatorioTabelaExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    public function __construct(
        private array $cabecalhos,
        private array $linhas,
    ) {}

    public function headings(): array
    {
        return $this->cabecalhos;
    }

    public function array(): array
    {
        return $this->linhas;
    }

    public function styles(Worksheet $planilha): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0B1957']],
            ],
        ];
    }
}