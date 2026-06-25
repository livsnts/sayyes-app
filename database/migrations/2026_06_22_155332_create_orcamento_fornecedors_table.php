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
        Schema::create('orcamento_fornecedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casamento_id')->constrained('casamentos')->cascadeOnDelete();
            $table->foreignId('fornecedor_casamento_id')->nullable()->constrained('fornecedor_casamentos')->nullOnDelete();
            $table->foreignId('fornecedor_confianca_id')->nullable()->constrained('fornecedor_confiancas')->nullOnDelete();
            $table->string('categoriaFornecedor')->nullable();
            $table->string('nomeFornecedor');
            $table->decimal('valorOrcado', 10, 2);
            $table->text('observacoes')->nullable();
            $table->boolean('selecionado')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamento_fornecedores');
    }
};
