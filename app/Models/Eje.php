<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $nombre
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Linea[] $lineas
 * @property ProyectoPrograma[] $planEjeLineaProgramas
 */
class Eje extends Model
{

    use SoftDeletes;

    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['lineas'];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ejes';

    /**
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'descripcion', 'codigo', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lineas()
    {
        return $this->hasMany(Linea::class, 'eje_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planEjeLineaProgramas()
    {
        return $this->hasMany(ProyectoPrograma::class, 'eje_id');
    }
}
