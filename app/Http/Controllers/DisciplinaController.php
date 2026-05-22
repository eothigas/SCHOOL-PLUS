<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Disciplina;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function index(Request $request)
    {
        $query = Disciplina::with('curso')->where('status', 1);

        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%');
        }
        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        $disciplinas = $query->orderBy('nome')->paginate(20)->withQueryString();
        $cursos = Curso::where('status', 1)->orderBy('nome')->get();

        return view('disciplinas.index', compact('disciplinas', 'cursos'));
    }

    public function create()
    {
        $cursos = Curso::where('status', 1)->orderBy('nome')->get();
        return view('disciplinas.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'curso_id'      => 'required|exists:cursos,id',
            'nome'          => 'required|string|max:200',
            'codigo'        => 'nullable|string|max:20',
            'carga_horaria' => 'nullable|integer|min:1|max:9999',
            'ementa'        => 'nullable|string',
        ], [
            'curso_id.required' => 'Curso obrigatório.',
            'nome.required'     => 'Nome obrigatório.',
        ]);

        Disciplina::create($request->only(['curso_id', 'nome', 'codigo', 'carga_horaria', 'ementa']));

        return redirect()->route('disciplinas.index')->with('success', 'Disciplina criada com sucesso!');
    }

    public function show(Disciplina $disciplina)
    {
        return redirect()->route('disciplinas.edit', $disciplina);
    }

    public function edit(Disciplina $disciplina)
    {
        $cursos = Curso::where('status', 1)->orderBy('nome')->get();
        return view('disciplinas.edit', compact('disciplina', 'cursos'));
    }

    public function update(Request $request, Disciplina $disciplina)
    {
        $request->validate([
            'curso_id'      => 'required|exists:cursos,id',
            'nome'          => 'required|string|max:200',
            'codigo'        => 'nullable|string|max:20',
            'carga_horaria' => 'nullable|integer|min:1|max:9999',
            'ementa'        => 'nullable|string',
            'status'        => 'required|in:0,1',
        ]);

        $disciplina->update($request->only(['curso_id', 'nome', 'codigo', 'carga_horaria', 'ementa', 'status']));

        return redirect()->route('disciplinas.index')->with('success', 'Disciplina atualizada!');
    }

    public function destroy(Disciplina $disciplina)
    {
        $disciplina->update(['status' => 0]);
        return redirect()->route('disciplinas.index')->with('success', 'Disciplina desativada.');
    }
}
