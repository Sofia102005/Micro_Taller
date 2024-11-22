<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notas extends Model
{
    use HasFactory;

    protected $table = 'notas'; // Nombre de la tabla

    // Campos que se pueden llenar
    protected $fillable = ['actividad', 'nota', 'codEstudiante'];

    // Relación con el modelo Alumno
    public function estudiante()
    {
        return $this->belongsTo(Alumno::class, 'codEstudiante', 'cod');
    }
}