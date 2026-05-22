<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\PlanoPagamento;
use Illuminate\Http\Request;

class PlanoPagamentoController extends Controller
{
    public function index()
    {
        $planos = PlanoPagamento::with('curso')->where('status', 1)->orderBy('nome')->get();
        return view('planos.index', compact('planos'));
    }

    public function create()
    {
        $cursos = Curso::where('status', 1)->orderBy('nome')->get();
        return view('planos.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'           => 'required|string|max:150',
            'tipo'           => 'required|in:mensal,semestral,anual,avulso',
            'valor'          => 'required|numeric|min:0.01',
            'dia_vencimento' => 'required|integer|min:1|max:28',
            'desconto_pct'   => 'nullable|numeric|min:0|max:100',
            'multa_pct'      => 'nullable|numeric|min:0|max:100',
            'juros_dia_pct'  => 'nullable|numeric|min:0',
            'curso_id'       => 'nullable|exists:cursos,id',
        ]);

        PlanoPagamento::create($request->only([
            'curso_id', 'nome', 'tipo', 'valor', 'dia_vencimento',
            'desconto_pct', 'multa_pct', 'juros_dia_pct',
        ]));

        return redirect()->route('planos.index')->with('success', 'Plano criado com sucesso!');
    }

    public function show(PlanoPagamento $plano)
    {
        return redirect()->route('planos.edit', $plano);
    }

    public function edit(PlanoPagamento $plano)
    {
        $cursos = Curso::where('status', 1)->orderBy('nome')->get();
        return view('planos.edit', compact('plano', 'cursos'));
    }

    public function update(Request $request, PlanoPagamento $plano)
    {
        $request->validate([
            'nome'           => 'required|string|max:150',
            'tipo'           => 'required|in:mensal,semestral,anual,avulso',
            'valor'          => 'required|numeric|min:0.01',
            'dia_vencimento' => 'required|integer|min:1|max:28',
            'desconto_pct'   => 'nullable|numeric|min:0|max:100',
            'multa_pct'      => 'nullable|numeric|min:0|max:100',
            'juros_dia_pct'  => 'nullable|numeric|min:0',
            'status'         => 'required|in:0,1',
        ]);

        $plano->update($request->only([
            'curso_id', 'nome', 'tipo', 'valor', 'dia_vencimento',
            'desconto_pct', 'multa_pct', 'juros_dia_pct', 'status',
        ]));

        return redirect()->route('planos.index')->with('success', 'Plano atualizado!');
    }

    public function destroy(PlanoPagamento $plano)
    {
        $plano->update(['status' => 0]);
        return redirect()->route('planos.index')->with('success', 'Plano desativado.');
    }
}
