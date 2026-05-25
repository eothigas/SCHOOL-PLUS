@extends('layouts.app')
@section('title', 'Diário de Classe')

@section('content')
{{-- Cabeçalho --}}
<div class="d-flex align-items-start justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:4px">
            Diário - {{ $td->disciplina->nome }}
        </h4>
        <div style="font-size:13px;color:var(--text-soft)">
            <i class="bi bi-grid me-1"></i>{{ $td->turma->nome }}
            &nbsp;&middot;&nbsp;
            <i class="bi bi-person me-1"></i>{{ $td->professor?->nome ?? 'Sem professor' }}
        </div>
    </div>
    <a href="{{ route('turmas.disciplinas', $td->turma) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="row g-3">

    {{-- Aulas --}}
    <div class="col-lg-6">
        <div class="sp-card" style="padding:0">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                <div>
                    <div style="font-size:14px;font-weight:700">Aulas Registradas</div>
                    <div style="font-size:11px;color:var(--text-soft)">{{ $total_aulas }} aula{{ $total_aulas != 1 ? 's' : '' }} no total</div>
                </div>
                <button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#formAula">
                    <i class="bi bi-plus-lg me-1"></i>Nova Aula
                </button>
            </div>

            {{-- Form nova aula (collapse) --}}
            <div class="collapse" id="formAula">
                <form method="POST" action="{{ route('diario.aulas.store', $td->id) }}">
                    @csrf
                    <div style="padding:16px 20px;background:var(--surface2);border-bottom:1px solid var(--border)">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Data *</label>
                                <input type="date" name="data_aula" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Conteúdo da Aula</label>
                                <input type="text" name="conteudo" class="form-control" placeholder="Ex: Introdução ao tema...">
                            </div>
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#formAula">Cancelar</button>
                                <button type="submit" class="btn btn-primary btn-sm px-4">
                                    <i class="bi bi-check-lg me-1"></i>Registrar Aula
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @forelse($aulas as $aula)
            <a href="{{ route('diario.aula', [$td->id, $aula->id]) }}"
               style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border);text-decoration:none;transition:background .15s"
               onmouseover="this.style.background='var(--surface2)'" onmouseout="this.style.background=''">
                <div style="display:flex;align-items:center;gap:12px">
                    <div style="width:40px;height:40px;border-radius:12px;background:var(--purple-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-calendar-check" style="color:var(--purple);font-size:16px"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:600;color:var(--text)">
                            {{ $aula->data_aula->format('d/m/Y') }}
                        </div>
                        <div style="font-size:12px;color:var(--text-soft)">
                            {{ $aula->conteudo ? \Str::limit($aula->conteudo, 50) : 'Sem conteúdo registrado' }}
                        </div>
                    </div>
                </div>
                @php
                    $p = $aula->frequencias->where('presente', 1)->count();
                    $t = $aula->frequencias->count();
                @endphp
                @if($t > 0)
                <div style="text-align:right">
                    <div style="font-size:13px;font-weight:700;color:{{ $p/$t >= 0.75 ? 'var(--green)' : 'var(--red)' }}">
                        {{ round($p/$t*100) }}%
                    </div>
                    <div style="font-size:11px;color:var(--text-soft)">{{ $p }}/{{ $t }} presentes</div>
                </div>
                @endif
            </a>
            @empty
            <div style="text-align:center;color:var(--text-soft);padding:32px;font-size:14px">
                Nenhuma aula registrada ainda.
            </div>
            @endforelse
        </div>
    </div>

    {{-- Avaliações + alunos --}}
    <div class="col-lg-6">

        {{-- Avaliações --}}
        <div class="sp-card" style="padding:0;margin-bottom:16px">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                <div>
                    <div style="font-size:14px;font-weight:700">Avaliações</div>
                    <div style="font-size:11px;color:var(--text-soft)">{{ $avaliacoes->count() }} avaliação(ões)</div>
                </div>
                <button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#formAv">
                    <i class="bi bi-plus-lg me-1"></i>Nova
                </button>
            </div>

            {{-- Form nova avaliação --}}
            <div class="collapse" id="formAv">
                <form method="POST" action="{{ route('diario.avaliacoes.store', $td->id) }}">
                    @csrf
                    <div style="padding:16px 20px;background:var(--surface2);border-bottom:1px solid var(--border)">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Nome *</label>
                                <input type="text" name="nome" class="form-control" placeholder="Ex: Prova 1" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tipo *</label>
                                <select name="tipo" class="form-select" required>
                                    <option value="prova">Prova</option>
                                    <option value="trabalho">Trabalho</option>
                                    <option value="seminario">Seminário</option>
                                    <option value="participacao">Participação</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nota Máx. *</label>
                                <input type="number" name="nota_maxima" class="form-control" value="10" min="0.1" step="0.1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Peso</label>
                                <input type="number" name="peso" class="form-control" value="1" min="0.1" step="0.1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Data</label>
                                <input type="date" name="data_aplicacao" class="form-control">
                            </div>
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#formAv">Cancelar</button>
                                <button type="submit" class="btn btn-primary btn-sm px-4">
                                    <i class="bi bi-check-lg me-1"></i>Criar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @forelse($avaliacoes as $av)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border)">
                <div>
                    <div style="font-size:13px;font-weight:600;color:var(--text)">{{ $av->nome }}</div>
                    <div style="font-size:11px;color:var(--text-soft)">
                        {{ ucfirst($av->tipo) }} &middot; Máx {{ number_format($av->nota_maxima, 1) }}
                        @if($av->data_aplicacao) &middot; {{ $av->data_aplicacao->format('d/m/Y') }} @endif
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <span style="font-size:11px;color:var(--purple)">
                        {{ $av->notas->count() }}/{{ $matriculas->count() }} notas
                    </span>
                    <a href="{{ route('diario.notas', [$td->id, $av->id]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i>Notas
                    </a>
                </div>
            </div>
            @empty
            <div style="text-align:center;color:var(--text-soft);padding:24px;font-size:14px">
                Nenhuma avaliação criada.
            </div>
            @endforelse
        </div>

        {{-- Alunos matriculados --}}
        <div class="sp-card" style="padding:0">
            <div style="padding:14px 20px;border-bottom:1px solid var(--border)">
                <div style="font-size:14px;font-weight:700">Alunos Matriculados</div>
                <div style="font-size:11px;color:var(--text-soft)">{{ $matriculas->count() }} aluno(s) ativo(s)</div>
            </div>
            @foreach($matriculas as $mat)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 20px;border-bottom:1px solid var(--border)">
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:34px;height:34px;border-radius:10px;background:var(--purple-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-person" style="color:var(--purple)"></i>
                    </div>
                    <div style="font-size:13px;font-weight:600">{{ $mat->aluno->usuario->nome ?? '-' }}</div>
                </div>
                @if(isset($stats_freq[$mat->id]))
                <div style="font-size:12px;font-weight:700;color:{{ $stats_freq[$mat->id] >= 75 ? 'var(--green)' : 'var(--red)' }}">
                    {{ $stats_freq[$mat->id] }}% freq.
                </div>
                @endif
            </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
