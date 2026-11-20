<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Casamento;

#[Signature('casamentos:atualizar-status')]
#[Description('Muda para REALIZADO os casamentos ATIVOS cuja data já passou')]
class AtualizarStatusCasamentos extends Command
{
    public function handle()
    {
        // atualiza de uma vez só, direto no banco (uma única query)
        $quantidadeAtualizada = Casamento::where('statusCasamento', 'ATIVO')
            ->whereDate('dataCasamento', '<', today())
            ->update(['statusCasamento' => 'REALIZADO']);

        $this->info("{$quantidadeAtualizada} casamento(s) marcado(s) como REALIZADO.");

        return self::SUCCESS;
    }
}
