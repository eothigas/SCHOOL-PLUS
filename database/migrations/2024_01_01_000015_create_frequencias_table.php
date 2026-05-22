<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('frequencias', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('aula_id');
            $table->unsignedInteger('matricula_id');
            $table->tinyInteger('presente')->default(1);
            $table->tinyInteger('justificada')->default(0);
            $table->string('obs', 300)->nullable();

            $table->unique(['aula_id', 'matricula_id'], 'uq_freq');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('aula_id')->references('id')->on('aulas')->cascadeOnDelete();
            $table->foreign('matricula_id')->references('id')->on('matriculas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frequencias');
    }
};
