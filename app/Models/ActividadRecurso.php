<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $actividad_id
 * @property int $recursos_id
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Actividad $actividade
 * @property Recurso $recurso
 */

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class ActividadRecurso extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actividades_recursos';

    /**
     * @var array
     */
    protected $fillable = ['id', 'actividad_id', 'recursos_id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recurso()
    {
        return $this->belongsTo(Recurso::class, 'recursos_id');
    }
}
