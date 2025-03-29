<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{

	
	protected $table = 'vendedores';
	
    protected $fillable = [
        'nombres',
        'apellidos',
        'correo',
        'url',
        'otros1',
        'otros2',
        'otros3',
        'otros4',
    ];
	
	public function organiser()
    {
        return $this->belongsTo(Organiser::class);
    }
}
