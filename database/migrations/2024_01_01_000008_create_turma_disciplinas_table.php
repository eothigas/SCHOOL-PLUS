<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('turma_disciplinas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('turma_id');
            $table->unsignedInteger('disciplina_id');
            $table->unsignedInteger('professor_id')->nullable();

            $table->unique(['turma_id', 'disciplina_id'], 'uq_turma_disc');
            $table->foreign('turma_id')->references('id')->on('turmas')->cascadeOnDelete();
            $table->foreign('disciplina_id')->references('id')->on('disciplinas');
            $table->foreign('professor_id')->references('id')->on('usuarios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turma_disciplinas');
    }
};
