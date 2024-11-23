<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\AlumnoController; // Cambio a "AlumnoController"

Route::get('/', function () {
    return view('welcome');
});

// Rutas para el controlador NotaController
Route::prefix('notas')->group(function () {
    Route::get('/', [NotaController::class, 'index']); // Obtener todas las notas
    Route::get('/{id}', [NotaController::class, 'show']); // Obtener una nota específica
    Route::post('/', [NotaController::class, 'store']); // Crear una nueva nota
    Route::put('/{id}', [NotaController::class, 'update']); // Actualizar una nota existente
    Route::delete('/{id}', [NotaController::class, 'destroy']); // Eliminar una nota
});

// Rutas para el controlador AlumnoController
Route::prefix('alumnos')->group(function () { // Cambio de 'estudiantes' a 'alumnos'
    Route::get('/', [AlumnoController::class, 'index']); // Obtener todos los alumnos
    Route::get('/{cod}', [AlumnoController::class, 'show']); // Obtener un alumno específico
    Route::post('/', [AlumnoController::class, 'store']); // Crear un nuevo alumno
    Route::put('/{cod}', [AlumnoController::class, 'update']); // Actualizar un alumno existente
    Route::delete('/{cod}', [AlumnoController::class, 'destroy']); // Eliminar un alumno
});
