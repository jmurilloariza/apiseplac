<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $linea_id
 * @property string $nombre
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Linea $linea
 * @property PlanEjeLineaPrograma[] $planEjeLineaProgramas
 */
class Programa extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'programas';

    /**
     * @var array
     */
    protected $fillable = ['id', 'linea_id', 'nombre', 'created_at', 'updated_at', 'deleted_at'];

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
    public function planEjeLineaProgramas()
    {
        return $this->hasMany(PlanEjeLineaPrograma::class, 'programa_id');
    }
}
