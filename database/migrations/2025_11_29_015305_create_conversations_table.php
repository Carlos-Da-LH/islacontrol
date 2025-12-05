<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->index(); // Para agrupar conversaciones por sesión
            $table->enum('role', ['user', 'assistant']); // Quien habla
            $table->text('message'); // El mensaje
            $table->json('context')->nullable(); // Datos del negocio en ese momento
            $table->timestamps();

            // Índice para buscar rápido por usuario y sesión
            $table->index(['user_id', 'session_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};
