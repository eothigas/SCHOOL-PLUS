<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Aluno;
use App\Models\Aula;
use App\Models\Cobranca;
use App\Models\Comunicado;
use App\Models\ComunicadoLeitura;
use App\Models\DocumentoAluno;
use App\Models\Frequencia;
use App\Models\Matricula;

class PortalController extends Controller
{
    // ── helpers ────────────────────────────────────────────────

    private function getAluno(): Aluno
    {
        return Aluno::withoutGlobalScope('tenant')
            ->with('usuario')
            ->findOrFail(session('portal_aluno_id'));
    }

    private function getAlunos(): \Illuminate\Database\Eloquent\Collection
    {
        $ids = session('portal_aluno_ids', [session('portal_aluno_id')]);

        return Aluno::withoutGlobalScope('tenant')
            ->with('usuario')
            ->whereIn('id', $ids)
            ->get();
    }

    // ── Dashboard ───────────────────────────────────────────────

    public function dashboard()
    {
        $aluno = $this->getAluno();

        $matriculaAtiva = Matricula::withoutGlobalScope('tenant')
            ->with('turma.curso', 'turma.periodo')
            ->where('aluno_id', $aluno->id)
            ->where('status', 'ativa')
            ->latest('data_matricula')
            ->first();

        // Cobranças abertas
        $cobrancasPendentes = Cobranca::withoutGlobalScope('tenant')
            ->where('tenant_id', session('portal_tenant_id'))
            ->whereHas('matricula', fn($q) => $q->where('aluno_id', $aluno->id))
            ->where('status', 'aberta')
            ->orderBy('data_vencimento')
            ->take(5)
            ->get();

        $totalDevido = Cobranca::withoutGlobalScope('tenant')
            ->where('tenant_id', session('portal_tenant_id'))
            ->whereHas('matricula', fn($q) => $q->where('aluno_id', $aluno->id))
            ->where('status', 'aberta')
            ->sum('valor_original');

        // Frequência geral (última matrícula ativa)
        $freqPct = null;
        if ($matriculaAtiva) {
            $totalAulas = Aula::withoutGlobalScope('tenant')
                ->whereHas('turmaDisiplina', fn($q) => $q->where('turma_id', $matriculaAtiva->turma_id))
                ->count();


            $presencas = Frequencia::withoutGlobalScope('tenant')
                ->where('matricula_id', $matriculaAtiva->id)
                ->where('presente', 1)
                ->count();

            $freqPct = $totalAulas > 0 ? round($presencas / $totalAulas * 100, 1) : null;
        }

        $alunos = session('portal_tipo') === 'responsavel' ? $this->getAlunos() : collect();

        return view('portal.dashboard', compact(
            'aluno', 'matriculaAtiva', 'cobrancasPendentes', 'totalDevido', 'freqPct', 'alunos'
        ));
    }

    // ── Boletim ─────────────────────────────────────────────────

    public function boletim()
    {
        $aluno = $this->getAluno();

        $matriculas = Matricula::withoutGlobalScope('tenant')
            ->with([
                'turma.curso',
                'turma.turmaDisiplinas.disciplina',
                'turma.turmaDisiplinas.avaliacoes.notas',
                'turma.turmaDisiplinas.professor',
            ])
            ->where('aluno_id', $aluno->id)
            ->get();

        $boletim = [];
        foreach ($matriculas as $mat) {
            $turmaData = ['matricula' => $mat, 'turma' => $mat->turma, 'disciplinas' => []];

            foreach ($mat->turma->turmaDisiplinas as $td) {
                $total_aulas = Aula::withoutGlobalScope('tenant')->where('turma_disc_id', $td->id)->count();
                $presencas   = Frequencia::withoutGlobalScope('tenant')
                    ->whereHas('aula', fn($q) => $q->where('turma_disc_id', $td->id))
                    ->where('matricula_id', $mat->id)
                    ->where('presente', 1)
                    ->count();

                $disciplinaData = [
                    'td'          => $td,
                    'disciplina'  => $td->disciplina,
                    'total_aulas' => $total_aulas,
                    'presencas'   => $presencas,
                    'ausencias'   => $total_aulas - $presencas,
                    'freq_pct'    => $total_aulas > 0 ? round($presencas / $total_aulas * 100, 1) : null,
                    'avaliacoes'  => [],
                    'media'       => null,
                ];

                $soma_pesos = 0;
                $soma_notas = 0;
                $tem_nota   = false;

                foreach ($td->avaliacoes as $av) {
                    $nota     = $av->notas->where('matricula_id', $mat->id)->first();
                    $nota_val = $nota?->nota;
                    $disciplinaData['avaliacoes'][] = ['avaliacao' => $av, 'nota' => $nota_val];

                    if ($nota_val !== null) {
                        $normalizada = $av->nota_maxima > 0 ? ($nota_val / $av->nota_maxima) * 10 : 0;
                        $soma_notas += $normalizada * $av->peso;
                        $soma_pesos += $av->peso;
                        $tem_nota    = true;
                    }
                }

                if ($tem_nota && $soma_pesos > 0) {
                    $disciplinaData['media'] = round($soma_notas / $soma_pesos, 1);
                }

                $turmaData['disciplinas'][] = $disciplinaData;
            }

            $boletim[] = $turmaData;
        }

        return view('portal.boletim', compact('aluno', 'boletim'));
    }

