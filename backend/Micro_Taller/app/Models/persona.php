<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas'; // Asegúrate de que este es el nombre correcto de la tabla
    protected $primaryKey = 'codigo'; // Establece la clave primaria si 'codigo' es el identificador único
    public $timestamps = true; // Cambia a false si no usas timestamps

    protected $fillable = ['nombre', 'email', 'codigo']; // Campos que se pueden llenar de forma masiva

    // Puedes agregar relaciones aquí si es necesario
}
