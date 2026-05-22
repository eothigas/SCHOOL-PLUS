<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('usuario_id')->unique();
            $table->string('matricula', 30);
            $table->date('data_nascimento')->nullable();
            $table->enum('sexo', ['M', 'F', 'outro', 'nao_informado'])->nullable();
            $table->string('rg', 20)->nullable();
            $table->string('cpf', 14)->nullable();
            $table->string('naturalidade', 100)->nullable();
            $table->string('nacionalidade', 100)->default('Brasileira');
            $table->string('nome_pai', 150)->nullable();
            $table->string('nome_mae', 150)->nullable();
            $table->string('endereco', 300)->nullable();
            $table->string('cidade', 100)->nullable();
            $table->char('estado', 2)->nullable();
            $table->string('cep', 10)->nullable();
            $table->tinyInteger('necessidade_especial')->default(0);
            $table->string('desc_necessidade', 300)->nullable();
            $table->timestamp('criado_em')->useCurrent();

            $table->unique(['matricula', 'tenant_id'], 'uq_matricula_tenant');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
