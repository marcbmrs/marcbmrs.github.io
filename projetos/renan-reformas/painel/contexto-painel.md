# Contexto de continuidade - Painel Renan Reformas

Atualizado em 29/08/2026.

## Objetivo atual

O painel em `projetos/renan-reformas/painel/` permite criar, editar, acompanhar e apresentar orçamentos da Renan Reformas. A experiência é mobile-first e o banco local é MySQL, pelo XAMPP.

## Como executar localmente

1. Iniciar o MySQL no XAMPP.
2. No PowerShell, a partir de `C:\marcbmrs.github.io`, executar:

   ```powershell
   C:\xampp\php\php.exe -S localhost:8000
   ```

3. Abrir o painel:

   ```text
   http://localhost:8000/projetos/renan-reformas/painel/orcamentos.php
   ```

Manter a janela do servidor aberta enquanto testa. Para encerrar, usar `Ctrl + C`.

## Fluxo de status definido

```text
Rascunho
  -> Pronto para enviar
  -> Aguardando cliente
      -> Em negociação -> nova revisão
      -> Aprovado -> futura geração de contrato
      -> Recusado
      -> Expirado
      -> Cancelado
```

Regras acordadas:

- `rascunho`: orçamento iniciado e ainda não concluído; pode editar.
- `pronto_para_envio`: conferido internamente, mas ainda não enviado; pode editar.
- `aguardando_cliente`: enviado e aguardando retorno; não altera o original.
- `negociacao`: cliente pediu ajuste ou está avaliando condições; criar revisão para alterar valores/itens.
- `aprovado`: cliente aceitou; fica bloqueado e, no futuro, dará origem ao contrato.
- `recusado`, `expirado` e `cancelado`: encerram a revisão; uma nova necessidade cria nova revisão.
- Não usar `concluído` para orçamento: conclusão pertence ao fluxo posterior de obra/contrato.

## Banco de dados e migrações já executadas

O usuário executou com sucesso:

```powershell
C:\xampp\php\php.exe projetos\renan-reformas\painel\database\migrar-status-orcamentos.php
C:\xampp\php\php.exe projetos\renan-reformas\painel\database\migrar-local-obra.php
C:\xampp\php\php.exe projetos\renan-reformas\painel\database\migrar-preco-tabela-itens.php
```

Arquivos relevantes:

- `database/schema.sql`: definição para instalações novas.
- `database/migrar-status-orcamentos.php`: migra os status antigos.
- `database/migrar-local-obra.php`: adiciona os campos detalhados de localização.
- `database/migrar-preco-tabela-itens.php`: preserva o preço de tabela para calcular e mostrar descontos negociados por item.
- `database/migrar-acompanhamento-obras.php`: cria o portal privado de acompanhamento de obras.

## Dados de cliente e obra

No novo orçamento e na edição:

- Obrigatórios: nome, WhatsApp brasileiro com DDD, cidade e bairro da obra.
- Opcionais: endereço/número, complemento/referência, CEP e validade.
- Cada campo apresenta orientação e exemplo de preenchimento.
- O campo de WhatsApp usa máscara visual, como `(51) 99999-8888`.
- No salvamento, o telefone é validado e normalizado para E.164 somente com dígitos, por exemplo `5551999998888`.
- A página do orçamento exibe um link direto para o WhatsApp do cliente quando o número for válido.

## Edição de orçamento

`editar.php` foi refeito para reutilizar o fluxo visual de `index.php`:

- Dados da revisão vêm preenchidos.
- Serviços e locações podem ser adicionados a partir dos catálogos.
- Itens podem ser editados ou removidos.
- Total fica fixo na parte inferior.
- Há opção de voltar sem salvar.
- O salvamento atualiza, insere e remove itens em uma transação, via `salvar-edicao.php`.
- Depois do salvamento, a pessoa volta para a revisão do orçamento.

## Lista de orçamentos

`orcamentos.php` recebeu:

- Campo de busca por cliente ou código.
- Filtros de status.
- Cartões com cliente, código, status, total e atalho.
- Estado vazio para busca sem resultado.
- A lupa foi corrigida em `styles/main.css`: texto de leitores de tela usa `.sr-only` e o ícone é desenhado em CSS.

## PDF

`gerar-pdf.php` monta uma página A4 preparada para a impressão nativa do navegador em PDF. Não usa biblioteca externa.

