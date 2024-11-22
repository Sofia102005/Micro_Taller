<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
   
    public function store(Request $request)
    {
        $data = $request->validate([
            'cod' => 'required|string|max:255|unique:estudiantes',
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:estudiantes',
        ]);

        $estudiante = Alumno::create($data);
        return response()->json(['data' => $estudiante], 201);
    }
}