<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notas extends Model
{
    use HasFactory;

    protected $table = 'notas';

    protected $fillable = [
        'actividad',  // Descripción o nombre de la actividad
        'nota',       // Nota obtenida
        'codEstudiante' // Código del estudiante relacionado
    ];

    /**
     * Relación con el modelo Alumno.
     * Cada nota pertenece a un único estudiante.
     */
    public function estudiante()
    {
        return $this->belongsTo(Alumno::class, 'codEstudiante', 'cod');
    }
}