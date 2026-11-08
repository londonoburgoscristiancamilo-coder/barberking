<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicios extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'precio',
        'duracion_minutos',
        'descripcion'
    ];

    public function citas()
    {
        return $this->hasMany(Citas::class);
    }
}