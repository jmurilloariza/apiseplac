<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'facultad';

    /**
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'codigo', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'facultad_id');
    }
}
