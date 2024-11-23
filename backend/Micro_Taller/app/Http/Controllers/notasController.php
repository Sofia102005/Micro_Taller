<?php

namespace App\Http\Controllers;

use App\Models\Notas;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function index()
    {
        $notas = Notas::with('estudiante')->get();
        return response()->json(['data' => $notas], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'actividad' => 'required|string|max:100',
            'nota' => 'required|numeric|between:0,5',
            'codEstudiante' => 'required|exists:estudiantes,cod',
        ]);

        $nota = Notas::create($validated);
        return response()->json(['data' => $nota], 201);
    }

    public function show($id)
    {
        $nota = Notas::with('estudiante')->find($id);
        if (!$nota) {
            return response()->json(['message' => 'Nota no encontrada'], 404);
        }
        return response()->json(['data' => $nota], 200);
    }

    public function update(Request $request, $id)
    {
        $nota = Notas::find($id);
        if (!$nota) {
            return response()->json(['message' => 'Nota no encontrada'], 404);
        }

        $validated = $request->validate([
            'actividad' => 'required|string|max:100',
            'nota' => 'required|numeric|between:0,5',
            'codEstudiante' => 'required|exists:estudiantes,cod',
        ]);

        $nota->update($validated);
        return response()->json(['data' => $nota], 200);
    }

    public function destroy($id)
    {
        $nota = Notas::find($id);
        if (!$nota) {
            return response()->json(['message' => 'Nota no encontrada'], 404);
        }

        $nota->delete();
        return response()->json(['message' => 'Nota eliminada con éxito'], 200);
    }
}