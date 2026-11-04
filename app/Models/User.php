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
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

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

    public function sendPasswordResetNotification($token): void
{
    ResetPassword::toMailUsing(function ($notifiable, $token) {
        $url = route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('SayYes - Redefinição de senha')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Recebemos um pedido para redefinir a senha da sua conta no SayYes.')
            ->action('Redefinir minha senha', $url)
            ->line('Este link expira em 60 minutos.')
            ->line('Se você não fez esse pedido, ignore este e-mail. Nada será alterado.')
            ->salutation('Com carinho, equipe SayYes');
    });

    $this->notify(new ResetPassword($token));
}
}
