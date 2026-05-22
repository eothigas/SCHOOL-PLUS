<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cobrancas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('matricula_id');
            $table->unsignedInteger('plano_id')->nullable();
            $table->string('descricao', 200);
            $table->decimal('valor_original', 10, 2);
            $table->decimal('valor_desconto', 10, 2)->default(0);
            $table->decimal('valor_acrescimo', 10, 2)->default(0);
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->string('competencia', 7)->nullable();
            $table->enum('status', ['aberta', 'paga', 'vencida', 'cancelada', 'negociada'])->default('aberta');
            $table->enum('forma_pagamento', ['dinheiro', 'pix', 'boleto', 'cartao_credito', 'cartao_debito', 'transferencia'])->nullable();
            $table->string('gateway_id', 100)->nullable();
            $table->string('gateway_url', 500)->nullable();
            $table->text('obs')->nullable();
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('atualizado_em')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('matricula_id')->references('id')->on('matriculas');
            $table->foreign('plano_id')->references('id')->on('planos_pagamento')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cobrancas');
    }
};
