<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Responsavel;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResponsavelController extends Controller
{
    public function store(Request $request, Aluno $aluno)
    {
        $request->validate([
            'nome'        => 'required|string|max:150',
            'parentesco'  => 'required|string|max:50',
            'telefone'    => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:150',
            'cpf'         => 'nullable|string|max:14',
        ], [
            'nome.required'       => 'Nome obrigatório.',
            'parentesco.required' => 'Parentesco obrigatório.',
        ]);

        Responsavel::create([
            'tenant_id'  => session('tenant_id'),
            'aluno_id'   => $aluno->id,
            'nome'       => $request->nome,
            'parentesco' => $request->parentesco,
            'telefone'   => $request->telefone,
            'email'      => $request->email,
            'cpf'        => $request->cpf,
        ]);

        return back()->with('success', 'Responsável adicionado.');
    }

    public function criarLogin(Request $request, Aluno $aluno, Responsavel $responsavel)
    {
        abort_if($responsavel->aluno_id !== $aluno->id, 403);

        $request->validate([
            'email'    => 'required|email|max:150',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'E-mail obrigatório.',
            'email.email'       => 'E-mail inválido.',
            'password.required' => 'Senha obrigatória.',
            'password.min'      => 'Senha mínimo 6 caracteres.',
        ]);

        // Email único no tenant
        $exists = Usuario::withoutGlobalScope('tenant')
            ->where('email', $request->email)
            ->where('tenant_id', session('tenant_id'))
            ->where('id', '!=', $responsavel->usuario_id ?? 0)
            ->exists();

        if ($exists) {
            return back()->withErrors(['email_resp_' . $responsavel->id => 'E-mail já cadastrado nesta escola.'])->withInput();
        }

        DB::transaction(function () use ($request, $responsavel) {
            // Se já tem usuario, atualiza credenciais
            if ($responsavel->usuario_id) {
                Usuario::withoutGlobalScope('tenant')
                    ->where('id', $responsavel->usuario_id)
                    ->update([
                        'email'      => $request->email,
                        'senha_hash' => Hash::make($request->password),
                        'status'     => 1,
                    ]);
            } else {
                $usuario = Usuario::create([
                    'tenant_id'  => session('tenant_id'),
                    'nome'       => $responsavel->nome,
                    'email'      => $request->email,
                    'senha_hash' => Hash::make($request->password),
                    'perfil'     => 'responsavel',
                    'status'     => 1,
                ]);

                $responsavel->timestamps = false;
                $responsavel->usuario_id = $usuario->id;
                $responsavel->save();
            }
        });

        return back()->with('success', 'Acesso ao portal criado para ' . $responsavel->nome . '.');
    }

    public function revogarLogin(Aluno $aluno, Responsavel $responsavel)
    {
        abort_if($responsavel->aluno_id !== $aluno->id, 403);

        if ($responsavel->usuario_id) {
            Usuario::withoutGlobalScope('tenant')
                ->where('id', $responsavel->usuario_id)
                ->update(['status' => 0]);
        }

        return back()->with('success', 'Acesso revogado.');
    }

    public function destroy(Aluno $aluno, Responsavel $responsavel)
    {
        abort_if($responsavel->aluno_id !== $aluno->id, 403);

        // Revoga login se existir
        if ($responsavel->usuario_id) {
            Usuario::withoutGlobalScope('tenant')
                ->where('id', $responsavel->usuario_id)
                ->update(['status' => 0]);
        }

        $responsavel->delete();

        return back()->with('success', 'Responsável removido.');
    }
}
