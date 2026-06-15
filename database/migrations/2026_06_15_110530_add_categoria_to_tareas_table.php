<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void // aplicar cambio (añadir columna)
    { // Modificamos la tabla 'tareas' en la BBDD
        Schema::table('tareas', function (Blueprint $table) {
            // cunado se ejecute migrate, se añade col. categoria a la tabla
            $table->string('categoria')->default('general');
        }); // y todas las tareas existentes trnadrán "general" por defecto
    }

    /**
     * Reverse the migrations.
     */                          // revertir cambio (quitar columna)
    public function down(): void // Schema: clase de Laraver para trbajar con la estrucutra de la BD
    { // function ($table): dentro se define q cambios se hacen
        Schema::table('tareas', function (Blueprint $table) {
            //
            $table->dropColumn('categoria');
        });
    }
};
