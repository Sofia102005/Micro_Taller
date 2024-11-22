<?php

namespace App\Http\Controllers;

use App\Models\Alumno; // Manteniendo el modelo Alumno
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumno::query();

        // Filtrar alumnos según el parámetro de búsqueda
        if ($request->filled('filtro')) {
            $filtro = $request->input('filtro');
            $query->where(function($q) use ($filtro) {
                $q->where('cod', 'like', "%$filtro%")
                  ->orWhere('nombre', 'like', "%$filtro%")
                  ->orWhere('email', 'like', "%$filtro%");
            });
        }

        // Obtener alumnos con sus notas
        $alumnos = $query->with('notas')->get();

        // Calcular el resumen de alumnos
        $totalAprobados = $alumnos->filter(fn($alumno) => $alumno->notas->avg('nota') >= 3)->count();
        $totalReprobados = $alumnos->filter(fn($alumno) => $alumno->notas->avg('nota') < 3 && $alumno->notas->isNotEmpty())->count();
        $sinNotas = $alumnos->filter(fn($alumno) => $alumno->notas->isEmpty())->count();

        return response()->json([
            'data' => $alumnos,
            'resumen' => [
                'totalAprobados' => $totalAprobados,
                'totalReprobados' => $totalReprobados,
                'sinNotas' => $sinNotas,
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        // Validación de datos
        $dataBody = $request->validate([
            'cod' => 'required|string|max:255|unique:estudiantes,cod', // Asegúrate que 'estudiantes' sea la tabla correcta
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:estudiantes,email', // Asegúrate que 'estudiantes' sea la tabla correcta
        ]);

        // Crear nuevo alumno
        $alumno = Alumno::create($dataBody);
        return response()->json(['data' => $alumno], 201);
    }

    public function show(string $cod)
    {
        // Buscar alumno por código
        $alumno = Alumno::with('notas')->where('cod', $cod)->first();
        if (!$alumno) {
            return response()->json(['msg' => 'Estudiante no encontrado'], 404);
        }
        return response()->json(['data' => $alumno], 200);
    }

    public function update(Request $request, string $cod)
    {
        // Buscar alumno por código
        $alumno = Alumno::where('cod', $cod)->first();
        if (!$alumno) {
            return response()->json(['msg' => 'Estudiante no encontrado'], 404);
        }

        // Validación de datos
        $dataBody = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:estudiantes,email,' . $alumno->id, // Cambié 'cod' por 'id' para la validación
        ]);

        // Actualizar datos del alumno
        $alumno->update(array_filter($dataBody));
        return response()->json(['data' => $alumno], 200);
    }

    public function destroy(string $cod)
    {
        // Buscar alumno por código
        $alumno = Alumno::where('cod', $cod)->first();
        if (!$alumno) {
            return response()->json(['msg' => 'Estudiante no encontrado'], 404);
        }

        // Verificar si el alumno tiene notas registradas
        if ($alumno->notas()->exists()) {
            return response()->json(['msg' => 'No se puede eliminar el estudiante porque tiene notas registradas.'], 403);
        }

        // Eliminar alumno
        $alumno->delete();
        return response()->json(['data' => 'Estudiante eliminado'], 200);
    }

    public function destroyAll(string $cod)
    {
        // Buscar alumno por código
        $alumno = Alumno::where('cod', $cod)->first();
        if (!$alumno) {
            return response ()->json(['msg' => 'Estudiante no encontrado'], 404);
        }

        // Eliminar todas las notas del alumno y luego el alumno
        $alumno->notas()->delete();
        $alumno->delete();
        return response()->json(['data' => 'Estudiante y sus notas eliminados'], 200);
    }
}