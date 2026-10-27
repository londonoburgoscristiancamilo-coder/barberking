<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citas extends Model
{
    use HasFactory;

    protected $table = 'citas';
    
    protected $fillable = [
        'nombre_cliente',
        'apellido_cliente',
        'servicio_id',
        'barbero_id',
        'fecha_cita',
        'notas',
        'estado'
    ];

    protected $casts = [
        'fecha_cita' => 'datetime',
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