- Layout contém logo, código, revisão, cliente, WhatsApp, local da obra, tabela de itens, total e observações.
- Logo vetorial criada em `assets/images/logo-renan-reformas.svg`, alinhada à marca usada no painel.
- O PDF é acessível em status `pronto_para_envio`, `aguardando_cliente`, `negociacao` e `aprovado`.
- A janela abre o diálogo de impressão automaticamente. O usuário escolhe **Salvar como PDF**.
- Nome sugerido: `Renan-Reformas-Nome-Do-Cliente-28-08-2026.pdf`.
- Na tela de geração há botão **Enviar pelo WhatsApp**. Ele abre uma conversa com mensagem preenchida; o PDF deve ser anexado manualmente. O link padrão `wa.me` não permite anexar arquivo automaticamente.
- Quando o preço negociado de um item é menor que o preço de tabela, o PDF mostra automaticamente o preço de tabela e o desconto total daquele item.

## Acompanhamento privado de obras

- `obras.php` é a área interna para abrir a página do cliente e copiar o link.
- O botão **Adicionar atualização** abre `atualizar-obra.php`: data, status, resumo, próximo passo e até cinco fotos.
- Na própria tela de atualização, o histórico interno permite editar texto, data, status e próximo passo; remover fotos individuais; ou excluir uma atualização com confirmação.
- `salvar-atualizacao-obra.php` mantém as fotos reais em `C:\MarcosDev-Originais\renan-reformas\images\obras\`, fora do repositório público. `foto-obra.php` só as entrega para uma sessão autorizada pelo código da obra.
- `obra-cliente.php` mostra o feed privado, protegido por endereço aleatório e código de seis dígitos.
- `database/popular-obra-acompanhamento-demonstracao.php` cria uma obra fictícia para avaliação. O código da demonstração é `482913`.
- A obra de demonstração usa cartões de imagem claramente demonstrativos; atualizações novas já aceitam fotos reais. Aviso por WhatsApp, criação de obras por orçamento e revogação de acesso continuam como próxima etapa.

## Avaliação UX mobile-first pendente de validação no MarcosOS

Foram analisados prints de `index.php`. Pontos positivos: identidade visual consistente, campos confortáveis, total fixo e etapas claras.

Melhorias propostas, ainda não implementadas:

1. Manter no primeiro trecho apenas nome, WhatsApp, cidade e bairro; agrupar endereço, complemento, CEP e validade em um expansor “Adicionar endereço completo e validade (opcional)”.
2. Só habilitar “Revisar orçamento” quando todos os obrigatórios estiverem preenchidos e houver pelo menos um item. Hoje o botão depende apenas de itens.
3. Remover a rolagem interna do catálogo no celular para manter uma única rolagem na página.
4. Dar retorno mais visível depois de adicionar item: confirmação breve, transição do total e atalho para o resumo.
5. Simplificar o texto introdutório da etapa de catálogo.

O usuário pediu que essas recomendações sejam confrontadas com o MarcosOS antes da implementação. Os documentos relevantes são:

- `C:\MarcosOS\20 - Conhecimento\M01 - Desenvolvimento Web e Experiência Digital\M02 - Estratégia e Design de Experiência\M03 - Experiência de Site\M03 - Experiência de Site.md`
- `C:\MarcosOS\20 - Conhecimento\M01 - Desenvolvimento Web e Experiência Digital\M02 - Estratégia e Design de Experiência\M03 - Experiência de Site\M04 - Estratégia, Arquitetura e UX de Sites.md`

Nesta sessão, o executor do agente falhou ao tentar ler arquivos fora do repositório com erro Windows 1920. Em um novo chat, tentar ler os dois documentos acima antes de aplicar melhorias de UX.

## Arquivos principais

- `painel.php`: página inicial do painel, com atalhos para orçamentos, obras e contratos.
- A página inicial tem aparência de app, barra de navegação fixa no celular e ícones gerados em `assets/images/` (AVIF/WebP).
- `contratos.php`: área preparatória de contratos; a geração automática ainda não foi implementada.
- `index.php`: criação de orçamento.
- `scripts/main.js`: interação de criação, máscara/validação de WhatsApp e salvamento.
- `salvar-orcamento.php`: valida e persiste o novo orçamento.
- `orcamentos.php` e `scripts/orcamentos.js`: lista, busca e filtros.
- `orcamento.php`: visualização, status, revisão e ações.
- `atualizar-status.php`: regras de transição de status.
- `criar-revisao.php`: cria nova revisão preservando a versão anterior.
- `editar.php`, `scripts/editar.js` e `salvar-edicao.php`: edição de rascunhos/prontos.
- `gerar-pdf.php`: página A4 para salvar como PDF e iniciar conversa pelo WhatsApp.
- `styles/main.css`: estilos compartilhados do painel.

## Observações importantes

- Não publicar, enviar ao GitHub ou alterar hospedagem sem pedido explícito.
- O usuário valoriza linguagem direta, orientação clara no formulário e experiência mobile-first.
- As imagens e o logo devem seguir a estrutura do projeto, dentro de `assets/`.
