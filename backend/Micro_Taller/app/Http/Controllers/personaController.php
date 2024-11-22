<?php

namespace App\Http\Controllers;

use App\Models\Persona; 
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function index(Request $request)
    {
        // Puedes agregar paginación si es necesario
        $personas = Persona::all(); // O puedes usar ->paginate(10) para paginación
        return response()->json(['data' => $personas], 200);
    }

    public function store(Request $request)
    {
        $dataBody = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:personas,email', // Asegúrate de que 'personas' sea la tabla correcta
            'codigo' => 'required|integer|max:10',
        ]);

        $persona = Persona::create($dataBody);
        return response()->json(['data' => $persona], 201);
    }

    public function show(string $codigo)
    {
        $persona = Persona::where('codigo', $codigo)->first(); // Cambié a buscar por 'codigo'
        if (!$persona) {
            return response()->json(['msg' => 'Persona no encontrada'], 404);
        }
        return response()->json(['data' => $persona], 200);
    }

    public function update(Request $request, string $codigo)
    {
        $dataBody = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:personas,email,' . $codigo . ',codigo', // Asegúrate que 'codigo' es el campo correcto
        ]);

        $persona = Persona::where('codigo', $codigo)->first(); // Cambié a buscar por 'codigo'
        if (!$persona) {
            return response()->json(['msg' => 'Persona no encontrada'], 404);
        }

        $persona->update($dataBody);
        return response()->json(['data' => $persona], 200);
    }

    public function destroy(string $codigo)
    {
        $persona = Persona::where('codigo', $codigo)->first(); // Cambié a buscar por 'codigo'
        if (!$persona) {
            return response()->json(['msg' => 'Persona no encontrada'], 404);
        }
        $persona->delete();
        return response()->json(['data' => 'Persona eliminada'], 200);
    }
}