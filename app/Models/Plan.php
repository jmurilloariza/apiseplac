<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $dependencia_id
 * @property string $fecha_inicio
 * @property string $fecha_fin
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Dependencia $dependencium
 * @property ProyectoPrograma[] $planEjeLineaProgramas
 */
class Plan extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'plan';

    /**
     * @var array
     */
    protected $fillable = ['id', 'programa_id', 'fecha_inicio', 'fecha_fin', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dependencia()
    {
        return $this->belongsTo(ProgramaAcademico::class, 'programa_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'plan_id');
    }
}
