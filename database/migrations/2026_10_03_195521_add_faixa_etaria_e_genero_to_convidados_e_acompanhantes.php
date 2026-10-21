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
        Schema::table('convidados', function (Blueprint $table) {
            $table->dropColumn('alergiasConvidado');
            $table->string('faixaEtariaConvidado')->nullable()->after('telefoneConvidado');
            $table->string('generoConvidado')->nullable()->after('faixaEtariaConvidado');
        });

        Schema::table('acompanhantes', function (Blueprint $table) {
            $table->dropColumn(['idadeAcompanhante', 'alergiasAcompanhante']);
            $table->string('faixaEtariaAcompanhante')->nullable()->after('nomeAcompanhante');
            $table->string('generoAcompanhante')->nullable()->after('faixaEtariaAcompanhante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acompanhantes', function (Blueprint $table) {
            $table->dropColumn(['faixaEtariaAcompanhante', 'generoAcompanhante']);
            $table->unsignedTinyInteger('idadeAcompanhante')->nullable()->after('nomeAcompanhante');
        });

        Schema::table('convidados', function (Blueprint $table) {
            $table->dropColumn(['faixaEtariaConvidado', 'generoConvidado']);
        });
    }

};
