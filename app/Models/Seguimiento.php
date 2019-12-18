<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 2.0
 */

class Seguimiento extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seguimiento';

    /**
     * @var array
     */
    protected $fillable = ['id', 'plan_actividad_id', 'periodo_evaluado', 'fecha_seguimiento', 
        'valoracion', 'situacion_actual', 'estado', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planActividad()
    {
        return $this->belongsTo(PlanActividad::class, 'plan_actividad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comentarios()
    {
        return $this->hasMany(Comentarios::class, 'seguimiento_id');
    }
}
