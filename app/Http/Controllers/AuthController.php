<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session()->has('usuario_id')) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'E-mail obrigatório.',
            'email.email'       => 'E-mail inválido.',
            'password.required' => 'Senha obrigatória.',
            'password.min'      => 'Senha deve ter no mínimo 6 caracteres.',
        ]);

        // Find user by email (without tenant scope — login is cross-tenant)
        $usuario = Usuario::withoutGlobalScope('tenant')
            ->where('email', $request->email)
            ->where('status', 1)
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->senha_hash)) {
            return back()->withErrors(['email' => 'E-mail ou senha incorretos.'])->withInput();
        }

        // Store session
        session([
            'usuario_id'     => $usuario->id,
            'usuario_nome'   => $usuario->nome,
            'usuario_email'  => $usuario->email,
            'usuario_perfil' => $usuario->perfil,
            'usuario_foto'   => $usuario->foto_url,
            'tenant_id'      => $usuario->tenant_id,
        ]);

        // Update ultimo_login
        $usuario->timestamps = false;
        $usuario->ultimo_login = now();
        $usuario->saveQuietly();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('login');
    }
}
