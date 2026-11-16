<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clientes extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'documento_identidad',
        'nombres',
        'apellidos',
        'telefono',
    ];

    public function citas(): HasMany
    {
        return $this->hasMany(Citas::class, 'cliente_id');
    }
}