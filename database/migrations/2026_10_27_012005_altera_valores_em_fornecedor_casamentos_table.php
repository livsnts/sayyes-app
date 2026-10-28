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
        Schema::table('fornecedor_casamentos', function (Blueprint $table) {
            $table->string('tipoValorFornecedorCasamento')->default('FIXO')->after('categoriaFornecedorCasamento');
            $table->decimal('valorFixoFornecedorCasamento', 10, 2)->nullable()->after('tipoValorFornecedorCasamento');
            $table->decimal('valorAdultoFornecedorCasamento', 10, 2)->nullable()->after('valorFixoFornecedorCasamento');
            $table->decimal('valorCriancaFornecedorCasamento', 10, 2)->nullable()->after('valorAdultoFornecedorCasamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fornecedor_casamentos', function (Blueprint $table) {
            $table->dropColumn([
                'tipoValorFornecedorCasamento',
                'valorFixoFornecedorCasamento',
                'valorAdultoFornecedorCasamento',
                'valorCriancaFornecedorCasamento',
            ]);
        });
    }
};
