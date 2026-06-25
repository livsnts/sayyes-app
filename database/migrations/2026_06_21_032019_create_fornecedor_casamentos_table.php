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
        Schema::create('fornecedor_casamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casamento_id')->constrained('casamentos')->cascadeOnDelete();
            $table->foreignId('fornecedor_confianca_id')->nullable()->constrained('fornecedor_confiancas')->nullOnDelete();
            $table->enum('statusFornecedorCasamento', ['EM_PESQUISA', "EM_NEGOCIACAO", 'CONTRATADO', 'CANCELADO'])->default('EM_PESQUISA');
            $table->string('nomeFornecedorCasamento');
            $table->string('categoriaFornecedorCasamento')->nullable();
            $table->decimal('valorTotalFornecedorCasamento', 10, 2)->default(0);
            $table->string('contratoFornecedorCasamento')->nullable();
            $table->text('observacoesFornecedorCasamento')->nullable();
            $table->unsignedInteger('totalParcelas')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fornecedor_casamentos');
    }
};
