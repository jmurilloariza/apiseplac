<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $plan_id
 * @property int $eje_id
 * @property int $linea_id
 * @property int $programa_id
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Eje $eje
 * @property Linea $linea
 * @property Plan $plan
 * @property Programa $programa
 * @property Proyecto[] $proyectos
 */
class PlanEjeLineaPrograma extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'plan_eje_linea_programa';

    /**
     * @var array
     */
    protected $fillable = ['id', 'plan_id', 'eje_id', 'linea_id', 'programa_id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eje()
    {
        return $this->belongsTo(Eje::class, 'eje_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function linea()
    {
        return $this->belongsTo(Linea::class, 'linea_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programa_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'planaccion_id');
    }
}
