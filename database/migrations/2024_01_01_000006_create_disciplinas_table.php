<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disciplinas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('curso_id');
            $table->string('nome', 200);
            $table->string('codigo', 20)->nullable();
            $table->unsignedSmallInteger('carga_horaria')->nullable();
            $table->text('ementa')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('curso_id')->references('id')->on('cursos')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplinas');
    }
};
