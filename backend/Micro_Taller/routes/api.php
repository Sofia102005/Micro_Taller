<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumnoController; // Cambio a "AlumnoController"
use App\Http\Controllers\NotaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rutas para Alumnos
Route::prefix('alumnos')->group(function () { // Cambio de 'estudiantes' a 'alumnos'
    Route::get('/', [AlumnoController::class, 'index']); // Listar todos los alumnos
    Route::post('/', [AlumnoController::class, 'store']); // Crear un nuevo alumno
    Route::get('/{id}', [AlumnoController::class, 'show']); // Mostrar un alumno específico
    Route::put('/{id}', [AlumnoController::class, 'update']); // Actualizar un alumno
    Route::delete('/{id}', [AlumnoController::class, 'destroy']); // Eliminar un alumno
    Route::get('/buscar', [AlumnoController::class, 'search']); // Buscar alumnos con filtros
    Route::get('/estadisticas', [AlumnoController::class, 'statistics']); // Mostrar resumen de estadísticas
});

/*
// Rutas para Notas
Route::prefix('notas')->group(function () {
    Route::get('/', [NotaController::class, 'index']); // Listar todas las notas
    Route::post('/', [NotaController::class, 'store']); // Crear una nueva nota
    Route::get('/{id}', [NotaController::class, 'show']); // Mostrar una nota específica
    Route::put('/{id}', [NotaController::class, 'update']); // Actualizar una nota
    Route::delete('/{id}', [NotaController::class, 'destroy']); // Eliminar una nota
    Route::get('/buscar', [NotaController::class, 'search']); // Buscar notas con filtros
    Route::get('/resumen', [NotaController::class, 'summary']); // Mostrar resumen de notas
});
*/

// Rutas para el CRUD de notas
Route::get('/notas', [NotaController::class, 'index']); // Listar todas las notas
Route::post('/notas', [NotaController::class, 'store']); // Crear una nueva nota
Route::get('/notas/{id}', [NotaController::class, 'show']); // Ver una nota específica
Route::put('/notas/{id}', [NotaController::class, 'update']); // Actualizar una nota
Route::delete('/notas/{id}', [NotaController::class, 'destroy']); // Eliminar una nota
