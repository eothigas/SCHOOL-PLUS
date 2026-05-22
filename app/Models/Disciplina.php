<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use BelongsToTenant;

    protected $table = 'disciplinas';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'curso_id', 'nome', 'codigo',
        'carga_horaria', 'ementa', 'status',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function turmaDisiplinas()
    {
        return $this->hasMany(TurmaDisiplina::class);
    }
}
