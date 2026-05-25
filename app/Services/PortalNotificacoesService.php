<?php

namespace App\Services;

use App\Models\Aula;
use App\Models\Comunicado;
use App\Models\Frequencia;
use App\Models\Matricula;
use App\Models\Nota;

class PortalNotificacoesService
{
    /**
     * Retorna array de notificações para o aluno atual da sessão do portal.
     * Cada item: ['tipo', 'icone', 'cor', 'titulo', 'detalhe', 'link']
     */
    public static function get(): array
    {
        $alunoId  = session('portal_aluno_id');
        $tenantId = session('portal_tenant_id');

        if (!$alunoId || !$tenantId) {
            return [];
        }

        $notificacoes = [];

        $matriculas = Matricula::withoutGlobalScope('tenant')
            ->with(['turma.turmaDisiplinas.disciplina', 'turma.turmaDisiplinas.avaliacoes'])
            ->where('aluno_id', $alunoId)
            ->where('status', 'ativa')
            ->get();

        foreach ($matriculas as $mat) {
            foreach ($mat->turma->turmaDisiplinas as $td) {

                // ── Notas lançadas ─────────────────────────────────
                foreach ($td->avaliacoes as $av) {
                    $nota = Nota::withoutGlobalScope('tenant')
                        ->where('avaliacao_id', $av->id)
                        ->where('matricula_id', $mat->id)
                        ->first();

                    if ($nota) {
                        $notificacoes[] = [
                            'tipo'   => 'nota',
                            'icone'  => 'bi-bar-chart-line',
                            'cor'    => 'var(--blue)',
                            'cor_bg' => 'var(--blue-bg)',
                            'titulo' => 'Nota disponível',
                            'detalhe'=> $td->disciplina->nome . ' — ' . $av->titulo . ': ' . number_format($nota->nota, 1, ',', ''),
                            'link'   => route('portal.boletim'),
                        ];
                    }
                }

                // ── Frequência baixa (< 75%) ───────────────────────
                $totalAulas = Aula::withoutGlobalScope('tenant')
                    ->where('turma_disc_id', $td->id)
                    ->count();

                if ($totalAulas >= 4) { // só alerta após mínimo de aulas
                    $presencas = Frequencia::withoutGlobalScope('tenant')
                        ->whereHas('aula', fn($q) => $q->where('turma_disc_id', $td->id))
                        ->where('matricula_id', $mat->id)
                        ->where('presente', 1)
                        ->count();

                    $pct = round($presencas / $totalAulas * 100, 1);

                    if ($pct < 75) {
                        $notificacoes[] = [
                            'tipo'   => 'frequencia',
                            'icone'  => 'bi-exclamation-triangle',
                            'cor'    => $pct < 50 ? 'var(--red)' : 'var(--amber)',
                            'cor_bg' => $pct < 50 ? 'var(--red-bg)' : 'var(--amber-bg)',
                            'titulo' => 'Frequência baixa',
                            'detalhe'=> $td->disciplina->nome . ' — ' . $pct . '% (mín. 75%)',
                            'link'   => route('portal.frequencia'),
                        ];
                    }
                }
            }
        }

        // ── Comunicados não lidos ──────────────────────────────
        $tipo      = session('portal_tipo');
        $usuarioId = session('portal_usuario_id');

        $turmaId = Matricula::withoutGlobalScope('tenant')
            ->where('aluno_id', $alunoId)
            ->where('status', 'ativa')
            ->value('turma_id');

        $destinosAluno       = ['todos', 'alunos'];
        $destinosResponsavel = ['todos', 'responsaveis'];
        $destinos = $tipo === 'responsavel' ? $destinosResponsavel : $destinosAluno;

        $naoLidos = Comunicado::withoutGlobalScope('tenant')
            ->where('tenant_id', $tenantId)
            ->where('publicado', 1)
            ->where(function ($q) use ($destinos, $turmaId) {
                $q->whereIn('destino', $destinos);
                if ($turmaId) {
                    $q->orWhere(fn($q2) => $q2->where('destino', 'turma')->where('turma_id', $turmaId));
                }
            })
            ->whereDoesntHave('leituras', fn($q) => $q->where('usuario_id', $usuarioId))
            ->count();

        if ($naoLidos > 0) {
            $notificacoes[] = [
                'tipo'   => 'comunicado',
                'icone'  => 'bi-megaphone',
                'cor'    => 'var(--purple)',
                'cor_bg' => 'var(--purple-light)',
                'titulo' => 'Avisos da escola',
                'detalhe'=> $naoLidos . ' comunicado' . ($naoLidos > 1 ? 's' : '') . ' não lido' . ($naoLidos > 1 ? 's' : ''),
                'link'   => route('portal.comunicados'),
            ];
        }

        return $notificacoes;
    }
}
