<?php

namespace App\Http\Controllers;

use App\Models\PeriodoLetivo;
use Illuminate\Http\Request;

class PeriodoLetivoController extends Controller
{
    public function index(Request $request)
    {
        $query = PeriodoLetivo::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $periodos = $query->orderByDesc('data_inicio')->paginate(20)->withQueryString();

        return view('periodos.index', compact('periodos'));
    }

    public function create()
    {
        return view('periodos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome'        => 'required|string|max:100',
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after:data_inicio',
            'status'      => 'required|in:planejamento,ativo,encerrado',
        ], [
            'nome.required'        => 'Nome obrigatório.',
            'data_inicio.required' => 'Data de início obrigatória.',
            'data_fim.required'    => 'Data de fim obrigatória.',
            'data_fim.after'       => 'Data fim deve ser após data início.',
        ]);

        PeriodoLetivo::create($request->only(['nome', 'data_inicio', 'data_fim', 'status']));

        return redirect()->route('periodos.index')->with('success', 'Período letivo criado!');
    }

    public function show(PeriodoLetivo $periodo)
    {
        $periodo->load('turmas.curso');

        return view('periodos.show', compact('periodo'));
    }

    public function edit(PeriodoLetivo $periodo)
    {
        return view('periodos.edit', compact('periodo'));
    }

    public function update(Request $request, PeriodoLetivo $periodo)
    {
        $request->validate([
            'nome'        => 'required|string|max:100',
            'data_inicio' => 'required|date',
            'data_fim'    => 'required|date|after:data_inicio',
            'status'      => 'required|in:planejamento,ativo,encerrado',
        ]);

        $periodo->update($request->only(['nome', 'data_inicio', 'data_fim', 'status']));

        return redirect()->route('periodos.show', $periodo)->with('success', 'Período atualizado!');
    }

    public function destroy(PeriodoLetivo $periodo)
    {
        $periodo->update(['status' => 'encerrado']);

        return redirect()->route('periodos.index')->with('success', 'Período encerrado.');
    }
}
