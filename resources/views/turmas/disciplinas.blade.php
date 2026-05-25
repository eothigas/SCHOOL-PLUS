@extends('layouts.app')
@section('title', 'Disciplinas da Turma')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 style="font-size:20px;font-weight:800;color:var(--text);margin-bottom:2px">
            Diário - {{ $turma->nome }}
        </h4>
        <div style="font-size:13px;color:var(--text-soft)">
            {{ $turma->curso->nome ?? '' }} &middot; {{ ucfirst($turma->turno) }}
        </div>
    </div>
    <a href="{{ route('turmas.show', $turma) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
</div>

<div class="row g-3">

    {{-- Lista de disciplinas da turma --}}
    <div class="col-lg-8">
        <div class="sp-card" style="padding:0">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border)">
                <div style="font-size:14px;font-weight:700">Disciplinas desta Turma</div>
                <div style="font-size:12px;color:var(--text-soft)">Clique em "Diário" para lançar aulas, frequência e notas</div>
            </div>

            @forelse($turma->turmaDisiplinas as $td)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--border)">
                <div style="display:flex;align-items:center;gap:14px">
                    <div style="width:42px;height:42px;border-radius:12px;background:var(--purple-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi bi-journal-text" style="color:var(--purple);font-size:18px"></i>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:700">{{ $td->disciplina->nome }}</div>
                        <div style="font-size:12px;color:var(--text-soft)">
                            @if($td->professor)
                                <i class="bi bi-person me-1"></i>{{ $td->professor->nome }}
                            @else
                                <span style="color:var(--amber)"><i class="bi bi-exclamation-circle me-1"></i>Sem professor</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    {{-- Atribuir professor --}}
                    <form method="POST" action="{{ route('turmas.disciplinas.professor', [$turma, $td]) }}" class="d-flex gap-1">
                        @csrf @method('PATCH')
                        <select name="professor_id" class="form-select form-select-sm" style="width:170px">
                            <option value="">Sem professor</option>
                            @foreach($professores as $prof)
                            <option value="{{ $prof->id }}" @selected($td->professor_id == $prof->id)>{{ explode(' ', $prof->nome)[0] }} {{ explode(' ', $prof->nome)[1] ?? '' }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-check-lg"></i>
                        </button>
                    </form>

                    <a href="{{ route('diario.index', $td->id) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-journal-richtext me-1"></i>Diário
                    </a>

                    <form method="POST" action="{{ route('turmas.disciplinas.destroy', [$turma, $td]) }}"
                          onsubmit="return confirm('Remover disciplina da turma?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align:center;color:var(--text-soft);padding:40px;font-size:14px">
                Nenhuma disciplina adicionada a esta turma ainda.
            </div>
            @endforelse
        </div>
    </div>

    {{-- Adicionar disciplina --}}
    <div class="col-lg-4">
        <div class="sp-card">
            <div style="font-size:14px;font-weight:700;margin-bottom:16px">
                <i class="bi bi-plus-circle me-2" style="color:var(--purple)"></i>Adicionar Disciplina
            </div>
            <form method="POST" action="{{ route('turmas.disciplinas.store', $turma) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Disciplina *</label>
                    <select name="disciplina_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        @foreach($disciplinas_disponiveis as $disc)
                        <option value="{{ $disc->id }}">{{ $disc->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Professor (opcional)</label>
                    <select name="professor_id" class="form-select">
                        <option value="">Nenhum</option>
                        @foreach($professores as $prof)
                        <option value="{{ $prof->id }}">{{ $prof->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-plus-lg me-1"></i>Adicionar
                </button>
            </form>
        </div>

        <div class="sp-card mt-3">
            <div style="font-size:13px;color:var(--text-soft)">
                <i class="bi bi-info-circle me-2" style="color:var(--purple)"></i>
                Adicione as disciplinas para depois registrar aulas, frequência e notas no <strong>Diário</strong>.
            </div>
        </div>
    </div>

</div>
@endsection
