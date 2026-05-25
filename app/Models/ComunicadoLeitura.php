<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComunicadoLeitura extends Model
{
    protected $table = 'comunicado_leituras';
    public $timestamps = false;

    protected $fillable = ['comunicado_id', 'usuario_id'];

    protected $casts = [
        'lido_em' => 'datetime',
    ];

    public function comunicado()
    {
        return $this->belongsTo(Comunicado::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
