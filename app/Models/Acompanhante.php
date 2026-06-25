<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'convidado_id',
    'nomeAcompanhante',
    'alergiasAcompanhante',
])]
class Acompanhante extends Model
{
    use HasFactory;

    public function convidado(): BelongsTo
    {
        return $this->belongsTo(Convidado::class);
    }
}
