<?php

namespace App\Http\Controllers;

use App\Models\TurmaDisiplina;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ProfessorController extends Controller
{
    public function index()
    {
        $professores = Usuario::withoutGlobalScope('tenant')
            ->where('tenant_id', session('tenant_id'))
            ->where('perfil', 'professor')
            ->orderBy('nome')
            ->paginate(20);

        return view('professores.index', compact('professores'));
    }

    public function create()
    {
        return view('professores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'     => 'required|string|max:200',
            'email'    => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'nome.required'      => 'Nome obrigatório.',
            'email.unique'       => 'E-mail já cadastrado.',
            'password.confirmed' => 'Confirmação de senha não confere.',
        ]);

        Usuario::create([
            'tenant_id'  => session('tenant_id'),
            'nome'       => $request->nome,
            'email'      => $request->email,
            'senha_hash' => bcrypt($request->password),
            'perfil'     => 'professor',
            'status'     => 1,
        ]);

        return redirect()->route('professores.index')->with('success', 'Professor cadastrado!');
    }

    public function show(Usuario $usuario)
    {
        return redirect()->route('professores.edit', $usuario);
    }

    public function minhasTurmas()
    {
        $turmaDiscs = TurmaDisiplina::with(['turma.curso', 'turma.periodo', 'disciplina'])
            ->withCount('aulas')
            ->whereHas('turma', fn($q) => $q->where('tenant_id', session('tenant_id')))
            ->where('professor_id', session('usuario_id'))
            ->get()
            ->groupBy('turma_id');

        return view('professores.minhas-turmas', compact('turmaDiscs'));
    }

    public function edit(Usuario $usuario)
    {
        abort_if($usuario->tenant_id !== session('tenant_id') || $usuario->perfil !== 'professor', 403);
        return view('professores.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        abort_if($usuario->tenant_id !== session('tenant_id') || $usuario->perfil !== 'professor', 403);

        $request->validate([
            'nome'     => 'required|string|max:200',
            'email'    => 'required|email|unique:usuarios,email,' . $usuario->id,
            'password' => 'nullable|min:6|confirmed',
            'status'   => 'required|in:0,1',
        ]);

        $data = $request->only(['nome', 'email', 'status']);
        if ($request->filled('password')) {
            $data['senha_hash'] = bcrypt($request->password);
        }
        $usuario->update($data);

        return redirect()->route('professores.index')->with('success', 'Professor atualizado!');
    }

    public function destroy(Usuario $usuario)
    {
        abort_if($usuario->tenant_id !== session('tenant_id') || $usuario->perfil !== 'professor', 403);
        $usuario->update(['status' => 0]);
        return redirect()->route('professores.index')->with('success', 'Professor desativado.');
    }
}
