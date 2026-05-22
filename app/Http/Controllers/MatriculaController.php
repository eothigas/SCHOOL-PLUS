<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Aluno;
use App\Models\Turma;
use App\Models\PeriodoLetivo;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index(Request $request)
    {
        $query = Matricula::with('aluno.usuario', 'turma.curso', 'periodo');

        if ($request->filled('busca')) {
            $query->whereHas('aluno.usuario', function ($q) use ($request) {
                $q->where('nome', 'like', '%' . $request->busca . '%');
            })->orWhereHas('aluno', function ($q) use ($request) {
                $q->where('matricula', 'like', '%' . $request->busca . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $matriculas = $query->latest('criado_em')->paginate(20)->withQueryString();

        return view('matriculas.index', compact('matriculas'));
    }

    public function create(Request $request)
    {
        $alunos   = Aluno::with('usuario')->get();
        $turmas   = Turma::with('curso')->where('status', '!=', 'encerrada')->orderBy('nome')->get();
        $periodos = PeriodoLetivo::orderByDesc('data_inicio')->get();

        // Pre-selecionar aluno se vier do perfil do aluno
        $aluno_selecionado = $request->filled('aluno_id')
            ? Aluno::find($request->aluno_id)
            : null;

        return view('matriculas.create', compact('alunos', 'turmas', 'periodos', 'aluno_selecionado'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aluno_id'       => 'required|integer',
            'turma_id'       => 'required|integer',
            'periodo_id'     => 'required|integer',
            'data_matricula' => 'required|date',
        ]);

        // Verifica duplicata
        $existe = Matricula::where('aluno_id', $request->aluno_id)
            ->where('turma_id', $request->turma_id)
            ->where('status', 'ativa')
            ->exists();

        if ($existe) {
            return back()->withErrors(['aluno_id' => 'Aluno já matriculado nesta turma.'])->withInput();
        }

        Matricula::create($request->only([
            'aluno_id', 'turma_id', 'periodo_id', 'data_matricula', 'obs',
        ]));

        return redirect()->route('matriculas.index')->with('success', 'Matrícula realizada!');
    }

    public function show(Matricula $matricula)
    {
        $matricula->load('aluno.usuario', 'turma.curso', 'periodo');

        return view('matriculas.show', compact('matricula'));
    }

    public function update(Request $request, Matricula $matricula)
    {
        $request->validate([
            'status' => 'required|in:ativa,trancada,cancelada,concluida,transferida',
        ]);

        $matricula->update(['status' => $request->status, 'obs' => $request->obs]);

        return redirect()->route('matriculas.show', $matricula)->with('success', 'Matrícula atualizada!');
    }
}
