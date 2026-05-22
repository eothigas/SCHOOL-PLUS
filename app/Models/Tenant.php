<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'tenants';
    public $timestamps = false;

    protected $fillable = [
        'nome', 'tipo', 'cnpj', 'email', 'telefone',
        'endereco', 'cidade', 'estado', 'cep',
        'logo_url', 'plano', 'status',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }
}
