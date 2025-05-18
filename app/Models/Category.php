<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'icono',
        'tipo',         // 'ingreso' o 'gasto'
        'user_id',
        'activa',
    ];

    // Relación: cada categoría pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación: una categoría tiene muchos movimientos
    public function movimientos(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    // Scope para filtrar categorías activas
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    // Scope para categorías de ingresos
    public function scopeDeIngresos($query)
    {
        return $query->where('tipo', 'ingreso');
    }

    // Scope para categorías de gastos
    public function scopeDeGastos($query)
    {
        return $query->where('tipo', 'gasto');
    }
}
