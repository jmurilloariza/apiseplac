<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departamento extends Model
{

    use SoftDeletes;

    use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
    protected $softCascade = ['programasAcademicos'];

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'departamento';

    /**
     * @var array
     */
    protected $fillable = ['id', 'nombre', 'codigo', 'facultad_id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function facultad()
    {
        return $this->belongsTo(Facultad::class, 'facultad_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programasAcademicos()
    {
        return $this->hasMany(ProgramaAcademico::class, 'departamento_id');
    }

}
