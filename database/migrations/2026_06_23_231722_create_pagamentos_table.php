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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_casamento_id')->constrained('fornecedor_casamentos')->cascadeOnDelete();
            $table->enum('statusPagamento', ['PENDENTE', 'PAGO'])->default('PENDENTE');
            $table->decimal('valorParcela', 10, 2);
            $table->date('dataVencimento')->nullable();
            $table->date('dataPagamento')->nullable();
            $table->unsignedInteger('numeroParcela')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
