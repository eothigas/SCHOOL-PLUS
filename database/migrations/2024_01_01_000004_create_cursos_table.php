<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->string('nome', 200);
            $table->enum('tipo', ['fundamental', 'medio', 'graduacao', 'pos_graduacao', 'tecnico', 'livre']);
            $table->unsignedSmallInteger('duracao_meses')->nullable();
            $table->text('descricao')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('criado_em')->useCurrent();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
