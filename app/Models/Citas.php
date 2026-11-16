<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Citas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'citas';
    
    protected $fillable = [
        'cliente_id',
        'servicio_id',
        'barbero_id',
        'fecha_cita',
        'hora_cita',
        'notas',
        'estado'
    ];

    protected $casts = [
        'fecha_cita' => 'date'
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Clientes::class, 'cliente_id')
            ->withTrashed();
    }

    public function servicio()
    {
        return $this->belongsTo(Servicios::class, 'servicio_id')
            ->withTrashed();
    }

    public function barbero()
    {
        return $this->belongsTo(User::class, 'barbero_id')
            ->withTrashed();
    }
}