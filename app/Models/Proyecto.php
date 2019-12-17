<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $planaccion_id
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property ProyectoPrograma $planEjeLineaPrograma
 * @property Actividad[] $actividades
 */

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class Proyecto extends Model
{

    use SoftDeletes;

    /** 
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'proyectos';

    /**
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'descripcion', 'objetivo', 'programa_academico_id', 'fecha_cierre', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programas()
    {
        return $this->hasMany(ProyectoPrograma::class, 'proyecto_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'proyecto_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function programaAcademico()
    {
        return $this->belongsTo(ProgramaAcademico::class, 'programa_academico_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function responsables()
    {
        return $this->hasMany(ProyectosUsuario::class, 'proyecto_id');
    }
}
