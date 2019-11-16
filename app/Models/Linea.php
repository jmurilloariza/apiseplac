<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $eje_id
 * @property string $nombre
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Eje $eje
 * @property ProyectoPrograma[] $planEjeLineaProgramas
 * @property Programa[] $programas
 */
class Linea extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lineas';

    /**
     * @var array
     */
    protected $fillable = ['id', 'eje_id', 'nombre', 'descripcion', 'codigo', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eje()
    {
        return $this->belongsTo(Eje::class, 'eje_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programas()
    {
        return $this->hasMany(Programa::class, 'linea_id');
    }
}
