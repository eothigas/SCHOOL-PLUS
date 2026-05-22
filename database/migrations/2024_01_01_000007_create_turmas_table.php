<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('turmas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('curso_id');
            $table->unsignedInteger('periodo_id');
            $table->string('nome', 100);
            $table->enum('turno', ['manha', 'tarde', 'noite', 'integral', 'ead']);
            $table->unsignedSmallInteger('vagas')->default(40);
            $table->string('sala', 50)->nullable();
            $table->enum('status', ['aberta', 'em_andamento', 'encerrada'])->default('aberta');
            $table->timestamp('criado_em')->useCurrent();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('curso_id')->references('id')->on('cursos');
            $table->foreign('periodo_id')->references('id')->on('periodos_letivos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('turmas');
    }
};
