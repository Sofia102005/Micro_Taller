<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    // Definir la tabla asociada al modelo
    protected $table = 'alumnos'; // Cambiado de 'estudiantes' a 'alumnos' para mantener consistencia

    // Clave primaria personalizada
    protected $primaryKey = 'cod';

    // Indicar que la tabla utiliza timestamps
    public $timestamps = true;

    // Campos asignables en las operaciones de creación/actualización masiva
    protected $fillable = ['cod', 'nombres', 'email'];

    // Relación: un alumno tiene muchas notas
    public function notas()
    {
        return $this->hasMany(Notas::class, 'cod_estudiante', 'cod'); // Cambiado 'codEstudiante' a 'cod_estudiante' según convención
    }

    // Atributo personalizado para obtener la nota definitiva
    public function getNotaDefinitivaAttribute()
    {
        $notas = $this->notas;

        if ($notas->isEmpty()) {
            return null; // Devuelve null si no hay notas
        }

        return round($notas->avg('nota'), 2); // Devuelve el promedio de las notas con 2 decimales
    }

    // Atributo personalizado para calcular el estado (Aprobado/Reprobado)
    public function getEstadoAttribute()
    {
        if ($this->nota_definitiva === null) {
            return 'Sin notas'; // Devuelve 'Sin notas' si no hay una nota definitiva
        }

        return $this->nota_definitiva >= 3 ? 'Aprobado' : 'Reprobado'; // Condición para definir el estado
    }
}