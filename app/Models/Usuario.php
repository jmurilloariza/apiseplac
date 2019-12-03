<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property int $rol_id
 * @property int $dependencia_id
 * @property string $name
 * @property string $apellidos
 * @property string $codigo
 * @property string $email
 * @property string $password
 * @property string $email_verified_at
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property Dependencia $dependencium
 * @property Rol $role
 * @property ActividadUsuario[] $actividadesUsuarios
 */

/**
 * @author jmurilloariza - jefersonmanuelma@ufps.edu.co 
 * @version 1.0
 */

class Usuario extends Authenticatable
{

    use Notifiable;
    use SoftDeletes;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $fillable = [
        'id', 'rol_id', 'programa_academico_id', 'name', 'apellidos', 'codigo', 'email', 'password', 'email_verified_at',
        'contrato', 'remember_token', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function programaAcademico()
    {
        return $this->belongsTo(ProgramaAcademico::class, 'programa_academico_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actividadesUsuarios()
    {
        return $this->hasMany(ActividadUsuario::class, 'usuario_id');
    }
}
