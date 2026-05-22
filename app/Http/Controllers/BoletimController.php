<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Aula;
use App\Models\Frequencia;
use App\Models\Matricula;
use App\Models\Nota;

class BoletimController extends Controller
{
    public function show(Aluno $aluno)
    {
        $matriculas = Matricula::with([
            'turma.curso',
            'turma.turmaDisiplinas.disciplina',
            'turma.turmaDisiplinas.avaliacoes.notas',
            'turma.turmaDisiplinas.professor',
        ])->where('aluno_id', $aluno->id)->get();

        $boletim = [];
        foreach ($matriculas as $mat) {
            $turmaData = ['matricula' => $mat, 'turma' => $mat->turma, 'disciplinas' => []];

            foreach ($mat->turma->turmaDisiplinas as $td) {
                $total_aulas = Aula::where('turma_disc_id', $td->id)->count();
                $presencas = Frequencia::whereHas('aula', fn($q) => $q->where('turma_disc_id', $td->id))
                    ->where('matricula_id', $mat->id)
                    ->where('presente', 1)
                    ->count();
                $ausencias = $total_aulas - $presencas;

                $disciplinaData = [
                    'td'          => $td,
                    'disciplina'  => $td->disciplina,
                    'total_aulas' => $total_aulas,
                    'presencas'   => $presencas,
                    'ausencias'   => $ausencias,
                    'freq_pct'    => $total_aulas > 0 ? round($presencas / $total_aulas * 100, 1) : null,
                    'avaliacoes'  => [],
                    'media'       => null,
                ];

                $soma_pesos = 0;
                $soma_notas = 0;
                $tem_nota = false;

                foreach ($td->avaliacoes as $av) {
                    $nota = $av->notas->where('matricula_id', $mat->id)->first();
                    $nota_val = $nota?->nota;
                    $disciplinaData['avaliacoes'][] = ['avaliacao' => $av, 'nota' => $nota_val];

                    if ($nota_val !== null) {
                        $normalizada = $av->nota_maxima > 0 ? ($nota_val / $av->nota_maxima) * 10 : 0;
                        $soma_notas += $normalizada * $av->peso;
                        $soma_pesos += $av->peso;
                        $tem_nota = true;
                    }
                }

                if ($tem_nota && $soma_pesos > 0) {
                    $disciplinaData['media'] = round($soma_notas / $soma_pesos, 1);
                }

                $turmaData['disciplinas'][] = $disciplinaData;
            }

            $boletim[] = $turmaData;
        }

        return view('boletim.show', compact('aluno', 'boletim'));
    }
}
