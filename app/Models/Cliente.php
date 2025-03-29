<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Cliente extends Authenticatable
{
    use Notifiable;

    // El nombre de la tabla
    protected $table = 'clientes';

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'nombres', 'email', 'contraseña', 'telefono', 'dni',
    ];

    // Campos que se deben ocultar
    protected $hidden = [
        'contraseña',
    ];

    // Campos que se deben lanzar como fechas
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
