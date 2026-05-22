<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios_sistema', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('nome', 150);
            $table->string('email', 150)->unique();
            $table->string('senha_hash', 255);
            $table->enum('perfil', ['superadmin', 'suporte'])->default('suporte');
            $table->tinyInteger('status')->default(1);
            $table->timestamp('criado_em')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios_sistema');
    }
};
