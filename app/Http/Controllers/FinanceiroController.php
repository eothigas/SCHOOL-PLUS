<?php

namespace App\Http\Controllers;

use App\Models\Cobranca;
use App\Models\Matricula;
use Illuminate\Support\Facades\DB;

class FinanceiroController extends Controller
{
    public function index()
    {
        $tid = session('tenant_id');

        // Receita mês atual
        $receita_mes = Cobranca::where('status', 'paga')
            ->whereMonth('data_pagamento', now()->month)
            ->whereYear('data_pagamento', now()->year)
            ->sum('valor_pago');

        // A receber (abertas não vencidas)
        $a_receber = Cobranca::aberta()
            ->where('data_vencimento', '>=', today())
            ->sum(DB::raw('valor_original - valor_desconto'));

        // Vencidas (abertas com data passada)
        $total_vencido = Cobranca::vencida()->sum('valor_original');
        $qtd_vencidas  = Cobranca::vencida()->count();

        // Receita anual (mês a mês)
        $receita_anual = Cobranca::where('status', 'paga')
            ->whereYear('data_pagamento', now()->year)
            ->select(DB::raw('MONTH(data_pagamento) as mes'), DB::raw('SUM(valor_pago) as total'))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->keyBy('mes');

        // Inadimplentes (alunos com cobranças vencidas)
        $inadimplentes = Cobranca::vencida()
            ->with('matricula.aluno.usuario')
            ->select('matricula_id', DB::raw('SUM(valor_original) as total_divida'), DB::raw('COUNT(*) as qtd'))
            ->groupBy('matricula_id')
            ->orderByDesc('total_divida')
            ->limit(10)
            ->get();

        // Últimas cobranças pagas
        $ultimas_pagas = Cobranca::paga()
            ->with('matricula.aluno.usuario')
            ->orderByDesc('data_pagamento')
            ->limit(8)
            ->get();

        // Stats rápidos
        $total_aberto = Cobranca::aberta()->count();
        $total_pago_mes = Cobranca::paga()
            ->whereMonth('data_pagamento', now()->month)
            ->whereYear('data_pagamento', now()->year)
            ->count();

        return view('financeiro.index', compact(
            'receita_mes', 'a_receber', 'total_vencido', 'qtd_vencidas',
            'receita_anual', 'inadimplentes', 'ultimas_pagas',
            'total_aberto', 'total_pago_mes'
        ));
    }
}
