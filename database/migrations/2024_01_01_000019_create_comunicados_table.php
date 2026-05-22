<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comunicados', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('autor_id');
            $table->string('titulo', 200);
            $table->text('corpo');
            $table->enum('destino', ['todos', 'professores', 'alunos', 'responsaveis', 'turma'])->default('todos');
            $table->unsignedInteger('turma_id')->nullable();
            $table->tinyInteger('fixado')->default(0);
            $table->tinyInteger('publicado')->default(1);
            $table->timestamp('criado_em')->useCurrent();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('autor_id')->references('id')->on('usuarios');
            $table->foreign('turma_id')->references('id')->on('turmas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comunicados');
    }
};
