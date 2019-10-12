<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Linea[] $lineas
 * @property PlanEjeLineaPrograma[] $planEjeLineaProgramas
 */
class Eje extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'deleted_at', 'created_at', 'updated_at'];

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
        return $this->hasMany(PlanEjeLineaPrograma::class, 'eje_id');
    }
}
