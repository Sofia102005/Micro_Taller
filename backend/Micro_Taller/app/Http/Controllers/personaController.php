<?php

namespace App\Http\Controllers;

use App\Models\Persona; 
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    public function index()
    {
        $personas = Persona::all();
        return response()->json(['data' => $personas], 200);
    }

    public function store(Request $request)
    {
        $dataBody = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'codigo' => 'required|integer|max:10',
          
        ]);

        $persona = Persona::create($dataBody);
        return response()->json(['data' => $persona], 201);
    }

    public function show(string $id)
    {
        $persona = Persona::find($id);
        if (!$persona) {
            return response()->json(['msg' => 'error'], 404);
        }
        return response()->json(['data' => $persona], 200);
    }

    public function update(Request $request, string $id)
    {
        $dataBody = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
        ]);

        $persona = Persona::find($id);
        if (!$persona) {
            return response()->json(['msg' => 'error'], 404);
        }

        $persona->update($dataBody);
        return response()->json(['data' => $persona], 200);
    }

    public function destroy(string $id)
    {
        $persona = Persona::find($id);
        if (!$persona) {
            return response()->json(['msg' => 'error'], 404);
        }
        $persona->delete();
        return response()->json(['data' => 'Persona eliminada'], 200);
    }
}
