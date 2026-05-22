<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Turma;
use App\Models\Matricula;
use App\Models\PeriodoLetivo;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'alunos'     => Aluno::count(),
            'turmas'     => Turma::count(),
            'matriculas' => Matricula::where('status', 'ativa')->count(),
            'periodos'   => PeriodoLetivo::where('status', 'ativo')->count(),
        ];

        $turmas_recentes = Turma::with('curso', 'periodo')
            ->latest('criado_em')
            ->limit(5)
            ->get();

        $matriculas_recentes = Matricula::with('aluno.usuario', 'turma')
            ->latest('criado_em')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'turmas_recentes', 'matriculas_recentes'));
    }
}
