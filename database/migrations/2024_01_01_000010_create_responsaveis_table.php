<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('responsaveis', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('aluno_id');
            $table->string('nome', 150);
            $table->string('parentesco', 50)->nullable();
            $table->string('cpf', 14)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->unsignedInteger('usuario_id')->nullable();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('aluno_id')->references('id')->on('alunos')->cascadeOnDelete();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responsaveis');
    }
};
