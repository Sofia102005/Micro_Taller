<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   Notas extends Model
{
    use HasFactory;

    protected $table = 'notas'; 

    public function estudiante()
    {
        return $this->belongsTo(Alumno::class, 'codEstudiante', 'cod');
    }
}