<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Remove a coluna legada: a quantidade de acompanhantes é contada pela relação.
     */
    public function up(): void
    {
        Schema::table('convidados', function (Blueprint $table) {
            $table->dropColumn('quantidadeMaxAcompanhantes');
        });
    }

    public function down(): void
    {
        Schema::table('convidados', function (Blueprint $table) {
            $table->unsignedInteger('quantidadeMaxAcompanhantes')->default(0);
        });
    }
};
