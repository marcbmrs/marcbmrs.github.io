# Operational Learning

This file stores durable, Marcos Dev-specific learning records created under KORA level 7.

## Record Format

```text
Date:
Scope:
Source/Evidence:
Learning:
Decision or Recommendation:
Follow-up:
```

## Records

### 2026-09-11 - Renan Reformas: serviços de forro e pintura no catálogo

Date: 2026-09-11
Scope: renan-reformas-service-catalog
Source/Evidence: Solicitação direta do titular para adicionar os serviços ao catálogo do painel.
Learning: Forro e pintura devem estar disponíveis nos fluxos de criação e edição de orçamento como itens por metro quadrado.
Decision or Recommendation: Manter `Forro` e `Pintura` em `painel/app/catalogo-inicial.php`, ambos com valor de tabela de R$ 50,00/m² (5.000 centavos). `Forro` deve ter filtro próprio no painel; `Pintura` permanece em `Acabamento`.
Follow-up: Qualquer mudança futura de preço deve alterar somente o catálogo central e preservar os valores já gravados em orçamentos existentes.

### 2026-09-11 - Renan Reformas: catálogo de forro e pintura publicado

Date: 2026-09-11
Scope: renan-reformas-production-deploy
Source/Evidence: Com aprovação explícita de Marcos, o upload por arquivo para `renanreformas.com.br` retornou `204` para `index.html`, `painel/app/catalogo-inicial.php`, `painel/index.php` e `painel/editar.php`; a limpeza de cache foi aceita pelo provedor. A página pública retornou `200` com as novas referências a Forro e Pintura; o painel protegido retornou sua tela de login sem erro.
Learning: O catálogo e os filtros do painel podem receber atualizações pequenas por upload direcionado, sem publicar outros arquivos ou substituir o site inteiro.
Decision or Recommendation: Para alterações futuras de categoria ou preço, publicar o catálogo e as duas telas do painel que renderizam os filtros, além do site público somente quando a oferta visível também mudar.
Follow-up: Em uma sessão autenticada, confirmar visualmente o filtro `Forro` ao montar e editar um orçamento.

### 2026-09-05 - Instagram read connector available for Marcos Dev

Date: 2026-09-05
Scope: instagram-read-operations
Source/Evidence: `C:\MarcosOS\tools\instagram-connector\health_check.py --profile marcos-dev` returned authorized read access; `read_media.py --profile marcos-dev --limit 5` returned sanitized media data.
Learning: Marcos Dev already has an authorized read-only Instagram connector in MarcosOS. The local KORA layer should use it instead of duplicating API scripts or credentials in this public repository.
Decision or Recommendation: Use `.kora/skills/collect-instagram-post-intelligence.md` for post analysis and `.kora/tools/instagram-connector.md` as the local reference to the MarcosOS connector.
Follow-up: Implement broader insights only after verifying current Meta endpoints and required permissions.
### 2026-09-05 - Instagram post evaluation needs an agent layer

Date: 2026-09-05
Scope: social-media-analysis
Source/Evidence: Marcos asked for a KORA capability to evaluate Marcos Dev Instagram posts and help social media decisions; existing local skill/tool already handle collection.
Learning: Post evaluation requires editorial judgment across audience, offer, CTA, format, visual/copy fit, repetition, and learning value. This is better represented as an agent than as another collection skill.
Decision or Recommendation: Use `.kora/agents/instagram-content-analyst.md` with `.kora/skills/collect-instagram-post-intelligence.md` for future Marcos Dev Instagram reviews.
Follow-up: If reviews become frequent and need scoring consistency, create a separate eval such as `instagram-content-quality.md`.
### 2026-09-05 - Instagram content production needs a local KORA loop

Date: 2026-09-05
Scope: social-media-production
Source/Evidence: Marcos stated that Marcos Dev should not depend on MarcosOS long-term and approved creating local KORA support for posts, videos, storytelling, and methodology.
Learning: Social media support for Marcos Dev now needs a full local loop: collect recent post evidence, analyze content patterns, generate new ideas/scripts from strategy, and evaluate quality before review.
Decision or Recommendation: Use `collect-instagram-post-intelligence`, `instagram-content-analyst`, `plan-instagram-content-from-strategy`, and `instagram-content-quality` together for future Instagram planning.
Follow-up: Migrate a KORA-owned/private executable connector and secret location before removing MarcosOS.
### 2026-09-05 - Instagram connector migrated to KORA runtime

