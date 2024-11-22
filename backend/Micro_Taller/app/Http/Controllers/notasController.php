<?php

namespace App\Http\Controllers;

use App\Models\Notas;
use App\Models\Alumno; 
use Illuminate\Http\Request;

class NotaController extends Controller
{
    // Método para obtener todas las notas
    public function index()
    {
        return response()->json(['data' => Notas::all()], 200);
    }

    // Método para obtener las notas por estudiante
    public function notasPorEstudiante(string $codEstudiante)
    {
        $alumno = Alumno::where('cod', $codEstudiante)->first();
        if (!$alumno) {
            return response()->json(['msg' => 'Estudiante no encontrado'], 404);
        }

        $notas = Notas::where('codEstudiante', $codEstudiante)->get();
        $promedio = $notas->avg('nota');
        $estado = $promedio < 3 ? 'Perdió' : 'Aprobó';

        return response()->json([
            'alumno' => [
                'codigo' => $alumno->cod,
                'email' => $alumno->email,
                'nombre' => $alumno->nombre,
                'estado' => $estado,
                'promedio' => number_format($promedio, 2),
            ],
            'notas' => $notas,
        ], 200);
    }

    // Método para registrar una nueva nota
    public function store(Request $request)
    {
        $dataBody = $request->validate([
            'actividad' => 'required|string|max:255',
            'nota' => 'required|numeric|between:0,5|regex:/^\d+(\.\d{1,2})?$/', 
            'codEstudiante' => 'required|string|max:255|exists:estudiantes,cod', 
        ]);

        $nota = Notas::create($dataBody);
        return response()->json(['data' => $nota], 201);
    }

    // Método para obtener una nota específica
    public function show(string $id)
    {
        $nota = Notas::find($id);
        if (!$nota) {
            return response()->json(['msg' => 'Nota no encontrada'], 404);
        }
        return response()->json(['data' => $nota], 200);
    }

    // Método para actualizar una nota
    public function update(Request $request, string $id)
    {
        $nota = Notas::find($id);
        if (!$nota) {
            return response()->json(['msg' => 'Nota no encontrada'], 404);
        }

        $dataBody = $request->validate([
            'actividad' => 'sometimes|required|string|max:255',
            'nota' => 'sometimes|required|numeric|between:0,5|regex:/^\d+(\.\d{1,2})?$/', 
        ]);

        $nota->update(array_filter($dataBody));
        return response()->json(['data' => $nota], 200);
    }

    // Método para eliminar una nota
    public function destroy(string $id)
    {
        $nota = Notas::find($id);
        if (!$nota) {
            return response()->json(['msg' => 'Nota no encontrada'], 404);
        }
        $nota->delete();
        return response()->json(['data' => 'Nota del alumno eliminada'], 200);
    }

    // Método para obtener un resumen de notas por estudiante
    public function resumenNotas(string $codEstudiante)
    {
        $notas = Notas::where('codEstudiante', $codEstudiante)->get();

        if ($notas->isEmpty()) {
            return response()->json(['msg' => 'El estudiante no tiene notas registradas'], 404);
        }

        $bajoTres = $notas->where('nota', '<', 3)->count();
        $mayorIgualTres = $notas->where('nota', '>=', 3)->count();

        return response()->json([
            'bajoTres' => $bajoTres,
            'mayorIgualTres' => $mayorIgualTres,
        ]);
    }

    // Método para filtrar notas por actividad o rango de notas
    public function filtrarNotas(Request $request, string $codEstudiante)
    {
        $request->validate([
            'actividad' => 'sometimes|string|max:255',
            'rango_inferior' => 'sometimes|numeric|between:0,5',
            'rango_superior' => 'sometimes|numeric|between:0,5',
        ]);

        $query = Notas::where('cod Estudiante', $codEstudiante);

        if ($request->has('actividad')) {
            $query->where('actividad', 'like', '%' . $request->actividad . '%');
        }

        if ($request->has('rango_inferior') && $request->has('rango_superior')) {
            $query->whereBetween('nota', [$request->rango_inferior, $request->rango_superior]);
        }

        $notas = $query->get();

        return response()->json(['data' => $notas], 200);
    }

    // Método para destacar notas según criterios
    public function destacarNotas(string $codEstudiante)
    {
        $notas = Notas::where('codEstudiante', $codEstudiante)->get();

        if ($notas->isEmpty()) {
            return response()->json(['msg' => 'El estudiante no tiene notas registradas'], 404);
        }

        $notasDestacadas = [
            'menorIgualDos' => $notas->where('nota', '<=', 2),
            'mayorDosMenosTres' => $notas->where('nota', '>', 2)->where('nota', '<', 3),
            'mayorIgualTresMenorCuatro' => $notas->where('nota', '>=', 3)->where('nota', '<', 4),
            'mayorIgualCuatro' => $notas->where('nota', '>=', 4),
        ];

        return response()->json(['data' => $notasDestacadas], 200);
    }
}