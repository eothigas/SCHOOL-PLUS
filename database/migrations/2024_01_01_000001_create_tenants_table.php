<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('nome', 200);
            $table->enum('tipo', ['escola', 'faculdade', 'curso_livre', 'tecnico']);
            $table->string('cnpj', 18)->unique()->nullable();
            $table->string('email', 150)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->string('endereco', 300)->nullable();
            $table->string('cidade', 100)->nullable();
            $table->char('estado', 2)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->enum('plano', ['basico', 'profissional', 'enterprise'])->default('basico');
            $table->enum('status', ['ativo', 'suspenso', 'cancelado'])->default('ativo');
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('atualizado_em')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
