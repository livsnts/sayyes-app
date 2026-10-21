<?php

namespace App\Enums;

enum FaixaEtaria: string
{
    case Bebe = '0 a 3 anos';
    case Crianca = '4 a 7 anos';
    case Infantil = '8 a 12 anos';
    case Adulto = '13 anos ou mais';

    public function ehCrianca(): bool
    {
        return $this !== self::Adulto;
    }
}
