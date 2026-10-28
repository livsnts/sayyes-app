<?php

namespace App\Enums;

enum TipoValorFornecedor: string
{
    case Fixo = 'FIXO';
    case PorPessoa = 'POR_PESSOA';

    public function rotulo(): string
    {
        return match ($this) {
            self::Fixo => 'Valor fixo',
            self::PorPessoa => 'Valor por pessoa',
        };
    }
}
