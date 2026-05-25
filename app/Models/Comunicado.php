<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Comunicado extends Model
{
    use BelongsToTenant;

    protected $table = 'comunicados';
    public $timestamps = false;
    const CREATED_AT = 'criado_em';

    protected $fillable = [
        'tenant_id', 'autor_id', 'titulo', 'corpo',
        'destino', 'turma_id', 'fixado', 'publicado',
    ];

    protected $casts = [
        'fixado'    => 'boolean',
        'publicado' => 'boolean',
        'criado_em' => 'datetime',
    ];

    public function autor()
    {
        return $this->belongsTo(Usuario::class, 'autor_id');
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function leituras()
    {
        return $this->hasMany(ComunicadoLeitura::class);
    }

    public function leituraDoUsuario(int $usuarioId)
    {
        return $this->leituras->where('usuario_id', $usuarioId)->first();
    }
}
