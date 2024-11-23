<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlumnoController extends Controller
{
    // Listar todos los alumnos
    public function index(Request $request)
    {
        $query = Alumno::query();

        // Filtros opcionales desde la request
        if ($request->filled('codigo')) {
            $query->where('cod', 'like', '%' . $request->codigo . '%');
        }
        if ($request->filled('nombre')) {
            $query->where('nombres', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('sin_notas') && $request->sin_notas) {
            $query->whereDoesntHave('notas');
        }

        $rows = $query->with('notas')->get();

        // Resumen de alumnos
        $resumen = [
            'aprobados' => $rows->where('estado', 'Aprobado')->count(),
            'reprobados' => $rows->where('estado', 'Reprobado')->count(),
            'sin_notas' => $rows->where('estado', 'Sin notas')->count(),
        ];

        return response()->json([
            'data' => $rows,
            'resumen' => $resumen,
        ], 200);
    }

    // Crear un nuevo alumno
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cod' => 'required|string|max:255|unique:alumnos,cod',
            'nombres' => 'required|string|max:255',
            'email' => 'required|email|unique:alumnos,email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $alumno = Alumno::create($request->only('cod', 'nombres', 'email'));

        return response()->json(['data' => $alumno], 201);
    }

    // Mostrar un alumno por ID
    public function show(string $id)
    {
        $alumno = Alumno::with('notas')->find($id);

        if (!$alumno) {
            return response()->json(["msg" => "Alumno no encontrado"], 404);
        }

        return response()->json(['data' => $alumno], 200);
    }

    // Actualizar un alumno
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'cod' => 'sometimes|required|string|max:255|unique:alumnos,cod,' . $id,
            'nombres' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:alumnos,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $alumno = Alumno::find($id);

        if (!$alumno) {
            return response()->json(["msg" => "Alumno no encontrado"], 404);
        }

        $alumno->update($request->only('cod', 'nombres', 'email'));

        return response()->json(['data' => $alumno], 200);
    }

    public function destroy(string $id)
    {
        $alumno = Alumno::find($id);

        if (!$alumno) {
            return response()->json(["msg" => "Alumno no encontrado"], 404);
        }

        $alumno->delete();

        return response()->json(["msg" => "Alumno eliminado"], 200);
    }
}
