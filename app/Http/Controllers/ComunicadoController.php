<?php

namespace App\Http\Controllers;

use App\Models\Comunicado;
use App\Models\Turma;
use Illuminate\Http\Request;

class ComunicadoController extends Controller
{
    public function index()
    {
        $comunicados = Comunicado::with('autor', 'turma')
            ->orderByDesc('fixado')
            ->orderByDesc('criado_em')
            ->paginate(20);

        $turmas = Turma::orderBy('nome')->get();

        return view('comunicados.index', compact('comunicados', 'turmas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'   => 'required|string|max:200',
            'corpo'    => 'required|string',
            'destino'  => 'required|in:todos,professores,alunos,responsaveis,turma',
            'turma_id' => 'nullable|required_if:destino,turma|exists:turmas,id',
            'fixado'   => 'nullable|boolean',
        ]);

        $data['autor_id']  = session('usuario_id');
        $data['tenant_id'] = session('tenant_id');
        $data['fixado']    = $request->boolean('fixado');
        $data['publicado'] = 1;

        if ($data['destino'] !== 'turma') {
            $data['turma_id'] = null;
        }

        Comunicado::create($data);

        return redirect()->route('comunicados.index')->with('success', 'Comunicado publicado!');
    }

    public function toggle(Comunicado $comunicado)
    {
        $comunicado->update(['publicado' => !$comunicado->publicado]);

        return back()->with('success', $comunicado->publicado ? 'Comunicado reativado.' : 'Comunicado despublicado.');
    }

    public function destroy(Comunicado $comunicado)
    {
        $comunicado->delete();

        return back()->with('success', 'Comunicado removido.');
    }
}
