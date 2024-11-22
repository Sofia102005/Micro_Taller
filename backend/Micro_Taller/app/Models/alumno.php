<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'estudiantes'; // Asegúrate de que el nombre de la tabla sea correcto
    protected $primaryKey = 'cod'; // Establece la clave primaria
    public $timestamps = true; // Cambié a true si estás usando timestamps (created_at, updated_at)

    protected $fillable = ['cod', 'nombre', 'email']; // Campos que se pueden llenar de forma masiva

    // Relación con el modelo Notas
    public function notas()
    {
        return $this->hasMany(Notas::class, 'codEstudiante', 'cod'); // Asegúrate de que 'codEstudiante' sea el nombre correcto de la clave foránea en la tabla notas
    }

    // Accesor para calcular la nota definitiva
    public function getNotaDefinitivaAttribute()
    {
        $notas = $this->notas;

        if ($notas->isEmpty()) {
            return 'No hay nota'; // Si no hay notas, devuelve un mensaje
        }

        return $notas->avg('nota'); // Devuelve el promedio de las notas
    }

    // Accesor para determinar el estado del alumno
    public function getEstadoAttribute()
    {
        if ($this->nota_definitiva === 'No hay nota') {
            return 'No hay nota'; // Si no hay nota, devuelve este mensaje
        }
        return $this->nota_definitiva >= 3 ? 'Aprobado' : 'Reprobado'; // Devuelve 'Aprobado' o 'Reprobado' según la nota
    }
}