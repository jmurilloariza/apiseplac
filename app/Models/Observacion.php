<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $actividad_id
 * @property string $observacion
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Actividad $actividade
 */
class Observacion extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'observaciones';

    /**
     * @var array
     */
    protected $fillable = ['id', 'actividad_id', 'observacion', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }
}
