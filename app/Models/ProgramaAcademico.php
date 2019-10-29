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
class ProgramaAcademico extends Model
{

    use SoftDeletes;

    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['planes', 'usuarios'];

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'programa_academico';

    /**
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'codigo', 'departamento_id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planes()
    {
        return $this->hasMany(Plan::class, 'programa_academico_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'programa_academico_id');
    }
}
