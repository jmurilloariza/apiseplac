<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evidencias extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'evidencias';

    /**
     * @var array
     */
    protected $fillable = ['id', 'url', 'comentario_id', 'deleted_at', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comentario()
    {
        return $this->belongsTo(Comentarios::class, 'comentario_id');
    }
}
