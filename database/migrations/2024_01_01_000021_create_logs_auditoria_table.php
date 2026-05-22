<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('logs_auditoria', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id')->nullable();
            $table->unsignedInteger('usuario_id')->nullable();
            $table->string('acao', 100);
            $table->string('tabela', 100)->nullable();
            $table->unsignedInteger('registro_id')->nullable();
            $table->json('dados_antes')->nullable();
            $table->json('dados_depois')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 300)->nullable();
            $table->timestamp('criado_em')->useCurrent();

            $table->index(['tenant_id', 'acao'], 'idx_tenant_acao');
            $table->index('criado_em', 'idx_criado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_auditoria');
    }
};
