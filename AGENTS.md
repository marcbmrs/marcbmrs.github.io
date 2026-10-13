# Marcos Dev — Repositório Operacional

## Papel deste repositório

`marcbmrs.github.io` é o repositório operacional da Marcos Dev. Ele hospeda o site institucional, páginas de campanha, protótipos, ativos e futuras implementações técnicas da empresa.

O `index.html` na raiz é o **site institucional oficial da Marcos Dev** e o ponto padrão para suas modificações. Antes de alterar páginas internas, estilos globais, imagens ou scripts compartilhados, verificar se a mudança afeta esse site e manter sua consistência.

Arquivos e páginas legados ou de demonstração podem coexistir aqui. Não os alterar, mover ou remover sem pedido explícito do titular.

## Organização de projetos

- O site oficial da Marcos Dev permanece na raiz, em `index.html`.
- Arquivos do site oficial seguem esta convenção:

  ```text
  index.html
  assets/
  ├── images/       # fotos, ilustrações e imagens de interface
  ├── icons/        # ícones, favicon e pequenos símbolos
  └── media/        # vídeos, áudios e arquivos multimídia
  styles/
  └── main.css
  scripts/
  └── main.js
  ```

  Criar `icons/`, `media/` ou `scripts/` somente quando houver arquivo correspondente. O `index.html` deve usar caminhos relativos para essas pastas; não deixar imagens, CSS ou JavaScript novos soltos na raiz.

- Cada site de cliente, demonstração reaproveitável ou projeto independente deve ficar em `projetos/<nome-do-projeto>/` e repetir a mesma estrutura interna:

  ```text
  projetos/<nome-do-projeto>/
  ├── index.html
  ├── README.md
  ├── assets/
  │   ├── images/
  │   ├── icons/
  │   └── media/
  ├── styles/
  │   └── main.css
  └── scripts/
      └── main.js
  ```

  Pastas e arquivos opcionais só devem existir quando necessários. Não criar estrutura vazia por padrão.

- Usar nomes estáveis, em minúsculas e com hífens. Exemplo: `ct-imperio` e `despachante-vicente`.
- Usar nomes descritivos em minúsculas e com hífens também para ativos: `hero-academia.webp`, `logo-cliente.svg`, `depoimento-01.webp`. Não usar espaços, acentos, versões vagas como `final`, `novo` ou `teste`, nem nomes genéricos como `imagem1` quando houver contexto melhor.
- Para páginas públicas adicionais do site oficial, usar `<rota>/index.html`, como `servicos/index.html` ou `contato/index.html`, para preservar URLs legíveis. Não criar novos arquivos HTML soltos na raiz, exceto `index.html` e arquivos de infraestrutura estritamente necessários.
- Todo projeto deve conter um `README.md` com finalidade, estado, arquivos principais e URL pública esperada no GitHub Pages.
- Ao criar um novo projeto em `projetos/<nome-do-projeto>/`, criar também a pasta local correspondente `C:\MarcosDev-Originais\<nome-do-projeto>\images\`. Ela é o local padrão para PNG, JPG, render e demais imagens-fonte, fica fora do repositório público e não deve ser publicada no GitHub. O site recebe apenas as versões derivadas e otimizadas em `projetos/<nome-do-projeto>/assets/images/`.
- Antes de mover uma página já publicada, confirmar o impacto na URL. Quando a troca for autorizada, atualizar referências internas, README e qualquer link conhecido que aponte para o endereço anterior.
- A URL pública padrão de um projeto é `https://marcbmrs.github.io/projetos/<nome-do-projeto>/`, enquanto não houver domínio personalizado ou outra configuração de publicação registrada.

## Conexão com o MarcosOS

Antes de tomar decisões relevantes de produto, marketing, UX, copy, identidade visual, conteúdo, estratégia, presença digital, oferta, comunicação com clientes ou melhoria do projeto, consultar primeiro a KORA local em `.kora/`. Enquanto existir e estiver acessível, o MarcosOS em `C:\MarcosOS` pode ser usado como fonte legada/transitória para migração ou conferência, mas não deve ser tratado como dependência operacional permanente da Marcos Dev.

Se a sessão não conseguir ler `C:\MarcosOS`, prosseguir com a KORA local e informar apenas quando a limitação afetar a tarefa. Não copiar conhecimento global do MarcosOS para este repositório sem necessidade; migrar somente contexto, procedimentos e decisões específicos da Marcos Dev, com adaptação ao caso.

Fontes centrais do MarcosOS:

- `C:\MarcosOS\README.md`
- `C:\MarcosOS\AGENTS.md`
- `C:\MarcosOS\CORE.md`
- `C:\MarcosOS\GOVERNANCE.md`
- `C:\MarcosOS\KNOWLEDGE_MODEL.md`
- `C:\MarcosOS\PROCESS_CATALOG.md`
- `C:\MarcosOS\Uso do MarcosOS em Outros Repositorios.md`

