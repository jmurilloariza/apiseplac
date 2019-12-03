<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $proyecto_id
 * @property int $indicador_id
 * @property string $acciones
 * @property string $descripcion
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property float $costo
 * @property string $unidad_medida
 * @property int $peso
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Indicador $indicadore
 * @property Proyecto $proyecto
 * @property ActividadRecurso[] $actividadesRecursos
 * @property ActividadUsuario[] $actividadesUsuarios
 * @property Observacion[] $observaciones
 */

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class Actividad extends Model
{

    use SoftDeletes;

    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['actividadesRecursos', 'actividadesUsuarios'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actividades';

    /**
     * @var array
     */
    protected $fillable = ['id', 'proyecto_id', 'indicador_id', 'nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'costo', 'unidad_medida', 'peso', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function indicador()
    {
        return $this->belongsTo(Indicador::class, 'indicador_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividadesRecursos()
    {
        return $this->hasMany(ActividadRecurso::class, 'actividad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividadesUsuarios()
    {
        return $this->hasMany(ActividadUsuario::class, 'actividad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class, 'actividad_id');
    }
}
