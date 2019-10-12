<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre
 * @property string $deleted_at
 * @property string $created_at
 * @property string $updated_at
 * @property Plan[] $plans
 * @property Usuario[] $users
 */
class Dependencia extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'dependencia';

    /**
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planes()
    {
        return $this->hasMany(Plan::class, 'dependencia_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'dependencia_id');
    }
}
