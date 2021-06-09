<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * @property int $id
 * @property int $id_responsable
 * @property string $nombre
 * @property boolean $estado
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User $user
 * @property Historiale[] $historiales
 * @property Historiale[] $historiales
 * @property Inventario[] $inventarios
 */
class Bodega extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['id_responsable', 'nombre', 'estado', 'created_by', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'id_responsable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historiales_destino()
    {
        return $this->hasMany('App\Historiales', 'id_bodega_destino');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historiales_origen()
    {
        return $this->hasMany('App\Historiales', 'id_bodega_origen');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventarios()
    {
        return $this->hasMany('App\Inventarios', 'id_bodega');
    }
}