Date: 2026-09-05
Scope: instagram-read-operations
Source/Evidence: Created `C:\KORA\runtime\tools\instagram-connector\`, copied read-only scripts without secrets, created `C:\KORA\.env` with Marcos Dev Instagram variables only, added `C:\KORA\.gitignore`, and verified `health_check.py --profile marcos-dev` plus `read_media.py --profile marcos-dev --limit 3` through the KORA runtime.
Learning: Marcos Dev Instagram profile/media reads no longer require the MarcosOS executable on this machine. The private KORA runtime and private KORA `.env` are now the operational path.
Decision or Recommendation: Use `C:\KORA\runtime\tools\instagram-connector\` for future Marcos Dev Instagram reads. Keep `.kora/tools/instagram-connector.md` as the public-safe project reference.
Follow-up: Add broader Insights only after official Meta documentation review and explicit scope approval.
## 2026-09-05 - Google Ads Read Capability

Marcos Dev now has a KORA-owned Google Ads read/proposal capability migrated from legacy MarcosOS. The safe default is read-only campaign intelligence through `googleAds:searchStream`; write/mutation execution is intentionally separate and not active.

Operational rule: when analyzing Google Ads, use `.kora/skills/collect-google-ads-campaign-intelligence.md`, `.kora/agents/google-ads-performance-analyst.md`, and `.kora/evals/google-ads-proposal-quality.md`. Keep private reports and credentials in `C:\KORA`, not in this public repository.

### 2026-09-06 - Renan panel file-level Hostinger deploy

Date: 2026-09-06
Scope: renan-reformas-production-deploy
Source/Evidence: Marcos approved publishing the client private work-feed design update. Hostinger MCP resolved `renanreformas.com.br` as an active `other` website and TUS file-level upload returned `201`/`204` for `painel/obra-cliente.php` and `painel/styles/main.css`. Public checks confirmed the CSS contained the new client summary/gallery rules and `obra-cliente.php` returned the expected private-page 404 for an invalid token without fatal errors.
Learning: Small PHP/CSS updates in `projetos/renan-reformas/painel/` can be safely published through Hostinger file-level upload, avoiding full archive deploy for the entire production site.
Decision or Recommendation: Keep file-level upload as the default for focused Renan panel changes. Use full archive deploy only when Marcos explicitly approves a whole-site replacement.
Follow-up: For future client-page visual deploys, verify public CSS cache with a cache-busting query and test `obra-cliente.php` with an invalid token to catch PHP fatal errors without exposing private client links.

### 2026-09-06 - Renan full panel UX deploy by file upload

Date: 2026-09-06
Scope: renan-reformas-panel-ux-production-deploy
Source/Evidence: Marcos approved publishing all local Renan panel UX changes. PHP lint passed for `painel.php`, `orcamentos.php`, `orcamento.php`, `contratos.php`, `editar-contrato.php`, `editar-modelo-contrato.php`, `obras.php`, `atualizar-obra.php`, and `obra-cliente.php`; `node --check` passed for the five panel scripts. Hostinger TUS file-level upload returned `201`/`204` for all approved files, cache clearing was accepted, production CSS and scripts returned `200`, key protected panel URLs returned `200`, and invalid private obra token returned expected `404` without fatal error.
Learning: The Renan panel can receive broader UX updates safely through explicit file-level upload of the touched PHP/CSS/JS files, while avoiding database, DNS, credential, and full-site overwrite risk.
Decision or Recommendation: For future broad panel publishes, prepare a named file manifest, lint PHP/JS first, upload only the approved manifest, clear cache, then verify public CSS/script assets plus protected page status.
Follow-up: When production login is available in-browser, add a post-deploy visual smoke test for logged-in dashboard, orçamento, contratos, obras, atualizar obra, and cliente private page.

### 2026-09-19 - Despachante Vicente weekly Google Ads reporting standard

Date: 2026-09-19
Scope: despachante-vicente-google-ads-reporting
Source/Evidence: Marcos requested a durable KORA workflow after the Gamma workspace confirmed reports for 24/08–30/08 and 31/08–06/09. The latter is the preferred current visual and structural reference.
Learning: A generic Gamma generation cannot reliably preserve the established client-report layout. The report workflow needs a private reference registry, read-only metric collection, and a specific visual/factual evaluation.
Decision or Recommendation: Use `create-despachante-vicente-google-ads-weekly-report`, the Google Ads read-only capability, and `despachante-vicente-google-ads-report-quality` for future report requests. Keep Gamma URLs and report history private in `C:\KORA\private\marcos-dev\despachante-vicente-google-ads-reporting.md`.
Follow-up: The preferred reference was converted into the private Gamma template `Modelo — Relatório Semanal de Tráfego Pago | Despachante Vicente` on 2026-09-19. Use its private registry ID for future reports and keep the template unchanged.

### 2026-09-19 - Despachante Vicente: publicação do relatório depende de recuperar o mapeamento Hostinger

Date: 2026-09-19
Scope: despachante-vicente-report-panel-production
Source/Evidence: Não há mapeamento deste cliente em `.kora/context/hostinger-production-map.md` ou no registro privado de relatórios. As consultas somente-leitura `hosting_listWebsitesV1` para o domínio e para a conta não responderam; a checagem do runtime local também não encontrou o pacote/binário nem uma configuração de token operacional.
Learning: O relatório do Gamma está pronto, mas a URL pública do site não identifica por si só a rota autenticada do painel nem o mecanismo correto para criar uma entrada de relatório.
Decision or Recommendation: Não fazer upload ou alteração em produção até a conexão Hostinger recuperar a leitura e confirmar domínio, usuário, rota do painel e o arquivo/mecanismo exato. Depois de uma publicação verificada, registrar este mapeamento sem credenciais em `hostinger-production-map.md` e manter detalhes sensíveis apenas na KORA privada.
Follow-up: Concluído no mesmo dia: a autenticação OAuth foi restaurada, o painel foi mapeado e a publicação focada do PDF foi verificada.

### 2026-09-19 - Despachante Vicente: primeiro relatório semanal publicado no painel

Date: 2026-09-19
Scope: despachante-vicente-report-panel-production
Source/Evidence: Após autorização explícita de Marcos, o MCP Hostinger confirmou `despachantevicente.com.br` como site ativo e o painel em `/painel/`. O upload direcionado do PDF de 07/09/2026–13/09/2026 concluiu com sucesso. A pasta protegida passou a conter três relatórios e a tela de login do painel retornou `200` normalmente.
Learning: O painel lê automaticamente PDFs na pasta protegida de relatórios; a rotina não requer alterações em PHP, banco de dados ou páginas públicas.
Decision or Recommendation: Para cada relatório semanal aprovado no Gamma, exportar e validar o PDF, enviar somente o arquivo com o padrão de nome registrado no mapa de produção e confirmar a presença do arquivo mais a disponibilidade do login do painel.
Follow-up: Na próxima publicação, reutilizar este mapeamento e registrar apenas a nova data/período no registro privado de relatórios.

### 2026-09-30 - Despachante Vicente: campanha de Google Ads retomada dentro do saldo autorizado

Date: 2026-09-30
Scope: despachante-vicente-google-ads-campaign-operations
Source/Evidence: Marcos autorizou explicitamente a retomada usando os fundos já disponíveis. A leitura inicial mostrou a campanha habilitada, mas encerrada por data, com o teto cumulativo anterior praticamente consumido. A estrutura B2B existente (palavras-chave, negativas, anúncios, URL de destino e limite de CPC) foi revisada sem necessidade de alteração. A mutação atômica foi validada e aplicada; a leitura posterior retornou campanha e orçamento habilitados, com status primário `ELIGIBLE`.
Learning: Em campanhas com orçamento total, `ENABLED` não basta para retomar a entrega quando o status primário é `ENDED`: é necessário estender a data de término e elevar o teto cumulativo dentro da autorização. Validar a mutação antes da aplicação evita reativação parcial.
Decision or Recommendation: Manter a segmentação B2B atual e o orçamento diário já configurado. Para esta retomada, registrar somente a operação e o estado verificável; valores, identificadores e credenciais permanecem fora do repositório público.
Follow-up: Acompanhar a primeira janela de sete dias de entrega, termos de pesquisa e contatos atribuíveis ao WhatsApp antes de propor uma nova mudança estrutural ou de orçamento.

### 2026-10-13 - Despachante Vicente: relatório da primeira semana após a retomada publicado

Date: 2026-10-13
Scope: despachante-vicente-report-panel-production
Source/Evidence: Com autorização explícita de Marcos, o relatório de 30/09/2026 a 06/10/2026 foi criado a partir do template oficial do Gamma, reconciliado com a leitura somente leitura do Google Ads, revisado visualmente em todas as páginas e enviado como PDF isolado ao armazenamento protegido do painel. A listagem remota confirmou o arquivo e o login do painel respondeu normalmente.
Learning: Quando a semana de comparação não tem atividade por campanha encerrada, o relatório deve apresentar a semana atual como retomada, sem calcular variações percentuais contra zero. A publicação rotineira continua exigindo somente o PDF validado, sem mudanças no código ou banco do painel.
Decision or Recommendation: Manter o template Gamma registrado, o padrão de nome do PDF e a verificação conjunta de arquivo remoto e disponibilidade do login após cada publicação.
Follow-up: Executar separadamente a melhoria aprovada da campanha, começando pela revisão de termos e negativas, e medir mais sete dias antes de nova decisão de orçamento ou lances.

### 2026-10-13 - Despachante Vicente: intenção de busca limpa e mensuração corrigida

Date: 2026-10-13
Scope: despachante-vicente-google-ads-optimization
Source/Evidence: Após autorização explícita de Marcos, uma mutação previamente validada adicionou negativas em nível de campanha para buscas navegacionais, plataformas concorrentes, login, rastreamento e intenção informacional observadas no relatório de termos. A conferência da mensuração também encontrou divergência entre o rótulo da conversão habilitada no Google Ads e o código publicado; somente esse rótulo foi corrigido por upload direcionado da landing page e verificado em produção.
Learning: A campanha pode estar tecnicamente ativa e ainda desperdiçar verba por expansão de correspondência de frase. Além disso, uma ação de conversão habilitada não prova que o rótulo publicado corresponde à ação; comparar o snippet retornado pela API com o HTML de produção é uma verificação necessária.
Decision or Recommendation: Preservar orçamento, estratégia, limite de CPC, anúncios e palavras positivas durante a próxima janela. Avaliar termos, conversões técnicas e qualidade dos contatos após sete dias completos antes de outra mudança.
Follow-up: Reexecutar a leitura somente após sete dias completos; não usar um clique real de teste no WhatsApp durante a janela para evitar contaminar os dados.
