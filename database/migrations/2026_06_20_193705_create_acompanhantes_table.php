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
        Schema::create('acompanhantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convidado_id')->constrained('convidados')->cascadeOnDelete();
            $table->string('nomeAcompanhante');
            $table->text('alergiasAcompanhante')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acompanhantes');
    }
};