    // ── Cobranças ───────────────────────────────────────────────

    public function cobrancas()
    {
        abort_if(session('portal_tipo') !== 'responsavel', 403);

        $aluno = $this->getAluno();

        $cobrancas = Cobranca::withoutGlobalScope('tenant')
            ->where('tenant_id', session('portal_tenant_id'))
            ->whereHas('matricula', fn($q) => $q->where('aluno_id', $aluno->id))
            ->with('matricula.turma.curso', 'plano')
            ->orderByRaw("FIELD(status,'aberta','negociada','paga','cancelada')")
            ->orderBy('data_vencimento', 'desc')
            ->get();

        $totalAberto = $cobrancas->where('status', 'aberta')->sum('valor_original');
        $totalPago   = $cobrancas->where('status', 'paga')->sum('valor_pago');

        return view('portal.cobrancas', compact('aluno', 'cobrancas', 'totalAberto', 'totalPago'));
    }

    // ── Frequência ──────────────────────────────────────────────

    public function frequencia()
    {
        $aluno = $this->getAluno();

        $matriculas = Matricula::withoutGlobalScope('tenant')
            ->with(['turma.turmaDisiplinas.disciplina', 'turma.curso'])
            ->where('aluno_id', $aluno->id)
            ->get();

        $dados = [];
        foreach ($matriculas as $mat) {
            $disciplinas = [];
            foreach ($mat->turma->turmaDisiplinas as $td) {
                $aulas = Aula::withoutGlobalScope('tenant')
                    ->where('turma_disc_id', $td->id)
                    ->with(['frequencias' => fn($q) => $q->withoutGlobalScope('tenant')->where('matricula_id', $mat->id)])
                    ->orderBy('data_aula')
                    ->get();

                $total    = $aulas->count();
                $presentes = $aulas->filter(fn($a) => $a->frequencias->first()?->presente == 1)->count();

                $disciplinas[] = [
                    'disciplina' => $td->disciplina,
                    'aulas'      => $aulas,
                    'total'      => $total,
                    'presentes'  => $presentes,
                    'ausencias'  => $total - $presentes,
                    'pct'        => $total > 0 ? round($presentes / $total * 100, 1) : null,
                ];
            }

            $dados[] = [
                'matricula'   => $mat,
                'turma'       => $mat->turma,
                'disciplinas' => $disciplinas,
            ];
        }

        return view('portal.frequencia', compact('aluno', 'dados'));
    }

    // ── Comunicados ─────────────────────────────────────────────

    public function comunicados()
    {
        $aluno    = $this->getAluno();
        $tipo     = session('portal_tipo');
        $usuarioId = session('portal_usuario_id');

        // Turma da matrícula ativa
        $turmaId = Matricula::withoutGlobalScope('tenant')
            ->where('aluno_id', $aluno->id)
            ->where('status', 'ativa')
            ->value('turma_id');

        $destinosAluno       = ['todos', 'alunos'];
        $destinosResponsavel = ['todos', 'responsaveis'];
        $destinos = $tipo === 'responsavel' ? $destinosResponsavel : $destinosAluno;

        $comunicados = Comunicado::withoutGlobalScope('tenant')
            ->where('tenant_id', session('portal_tenant_id'))
            ->where('publicado', 1)
            ->where(function ($q) use ($destinos, $turmaId) {
                $q->whereIn('destino', $destinos);
                if ($turmaId) {
                    $q->orWhere(fn($q2) => $q2->where('destino', 'turma')->where('turma_id', $turmaId));
                }
            })
            ->with(['leituras' => fn($q) => $q->where('usuario_id', $usuarioId)])
            ->orderByDesc('fixado')
            ->orderByDesc('criado_em')
            ->get();

        return view('portal.comunicados', compact('aluno', 'comunicados'));
    }

    public function marcarLida(Comunicado $comunicado)
    {
        ComunicadoLeitura::firstOrCreate([
            'comunicado_id' => $comunicado->id,
            'usuario_id'    => session('portal_usuario_id'),
        ]);

        return response()->json(['ok' => true]);
    }

    // ── Documentos ──────────────────────────────────────────────

    public function documentos()
    {
        $aluno = $this->getAluno();

        $documentos = DocumentoAluno::withoutGlobalScope('tenant')
            ->where('aluno_id', $aluno->id)
            ->orderByDesc('criado_em')
            ->get();

        return view('portal.documentos', compact('aluno', 'documentos'));
    }
}
