<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Aula;
use App\Models\Matricula;
use App\Models\PeriodoLetivo;
use App\Models\TurmaDisiplina;
use App\Models\Turma;

class DashboardController extends Controller
{
    public function index()
    {
        if (session('usuario_perfil') === 'professor') {
            return $this->professorDashboard();
        }

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

    private function professorDashboard()
    {
        $professorId = session('usuario_id');
        $tenantId    = session('tenant_id');

        $turmaDiscs = TurmaDisiplina::with(['turma.curso', 'turma.periodo', 'disciplina'])
            ->whereHas('turma', fn($q) => $q->where('tenant_id', $tenantId))
            ->where('professor_id', $professorId)
            ->get();

        $tdIds = $turmaDiscs->pluck('id');

        $ultimasAulas = Aula::whereIn('turma_disc_id', $tdIds)
            ->orderByDesc('data_aula')
            ->limit(6)
            ->get()
            ->map(fn($a) => $a->setRelation('turmaDisc', $turmaDiscs->firstWhere('id', $a->turma_disc_id)));

        $stats = [
            'turmas'      => $turmaDiscs->pluck('turma_id')->unique()->count(),
            'disciplinas' => $turmaDiscs->count(),
            'aulas_mes'   => Aula::whereIn('turma_disc_id', $tdIds)
                                 ->where('data_aula', '>=', now()->startOfMonth())
                                 ->count(),
        ];

        return view('dashboard.professor', compact('turmaDiscs', 'ultimasAulas', 'stats'));
    }
}
