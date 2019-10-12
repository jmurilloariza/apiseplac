<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
class ActividadRecurso extends Model
{
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
