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
use App\Http\Controllers\ResponsavelController;
use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\Portal\PortalAuthController;
use App\Http\Controllers\Portal\PortalController;

// ── Portal do Aluno / Responsável ───────────────────────────────
Route::prefix('portal')->name('portal.')->group(function () {

    // Auth (sem middleware)
    Route::get('/login',  [PortalAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [PortalAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[PortalAuthController::class, 'logout'])->name('logout');

    // Autenticado
    Route::middleware('portal.auth')->group(function () {
        Route::get('/',           [PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/boletim',    [PortalController::class, 'boletim'])->name('boletim');
        Route::get('/cobrancas',  [PortalController::class, 'cobrancas'])->name('cobrancas');
        Route::get('/frequencia',   [PortalController::class, 'frequencia'])->name('frequencia');
        Route::get('/comunicados',  [PortalController::class, 'comunicados'])->name('comunicados');
        Route::post('/comunicados/{comunicado}/lida', [PortalController::class, 'marcarLida'])->name('comunicados.lida');
        Route::get('/documentos',   [PortalController::class, 'documentos'])->name('documentos');
        Route::post('/trocar-aluno', [PortalAuthController::class, 'trocarAluno'])->name('trocar-aluno');
    });
});

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// App (autenticado)
Route::middleware('auth.session')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ── Rotas compartilhadas: admin + professor ──────────────
    Route::middleware('role:admin,secretaria,superadmin,professor')->group(function () {

        // Diário — professor só acessa suas próprias turmas/disciplinas (filtrado no DiarioController)
        Route::prefix('diario/{td}')->name('diario.')->group(function () {
            Route::get('/',                               [DiarioController::class, 'index'])->name('index');
            Route::post('/aulas',                         [DiarioController::class, 'storeAula'])->name('aulas.store');
            Route::get('/aulas/{aula}',                   [DiarioController::class, 'showAula'])->name('aula');
            Route::patch('/aulas/{aula}/frequencia',      [DiarioController::class, 'salvarFrequencia'])->name('frequencia');
            Route::post('/avaliacoes',                    [DiarioController::class, 'storeAvaliacao'])->name('avaliacoes.store');
            Route::get('/avaliacoes/{avaliacao}/notas',   [DiarioController::class, 'lancarNotas'])->name('notas');
            Route::post('/avaliacoes/{avaliacao}/notas',  [DiarioController::class, 'salvarNotas'])->name('notas.salvar');
        });

        // Boletim — professor pode consultar boletim dos alunos
        Route::get('alunos/{aluno}/boletim', [BoletimController::class, 'show'])->name('boletim.show');

        // Minhas turmas/disciplinas (lista do professor)
        Route::get('professor/minhas-turmas', [ProfessorController::class, 'minhasTurmas'])->name('professor.minhas-turmas');
    });

    Route::middleware('role:admin,secretaria,superadmin')->group(function () {

        // ── Fase 1 ──────────────────────────────────────────
        Route::resource('alunos', AlunoController::class);
        Route::resource('turmas', TurmaController::class);
        Route::resource('matriculas', MatriculaController::class)->except(['edit', 'destroy']);
        Route::patch('matriculas/{matricula}/status', [MatriculaController::class, 'update'])->name('matriculas.status');
        Route::resource('cursos', CursoController::class);
        Route::resource('periodos', PeriodoLetivoController::class);

        // ── Fase 2 - Diário de Classe ────────────────────────
        Route::resource('disciplinas', DisciplinaController::class);
        Route::resource('professores', ProfessorController::class)->parameters(['professores' => 'usuario']);

        // Disciplinas de uma turma
        Route::get('turmas/{turma}/disciplinas',        [TurmaDisiplinaController::class, 'index'])->name('turmas.disciplinas');
        Route::post('turmas/{turma}/disciplinas',       [TurmaDisiplinaController::class, 'store'])->name('turmas.disciplinas.store');
        Route::delete('turmas/{turma}/disciplinas/{td}',[TurmaDisiplinaController::class, 'destroy'])->name('turmas.disciplinas.destroy');
        Route::patch('turmas/{turma}/disciplinas/{td}/professor', [TurmaDisiplinaController::class, 'updateProfessor'])->name('turmas.disciplinas.professor');

        // ── Fase 4 - Comunicados ─────────────────────────────────
        Route::get('comunicados', [ComunicadoController::class, 'index'])->name('comunicados.index');
        Route::post('comunicados', [ComunicadoController::class, 'store'])->name('comunicados.store');
        Route::post('comunicados/{comunicado}/toggle', [ComunicadoController::class, 'toggle'])->name('comunicados.toggle');
        Route::delete('comunicados/{comunicado}', [ComunicadoController::class, 'destroy'])->name('comunicados.destroy');

        // ── Fase 4 - Documentos ──────────────────────────────────
        Route::post('alunos/{aluno}/documentos', [DocumentoController::class, 'store'])->name('documentos.store');
        Route::delete('alunos/{aluno}/documentos/{documento}', [DocumentoController::class, 'destroy'])->name('documentos.destroy');

        // Responsáveis
        Route::post('alunos/{aluno}/responsaveis',                               [ResponsavelController::class, 'store'])->name('responsaveis.store');
        Route::post('alunos/{aluno}/responsaveis/{responsavel}/login',           [ResponsavelController::class, 'criarLogin'])->name('responsaveis.login');
        Route::post('alunos/{aluno}/responsaveis/{responsavel}/revogar',         [ResponsavelController::class, 'revogarLogin'])->name('responsaveis.revogar');
        Route::delete('alunos/{aluno}/responsaveis/{responsavel}',               [ResponsavelController::class, 'destroy'])->name('responsaveis.destroy');

        // ── Fase 3 - Financeiro ──────────────────────────────
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
