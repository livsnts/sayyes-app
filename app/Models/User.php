<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'telefoneUsuario', 'cpfUsuario', 'sexoUsuario', 'cepUsuario', 'cidadeUsuario', 'tipoUsuario', 'statusUsuario', 'descricaoUsuario'])]
#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable implements CanResetPassword
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function casamentos(): BelongsToMany
    {
        return $this->belongsToMany(Casamento::class);
    }

    public function fornecedoresConfianca(): HasMany
    {
        return $this->hasMany(FornecedorConfianca::class);
    }
}
