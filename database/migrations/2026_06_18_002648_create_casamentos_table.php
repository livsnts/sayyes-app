<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('casamentos', function (Blueprint $table) {
            $table->id();
            $table->enum('statusCasamento', ['ATIVO', 'REALIZADO', 'CANCELADO', 'INATIVO'])->default('ATIVO');
            $table->string('nomeCasamento');
            $table->date('dataCasamento');
            $table->decimal('orcamentoTotal', 10, 2)->nullable();
            $table->string('localCasamento')->nullable();
            $table->text('descricaoCasamento')->nullable();
            $table->binary('imagemCasamento')->nullable();
            $table->text('urlListaDePresentes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casamentos');
    }
};
