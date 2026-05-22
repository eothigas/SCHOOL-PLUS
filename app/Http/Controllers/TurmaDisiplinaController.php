<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Turma;
use App\Models\TurmaDisiplina;
use App\Models\Usuario;
use Illuminate\Http\Request;

class TurmaDisiplinaController extends Controller
{
    public function index(Turma $turma)
    {
        $turma->load('turmaDisiplinas.disciplina', 'turmaDisiplinas.professor');
        $disciplinas_disponiveis = Disciplina::where('status', 1)->orderBy('nome')->get();
        $professores = Usuario::withoutGlobalScope('tenant')
            ->where('tenant_id', session('tenant_id'))
            ->where('perfil', 'professor')
            ->where('status', 1)
            ->orderBy('nome')
            ->get();

        return view('turmas.disciplinas', compact('turma', 'disciplinas_disponiveis', 'professores'));
    }

    public function store(Request $request, Turma $turma)
    {
        $request->validate(['disciplina_id' => 'required|exists:disciplinas,id']);

        TurmaDisiplina::firstOrCreate(
            ['turma_id' => $turma->id, 'disciplina_id' => $request->disciplina_id],
            ['professor_id' => $request->professor_id ?: null]
        );

        return back()->with('success', 'Disciplina adicionada!');
    }

    public function destroy(Turma $turma, TurmaDisiplina $td)
    {
        $td->delete();
        return back()->with('success', 'Disciplina removida.');
    }

    public function updateProfessor(Request $request, Turma $turma, TurmaDisiplina $td)
    {
        $td->update(['professor_id' => $request->professor_id ?: null]);
        return back()->with('success', 'Professor atualizado!');
    }
}
