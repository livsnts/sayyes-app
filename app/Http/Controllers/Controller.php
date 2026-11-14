<?php

namespace App\Http\Controllers;

use App\Exports\RelatorioTabelaExport;
use Maatwebsite\Excel\Facades\Excel;

abstract class Controller
{
    protected function baixarRelatorio(string $titulo, array $cabecalhos, array $linhas)
    {
        $nomeArquivo = str($titulo)->slug() . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new RelatorioTabelaExport($cabecalhos, $linhas), $nomeArquivo);
    }
}
