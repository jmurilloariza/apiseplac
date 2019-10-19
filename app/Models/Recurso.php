<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property ActividadRecurso[] $actividadesRecursos
 */
class Recurso extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'recursos';

    /**
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividadesRecursos()
    {
        return $this->hasMany(ActividadRecurso::class, 'recursos_id');
    }
}
