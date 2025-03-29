<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $fillable = [
        'pregunta',
        'respuesta',
        'activado',
    ];

  public function organiser()
    {
        return $this->belongsTo(Organiser::class);
    }
}
