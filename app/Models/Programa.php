<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $linea_id
 * @property string $nombre
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Linea $linea
 * @property ProyectoPrograma[] $planEjeLineaProgramas
 */
class Programa extends Model
{

    use SoftDeletes;

    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['proyectos'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'programas';

    /**
     * @var array
     */
    protected $fillable = ['id', 'linea_id', 'nombre', 'descripcion', 'codigo', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function linea()
    {
        return $this->belongsTo(Linea::class, 'linea_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proyectos()
    {
        return $this->hasMany(ProyectoPrograma::class, 'programa_id');
    }
}
