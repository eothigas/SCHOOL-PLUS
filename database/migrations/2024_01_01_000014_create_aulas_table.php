<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aulas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('turma_disc_id');
            $table->date('data_aula');
            $table->text('conteudo')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamp('criado_em')->useCurrent();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('turma_disc_id')->references('id')->on('turma_disciplinas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
