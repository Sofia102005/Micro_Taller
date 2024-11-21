<?php

namespace App\Http\Controllers;

use App\Models\Notas;
use App\Models\Alumno; 
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function index()
    {
        return response()->json(['data' => Notas::all()], 200);
    }

    public function notasPorEstudiante(string $codEstudiante)
    {
        $notas = Notas::where('codEstudiante', $codEstudiante)->get();
        return response()->json(['data' => $notas], 200);
    }

    public function store(Request $request)
    {
        $dataBody = $request->validate([
            'actividad' => 'required|string|max:255',
            'nota' => 'required|numeric|between:0,5', 
            'codEstudiante' => 'required|string|max:255|exists:estudiantes,cod', 
        ]);

        $nota = Notas::create($dataBody);
        return response()->json(['data' => $nota], 201);
    }

    public function show(string $id)
    {
        $nota = Notas::find($id);
        if (!$nota) {
            return response()->json(['msg' => 'Nota no encontrada'], 404);
        }
        return response()->json(['data' => $nota], 200);
    }

    public function update(Request $request, string $id)
    {
        $dataBody = $request->validate([
            'actividad' => 'sometimes|required|string|max:255',
            'nota' => 'sometimes|required|numeric|between:0,5', // Asegúrate de que la nota esté entre 0 y 5
        ]);

        $nota = Notas::find($id);
        if (!$nota) {
            return response()->json(['msg' => 'Nota no encontrada'], 404);
        }

        $nota->update(array_filter($dataBody));
        return response()->json(['data' => $nota], 200);
    }

    public function destroy(string $id)
    {
        $nota = Notas::find($id);
        if (!$nota) {
            return response()->json(['msg' => 'Nota no encontrada'], 404);
        }
        $nota->delete();
        return response()->json(['data' => 'Nota del alumno eliminada'], 200);
    }

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
}