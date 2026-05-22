<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use BelongsToTenant;

    protected $table = 'aulas';
    public $timestamps = false;

    protected $fillable = ['tenant_id', 'turma_disc_id', 'data_aula', 'conteudo', 'observacao'];

    protected $casts = ['data_aula' => 'date', 'criado_em' => 'datetime'];

    public function turmaDisiplina()
    {
        return $this->belongsTo(TurmaDisiplina::class, 'turma_disc_id');
    }

    public function frequencias()
    {
        return $this->hasMany(Frequencia::class);
    }
}