Referências prioritárias:

- `C:\MarcosOS\30 - Negócios\Marcos Dev\Negócio e Estratégia.md`
- `C:\MarcosOS\30 - Negócios\Marcos Dev\Identidade Visual\README.md`
- `C:\MarcosOS\30 - Negócios\Marcos Dev\Onboarding de Domínio e Hospedagem Hostinger.md`
- `C:\MarcosOS\40 - GPTs\Marcos Dev — Diretor de Conteúdo para Instagram\` quando a tarefa envolver conteúdo de Instagram.

Quando o titular acionar `Post Mega Power` ou `Story Mega Power`, consultar primeiro `Configuração.md` e `Conhecimento\09 - Produção via ChatGPT Images e Referências.md` nesse pacote do MarcosOS. Esses comandos significam produção completa e revisável de conteúdo: estratégia e direção aprovadas → artes geradas e revisadas → pacote local com legenda e briefing → uma única aprovação final do titular. As artes devem ficar em `C:\MarcosDev-Originais\conteudos\`, fora deste repositório público. Não publicar no Instagram, acessar contas ou tratar a revisão interna como aprovação final.

Para decisões técnicas, consultar o módulo pertinente em vez de trabalhar apenas por preferência pessoal:

| Necessidade | Fonte no MarcosOS |
| --- | --- |
| UX, arquitetura, layout e design de interface | `20 - Conhecimento\M01 - Desenvolvimento Web e Experiência Digital\M02 - Estratégia e Design de Experiência\` |
| Front-end, responsividade, acessibilidade, animação e desempenho | `20 - Conhecimento\M01 - Desenvolvimento Web e Experiência Digital\M02 - Engenharia Web\` |
| SEO técnico, presença local e mensuração de sites | `20 - Conhecimento\M01 - Desenvolvimento Web e Experiência Digital\M02 - Descoberta e Avaliação\` |
| Identidade, logo, direção de arte e imagem | `20 - Conhecimento\M01 - Design e Direção de Arte\` |
| Posicionamento, aquisição, conversão, conteúdo e métricas | `20 - Conhecimento\M01 - Marketing\` |
| Oferta, escopo, venda e operação sustentável | `20 - Conhecimento\M01 - Gestão de Serviços Profissionais\` |

Ler apenas os documentos diretamente relevantes para a tarefa. Para uma landing page, por exemplo, combinar estratégia de site, conversão, performance e a oferta da Marcos Dev; para uma alteração visual, consultar identidade e UX antes de mudar componentes.

Ao aplicar o MarcosOS neste repositório, distinguir claramente:

- regra local deste projeto;
- conhecimento global reutilizável;
- contexto de negócio da Marcos Dev ou de cliente;
- hipótese de trabalho;
- recomendação técnica ou estratégica;
- decisão aprovada pelo titular.

Não duplicar o cérebro inteiro neste repositório. Registrar aqui apenas decisões, adaptações, evidências e documentação específicas deste projeto. Como MarcosOS será descontinuado, novas decisões duráveis da Marcos Dev devem ser registradas prioritariamente na KORA local.

Não alterar o MarcosOS a partir deste repositório sem pedido explícito do titular.

## Regras de implementação

- Começar pelo problema e objetivo da página; não adicionar tecnologia, animação ou seção apenas por aparência.
- Preservar clareza, rapidez, acessibilidade, responsividade e caminho de contato como prioridades de produto.
- Antes de incluir foto, fundo, render, print ou outra imagem rasterizada em `assets/images`, avaliar dimensões e peso. Para novos assets de site, usar o otimizador local em `C:\MarcosOS\tools\image-optimizer`, tomando `C:\MarcosDev-Originais\<nome-do-projeto>\images\` como origem e gerando versões AVIF e WebP responsivas para o site. PNG permanece reservado a screenshots que precisem preservar texto fino ou transparência sem alternativa adequada; SVG é preferível para logos e ícones vetoriais. Nunca substituir ou apagar o original automaticamente.
- Usar `<picture>` com AVIF, WebP e fallback quando a compatibilidade justificar; declarar `width` e `height`; aplicar `loading="lazy"` apenas a imagens fora da primeira tela. A imagem principal visível ao abrir a página não deve ser carregada tardiamente. Conferir a qualidade visual em celular e desktop antes de publicar.
- Usar a identidade vigente: azul-marinho `#0B1F3A`, azul `#2563EB`, verde-água `#14B8A6`, fundo claro `#F8FAFC`; Manrope para títulos e Inter para apoio, quando disponíveis.
- A linguagem deve ser natural, direta e didática. Não inventar cases, métricas, clientes, depoimentos, promessas de ranking, vendas ou resultados.
- Informações de plataformas, políticas, APIs, preços, anúncios ou métricas são atualizáveis: conferir fonte oficial antes de afirmar detalhes ou implementar integração dependente delas.
- Não armazenar credenciais, tokens, chaves privadas, dados pessoais desnecessários ou segredos no repositório.
- Em links `https://wa.me/`, usar sempre o número no formato internacional E.164, somente com dígitos: código do país + DDD + número (por exemplo, `5551980109047`). Nunca omitir o código do país, nem incluir `+`, espaços, parênteses ou hífens na URL.
- Não publicar, enviar para GitHub, alterar DNS, hospedagem ou serviços externos sem pedido explícito do titular.

