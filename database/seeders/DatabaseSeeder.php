<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tenant demo
        $tenantId = DB::table('tenants')->insertGetId([
            'nome'     => 'Escola Demo School+',
            'tipo'     => 'escola',
            'email'    => 'contato@escola-demo.com',
            'plano'    => 'profissional',
            'status'   => 'ativo',
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        // Admin do tenant
        DB::table('usuarios')->insert([
            'tenant_id'   => $tenantId,
            'nome'        => 'Administrador',
            'email'       => 'admin@escola-demo.com',
            'senha_hash'  => Hash::make('password'),
            'perfil'      => 'admin',
            'status'      => 1,
            'criado_em'   => now(),
            'atualizado_em' => now(),
        ]);

        // Período letivo ativo
        $periodoId = DB::table('periodos_letivos')->insertGetId([
            'tenant_id'   => $tenantId,
            'nome'        => '2025/1',
            'data_inicio' => '2025-02-01',
            'data_fim'    => '2025-07-31',
            'status'      => 'ativo',
            'criado_em'   => now(),
        ]);

        // Curso demo
        $cursoId = DB::table('cursos')->insertGetId([
            'tenant_id'     => $tenantId,
            'nome'          => 'Ensino Médio',
            'tipo'          => 'medio',
            'duracao_meses' => 36,
            'status'        => 1,
            'criado_em'     => now(),
        ]);

        // Turma demo
        DB::table('turmas')->insert([
            'tenant_id'  => $tenantId,
            'curso_id'   => $cursoId,
            'periodo_id' => $periodoId,
            'nome'       => '1º Ano A',
            'turno'      => 'manha',
            'vagas'      => 35,
            'status'     => 'em_andamento',
            'criado_em'  => now(),
        ]);

        $this->command->info('✓ Tenant criado: Escola Demo School+');
        $this->command->info('✓ Login: admin@escola-demo.com / password');
    }
}
