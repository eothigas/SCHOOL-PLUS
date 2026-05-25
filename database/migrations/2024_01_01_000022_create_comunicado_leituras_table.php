<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comunicado_leituras', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('comunicado_id');
            $table->unsignedInteger('usuario_id');
            $table->timestamp('lido_em')->useCurrent();

            $table->unique(['comunicado_id', 'usuario_id']);
            $table->foreign('comunicado_id')->references('id')->on('comunicados')->cascadeOnDelete();
            $table->foreign('usuario_id')->references('id')->on('usuarios')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comunicado_leituras');
    }
};
