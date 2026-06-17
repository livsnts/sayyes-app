# SayYes – Plataforma Web para Gestão de Casamentos

> Documento de contexto do projeto para uso em repositório. Gerado a partir do Projeto de TCC – IFPR Campus Umuarama.

---

## Visão Geral

**SayYes** é um sistema web voltado à gestão e organização completa de eventos de casamento. O objetivo central é centralizar em um único ambiente todas as informações relacionadas ao planejamento do evento, atendendo tanto aos noivos quanto aos assessores e convidados.

**Instituição:** IFPR – Instituto Federal do Paraná – Campus Umuarama  
**Curso:** Tecnologia em Análise e Desenvolvimento de Sistemas – 3º ano  
**Aluna:** Livia Maria dos Santos  
**Orientadora:** Márcia Cristina Dadalto Pascutti  

---

## Stack Tecnológica

| Camada | Tecnologia |
|---|---|
| Back-end | Laravel 11 |
| Front-end (views) | Blade + Tailwind CSS + Alpine.js |
| Banco de dados | MySQL |
| Build de assets | Vite |

---

## Perfis de Usuário

O sistema possui três perfis distintos:

- **Noivo** – cadastra e gerencia o casamento, convidados, fornecedores e finanças.
- **Assessor** – acompanha e gerencia múltiplos eventos sob sua responsabilidade. Mantém um catálogo próprio de fornecedores de confiança.
- **Convidado** – acessa o sistema via link com token para confirmar presença (sem login).

O tipo de usuário é definido no momento do cadastro e não pode ser alterado depois (decisão de modelagem documentada). Um assessor que também seja noivo é reconhecido como limitação conhecida do sistema.

---

## Identidade Visual

| Elemento | Valor |
|---|---|
| Cor primária (navy) | `#0b1957` |
| Fundo | `#fff2dc` |
| Acento lilás | `#d2b3dc` |
| Acento azul claro | `#e9f3ff` |
| Fonte primária | League Spartan |
| Fonte secundária | Mandani Arabic Bold (via `@font-face` manual) |

---

## Modelo de Dados

### Entidades Principais

#### Usuário
```
idUsuario (PK), statusUsuario*, tipoUsuario (NOIVO | ASSESSOR)*,
nomeUsuario*, telefoneUsuario*, cpfUsuario (único), sexoUsuario,
cepUsuario, cidadeUsuario, email* (único), senha*
```

#### Casamento
```
idCasamento (PK), statusCasamento (ATIVO | REALIZADO | CANCELADO)*,
nomeCasamento*, dataCasamento*, localCasamento, descricaoCasamento,
imagemCasamento (BLOB), urlListaDePresentes
```

#### CasamentoUsuario (tabela pivot N:N)
```
casamento_idCasamento (FK)*, usuario_idUsuario (FK)*, papelUsuario (NOIVO | ASSESSOR)*
```
> Um casamento pode ter múltiplos usuários com papéis diferentes (ex: 2 noivos + 1 assessor).

#### Convidado
```
idConvidado (PK), statusConvidado (PENDENTE | CONFIRMADO | RECUSADO)*,
nomeConvidado*, telefoneConvidado, tokenConfirmacao* (UUID),
quantidadeMaxAcompanhantes*, dataConfirmacao, observacoesConfirmacao,
alergiasConvidado, casamento_idCasamento (FK)*
```
> Vinculação de convidado ao casamento é feita por busca direta via e-mail, sem fluxo de convite formal.  
> Confirmação de presença foi absorvida dentro de `Convidado` (não existe tabela separada).

#### Acompanhante
```
idAcompanhante (PK), nomeAcompanhante*, alergiasAcompanhante,
convidado_idConvidado (FK)*
```

#### FornecedorConfianca (catálogo do assessor)
```
idFornecedorConfianca (PK), nomeFornecedorConfianca*, categoriaFornecedorConfianca,
telefoneFornecedorConfianca, instagramFornecedorConfianca,
usuario_idUsuario (FK)* — usuário que o cadastrou
```

#### FornecedorCasamento
```
idFornecedorCasamento (PK), statusFornecedorCasamento*, nomeFornecedorCasamento*,
categoriaFornecedorCasamento, valorTotalFornecedorCasamento (DECIMAL 10,2),
contratoFornecedorCasamento, observacoesFornecedorCasamento,
fornecedorConfianca_idFornecedorConfianca (FK, opcional),
casamento_idCasamento (FK)*
```
> `FornecedorConfianca` e `FornecedorCasamento` são entidades separadas por decisão de modelagem.

