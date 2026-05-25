<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\DocumentoAluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function store(Request $request, Aluno $aluno)
    {
        $request->validate([
            'tipo'    => 'required|in:rg,cpf,comprovante_residencia,foto,historico,declaracao,outro',
            'nome'    => 'nullable|string|max:200',
            'arquivo' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,webp',
        ]);

        $tenantId = session('tenant_id');
        $path = $request->file('arquivo')->store(
            "documentos/{$tenantId}/{$aluno->id}",
            'public'
        );

        DocumentoAluno::create([
            'tenant_id'   => $tenantId,
            'aluno_id'    => $aluno->id,
            'tipo'        => $request->tipo,
            'nome'        => $request->nome ?: $request->file('arquivo')->getClientOriginalName(),
            'arquivo_url' => $path,
        ]);

        return back()->with('success', 'Documento enviado!');
    }

    public function destroy(Aluno $aluno, DocumentoAluno $documento)
    {
        abort_if($documento->aluno_id !== $aluno->id, 403);

        if ($documento->arquivo_url) {
            Storage::disk('public')->delete($documento->arquivo_url);
        }

        $documento->delete();

        return back()->with('success', 'Documento removido.');
    }
}
