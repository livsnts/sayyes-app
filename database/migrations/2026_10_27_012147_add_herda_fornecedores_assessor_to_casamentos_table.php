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
        Schema::table('casamentos', function (Blueprint $table) {
            $table->boolean('herdaFornecedoresAssessor')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('casamentos', function (Blueprint $table) {
            $table->dropColumn('herdaFornecedoresAssessor');
        });
    }
};
