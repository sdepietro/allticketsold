<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalEvent extends Model
{
    protected $fillable = [
    	'title',
	'description',
    'img_mini',
    'img_main',
    'estado',
    'teatro_id',
    'duracion',
    'destacado',
    'start_date',
    'end_date',
    'img_sinopsi',
    'on_sale_date'
	];


    public function organiser()
    {
        return $this->belongsTo(Organiser::class);
    }

    public function teatro()
    {
        return $this->belongsTo(Teatro2::class);
    }


    public function categorias()
    {
        return $this->belongsToMany(Categoria::class,'global_events_categoria','global_event_id','categoria_id');
    }

    public function event()
    {
        return $this->hasMany(Event::class,'global_event_id');
    }
}
