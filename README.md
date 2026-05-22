# School+

Sistema de gestão escolar multi-tenant construído em Laravel 11. Desenvolvido para escolas, faculdades, cursos livres e técnicos que precisam de uma plataforma completa de administração acadêmica e financeira.

---

## Sumário

- [Visão Geral](#visão-geral)
- [Stack Tecnológica](#stack-tecnológica)
- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Banco de Dados](#banco-de-dados)
- [Arquitetura](#arquitetura)
- [Multi-Tenancy](#multi-tenancy)
- [Autenticação e Perfis](#autenticação-e-perfis)
- [Módulos do Sistema](#módulos-do-sistema)
  - [Fase 1 — Acadêmico (MVP)](#fase-1--acadêmico-mvp)
  - [Fase 2 — Diário de Classe](#fase-2--diário-de-classe)
  - [Fase 3 — Financeiro](#fase-3--financeiro)
- [Rotas](#rotas)
- [Modelos e Relacionamentos](#modelos-e-relacionamentos)
- [Interface](#interface)
- [Fases Futuras](#fases-futuras)

---

## Visão Geral

O School+ é uma plataforma SaaS que centraliza:

- **Gestão acadêmica** — alunos, turmas, matrículas, cursos e períodos letivos
- **Diário de classe** — disciplinas, professores, aulas, frequência e notas
- **Financeiro** — planos de pagamento, cobranças, baixa de pagamento e negociação de débitos
- **Multi-tenancy** — cada escola (tenant) opera em total isolamento de dados

---

## Stack Tecnológica

| Camada       | Tecnologia                          |
|--------------|-------------------------------------|
| Backend      | PHP 8.2 + Laravel 11                |
| Banco        | MariaDB / MySQL via XAMPP           |
| Frontend     | Blade + Bootstrap 5.3 + Bootstrap Icons |
| Servidor Dev | Apache (XAMPP)                      |
| Autenticação | Session-based customizada (sem Laravel Sanctum/Passport) |

---

## Requisitos

- PHP >= 8.2 com extensões: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer
- MySQL / MariaDB >= 10.4
- Apache ou Nginx (XAMPP recomendado para desenvolvimento)

---

## Instalação

```bash
# 1. Clonar o repositório
git clone <repo-url> school-plus
cd school-plus

# 2. Instalar dependências PHP
composer install

# 3. Copiar e configurar .env
cp .env.example .env
php artisan key:generate

# 4. Configurar banco no .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=schoolplus
DB_USERNAME=root
DB_PASSWORD=

# 5. Criar banco e rodar migrations
php artisan migrate

# 6. (Opcional) Popular com dados de exemplo
php artisan db:seed
```

Acesse: `http://localhost/school-plus/public`

---

## Banco de Dados

### Diagrama de Tabelas

```
tenants
  └── usuarios (perfil: superadmin | admin | secretaria | professor)
  └── cursos
  └── periodos_letivos
  └── disciplinas
  └── turmas
        └── turma_disciplinas (pivot: turma + disciplina + professor)
              └── aulas
                    └── frequencias (por matrícula)
              └── avaliacoes
                    └── notas (por matrícula)
  └── alunos
        └── responsaveis
        └── matriculas (aluno + turma + periodo)
              └── cobrancas
  └── planos_pagamento
  └── negociacoes
  └── comunicados          (Fase 4)
  └── documentos_aluno     (Fase 4)
  └── logs_auditoria       (Fase 4)
```

### Convenções de Schema

- Todas as tabelas usam `criado_em` / `atualizado_em` (não `created_at` / `updated_at`)
- IDs como `unsignedInteger`, não `bigInteger`
- Toda tabela de domínio tem `tenant_id` com FK para `tenants`
- Exceção: `turma_disciplinas` — isolamento feito via relacionamento com `turmas`

---

## Arquitetura

### Estrutura de Diretórios

```
app/
├── Http/
│   ├── Controllers/       # Um controller por recurso
│   └── Middleware/
│       ├── EnsureAuthenticated.php   # Guarda todas as rotas autenticadas
│       └── EnsureRole.php            # Controle por perfil (admin, secretaria...)
├── Models/                # Eloquent models com BelongsToTenant
└── Traits/
    └── BelongsToTenant.php           # Escopo global de tenant

resources/views/
├── layouts/app.blade.php  # Layout principal (sidebar + topbar + content)
├── auth/login.blade.php
├── dashboard/
├── alunos/ turmas/ matriculas/ cursos/ periodos/
├── disciplinas/ professores/ diario/ boletim/
├── financeiro/ cobrancas/ planos/ negociacoes/
```

---

## Multi-Tenancy

O isolamento é feito manualmente via `tenant_id` em todas as tabelas, sem pacotes externos.

### Trait `BelongsToTenant`

Aplicado em todos os models de domínio. Faz duas coisas automaticamente:

**1. Auto-inject no create:**
```php
static::creating(function ($model) {
    if (session()->has('tenant_id') && empty($model->tenant_id)) {
        $model->tenant_id = session('tenant_id');
    }
});
```

**2. Global scope em todas as queries:**
```php
static::addGlobalScope('tenant', function (Builder $builder) {
    if (session()->has('tenant_id')) {
        $builder->where('table.tenant_id', session('tenant_id'));
    }
});
```

### Exceção: `TurmaDisiplina`

A tabela `turma_disciplinas` não possui `tenant_id`. O isolamento acontece via relacionamento — qualquer query que precise de segurança passa por `whereHas('turma', fn($q) => $q->where('tenant_id', session('tenant_id')))`.

---

## Autenticação e Perfis

### Sessão

Não usa o sistema `Auth` do Laravel. Após login bem-sucedido, são gravadas na sessão:

| Chave | Conteúdo |
|---|---|
| `usuario_id` | ID do usuário logado |
| `usuario_nome` | Nome completo |
| `usuario_perfil` | Perfil: `superadmin`, `admin`, `secretaria`, `professor` |
| `tenant_id` | ID da escola atual |

### Middlewares

```php
// bootstrap/app.php:
'auth.session' => EnsureAuthenticated::class
'role'         => EnsureRole::class

// Uso nas rotas:
Route::middleware('auth.session')->group(function () {
    Route::middleware('role:admin,secretaria,superadmin')->group(function () {
        // rotas protegidas
    });
});
```

### Perfis Disponíveis

| Perfil | Acesso |
|---|---|
| `superadmin` | Tudo, incluindo gestão de tenants |
| `admin` | Gestão completa da escola |
| `secretaria` | Acadêmico + Financeiro (sem configurações) |
| `professor` | Diário de classe (aulas, frequência, notas) |

---

## Módulos do Sistema

### Fase 1 — Acadêmico (MVP)

#### Alunos (`/alunos`)
- Cadastro completo com dados pessoais, endereço e contato
- Listagem com busca por nome, CPF ou email
- Ficha do aluno com histórico de matrículas

#### Cursos (`/cursos`)
- Cursos vinculados ao tenant
- Status: ativo / inativo

#### Períodos Letivos (`/periodos`)
- Define os semestres/anos da escola
- Status: planejamento → em andamento → encerrado

#### Turmas (`/turmas`)
- Vinculadas a curso + período letivo
- Capacidade máxima de alunos
- Gestão de disciplinas via aba dedicada

#### Matrículas (`/matriculas`)
- Associa aluno + turma + período
- Status: ativa / trancada / cancelada / concluída / transferida
- Atualização de status via PATCH

---

### Fase 2 — Diário de Classe

#### Disciplinas (`/disciplinas`)
- Cadastro de matérias com carga horária
- Vinculáveis a qualquer turma

#### Professores (`/professores`)
- Usuários com perfil `professor`
- CRUD separado com parâmetro de rota `usuario` (evita singularização incorreta do Laravel)

#### Disciplinas por Turma (`/turmas/{turma}/disciplinas`)
- Associa disciplinas à turma
- Atribui professor responsável por cada disciplina
- Pivot `turma_disciplinas` sem `tenant_id`

#### Diário (`/diario/{td}`)

| Rota | Ação |
|---|---|
| `GET /diario/{td}` | Lista aulas e avaliações da disciplina |
| `POST /diario/{td}/aulas` | Cria nova aula (gera frequência para todos os alunos automaticamente com `presente=1`) |
| `GET /diario/{td}/aulas/{aula}` | Tela de frequência da aula |
| `PATCH /diario/{td}/aulas/{aula}/frequencia` | Salva presenças/faltas |
| `POST /diario/{td}/avaliacoes` | Cria avaliação |
| `GET /diario/{td}/avaliacoes/{avaliacao}/notas` | Tela de lançamento de notas |
| `POST /diario/{td}/avaliacoes/{avaliacao}/notas` | Salva notas (upsert) |

**Frequência automática:** ao criar uma aula, o sistema gera registros de `frequencias` para todos os alunos com matrícula ativa na turma (presente por padrão). O professor só precisa desmarcar as faltas.

**Notas:** usa `Nota::updateOrCreate(['avaliacao_id', 'matricula_id'], ['nota'])` — seguro para redigitar sem duplicatas.

#### Boletim (`/alunos/{aluno}/boletim`)
- Organizado por turma → por disciplina
- Exibe todas as notas por avaliação
- Calcula média e percentual de frequência por disciplina

---

### Fase 3 — Financeiro

#### Dashboard Financeiro (`/financeiro`)
- Receita do mês atual
- Total a receber (cobranças abertas não vencidas)
- Total vencido com quantidade de cobranças
- Contador de inadimplentes
- Gráfico de barras CSS puro — receita mês a mês no ano corrente
- Lista top inadimplentes com valor total de dívida
- Últimos 8 pagamentos registrados

#### Planos de Pagamento (`/planos`)
- Define valor, tipo (mensal/semestral/anual/avulso), dia de vencimento
- Condições de atraso: multa percentual + juros diário
- Desconto por pontualidade
- Vinculável a curso específico ou geral

#### Cobranças (`/cobrancas`)

Listagem com filtros por status, competência e busca por nome/descrição. Cards de totais: a receber / vencidas / pago no mês.

**Status possíveis:**

| Status DB | `status_real` (computed) | Quando |
|---|---|---|
| `aberta` | `aberta` | Vencimento futuro |
| `aberta` | `vencida` | Vencimento passado |
| `paga` | `paga` | — |
| `cancelada` | `cancelada` | — |
| `negociada` | `negociada` | Substituída por negociação |

**Valor corrigido** (calculado em `Cobranca::getValorCorrigidoAttribute`):
```
valor_corrigido = valor_original - desconto + multa + (juros_dia × dias_atraso)
```

**Fluxos:**
- `POST /cobrancas/{id}/pagar` — registra pagamento com forma, valor e data
- `POST /cobrancas/{id}/cancelar` — cancela a cobrança
- `GET /cobrancas/gerar` → `POST /cobrancas/gerar` — gera lote de mensalidades para uma matrícula, pulando automaticamente competências já existentes (sem duplicatas)

#### Negociações (`/negociacoes`)

Fluxo completo de renegociação de débitos:

1. Selecionar aluno (matrícula ativa)
2. Escolher cobranças vencidas a incluir no acordo
3. Aplicar desconto percentual + definir número de parcelas e data inicial
4. Resumo dinâmico em JS (total original → desconto → valor final → por parcela)
5. Ao confirmar:
   - Cobranças originais marcadas como `negociada`
   - Novas cobranças geradas (uma por parcela) com `obs = "Negociação #{id}"`
   - Tudo dentro de `DB::transaction()`

Tela de detalhe exibe os 4 valores financeiros + tabela de parcelas com link para cada cobrança individual.

---

## Rotas

```
GET    /login
POST   /login
POST   /logout

GET    /                               dashboard

# Alunos
GET    /alunos                         index
GET    /alunos/create                  form criação
POST   /alunos                         store
GET    /alunos/{aluno}                 show
GET    /alunos/{aluno}/edit            form edição
PUT    /alunos/{aluno}                 update
GET    /alunos/{aluno}/boletim         boletim do aluno

# Cursos, Períodos — CRUD completo
GET/POST/PUT/DELETE  /cursos
GET/POST/PUT/DELETE  /periodos

# Turmas
GET/POST/PUT/DELETE  /turmas
GET    /turmas/{turma}/disciplinas              gerenciar disciplinas
POST   /turmas/{turma}/disciplinas              adicionar disciplina
DELETE /turmas/{turma}/disciplinas/{td}         remover disciplina
PATCH  /turmas/{turma}/disciplinas/{td}/professor  atribuir professor

# Matrículas
GET    /matriculas                     index
GET    /matriculas/create              form
POST   /matriculas                     store
GET    /matriculas/{matricula}         show
PATCH  /matriculas/{matricula}/status  atualizar status

# Disciplinas, Professores — CRUD completo
GET/POST/PUT/DELETE  /disciplinas
GET/POST/PUT/DELETE  /professores

# Diário de Classe
GET    /diario/{td}                                        index
POST   /diario/{td}/aulas                                  nova aula
GET    /diario/{td}/aulas/{aula}                           frequência
PATCH  /diario/{td}/aulas/{aula}/frequencia                salvar frequência
POST   /diario/{td}/avaliacoes                             nova avaliação
GET    /diario/{td}/avaliacoes/{avaliacao}/notas           lançar notas
POST   /diario/{td}/avaliacoes/{avaliacao}/notas           salvar notas

# Financeiro
GET    /financeiro                     dashboard

GET    /planos                         index
GET    /planos/create                  form
POST   /planos                         store
GET    /planos/{plano}/edit            form edição
PUT    /planos/{plano}                 update
DELETE /planos/{plano}                 desativar

GET    /cobrancas/gerar                form lote  ← DEVE vir antes do resource
POST   /cobrancas/gerar                processar lote
GET    /cobrancas                      index com filtros
GET    /cobrancas/create               nova avulsa
POST   /cobrancas                      store
GET    /cobrancas/{cobranca}           detalhe
POST   /cobrancas/{cobranca}/pagar     registrar pagamento
POST   /cobrancas/{cobranca}/cancelar  cancelar

GET    /negociacoes                    index
GET    /negociacoes/create             form
POST   /negociacoes                    store
GET    /negociacoes/{negociacao}       detalhe
```

> **Importante:** as rotas `cobrancas/gerar` são declaradas **antes** de `Route::resource('cobrancas')` em `web.php`. Caso contrário, o Laravel interpreta `gerar` como o parâmetro `{cobranca}`.

---

## Modelos e Relacionamentos

```
Tenant
  hasMany → Usuario, Curso, PeriodoLetivo, Disciplina, Turma,
            Aluno, Matricula, PlanoPagamento, Cobranca, Negociacao

Usuario
  hasOne  → Aluno

Aluno
  belongsTo → Usuario
  hasMany   → Responsavel, Matricula

Matricula
  belongsTo → Aluno, Turma, PeriodoLetivo
  hasMany   → Cobranca, Frequencia, Nota

Turma
  belongsTo → Curso, PeriodoLetivo
  hasMany   → TurmaDisiplina, Matricula

TurmaDisiplina  [sem BelongsToTenant — sem tenant_id na tabela]
  belongsTo → Turma, Disciplina, Usuario (professor)
  hasMany   → Aula, Avaliacao

Aula
  belongsTo → TurmaDisiplina
  hasMany   → Frequencia

Frequencia
  belongsTo → Aula, Matricula

Avaliacao
  belongsTo → TurmaDisiplina
  hasMany   → Nota

Nota
  belongsTo → Avaliacao, Matricula

PlanoPagamento
  belongsTo → Curso (nullable)
  hasMany   → Cobranca

Cobranca
  belongsTo → Matricula, PlanoPagamento (nullable)

Negociacao
  belongsTo → Matricula, Usuario
```

---

## Interface

### Tema Visual

Light purple — baseado em CSS custom properties:

| Variável | Valor | Uso |
|---|---|---|
| `--purple` | `#7c3aed` | Cor primária, sidebar, botões |
| `--bg` | `#ece8f8` | Fundo da página |
| `--surface` | `#ffffff` | Cards, main wrapper |
| `--surface2` | `#f8f7ff` | Fundos secundários |
| `--border` | `#e5e0f5` | Bordas |
| `--text` | `#1e1b4b` | Texto principal |
| `--green` | `#16a34a` | Sucesso, pago |
| `--red` | `#dc2626` | Erro, vencido |
| `--amber` | `#d97706` | Aviso, a vencer |
| `--blue` | `#2563eb` | Info, parcelas |

### Layout

- **Body:** `padding: 12px; gap: 12px; display: flex` — sidebar + main lado a lado com bordas arredondadas flutuando
- **Sidebar:** `border-radius: 20px`, fundo `--purple`, colapsável
- **Main wrapper:** `border-radius: 20px`, fundo branco, scrollável
- **Sidebar colapsável:** estado em `localStorage('sbClosed')`, labels com `opacity: 0; max-width: 0` em modo fechado

### Tela de Login

Glassmorphism — fundo lavanda (`#edeaf7`) com card central branco:
- Painel esquerdo: gradiente roxo com SVG ilustrado (girl in armchair)
- Painel direito: formulário limpo com inputs estilizados e sombra suave

### Componentes Reutilizáveis

| Classe | Descrição |
|---|---|
| `.sp-card` | Card com borda, sombra suave e border-radius 18px |
| `.sp-table` | Tabela com hover purple-light e header cinza |
| `.stat-card` | Card de estatística com ícone flutuante e hover animado |
| `.badge-sp .badge-green/red/amber/blue/purple/muted` | Badges coloridos pill-shaped |
| `.welcome-banner` | Banner degradê com círculos decorativos em pseudo-elementos |
| `.section-label` | Label de seção uppercase com borda inferior |
| `.nav-item / .nav-section` | Itens e grupos da sidebar |

---

## Notas de Desenvolvimento

### Padrão de Queries com Tenant

```php
// BelongsToTenant aplica WHERE tenant_id automaticamente em toda query
$alunos = Aluno::all();

// Para burlar o escopo (ex: professores são Usuarios sem escopo próprio)
$professores = Usuario::withoutGlobalScope('tenant')
    ->where('tenant_id', session('tenant_id'))
    ->where('perfil', 'professor')
    ->get();
```

### Verificação de Ownership em Controllers

```php
abort_if($model->tenant_id !== session('tenant_id'), 403);
```

### Computed Attributes — Model Cobranca

```php
// Considera data de vencimento para cobranças com status 'aberta'
$cobranca->status_real;   // 'vencida' se aberta + data passada

// Calcula valor com multa + juros proporcionais aos dias de atraso
$cobranca->valor_corrigido;

// Subtrai desconto do valor original
$cobranca->valor_liquido;
```

### Timestamps Personalizados

Todos os models têm:
```php
public $timestamps = false;
protected $dates = ['criado_em', 'atualizado_em'];
```

---

## Fases Futuras

| Fase | Módulo | Descrição |
|---|---|---|
| 4 | Portais | Portal do aluno e responsável (login separado, visualização de notas/cobranças) |
| 4 | Comunicados | Avisos por turma com leitura confirmada |
| 4 | Documentos | Upload e gestão de documentos por aluno |
| 5 | Relatórios | Boletins em PDF, diário de classe, relatório de inadimplência |
| 6 | Integrações | Pix automático via API, boletos por email, WhatsApp para cobranças |
| 7 | SuperAdmin | Painel de gestão de tenants, métricas globais, logs de auditoria |

---

## Licença

Projeto proprietário — todos os direitos reservados.
