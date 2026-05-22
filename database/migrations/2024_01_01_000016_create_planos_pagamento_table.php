<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('planos_pagamento', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('curso_id')->nullable();
            $table->string('nome', 150);
            $table->enum('tipo', ['mensal', 'semestral', 'anual', 'avulso'])->default('mensal');
            $table->decimal('valor', 10, 2);
            $table->unsignedTinyInteger('dia_vencimento')->default(10);
            $table->decimal('desconto_pct', 5, 2)->default(0);
            $table->decimal('multa_pct', 5, 2)->default(2.00);
            $table->decimal('juros_dia_pct', 5, 4)->default(0.0333);
            $table->tinyInteger('status')->default(1);

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('curso_id')->references('id')->on('cursos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planos_pagamento');
    }
};
