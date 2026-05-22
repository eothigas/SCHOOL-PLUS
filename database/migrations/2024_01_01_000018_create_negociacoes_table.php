<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negociacoes', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('matricula_id');
            $table->unsignedInteger('usuario_id');
            $table->decimal('valor_total', 10, 2);
            $table->decimal('desconto_pct', 5, 2)->default(0);
            $table->unsignedTinyInteger('qtd_parcelas')->default(1);
            $table->text('obs')->nullable();
            $table->timestamp('criado_em')->useCurrent();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('matricula_id')->references('id')->on('matriculas');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negociacoes');
    }
};
