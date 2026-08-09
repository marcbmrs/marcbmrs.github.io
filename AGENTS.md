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
- Antes de mover uma página já publicada, confirmar o impacto na URL. Quando a troca for autorizada, atualizar referências internas, README e qualquer link conhecido que aponte para o endereço anterior.
- A URL pública padrão de um projeto é `https://marcbmrs.github.io/projetos/<nome-do-projeto>/`, enquanto não houver domínio personalizado ou outra configuração de publicação registrada.

## Conexão com o MarcosOS

Antes de tomar decisões de negócio, conteúdo, posicionamento, identidade visual, oferta ou processo, consultar o MarcosOS em `C:\MarcosOS`. O MarcosOS é a fonte de contexto e decisões registradas; este repositório é a fonte de implementação técnica e dos ativos publicados.

Referências prioritárias:

- `C:\MarcosOS\30 - Negócios\Marcos Dev\Negócio e Estratégia.md`
- `C:\MarcosOS\30 - Negócios\Marcos Dev\Identidade Visual\README.md`
- `C:\MarcosOS\30 - Negócios\Marcos Dev\Onboarding de Domínio e Hospedagem Hostinger.md`
- `C:\MarcosOS\40 - GPTs\Marcos Dev — Diretor de Conteúdo para Instagram\` quando a tarefa envolver conteúdo de Instagram.

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

Não duplicar o cérebro inteiro neste repositório. Quando uma decisão nova for durável e relevante para a Marcos Dev, registrar primeiro ou também no local adequado do MarcosOS.

## Regras de implementação

- Começar pelo problema e objetivo da página; não adicionar tecnologia, animação ou seção apenas por aparência.
- Preservar clareza, rapidez, acessibilidade, responsividade e caminho de contato como prioridades de produto.
- Usar a identidade vigente: azul-marinho `#0B1F3A`, azul `#2563EB`, verde-água `#14B8A6`, fundo claro `#F8FAFC`; Manrope para títulos e Inter para apoio, quando disponíveis.
- A linguagem deve ser natural, direta e didática. Não inventar cases, métricas, clientes, depoimentos, promessas de ranking, vendas ou resultados.
- Informações de plataformas, políticas, APIs, preços, anúncios ou métricas são atualizáveis: conferir fonte oficial antes de afirmar detalhes ou implementar integração dependente delas.
- Não armazenar credenciais, tokens, chaves privadas, dados pessoais desnecessários ou segredos no repositório.
- Não publicar, enviar para GitHub, alterar DNS, hospedagem ou serviços externos sem pedido explícito do titular.

## Qualidade antes de entrega

Em alterações de páginas da Marcos Dev, verificar conforme aplicável: links e formulários, versão móvel, contraste, navegação por teclado, carregamento de imagens, desempenho básico, metadados e consistência visual. Explicar qualquer limitação relevante em vez de ocultá-la.

## Escopo e versionamento

Antes de editar, identificar exatamente quais arquivos pertencem à tarefa. Em uma árvore com alterações de terceiros ou de outros sites, nunca incluir arquivos fora do escopo em commit ou publicação.
