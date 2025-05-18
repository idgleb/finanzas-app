<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'contenido',
        'imagen',
        'fecha',
        'created_by',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación: la novedad fue creada por un usuario (admin)
    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accesor opcional para mostrar fecha formateada
    public function getFechaFormattedAttribute(): string
    {
        return $this->fecha ? $this->fecha->format('d/m/Y') : '';
    }
}
