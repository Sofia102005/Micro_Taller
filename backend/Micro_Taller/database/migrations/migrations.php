<?php

namespace App\Http\Controllers;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotasTable extends Migration
{
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->id(); // Clave primaria
            $table->unsignedBigInteger('codEstudiante'); // Clave foránea
            $table->string('asignatura');
            $table->float('nota');
            $table->timestamps();

            // Definir la clave foránea
            $table->foreign('codEstudiante')->references('cod')->on('estudiantes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas');
    }
}