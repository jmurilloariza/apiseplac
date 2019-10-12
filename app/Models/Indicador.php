<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Actividad[] $actividades
 */
class Indicador extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividades()
    {
        return $this->hasMany(Actividad::class, 'indicador_id');
    }
}
