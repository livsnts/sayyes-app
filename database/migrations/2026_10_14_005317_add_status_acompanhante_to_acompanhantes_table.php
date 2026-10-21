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
        Schema::table('acompanhantes', function (Blueprint $table) {
            $table->enum('statusAcompanhante', ['PENDENTE', 'CONFIRMADO', 'RECUSADO'])
                ->default('PENDENTE')
                ->after('generoAcompanhante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            $table->dropColumn('statusAcompanhante');
        });
    }
};
