<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\TurmaController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\PeriodoLetivoController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\TurmaDisiplinaController;
use App\Http\Controllers\DiarioController;
use App\Http\Controllers\BoletimController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\PlanoPagamentoController;
use App\Http\Controllers\CobrancaController;
use App\Http\Controllers\NegociacaoController;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// App (autenticado)
Route::middleware('auth.session')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin,secretaria,superadmin')->group(function () {

        // ── Fase 1 ──────────────────────────────────────────
        Route::resource('alunos', AlunoController::class);
        Route::resource('turmas', TurmaController::class);
        Route::resource('matriculas', MatriculaController::class)->except(['edit', 'destroy']);
        Route::patch('matriculas/{matricula}/status', [MatriculaController::class, 'update'])->name('matriculas.status');
        Route::resource('cursos', CursoController::class);
        Route::resource('periodos', PeriodoLetivoController::class);

        // ── Fase 2 — Diário de Classe ────────────────────────
        Route::resource('disciplinas', DisciplinaController::class);
        Route::resource('professores', ProfessorController::class)->parameters(['professores' => 'usuario']);

        // Disciplinas de uma turma
        Route::get('turmas/{turma}/disciplinas',        [TurmaDisiplinaController::class, 'index'])->name('turmas.disciplinas');
        Route::post('turmas/{turma}/disciplinas',       [TurmaDisiplinaController::class, 'store'])->name('turmas.disciplinas.store');
        Route::delete('turmas/{turma}/disciplinas/{td}',[TurmaDisiplinaController::class, 'destroy'])->name('turmas.disciplinas.destroy');
        Route::patch('turmas/{turma}/disciplinas/{td}/professor', [TurmaDisiplinaController::class, 'updateProfessor'])->name('turmas.disciplinas.professor');

        // Diário (aulas + frequência + notas)
        Route::prefix('diario/{td}')->name('diario.')->group(function () {
            Route::get('/',                               [DiarioController::class, 'index'])->name('index');
            Route::post('/aulas',                         [DiarioController::class, 'storeAula'])->name('aulas.store');
            Route::get('/aulas/{aula}',                   [DiarioController::class, 'showAula'])->name('aula');
            Route::patch('/aulas/{aula}/frequencia',      [DiarioController::class, 'salvarFrequencia'])->name('frequencia');
            Route::post('/avaliacoes',                    [DiarioController::class, 'storeAvaliacao'])->name('avaliacoes.store');
            Route::get('/avaliacoes/{avaliacao}/notas',   [DiarioController::class, 'lancarNotas'])->name('notas');
            Route::post('/avaliacoes/{avaliacao}/notas',  [DiarioController::class, 'salvarNotas'])->name('notas.salvar');
        });

        // Boletim do aluno
        Route::get('alunos/{aluno}/boletim', [BoletimController::class, 'show'])->name('boletim.show');

        // ── Fase 3 — Financeiro ──────────────────────────────
        Route::get('financeiro', [FinanceiroController::class, 'index'])->name('financeiro.index');

        Route::resource('planos', PlanoPagamentoController::class);

        // Cobrancas: gerar antes do resource para evitar conflito de rota
        Route::get('cobrancas/gerar',  [CobrancaController::class, 'gerarForm'])->name('cobrancas.gerar');
        Route::post('cobrancas/gerar', [CobrancaController::class, 'gerarLote'])->name('cobrancas.gerar.store');
        Route::resource('cobrancas', CobrancaController::class)->except(['edit', 'update', 'destroy']);
        Route::post('cobrancas/{cobranca}/pagar',    [CobrancaController::class, 'pagar'])->name('cobrancas.pagar');
        Route::post('cobrancas/{cobranca}/cancelar', [CobrancaController::class, 'cancelar'])->name('cobrancas.cancelar');

        Route::resource('negociacoes', NegociacaoController::class)->only(['index', 'create', 'store', 'show']);
    });
});
