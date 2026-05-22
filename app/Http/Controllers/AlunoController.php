<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AlunoController extends Controller
{
    public function index(Request $request)
    {
        $query = Aluno::with('usuario')
            ->join('usuarios', 'alunos.usuario_id', '=', 'usuarios.id')
            ->select('alunos.*');

        if ($request->filled('busca')) {
            $busca = '%' . $request->busca . '%';
            $query->where(function ($q) use ($busca) {
                $q->where('usuarios.nome', 'like', $busca)
                  ->orWhere('alunos.matricula', 'like', $busca)
                  ->orWhere('usuarios.email', 'like', $busca);
            });
        }

        $alunos = $query->orderBy('usuarios.nome')->paginate(20)->withQueryString();

        return view('alunos.index', compact('alunos'));
    }

    public function create()
    {
        return view('alunos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'            => 'required|string|max:150',
            'email'           => 'required|email|max:150',
            'password'        => 'required|min:6',
            'matricula'       => 'required|string|max:30',
            'data_nascimento' => 'nullable|date',
            'sexo'            => 'nullable|in:M,F,outro,nao_informado',
            'cpf'             => 'nullable|string|max:14',
            'telefone'        => 'nullable|string|max:20',
        ], [
            'nome.required'     => 'Nome obrigatório.',
            'email.required'    => 'E-mail obrigatório.',
            'email.email'       => 'E-mail inválido.',
            'password.required' => 'Senha obrigatória.',
            'password.min'      => 'Senha mínimo 6 caracteres.',
            'matricula.required' => 'Matrícula obrigatória.',
        ]);

        // Check email unique within tenant
        $exists = Usuario::withoutGlobalScope('tenant')
            ->where('email', $request->email)
            ->where('tenant_id', session('tenant_id'))
            ->exists();

        if ($exists) {
            return back()->withErrors(['email' => 'E-mail já cadastrado nesta escola.'])->withInput();
        }

        DB::transaction(function () use ($request) {
            $usuario = Usuario::create([
                'tenant_id'  => session('tenant_id'),
                'nome'       => $request->nome,
                'email'      => $request->email,
                'senha_hash' => Hash::make($request->password),
                'perfil'     => 'aluno',
                'cpf'        => $request->cpf,
                'telefone'   => $request->telefone,
                'status'     => 1,
            ]);

            Aluno::create([
                'tenant_id'       => session('tenant_id'),
                'usuario_id'      => $usuario->id,
                'matricula'       => $request->matricula,
                'data_nascimento' => $request->data_nascimento,
                'sexo'            => $request->sexo,
                'cpf'             => $request->cpf,
                'nome_pai'        => $request->nome_pai,
                'nome_mae'        => $request->nome_mae,
                'endereco'        => $request->endereco,
                'cidade'          => $request->cidade,
                'estado'          => $request->estado,
                'cep'             => $request->cep,
            ]);
        });

        return redirect()->route('alunos.index')->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function show(Aluno $aluno)
    {
        $aluno->load('usuario', 'matriculas.turma.curso', 'matriculas.periodo', 'responsaveis');

        return view('alunos.show', compact('aluno'));
    }

    public function edit(Aluno $aluno)
    {
        $aluno->load('usuario');

        return view('alunos.edit', compact('aluno'));
    }

    public function update(Request $request, Aluno $aluno)
    {
        $request->validate([
            'nome'            => 'required|string|max:150',
            'data_nascimento' => 'nullable|date',
            'sexo'            => 'nullable|in:M,F,outro,nao_informado',
            'cpf'             => 'nullable|string|max:14',
            'telefone'        => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, $aluno) {
            $aluno->usuario->update([
                'nome'     => $request->nome,
                'telefone' => $request->telefone,
                'cpf'      => $request->cpf,
            ]);

            $aluno->update([
                'data_nascimento' => $request->data_nascimento,
                'sexo'            => $request->sexo,
                'cpf'             => $request->cpf,
                'nome_pai'        => $request->nome_pai,
                'nome_mae'        => $request->nome_mae,
                'endereco'        => $request->endereco,
                'cidade'          => $request->cidade,
                'estado'          => $request->estado,
                'cep'             => $request->cep,
            ]);
        });

        return redirect()->route('alunos.show', $aluno)->with('success', 'Aluno atualizado!');
    }

    public function destroy(Aluno $aluno)
    {
        // Soft delete: desativa usuario em vez de deletar
        $aluno->usuario->update(['status' => 0]);

        return redirect()->route('alunos.index')->with('success', 'Aluno desativado.');
    }
}
