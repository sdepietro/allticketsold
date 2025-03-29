<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teatro2 extends Model
{
	protected $table = 'teatros2';
    protected $fillable = [
        'organiser_id',
        'nombre',
        'direccion',
        'coordenadas',
        'imagen',
    ];
}
