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
id (PK), statusUsuario*, tipoUsuario (NOIVO | ASSESSOR)*,
nomeUsuario*, telefoneUsuario*, sexoUsuario, cpfUsuario (único),
cidadeUsuario, email* (único), senha*
```
> Os nomes `nomeUsuario` e `senha` seguem o diagrama (modelo conceitual); na implementação, o Laravel usa as colunas `name` e `password`. A tabela também tem `cepUsuario` e `descricaoUsuario`, que não aparecem no diagrama.

> **Convenção de chaves:** PK é sempre `id` (auto-incremento) e as FKs seguem o padrão Laravel `<tabela_singular>_id` (ex: `casamento_id`). Os nomes abaixo refletem a implementação real.

#### Casamento
```
id (PK), statusCasamento (ATIVO | REALIZADO | CANCELADO)*,
nomeCasamento*, dataCasamento*, orcamentoTotal (DECIMAL 10,2),
localCasamento, descricaoCasamento, imagemCasamento (BLOB), urlListaDePresentes
```
> `orcamentoTotal` é a meta de orçamento global do casamento, usada na tela de fornecedores e no dashboard para comparar o orçamento com o total contratado.  
> A relação `Casamento::assessores()` filtra os membros da equipe com `tipoUsuario = ASSESSOR` e é usada para oferecer os fornecedores de confiança deles ao cadastrar fornecedores do casamento.

#### CasamentoUsuario (tabela pivot N:N — `casamento_user`)
```
casamento_id (FK)*, user_id (FK)*
```
> Um casamento pode ter múltiplos usuários (ex: 2 noivos + 1 assessor). A pivot **não** tem `papelUsuario`: o papel de cada pessoa no casamento é derivado do `tipoUsuario` do próprio `Usuario`.

#### Convidado
```
id (PK), statusConvidado (PENDENTE | CONFIRMADO | RECUSADO)*,
nomeConvidado*, telefoneConvidado, faixaEtariaConvidado, generoConvidado,
tokenConfirmacao* (UUID), quantidadeMaxAcompanhantes*, dataConfirmacao,
observacoesConfirmacao, casamento_id (FK)*
```
> `faixaEtariaConvidado` usa o enum `App\Enums\FaixaEtaria`: Bebê (0 a 3 anos), Criança (4 a 7), Infantil (8 a 12) e Adulto (13 ou mais). Convidado sem faixa preenchida conta como adulto nos totais.
> Vinculação de convidado ao casamento é feita por busca direta via e-mail, sem fluxo de convite formal.  
> Confirmação de presença foi absorvida dentro de `Convidado` (não existe tabela separada).  
> O `tokenConfirmacao` (UUID) é gerado automaticamente na criação do registro (evento `creating` do model).

#### Acompanhante
```
id (PK), statusAcompanhante (PENDENTE | CONFIRMADO | RECUSADO)*,
nomeAcompanhante*, faixaEtariaAcompanhante, generoAcompanhante,
convidado_id (FK)*
```
> A faixa etária também usa o enum `App\Enums\FaixaEtaria`. O status do acompanhante é independente do status do convidado.  
> A listagem de convidados mostra, em cada card de status, a quantidade de adultos, crianças e bebês (método `Convidado::faixasEtariasComStatus()` e `Convidado::resumoFaixas()`).

#### FornecedorConfianca (catálogo do assessor)
```
id (PK), nomeFornecedorConfianca*, categoriaFornecedorConfianca,
cidadeFornecedorConfianca, telefoneFornecedorConfianca,
instagramFornecedorConfianca, observacoesFornecedorConfianca,
user_id (FK)* — usuário que o cadastrou
```
> `categoriaFornecedorConfianca` usa o enum PHP `App\Enums\CategoriaFornecedor` (lista compartilhada com `FornecedorCasamento`).

#### FornecedorCasamento
```
id (PK), statusFornecedorCasamento (EM_PESQUISA | EM_NEGOCIACAO | CONTRATADO | CANCELADO)*,
nomeFornecedorCasamento*, categoriaFornecedorCasamento,
valorTotalFornecedorCasamento* (DECIMAL 10,2),
contratoFornecedorCasamento, observacoesFornecedorCasamento, totalParcelas* (INT, padrão 1),
telefoneFornecedorCasamento,
tipoValorFornecedorCasamento (FIXO | POR_PESSOA)*,
valorAdultoFornecedorCasamento (DECIMAL 10,2), valorCriancaFornecedorCasamento (DECIMAL 10,2),
quantidadeAdultosFornecedorCasamento (INT), quantidadeCriancasFornecedorCasamento (INT),
casamento_id (FK)*, fornecedor_confianca_id (FK, opcional)
```
> `FornecedorConfianca` e `FornecedorCasamento` são entidades separadas por decisão de modelagem.  
> `categoriaFornecedorCasamento` usa o enum `App\Enums\CategoriaFornecedor`; `tipoValorFornecedorCasamento` usa o enum `App\Enums\TipoValorFornecedor`.  
> **Valor do fornecedor.** `valorTotalFornecedorCasamento` é o valor combinado com o fornecedor, **salvo e editável**. No tipo `FIXO`, o usuário informa só o total. No tipo `POR_PESSOA`, informa o valor por adulto e por criança e as quantidades de adultos e crianças do contrato; o formulário sugere o total como `(valorAdulto × quantidadeAdultos) + (valorCrianca × quantidadeCriancas)`, e o usuário pode editar o resultado. Os campos de valor unitário e de quantidade ficam nulos no tipo `FIXO`.  
> **Quando o total é recalculado.** O total só é refeito (no navegador, via Alpine.js) quando o usuário altera um dos quatro campos de valor unitário ou quantidade. Abrir ou editar outros dados do fornecedor não muda o total, e ele **não** acompanha a lista de convidados. O formulário apenas exibe um lembrete em texto com o número atual de convidados adultos e crianças (bebês e recusados não entram na contagem).  
> **Status e totais.** Todos os status continuam disponíveis no cadastro. Apenas fornecedores `CONTRATADO` entram no card "Contratado" e são descontados do "Disponível" (`orcamentoTotal` − soma dos totais contratados).  
> **Herança do assessor.** Se houver um assessor na equipe do casamento, o modal de cadastro oferece automaticamente, sem nenhuma configuração, um dropdown "Puxar do assessor" com os fornecedores de confiança dos assessores da equipe que ainda não foram adicionados ao casamento. Nome, categoria e telefone são **copiados** para o `FornecedorCasamento`, e `fornecedor_confianca_id` guarda só a origem; alterar o catálogo do assessor depois não afeta casamentos já cadastrados. O servidor só aceita o vínculo se o fornecedor pertencer a um assessor da equipe.  
> `totalParcelas` é o número de parcelas em que o fornecedor será pago. Fica em `FornecedorCasamento` e se relaciona com `Pagamento`, que guarda cada parcela (`numeroParcela`). Não aparece no cadastro do fornecedor: é informado no modal de parcelas e gravado quando as parcelas são geradas (ver "Pagamento").  
> `contratoFornecedorCasamento` guarda o **caminho** do PDF do contrato (opcional), armazenado em disco via Storage — não o conteúdo do arquivo.  
> A entidade `OrcamentoFornecedor` foi descartada: o ciclo de vida do fornecedor é controlado apenas pelo status.

#### Pagamento (parcelas do fornecedor)
```
id (PK), statusPagamento (PENDENTE | PAGO)*,
valorParcela* (DECIMAL 10,2), dataVencimento, dataPagamento,
numeroParcela, observacao,
fornecedor_casamento_id (FK)*
```
> O status `ATRASADO` é **calculado em tempo real via PHP** (método `Pagamento::statusReal()`: parcela não paga com `dataVencimento` anterior à data atual), não armazenado no banco. Por isso a coluna só aceita `PENDENTE` e `PAGO`.  
> **Geração das parcelas.** No modal "Parcelas" da tela de fornecedores (botão disponível só para fornecedores `CONTRATADO`), o usuário informa o número de parcelas (1 a 60) e o vencimento da primeira. O sistema divide o `valorTotalFornecedorCasamento` em parcelas iguais, calculando em **centavos inteiros** para não perder dinheiro no arredondamento; a última parcela absorve a sobra (ex.: R$ 1.000,00 em 3× = 333,33 + 333,33 + 333,34). Os vencimentos são mensais, no mesmo dia (`addMonthsNoOverflow`, de modo que 31/01 vira 28/02 e não 03/03). A criação das parcelas e a gravação de `totalParcelas` ocorrem em uma transação (tudo ou nada). Só é possível gerar se o fornecedor ainda não tem parcelas.  
> **Registro de pagamento.** Cada parcela pode ser marcada como paga (grava `PAGO` e `dataPagamento`, que por padrão é hoje e não pode ser futura) e o pagamento pode ser desfeito (volta a `PENDENTE` e zera a data). "Refazer parcelas" apaga todas as parcelas do fornecedor, com confirmação, para gerar novas.  
> **Segurança.** Toda ação no `PagamentoController` verifica a permissão sobre o casamento e que o fornecedor pertence ao casamento e a parcela ao fornecedor, evitando acesso a dados de outro casamento trocando IDs na URL.  
> **Rotas** (nomes): `pagamento.gerar` (POST), `pagamento.reiniciar` (DELETE), `pagamento.pagar` e `pagamento.desfazer` (PATCH), todas aninhadas em `/casamentos/{casamento}/fornecedores/{fornecedor}/parcelas`.

### Relacionamentos (diagrama entidade-relacionamento)

| Relação | Cardinalidade | Observação |
|---|---|---|
| Usuário — Casamento | N:N, via `CasamentoUsuario` (`casamento_user`) | Um casamento pode ter vários usuários (noivos e assessor) e um assessor atende vários casamentos. |
| Usuário — FornecedorConfianca | 1:N | O catálogo pertence ao usuário que o cadastrou (assessor). |
| Casamento — Convidado | 1:N | |
| Convidado — Acompanhante | 1:N | |
| Casamento — FornecedorCasamento | 1:N | |
| FornecedorConfianca — FornecedorCasamento | 1:N, **opcional** | `fornecedor_confianca_id` guarda só a origem quando o fornecedor foi puxado do assessor. |
| FornecedorCasamento — Pagamento | 1:N | Cada parcela é um `Pagamento`; `totalParcelas` indica quantas existem. Ao excluir o fornecedor, suas parcelas são excluídas em cascata. |

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
| `Mensagem` não implementado | Adiado para iterações futuras. |
| Status `ATRASADO` calculado em PHP | Evita inconsistências; não é armazenado no banco. |
| Tipo de usuário fixo no cadastro | Simplicidade; troca de tipo não é um caso de uso previsto. |
| `FornecedorConfianca` ≠ `FornecedorCasamento` | Assessor mantém catálogo próprio independente dos eventos. |
| Vinculação de convidado por e-mail | Sem fluxo de convite; busca direta simplifica o processo. |
| WhatsApp via link `wa.me` | Sem integração de API; suficiente para o escopo do TCC. |
| Confirmação de presença via token UUID | Acesso sem login por link único. Preferido a login (fricção desnecessária para um ato pontual) e a código curto (risco de colisão/adivinhação). UUID v4 garante unicidade e imprevisibilidade (122 bits aleatórios). |
| FKs e PKs na convenção Laravel | PK `id` e FK `<tabela>_id`. O Eloquent infere relacionamentos automaticamente, reduzindo código e erros. |
| `tipoUsuario` no `Usuario` (não na pivot) | Papel fixo por usuário controla interface/rotas/middleware; pivot `casamento_user` sem `papelUsuario`. Simplifica login e apresentação (Opção 3). |
| Orçamento: meta global + total por fornecedor | `orcamentoTotal` no casamento e `valorTotal` por fornecedor (somando só os `CONTRATADO`) permitem comparar orçamento e contratado, sem entidade de orçamento dedicada nem `valorEstimado` (deixados como evolução futura). |
| Sem entidade `OrcamentoFornecedor` | O fornecedor é cadastrado direto, e o status (`EM_PESQUISA`, `EM_NEGOCIACAO`, `CONTRATADO`, `CANCELADO`) indica em que etapa ele está. Evita duplicar dados entre cotação e fornecedor. |
| Total do fornecedor salvo e editável | Em contrato, o número de pessoas é fixo. Guardar o total evita que o orçamento oscile com as confirmações de convidados. Valores unitários e quantidades são guardados como referência e alimentam a sugestão do total. |
| Lista de convidados só como lembrete no fornecedor | A contagem atual de adultos e crianças aparece em texto no formulário, mas não entra no cálculo salvo; assim editar o fornecedor não altera o total sem o usuário querer. |
| Bebês não pagam no valor por pessoa | Bebês (0 a 3 anos) não entram na contagem de pessoas que pagam; crianças são as faixas de 4 a 12 anos. |
| Herança de fornecedores do assessor por cópia | Nome, categoria e telefone do fornecedor de confiança são copiados ao cadastrar; `fornecedor_confianca_id` registra a origem. O dropdown aparece automaticamente quando há um assessor na equipe do casamento, sem opção de ativar ou desativar. |
| Categorias de fornecedor via enum PHP | `App\Enums\CategoriaFornecedor` centraliza a lista (compartilhada pelas duas entidades de fornecedor), alimenta dropdown, filtro e validação. |
| Contrato em PDF opcional via Storage | Caminho do arquivo no banco (`string`), arquivo em disco. Evita inchar o banco com BLOBs; prática padrão do Laravel. |
| Parcelas geradas em centavos inteiros | Dividir valores decimais (`float`) acumula erro de arredondamento e a soma das parcelas pode divergir do total. Com inteiros a divisão é exata e a última parcela absorve a sobra. |
| Parcelas iguais, vencimento mensal e "congeladas" | A geração é automática e simples (valor igual, mesmo dia de cada mês). Depois de geradas, as parcelas não acompanham mudanças no valor do fornecedor: o modal avisa quando a soma diverge do total e "Refazer parcelas" recalcula. |
| Geração de parcelas em transação | `DB::transaction` garante que as parcelas e o `totalParcelas` sejam gravados juntos ou nenhum, sem deixar o fornecedor com parcelas incompletas. |
| Parcelas só para fornecedor `CONTRATADO` | O botão de parcelas só aparece para fornecedores contratados, pois só eles representam um compromisso de pagamento. |
| Modais específicos de tela como `@include` | Blocos usados em uma única página (cadastro, edição, detalhes, parcelas) ficam em `resources/views/<funcionalidade>/` e são incluídos com `@include`; `components/` guarda só elementos genéricos e reutilizáveis (botão, campo, modal, etc.). |

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
- Orçamento detalhado por categoria não implementado (apenas meta global + total por fornecedor); fica como evolução futura. A comparação "estimado × contratado" por fornecedor também fica como evolução futura.
- O total de um fornecedor cobrado por pessoa não é recalculado automaticamente quando a lista de convidados muda; o usuário precisa atualizar o fornecedor manualmente (o formulário exibe apenas um lembrete com o número atual de convidados).
- As parcelas de um fornecedor não são recalculadas quando o valor total dele muda depois de geradas; o usuário precisa usar "Refazer parcelas" (o modal exibe um aviso quando a soma das parcelas diverge do total). Também não há edição individual de valor ou vencimento de uma parcela, nem parcelas de valores diferentes; fica como evolução futura.
- Convidado ou acompanhante sem faixa etária preenchida conta como adulto nas contagens.
- Integração com WhatsApp é apenas via link `wa.me`, sem API oficial.
- O link de confirmação de presença, se compartilhado, permite que terceiros alterem a confirmação do convidado — risco aceitável dado o baixo impacto (não há dado sensível nem financeiro envolvido).