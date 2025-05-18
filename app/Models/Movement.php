<?php

namespace App\Models;

use App\Traits\UsesUserTimezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo',          // 'ingreso' o 'gasto'
        'monto',
        'categoria_id',
        'fecha'
    ];


    public function getFechaLocalAttribute(): string
    {
        // 1) Creamos Carbon desde el raw UTC
        $dt = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->attributes['fecha'],
            'UTC'
        );

        // 2) Detectamos zona (o fallback)
        $tz = Auth::check()
        && in_array(Auth::user()->zona_horaria, \DateTimeZone::listIdentifiers())
            ? Auth::user()->zona_horaria
            : config('app.timezone', 'UTC');

        // 3) Ajustamos y formateamos
        return $dt
            ->setTimezone($tz)
            ->format('Y-m-d H:i');
    }



    // Relación: cada movimiento pertenece a un usuario
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación: cada movimiento pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

}
