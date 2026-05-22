<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->string('nome', 150);
            $table->string('email', 150);
            $table->string('senha_hash', 255);
            $table->enum('perfil', ['admin', 'secretaria', 'professor', 'aluno', 'responsavel', 'financeiro']);
            $table->string('cpf', 14)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('foto_url', 500)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->dateTime('ultimo_login')->nullable();
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('atualizado_em')->useCurrent()->useCurrentOnUpdate();

            $table->unique(['email', 'tenant_id'], 'uq_email_tenant');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
