<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\EstudianteController;

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

// Rutas para el controlador EstudianteController
Route::prefix('estudiantes')->group(function () {
    Route::get('/', [EstudianteController::class, 'index']); // Obtener todos los estudiantes
    Route::get('/{cod}', [EstudianteController::class, 'show']); // Obtener un estudiante específico
    Route::post('/', [EstudianteController::class, 'store']); // Crear un nuevo estudiante
    Route::put('/{cod}', [EstudianteController::class, 'update']); // Actualizar un estudiante existente
    Route::delete('/{cod}', [EstudianteController::class, 'destroy']); // Eliminar un estudiante
});