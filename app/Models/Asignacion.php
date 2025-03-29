<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{


    // Si el nombre de la tabla no es plural o es diferente de la convención
    // puedes especificarlo de la siguiente manera (esto es opcional):
    protected $table = 'asignaciones';

    // Los campos que pueden ser llenados de forma masiva (mass assignable)
    protected $fillable = [
        'idobra',
        'idvendedor',
        'otro1',
        'otro2',
        'otro3',
        'otro4',
    ];
}
