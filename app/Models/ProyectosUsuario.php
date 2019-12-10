<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $proyectos_id
 * @property int $usuario_id
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Actividad $actividade
 * @property Usuario $user
 */

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class ProyectosUsuario extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proyectos_usuarios';

    /**
     * @var array
     */
    protected $fillable = ['id', 'proyectos_id', 'usuario_id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyectos_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
