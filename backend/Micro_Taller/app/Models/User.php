<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cod',          // Código único para el usuario
        'nombres',      // Nombre completo del usuario
        'email',        // Correo electrónico del usuario
        'actividad',    // Actividad o rol del usuario
        'nota',         // Nota asociada al usuario
        'codEstudiante', // Código del estudiante (si aplica)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',  // Token de recordatorio para la autenticación
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime', // Convertir email_verified_at a una instancia de datetime
    ];

    /**
     * Los atributos que deben ser únicos.
     *
     * @var array<int, string>
     */
    public static $rules = [
        'cod' => 'required|integer|unique:users,cod',
        'nombres' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email',
        'actividad' => 'nullable|string|max:255',
        'nota' => 'nullable|numeric|min:0|max:10', // Ajusta según el rango de notas
        'codEstudiante' => 'nullable|integer|exists:estudiantes,cod', // Asegúrate de que 'estudiantes' sea la tabla correcta
    ];
}