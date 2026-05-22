<?php

namespace App\Http\Controllers;

use App\Models\Turma;
use App\Models\Curso;
use App\Models\PeriodoLetivo;
use Illuminate\Http\Request;

class TurmaController extends Controller
{
    public function index(Request $request)
    {
        $query = Turma::with('curso', 'periodo');

        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $turmas = $query->orderBy('nome')->paginate(20)->withQueryString();

        return view('turmas.index', compact('turmas'));
    }

    public function create()
    {
        $cursos  = Curso::where('status', 1)->orderBy('nome')->get();
        $periodos = PeriodoLetivo::orderByDesc('data_inicio')->get();

        return view('turmas.create', compact('cursos', 'periodos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'curso_id'   => 'required|integer',
            'periodo_id' => 'required|integer',
            'nome'       => 'required|string|max:100',
            'turno'      => 'required|in:manha,tarde,noite,integral,ead',
            'vagas'      => 'required|integer|min:1|max:9999',
            'sala'       => 'nullable|string|max:50',
        ]);

        Turma::create($request->only([
            'curso_id', 'periodo_id', 'nome', 'turno', 'vagas', 'sala',
        ]));

        return redirect()->route('turmas.index')->with('success', 'Turma criada com sucesso!');
    }

    public function show(Turma $turma)
    {
        $turma->load('curso', 'periodo', 'matriculas.aluno.usuario');

        return view('turmas.show', compact('turma'));
    }

    public function edit(Turma $turma)
    {
        $cursos   = Curso::where('status', 1)->orderBy('nome')->get();
        $periodos = PeriodoLetivo::orderByDesc('data_inicio')->get();

        return view('turmas.edit', compact('turma', 'cursos', 'periodos'));
    }

    public function update(Request $request, Turma $turma)
    {
        $request->validate([
            'curso_id'   => 'required|integer',
            'periodo_id' => 'required|integer',
            'nome'       => 'required|string|max:100',
            'turno'      => 'required|in:manha,tarde,noite,integral,ead',
            'vagas'      => 'required|integer|min:1',
            'sala'       => 'nullable|string|max:50',
            'status'     => 'required|in:aberta,em_andamento,encerrada',
        ]);

        $turma->update($request->only([
            'curso_id', 'periodo_id', 'nome', 'turno', 'vagas', 'sala', 'status',
        ]));

        return redirect()->route('turmas.show', $turma)->with('success', 'Turma atualizada!');
    }

    public function destroy(Turma $turma)
    {
        $turma->update(['status' => 'encerrada']);

        return redirect()->route('turmas.index')->with('success', 'Turma encerrada.');
    }
}
