<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class PlanActividad extends Model
{
    use SoftDeletes;

    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['seguimientos'];

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'plan_actividad';

    /**
     * @var array
     */
    protected $fillable = ['id', 'actividades_id', 'plan_id', 'fecha_inicio', 'fecha_fin', 'costo', 'peso', 'estado'];

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
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividades_id');
    }

    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class, 'plan_actividad_id');
    }
}
