<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horarios extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'horarios';
    
    protected $fillable = [
        'barbero_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'hora_inicio_descanso',
        'hora_fin_descanso'
    ];

    public function barbero()
    {
        return $this->belongsTo(User::class, 'barbero_id');
    }
}