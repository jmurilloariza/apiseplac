<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $actividad_id
 * @property int $usuario_id
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Actividad $actividade
 * @property Usuario $user
 */
class ActividadUsuario extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actividades_usuarios';

    /**
     * @var array
     */
    protected $fillable = ['id', 'actividad_id', 'usuario_id', 'deleted_at', 'created_at', 'updated_at'];

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
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
