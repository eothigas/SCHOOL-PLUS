<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documentos_aluno', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('aluno_id');
            $table->enum('tipo', ['rg', 'cpf', 'comprovante_residencia', 'foto', 'historico', 'declaracao', 'outro']);
            $table->string('nome', 200)->nullable();
            $table->string('arquivo_url', 500)->nullable();
            $table->timestamp('criado_em')->useCurrent();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('aluno_id')->references('id')->on('alunos')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_aluno');
    }
};
