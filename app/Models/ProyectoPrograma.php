<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $plan_id
 * @property int $eje_id
 * @property int $linea_id
 * @property int $programa_id
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Eje $eje
 * @property Linea $linea
 * @property Plan $plan
 * @property Programa $programa
 * @property Proyecto[] $proyectos
 */
class ProyectoPrograma extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'proyecto_programa';

    /**
     * @var array
     */
    protected $fillable = ['id', 'proyecto_id', 'programa_id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function programa()
    {
        return $this->belongsTo(Programa::class, 'programa_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }
}
