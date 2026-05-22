<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $query = Curso::query();

        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $cursos = $query->orderBy('nome')->paginate(20)->withQueryString();

        return view('cursos.index', compact('cursos'));
    }

    public function create()
    {
        return view('cursos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'          => 'required|string|max:200',
            'tipo'          => 'required|in:fundamental,medio,graduacao,pos_graduacao,tecnico,livre',
            'duracao_meses' => 'nullable|integer|min:1',
            'descricao'     => 'nullable|string',
        ], [
            'nome.required' => 'Nome obrigatório.',
            'tipo.required' => 'Tipo obrigatório.',
        ]);

        Curso::create($request->only(['nome', 'tipo', 'duracao_meses', 'descricao']));

        return redirect()->route('cursos.index')->with('success', 'Curso criado com sucesso!');
    }

    public function show(Curso $curso)
    {
        $curso->load('turmas.periodo');
        $total_alunos = $curso->turmas->sum(fn($t) => $t->matriculas()->count());

        return view('cursos.show', compact('curso', 'total_alunos'));
    }

    public function edit(Curso $curso)
    {
        return view('cursos.edit', compact('curso'));
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nome'          => 'required|string|max:200',
            'tipo'          => 'required|in:fundamental,medio,graduacao,pos_graduacao,tecnico,livre',
            'duracao_meses' => 'nullable|integer|min:1',
            'descricao'     => 'nullable|string',
            'status'        => 'required|in:0,1',
        ]);

        $curso->update($request->only(['nome', 'tipo', 'duracao_meses', 'descricao', 'status']));

        return redirect()->route('cursos.show', $curso)->with('success', 'Curso atualizado!');
    }

    public function destroy(Curso $curso)
    {
        $curso->update(['status' => 0]);

        return redirect()->route('cursos.index')->with('success', 'Curso desativado.');
    }
}
