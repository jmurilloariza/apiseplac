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
class Actividad extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actividades';

    /**
     * @var array
     */
    protected $fillable = ['id', 'proyecto_id', 'indicador_id', 'acciones', 'descripcion', 'fecha_inicio', 'fecha_fin', 'costo', 'unidad_medida', 'peso', 'deleted_at', 'created_at', 'updated_at'];

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
    public function observaciones()
    {
        return $this->hasMany(Observacion::class, 'actividad_id');
    }
}
