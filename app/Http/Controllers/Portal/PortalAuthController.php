<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Aluno;
use App\Models\Responsavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PortalAuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('portal_usuario_id')) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'E-mail obrigatório.',
            'email.email'       => 'E-mail inválido.',
            'password.required' => 'Senha obrigatória.',
        ]);

        $usuario = Usuario::withoutGlobalScope('tenant')
            ->where('email', $request->email)
            ->where('status', 1)
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->senha_hash)) {
            return back()->withErrors(['email' => 'E-mail ou senha incorretos.'])->withInput();
        }

        // Check if user is a student
        $aluno = Aluno::withoutGlobalScope('tenant')
            ->where('usuario_id', $usuario->id)
            ->first();

        if ($aluno) {
            session([
                'portal_usuario_id' => $usuario->id,
                'portal_nome'       => $usuario->nome,
                'portal_tenant_id'  => $usuario->tenant_id,
                'portal_tipo'       => 'aluno',
                'portal_aluno_id'   => $aluno->id,
            ]);

            return redirect()->route('portal.dashboard');
        }

        // Check if user is a responsavel
        $responsaveis = Responsavel::withoutGlobalScope('tenant')
            ->where('usuario_id', $usuario->id)
            ->with('aluno')
            ->get();

        if ($responsaveis->isNotEmpty()) {
            session([
                'portal_usuario_id'  => $usuario->id,
                'portal_nome'        => $usuario->nome,
                'portal_tenant_id'   => $usuario->tenant_id,
                'portal_tipo'        => 'responsavel',
                'portal_aluno_id'    => $responsaveis->first()->aluno_id,
                'portal_aluno_ids'   => $responsaveis->pluck('aluno_id')->toArray(),
            ]);

            return redirect()->route('portal.dashboard');
        }

        return back()->withErrors(['email' => 'Nenhum aluno vinculado a esta conta.'])->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->forget([
            'portal_usuario_id', 'portal_nome', 'portal_tenant_id',
            'portal_tipo', 'portal_aluno_id', 'portal_aluno_ids',
        ]);

        return redirect()->route('portal.login');
    }

    public function trocarAluno(Request $request)
    {
        $aluno_ids = session('portal_aluno_ids', []);
        $novo_id   = (int) $request->aluno_id;

        if (!in_array($novo_id, $aluno_ids)) {
            abort(403);
        }

        session(['portal_aluno_id' => $novo_id]);

        return redirect()->route('portal.dashboard');
    }
}
