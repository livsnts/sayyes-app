<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('convidados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casamento_id')->constrained('casamentos')->cascadeOnDelete();
            $table->enum('statusConvidado', ['PENDENTE', 'CONFIRMADO', 'RECUSADO'])->default('PENDENTE');
            $table->string('nomeConvidado');
            $table->string('telefoneConvidado')->nullable();
            $table->uuid('tokenConfirmacao')->unique();
            $table->unsignedInteger('quantidadeMaxAcompanhantes')->default(0);
            $table->dateTime('dataConfirmacao')->nullable();
            $table->text('observacoesConfirmacao')->nullable();
            $table->text('alergiasConvidado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convidados');
    }
};
