<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $planaccion_id
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property PlanEjeLineaPrograma $planEjeLineaPrograma
 * @property Actividad[] $actividades
 */
class Proyecto extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proyectos';

    /**
     * @var array
     */
    protected $fillable = ['id', 'planaccion_id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planEjeLineaPrograma()
    {
        return $this->belongsTo(PlanEjeLineaPrograma::class, 'planaccion_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'proyecto_id');
    }
}
