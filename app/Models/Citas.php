<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Citas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'citas';
    
    protected $fillable = [
        'nombre_cliente',
        'apellido_cliente',
        'telefono_cliente',
        'servicio_id',
        'barbero_id',
        'fecha_cita',
        'hora_cita',
        'notas',
        'estado'
    ];

    protected $casts = [
        'fecha_cita' => 'datetime',
        'hora_cita'=> 'datetime:H:i:s',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'servicio_id');
    }

    public function barbero()
    {
        return $this->belongsTo(User::class, 'barbero_id');
    }
}