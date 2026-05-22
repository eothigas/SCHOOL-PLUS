<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    use BelongsToTenant;

    protected $table = 'notas';
    public $timestamps = false;

    protected $fillable = ['tenant_id', 'avaliacao_id', 'matricula_id', 'nota', 'obs'];

    protected $casts = ['nota' => 'float'];

    public function avaliacao()
    {
        return $this->belongsTo(Avaliacao::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }
}
