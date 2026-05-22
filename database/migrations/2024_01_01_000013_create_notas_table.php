<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('avaliacao_id');
            $table->unsignedInteger('matricula_id');
            $table->decimal('nota', 5, 2)->nullable();
            $table->string('obs', 300)->nullable();
            $table->timestamp('lancado_em')->useCurrent();

            $table->unique(['avaliacao_id', 'matricula_id'], 'uq_nota');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('avaliacao_id')->references('id')->on('avaliacoes')->cascadeOnDelete();
            $table->foreign('matricula_id')->references('id')->on('matriculas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
