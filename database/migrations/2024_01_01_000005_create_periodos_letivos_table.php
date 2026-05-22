<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('periodos_letivos', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->string('nome', 100);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->enum('status', ['planejamento', 'ativo', 'encerrado'])->default('planejamento');
            $table->timestamp('criado_em')->useCurrent();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periodos_letivos');
    }
};
