<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('aluno_id');
            $table->unsignedInteger('turma_id');
            $table->unsignedInteger('periodo_id');
            $table->date('data_matricula');
            $table->enum('status', ['ativa', 'trancada', 'cancelada', 'concluida', 'transferida'])->default('ativa');
            $table->text('obs')->nullable();
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('atualizado_em')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('aluno_id')->references('id')->on('alunos');
            $table->foreign('turma_id')->references('id')->on('turmas');
            $table->foreign('periodo_id')->references('id')->on('periodos_letivos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
