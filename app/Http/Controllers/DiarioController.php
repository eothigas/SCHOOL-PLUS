<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use App\Models\Avaliacao;
use App\Models\Frequencia;
use App\Models\Matricula;
use App\Models\Nota;
use App\Models\TurmaDisiplina;
use Illuminate\Http\Request;

class DiarioController extends Controller
{
    private function findTd(int $id): TurmaDisiplina
    {
        return TurmaDisiplina::whereHas('turma', fn($q) => $q->where('tenant_id', session('tenant_id')))
            ->with(['turma.curso', 'disciplina', 'professor'])
            ->findOrFail($id);
    }

    public function index(int $td)
    {
        $td = $this->findTd($td);
        $aulas = Aula::where('turma_disc_id', $td->id)->orderByDesc('data_aula')->get();
        $avaliacoes = Avaliacao::where('turma_disc_id', $td->id)->orderByDesc('data_aplicacao')->get();
        $matriculas = Matricula::with('aluno.usuario')
            ->where('turma_id', $td->turma_id)
            ->where('status', 'ativa')
            ->get();

        $total_aulas = $aulas->count();

        $stats_freq = [];
        foreach ($matriculas as $mat) {
            $presencas = Frequencia::whereHas('aula', fn($q) => $q->where('turma_disc_id', $td->id))
                ->where('matricula_id', $mat->id)
                ->where('presente', 1)
                ->count();
            $stats_freq[$mat->id] = $total_aulas > 0 ? round($presencas / $total_aulas * 100) : null;
        }

        return view('diario.index', compact('td', 'aulas', 'avaliacoes', 'matriculas', 'total_aulas', 'stats_freq'));
    }

    public function storeAula(Request $request, int $td)
    {
        $td = $this->findTd($td);
        $request->validate([
            'data_aula' => 'required|date',
            'conteudo'  => 'nullable|string|max:2000',
        ]);

        $aula = Aula::create([
            'turma_disc_id' => $td->id,
            'data_aula'     => $request->data_aula,
            'conteudo'      => $request->conteudo,
            'observacao'    => $request->observacao,
        ]);

        $matriculas = Matricula::where('turma_id', $td->turma_id)->where('status', 'ativa')->get();
        foreach ($matriculas as $mat) {
            Frequencia::create([
                'aula_id'      => $aula->id,
                'matricula_id' => $mat->id,
                'presente'     => 1,
                'justificada'  => 0,
            ]);
        }

        return redirect()->route('diario.aula', [$td->id, $aula->id])
            ->with('success', 'Aula registrada! Confirme a frequência abaixo.');
    }

    public function showAula(int $td, Aula $aula)
    {
        $td = $this->findTd($td);
        $aula->load('frequencias.matricula.aluno.usuario');
        return view('diario.aula', compact('td', 'aula'));
    }

    public function salvarFrequencia(Request $request, int $td, Aula $aula)
    {
        $aula->load('frequencias');
        $presentes = $request->input('presentes', []);

        foreach ($aula->frequencias as $freq) {
            $freq->update([
                'presente'    => in_array($freq->matricula_id, $presentes) ? 1 : 0,
                'justificada' => in_array($freq->matricula_id, $request->input('justificadas', [])) ? 1 : 0,
            ]);
        }

        return back()->with('success', 'Frequência salva!');
    }

    public function storeAvaliacao(Request $request, int $td)
    {
        $td = $this->findTd($td);
        $request->validate([
            'nome'             => 'required|string|max:100',
            'tipo'             => 'required|in:prova,trabalho,seminario,participacao,outro',
            'nota_maxima'      => 'required|numeric|min:0.01|max:100',
            'peso'             => 'nullable|numeric|min:0.01',
            'data_aplicacao'   => 'nullable|date',
        ]);

        $av = Avaliacao::create([
            'turma_disc_id'  => $td->id,
            'nome'           => $request->nome,
            'tipo'           => $request->tipo,
            'peso'           => $request->peso ?? 1,
            'nota_maxima'    => $request->nota_maxima,
            'data_aplicacao' => $request->data_aplicacao,
        ]);

        return redirect()->route('diario.notas', [$td->id, $av->id])
            ->with('success', 'Avaliação criada! Lance as notas abaixo.');
    }

    public function lancarNotas(int $td, Avaliacao $avaliacao)
    {
        $td = $this->findTd($td);
        $matriculas = Matricula::with('aluno.usuario')
            ->where('turma_id', $td->turma_id)
            ->where('status', 'ativa')
            ->get();
        $notas_existentes = Nota::where('avaliacao_id', $avaliacao->id)->pluck('nota', 'matricula_id');

        return view('diario.notas', compact('td', 'avaliacao', 'matriculas', 'notas_existentes'));
    }

    public function salvarNotas(Request $request, int $td, Avaliacao $avaliacao)
    {
        $notas = $request->input('notas', []);
        foreach ($notas as $matricula_id => $nota) {
            if ($nota === '' || $nota === null) continue;
            Nota::updateOrCreate(
                ['avaliacao_id' => $avaliacao->id, 'matricula_id' => (int) $matricula_id],
                ['nota' => (float) $nota, 'obs' => $request->input("obs.$matricula_id")]
            );
        }

        return redirect()->route('diario.index', $td)->with('success', 'Notas salvas com sucesso!');
    }
}
