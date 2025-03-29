<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{


    protected $fillable = [
        'organiser_id',
        'descripcion',
		'posicion',
        'imagen',
        'activado',
        // Otros campos según sea necesario
    ];

    // Si necesitas definir una relación con otro modelo, hazlo aquí
    // Por ejemplo, si cada categoría pertenece a un organizador:
    public function organiser()
    {
        return $this->belongsTo(Organiser::class);
    }


    public function globalEvents()
    {
        return $this->belongsToMany(GlobalEvent::class,'global_events_categoria','categoria_id','global_event_id');
    }
}