## Qualidade antes de entrega

Em alterações de páginas da Marcos Dev, verificar conforme aplicável: links e formulários, versão móvel, contraste, navegação por teclado, carregamento de imagens, desempenho básico, metadados e consistência visual. Explicar qualquer limitação relevante em vez de ocultá-la.

## Escopo e versionamento

Antes de editar, identificar exatamente quais arquivos pertencem à tarefa. Em uma árvore com alterações de terceiros ou de outros sites, nunca incluir arquivos fora do escopo em commit ou publicação.
## KORA

Este repositório está conectado à KORA em `C:\KORA` e usa uma camada local em `.kora/`.

Nível ativo: KORA nível 7.

### KORA instalada neste repositório

Quando este repositório estiver aberto, trate a Marcos Dev como um projeto operacional conectado à KORA. O usuário não precisa mencionar KORA em todo pedido.

Use este fluxo padrão:

```text
1. Leia `.kora/binding.md`.
2. Leia este `AGENTS.md` para regras locais.
3. Selecione apenas o contexto local necessário em `.kora/context/`.
4. Use capacidades locais quando a tarefa depender da Marcos Dev, clientes, site, conteúdo, publicação, anúncios ou deploy.
5. Use a KORA Core em `C:\KORA` quando precisar de métodos reutilizáveis.
6. Mantenha detalhes do projeto dentro deste repositório.
7. Promova padrões reutilizáveis para a KORA Core somente depois de revisão.
```

Capacidades Core úteis para uso instalado:

- `C:\KORA\skills\use-installed-kora.md`
- `C:\KORA\skills\check-installed-kora.md`
- `C:\KORA\skills\route-user-request.md`
- `C:\KORA\skills\check-approval-needed.md`
- `C:\KORA\skills\detect-capability-gap.md`
- `C:\KORA\projects\PROJECT-FLOW.md`

Ao tomar decisões relevantes sobre estrutura do repositório, site oficial, páginas de cliente, ofertas, conteúdo, UX, publicação, qualidade web ou recorrência de trabalho, consultar a KORA local antes de concluir a resposta:

- `.kora/binding.md`
- `.kora/context/overview.md`
- `.kora/context/stack.md`
- `.kora/context/social-content-strategy.md`
- `.kora/context/brand-and-content-identity.md`
- `.kora/agents/`
- `.kora/skills/`
- `.kora/evals/`
- `.kora/memory/`
- `.kora/experiments/`
- `.kora/decisions/`

Regra prática da KORA: agent = papel com julgamento, skill = procedimento repetível, tool = execução, eval = critério de qualidade, automation = recorrência sem supervisão.

Capacidades locais ativas:

- agentes: `brand-site-guardian`, `offer-content-strategist`, `web-quality-reviewer`, `instagram-content-analyst`, `google-ads-performance-analyst`;
- skills: `review-landing-page-change`, `review-offer-and-positioning`, `review-content-publication`, `review-client-project-page`, `review-publish-readiness`, `collect-instagram-post-intelligence`, `migrate-marcos-dev-capability-to-kora`, `plan-instagram-content-from-strategy`, `collect-google-ads-campaign-intelligence`, `create-despachante-vicente-google-ads-weekly-report`;
- evals: `brand-positioning-fit`, `landing-page-quality`, `contact-conversion`, `site-accessibility-performance`, `repository-boundary-safety`, `instagram-content-quality`, `despachante-vicente-google-ads-report-quality`;
- tools: `instagram-connector` como referência local para o conector somente leitura; a implementação atual usa o runtime privado em `C:\KORA\runtime\tools\instagram-connector\` e segredos em `C:\KORA\.env`.

Usar essas capacidades de forma proporcional à tarefa. Para uma edição simples e local, basta aplicar a skill/eval relevante mentalmente e citar o que foi verificado. Para mudanças maiores, explicitar a capacidade usada, arquivos consultados, riscos e validações.

Persistência operacional de nível 7 está ativa: quando uma decisão, resultado de eval, experimento, lacuna de capacidade ou aprendizado for durável e útil para trabalhos futuros da Marcos Dev, registrar de forma objetiva na `.kora/` local. Não persistir rascunhos, transcrições cruas, dados pessoais desnecessários, segredos, hipóteses fracas ou conhecimento genérico que pertença ao MarcosOS/KORA Core.

Automações continuam inativas por padrão. Não implementar, agendar, publicar, acionar integrações externas ou criar recorrência sem pedido explícito do titular para a automação específica.
