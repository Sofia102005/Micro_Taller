<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumno::query();

        if ($request->filled('filtro')) {
            $filtro = $request->input('filtro');
            $query->where(function($q) use ($filtro) {
                $q->where('cod', 'like', "%$filtro%")
                  ->orWhere('nombres', 'like', "%$filtro%")
                  ->orWhere('email', 'like', "%$filtro%");
            });
        }

     
        $alumnos = $query->with('notas')->get();

    
        $totalAprobados = $alumnos->filter(fn($alumno) => $alumno->nota_definitiva >= 3)->count();
        $totalReprobados = $alumnos->filter(fn($alumno) => $alumno->nota_definitiva < 3 && $alumno->nota_definitiva !== 'No hay nota')->count();
        $sinNotas = $alumnos->filter(fn($alumno) => $alumno->nota_definitiva === 'No hay nota')->count();

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
            'cod' => 'required|string|max:255|unique:estudiantes,cod',
            'nombres' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:estudiantes,email',
        ]);

        // Crear nuevo alumno
        $alumno = Alumno::create($dataBody);
        return response()->json(['data' => $alumno], 201);
    }

    public function show(string $cod)
    {
        // Buscar alumno por código
        $alumno = Alumno::with('notas')->find($cod);
        if (!$alumno) {
            return response()->json(['msg' => 'Estudiante no encontrado'], 404);
        }
        return response()->json(['data' => $alumno], 200);
    }

    public function update(Request $request, string $cod)
    {
        // Buscar alumno por código
        $alumno = Alumno::find($cod);
        if (!$alumno) {
            return response()->json(['msg' => 'Estudiante no encontrado'], 404);
        }

        // Validación de datos
        $dataBody = $request->validate([
            'nombres' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:estudiantes,email,' . $cod,
        ]);

        // Actualizar datos del alumno
        $alumno->update(array_filter($dataBody));
        return response()->json(['data' => $alumno], 200);
    }

    public function destroy(string $cod)
    {
        // Buscar alumno por código
        $alumno = Alumno::find($cod);
        if (!$alumno) {
            return response()->json(['msg' => 'Estudiante no encontrado'], 404);
        }

        if ($alumno->notas()->exists()) {
            return response()->json(['msg' => 'No se puede eliminar el estudiante porque tiene notas registradas.'], 403);
        }

        $alumno->delete();
        return response()->json(['data' => 'Estudiante eliminado'], 200);
    }

    public function destroyAll(string $cod)
    {
     
        $alumno = Alumno::find($cod);
        if (!$alumno) {
            return response()->json(['msg' => 'Estudiante no encontrado'], 404);
        }

        $alumno->notas()->delete();
        $alumno->delete();
        return response()->json(['data' => 'Estudiante y sus notas eliminados'], 200);
   
    }
}