#### Pagamento (parcelas do fornecedor)
```
idPagamento (PK), statusPagamento (PENDENTE | PAGO | ATRASADO)*,
valorParcela* (DECIMAL 10,2), dataVencimento, dataPagamento,
numeroParcela, observacao,
fornecedor_idFornecedorCasamento (FK)*
```
> O status `ATRASADO` é **calculado em tempo real via PHP** (comparando `dataVencimento` com a data atual), não armazenado no banco.

---

## Requisitos Funcionais

### Cadastros
1. Cadastrar usuário (noivo ou assessor)
2. Cadastrar casamento
3. Vincular usuário a casamento com papel (`CasamentoUsuario`)
4. Cadastrar convidado
5. Cadastrar acompanhante de convidado
6. Cadastrar fornecedor de confiança (catálogo do assessor)
7. Cadastrar fornecedor do casamento

### Movimentações
1. Registrar pagamento de fornecedor (parcelas)

### Relatórios e Dashboards
1. **Dashboard financeiro do casamento**
   - Valor total previsto (soma dos fornecedores)
   - Valor total pago (parcelas com status PAGO)
   - Valor total pendente
   - Listagem de fornecedores com valores
   - Visualização gráfica dos gastos
2. Relatório de convidados por casamento
3. Relatório de casamentos por assessor
4. Relatório de fornecedores por assessor
5. Relatório de fornecedores por casamento

---

## Requisitos Não Funcionais

- **Usabilidade:** Interface amigável e intuitiva para todos os perfis, independente de familiaridade com tecnologia.
- **Compatibilidade:** Responsivo, acessível nos principais navegadores modernos (Chrome, Firefox, Edge, Safari) em desktop e mobile.
- **Desempenho:** Carregamento de páginas em até 3s; resposta de ações em menos de 2s.
- **Segurança:** Senhas com hash, validação de entradas, proteção contra ataques comuns (XSS, CSRF, SQL Injection).
- **Disponibilidade:** 24/7, salvo manutenções programadas.
- **Manutenibilidade:** Código com separação de responsabilidades, documentado e versionado.
- **Escalabilidade:** Arquitetura que permita expansão futura.
- **Backup:** Mecanismo periódico de backup do banco de dados.

---

## Decisões de Modelagem Relevantes

| Decisão | Justificativa |
|---|---|
| `ConfirmacaoPresenca` absorvida em `Convidado` | Simplificação do modelo; os dados de confirmação cabem na própria entidade. |
| `Mensagem` e upload de contratos não implementados | Adiados para iterações futuras. |
| Status `ATRASADO` calculado em PHP | Evita inconsistências; não é armazenado no banco. |
| Tipo de usuário fixo no cadastro | Simplicidade; troca de tipo não é um caso de uso previsto. |
| `FornecedorConfianca` ≠ `FornecedorCasamento` | Assessor mantém catálogo próprio independente dos eventos. |
| Vinculação de convidado por e-mail | Sem fluxo de convite; busca direta simplifica o processo. |
| WhatsApp via link `wa.me` | Sem integração de API; suficiente para o escopo do TCC. |

---

## Estrutura de Rotas e Middleware (Laravel)

- Rotas organizadas com `Route::resource` e rotas aninhadas (ex: casamento → convidados → acompanhantes).
- Route Model Binding para resolução automática de modelos via ID na URL.
- Middleware `VerificarTipoUsuario` / `CheckTipoUsuario` para separação de acesso entre noivos e assessores.
- Pivot table `casamento_usuario` com coluna `papel` para controle de papel por casamento.

---

## Cronograma de Implementação (2026)

| Mês | Atividade |
|---|---|
| Março – Abril | Elaboração do Projeto TCC |
| Maio – Junho | Revisão bibliográfica e modelagem |
| Junho – Outubro | Implementação do sistema |
| Agosto – Outubro | Escrita do artigo |
| Novembro | Entrega do artigo + Banca Examinadora |
| Dezembro | Entrega da versão final |

---

## Escopo Fora do Sistema (Limitações Conhecidas)

- Não há troca de tipo de usuário após o cadastro.
- Assessor que também é noivo deve escolher um único perfil (limitação documentada).
- Módulo de mensagens internas não implementado nesta versão.
- Upload de contratos de fornecedores não implementado nesta versão.
- Integração com WhatsApp é apenas via link `wa.me`, sem API oficial.