<?php

namespace App\Http\Controllers;

use App\Models\Cobranca;
use App\Models\Matricula;
use App\Models\Negociacao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NegociacaoController extends Controller
{
    public function index()
    {
        $negociacoes = Negociacao::with('matricula.aluno.usuario', 'usuario')
            ->orderByDesc('criado_em')
            ->paginate(20);

        return view('negociacoes.index', compact('negociacoes'));
    }

    public function create(Request $request)
    {
        $matriculas = Matricula::with('aluno.usuario')->where('status', 'ativa')->get();

        $matricula = null;
        $cobrancas_vencidas = collect();

        if ($request->filled('matricula_id')) {
            $matricula = Matricula::with('aluno.usuario')->findOrFail($request->matricula_id);
            $cobrancas_vencidas = Cobranca::where('matricula_id', $matricula->id)
                ->whereIn('status', ['aberta', 'vencida'])
                ->where('data_vencimento', '<', today())
                ->orWhere(fn($q) => $q->where('matricula_id', $matricula->id)->where('status', 'aberta')->where('data_vencimento', '<', today()))
                ->get();

            // Simplify: aberta + vencidas
            $cobrancas_vencidas = Cobranca::where('matricula_id', $matricula->id)
                ->where(fn($q) => $q
                    ->where('status', 'vencida')
                    ->orWhere(fn($q2) => $q2->where('status', 'aberta')->where('data_vencimento', '<', today()))
                )->get();
        }

        return view('negociacoes.create', compact('matriculas', 'matricula', 'cobrancas_vencidas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula_id'  => 'required|exists:matriculas,id',
            'cobranca_ids'  => 'required|array|min:1',
            'cobranca_ids.*'=> 'exists:cobrancas,id',
            'desconto_pct'  => 'required|numeric|min:0|max:100',
            'qtd_parcelas'  => 'required|integer|min:1|max:48',
            'data_primeira' => 'required|date|after_or_equal:today',
            'obs'           => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $cobrancas = Cobranca::whereIn('id', $request->cobranca_ids)
                ->where('matricula_id', $request->matricula_id)
                ->get();

            $valor_total = $cobrancas->sum('valor_original');

            $neg = Negociacao::create([
                'matricula_id' => $request->matricula_id,
                'usuario_id'   => session('usuario_id'),
                'valor_total'  => $valor_total,
                'desconto_pct' => $request->desconto_pct,
                'qtd_parcelas' => $request->qtd_parcelas,
                'obs'          => $request->obs,
            ]);

            // Marcar cobranças originais como negociadas
            Cobranca::whereIn('id', $request->cobranca_ids)->update(['status' => 'negociada']);

            // Gerar novas parcelas
            $valor_com_desconto = $valor_total * (1 - $request->desconto_pct / 100);
            $valor_parcela      = round($valor_com_desconto / $request->qtd_parcelas, 2);
            $inicio             = Carbon::parse($request->data_primeira);

            for ($i = 0; $i < $request->qtd_parcelas; $i++) {
                $venc = $inicio->copy()->addMonths($i);
                Cobranca::create([
                    'matricula_id'    => $request->matricula_id,
                    'descricao'       => "Negociação #{$neg->id} - Parcela " . ($i + 1) . "/{$request->qtd_parcelas}",
                    'valor_original'  => $valor_parcela,
                    'valor_desconto'  => 0,
                    'valor_acrescimo' => 0,
                    'data_vencimento' => $venc,
                    'competencia'     => $venc->format('Y-m'),
                    'status'          => 'aberta',
                    'obs'             => "Negociação #{$neg->id}",
                ]);
            }
        });

        return redirect()->route('negociacoes.index')->with('success', 'Negociação criada e cobranças geradas!');
    }

    public function show(Negociacao $negociacao)
    {
        $negociacao->load('matricula.aluno.usuario', 'usuario');
        $cobrancas_geradas = Cobranca::where('matricula_id', $negociacao->matricula_id)
            ->where('obs', "Negociação #{$negociacao->id}")
            ->orderBy('data_vencimento')
            ->get();

        return view('negociacoes.show', compact('negociacao', 'cobrancas_geradas'));
    }
}
