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
            if (Schema::hasColumn('fornecedor_casamentos', 'valorFixoFornecedorCasamento')) {
                $table->dropColumn('valorFixoFornecedorCasamento');
            }

            $table->string('telefoneFornecedorCasamento')->nullable()->after('categoriaFornecedorCasamento');
            $table->unsignedInteger('quantidadeAdultosFornecedorCasamento')->nullable()->after('valorCriancaFornecedorCasamento');
            $table->unsignedInteger('quantidadeCriancasFornecedorCasamento')->nullable()->after('quantidadeAdultosFornecedorCasamento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fornecedor_casamentos', function (Blueprint $table) {
            $table->dropColumn(['telefoneFornecedorCasamento', 'quantidadeAdultosFornecedorCasamento', 'quantidadeCriancasFornecedorCasamento']);
            $table->decimal('valorFixoFornecedorCasamento', 10, 2)->nullable();
        });
    }
};
