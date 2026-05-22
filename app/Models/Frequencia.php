<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Frequencia extends Model
{
    use BelongsToTenant;

    protected $table = 'frequencias';
    public $timestamps = false;

    protected $fillable = ['tenant_id', 'aula_id', 'matricula_id', 'presente', 'justificada', 'obs'];

    public function aula()
    {
        return $this->belongsTo(Aula::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }
}
