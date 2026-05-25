<?php

namespace App\Http\Controllers;

use App\Models\Cobranca;
use App\Models\Matricula;
use App\Models\PlanoPagamento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CobrancaController extends Controller
{
    public function index(Request $request)
    {
        $query = Cobranca::with('matricula.aluno.usuario', 'plano');

        if ($request->filled('status')) {
            if ($request->status === 'vencida') {
                $query->aberta()->where('data_vencimento', '<', today());
            } elseif ($request->status === 'a_vencer') {
                $query->aberta()->where('data_vencimento', '>=', today());
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('competencia')) {
            $query->where('competencia', $request->competencia);
        }

        if ($request->filled('busca')) {
            $query->where('descricao', 'like', '%' . $request->busca . '%')
                ->orWhereHas('matricula.aluno.usuario', fn($q) =>
                    $q->where('nome', 'like', '%' . $request->busca . '%')
                );
        }

        $cobrancas = $query->orderBy('data_vencimento')->paginate(25)->withQueryString();

        // Totais filtrados
        $totais = [
            'aberta'  => Cobranca::aberta()->where('data_vencimento', '>=', today())->sum(DB::raw('valor_original - valor_desconto')),
            'vencida' => Cobranca::vencida()->sum('valor_original'),
            'paga_mes'=> Cobranca::paga()->whereMonth('data_pagamento', now()->month)->sum('valor_pago'),
        ];

        return view('cobrancas.index', compact('cobrancas', 'totais'));
    }

    public function create()
    {
        $matriculas = Matricula::with('aluno.usuario', 'turma')
            ->where('status', 'ativa')->get();
        $planos = PlanoPagamento::where('status', 1)->orderBy('nome')->get();
        return view('cobrancas.create', compact('matriculas', 'planos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula_id'    => 'required|exists:matriculas,id',
            'descricao'       => 'required|string|max:200',
            'valor_original'  => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'competencia'     => 'nullable|string|max:7',
            'plano_id'        => 'nullable|exists:planos_pagamento,id',
            'obs'             => 'nullable|string',
        ]);

        Cobranca::create($request->only([
            'matricula_id', 'plano_id', 'descricao', 'valor_original',
            'data_vencimento', 'competencia', 'obs',
        ]) + ['valor_desconto' => 0, 'valor_acrescimo' => 0, 'status' => 'aberta']);

        return redirect()->route('cobrancas.index')->with('success', 'Cobrança criada!');
    }

    public function show(Cobranca $cobranca)
    {
        $cobranca->load('matricula.aluno.usuario', 'plano');
        return view('cobrancas.show', compact('cobranca'));
    }

    // Baixa de pagamento
    public function pagar(Request $request, Cobranca $cobranca)
    {
        $request->validate([
            'forma_pagamento' => 'required|in:dinheiro,pix,boleto,cartao_credito,cartao_debito,transferencia',
            'valor_pago'      => 'required|numeric|min:0.01',
            'data_pagamento'  => 'required|date',
        ]);

        $cobranca->update([
            'status'          => 'paga',
            'forma_pagamento' => $request->forma_pagamento,
            'valor_pago'      => $request->valor_pago,
            'data_pagamento'  => $request->data_pagamento,
        ]);

        return redirect()->route('cobrancas.show', $cobranca)->with('success', 'Pagamento registrado!');
    }

    public function cancelar(Cobranca $cobranca)
    {
        $cobranca->update(['status' => 'cancelada']);
        return redirect()->route('cobrancas.show', $cobranca)->with('success', 'Cobrança cancelada.');
    }

    // Formulário geração em lote
    public function gerarForm()
    {
        $matriculas = Matricula::with('aluno.usuario', 'turma')
            ->where('status', 'ativa')->get();
        $planos = PlanoPagamento::where('status', 1)->orderBy('nome')->get();
        return view('cobrancas.gerar', compact('matriculas', 'planos'));
    }

    // Gerar mensalidades em lote para uma matrícula
    public function gerarLote(Request $request)
    {
        $request->validate([
            'matricula_id'   => 'required|exists:matriculas,id',
            'plano_id'       => 'required|exists:planos_pagamento,id',
            'data_inicio'    => 'required|date',
            'qtd_meses'      => 'required|integer|min:1|max:24',
        ]);

        $plano     = PlanoPagamento::findOrFail($request->plano_id);
        $inicio    = Carbon::parse($request->data_inicio);
        $criadas   = 0;

        DB::transaction(function () use ($request, $plano, $inicio, &$criadas) {
            for ($i = 0; $i < $request->qtd_meses; $i++) {
                $venc = $inicio->copy()->addMonths($i)->setDay(min($plano->dia_vencimento, 28));
                $comp = $venc->format('Y-m');

                // Evitar duplicata
                $existe = Cobranca::where('matricula_id', $request->matricula_id)
                    ->where('competencia', $comp)
                    ->where('plano_id', $plano->id)
                    ->exists();
                if ($existe) continue;

                Cobranca::create([
                    'matricula_id'    => $request->matricula_id,
                    'plano_id'        => $plano->id,
                    'descricao'       => $plano->nome . ' - ' . $venc->format('m/Y'),
                    'valor_original'  => $plano->valor,
                    'valor_desconto'  => $plano->valor * $plano->desconto_pct / 100,
                    'valor_acrescimo' => 0,
                    'data_vencimento' => $venc,
                    'competencia'     => $comp,
                    'status'          => 'aberta',
                ]);
                $criadas++;
            }
        });

        return redirect()->route('cobrancas.index')
            ->with('success', "$criadas cobrança(s) gerada(s) com sucesso!");
    }
}
