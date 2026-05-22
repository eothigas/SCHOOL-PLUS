<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Usuario extends Model implements Authenticatable
{
    use AuthenticatableTrait, BelongsToTenant;

    protected $table = 'usuarios';
    public $timestamps = false;

    protected $fillable = [
        'tenant_id', 'nome', 'email', 'senha_hash',
        'perfil', 'cpf', 'telefone', 'foto_url', 'status',
    ];

    protected $hidden = ['senha_hash'];

    // Laravel Auth needs getAuthPassword to return the password field
    public function getAuthPassword()
    {
        return $this->senha_hash;
    }

    public function getAuthPasswordName(): string
    {
        return 'senha_hash';
    }

    public function aluno()
    {
        return $this->hasOne(Aluno::class, 'usuario_id');
    }
}
