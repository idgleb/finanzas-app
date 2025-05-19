<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'code',
        'price',
        'description',
        'features',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    /**
     * Obtiene los usuarios asociados a este plan
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Obtiene el plan PRO activo
     */
    public static function getProPlan()
    {
        return static::where('code', 'pro')->where('is_active', true)->first();
    }

    /**
     * Obtiene el plan gratuito activo
     */
    public static function getFreePlan()
    {
        return static::where('code', 'free')->where('is_active', true)->first();
    }

    /**
     * Verifica si el plan es gratuito
     */
    public function isFree(): bool
    {
        return $this->code === 'free';
    }

    /**
     * Verifica si el plan es PRO
     */
    public function isPro(): bool
    {
        return $this->code === 'pro';
    }
} 