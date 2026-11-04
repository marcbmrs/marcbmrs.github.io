# Megamix — Doces & Festas

## Finalidade e estado

Protótipo de site para apresentar à Megamix Doces & Festas, no Guajuviras, em Canoas (RS). O titular autorizou sua publicação como protótipo em 04/11/2026. A loja ainda não aprovou conteúdo, identidade ou canal de consulta; a página permanece com `noindex, nofollow`.

A página ajuda o visitante a reconhecer as categorias anunciadas pela loja, consultar disponibilidade e valores, conferir o endereço e planejar uma visita. A lista enviada pelo WhatsApp é **uma consulta**, não um pedido fechado ou uma compra.

## Direção atual — 04/11/2026

A referência [Sul Doces](https://www.suldoces.com/) orientou apenas a organização por categorias de uma loja de doces. A versão anterior imitava uma loja virtual com carrossel, vitrines genéricas e ícones repetidos. Esta versão usa uma hierarquia mais curta: apresentação, categorias, explicação da consulta e informações da loja.

- A marca no cabeçalho é uma reconstrução tipográfica provisória inspirada nas cores da fachada, não o arquivo oficial.
- A composição de doces e artigos de festa é **ilustrativa e gerada por IA**. Ela não representa produtos, estoque ou interior da Megamix. Por escolha do titular, a página não exibe um selo sobre a imagem; a origem fica documentada aqui para orientar sua substituição futura. O original está fora do repositório em `C:\MarcosDev-Originais\mega-mix-doces-festas\images\editorial-doces-festas\`; o site usa derivados responsivos em AVIF e WebP.
- As categorias se baseiam na placa da fachada e em fotos públicas descritas na pesquisa inicial. A lista de categorias fica em `CATEGORIES` em `scripts/main.js`; itens específicos, marcas, preços, formatos, ofertas e condições de atacado não são afirmados.
- O site mantém busca de categorias, lista local e geração da mensagem de consulta. A lista é salva somente no navegador do visitante.

## Dados usados no protótipo

Informações levantadas em perfil público e placa da fachada em 03/11/2026, ainda sujeitas à confirmação da loja:

- Endereço: Av. Dezessete de Abril, 79 — Guajuviras, Canoas — RS, 92415-000.
- Telefone: (51) 99548-1422. O uso desse número no WhatsApp ainda precisa ser confirmado.
- Horário informado: segunda a sexta, 9h–19h; sábado, 9h–12h e 14h–19h; domingo fechado.
- A placa usa “Atacarejo” e anuncia pirulitos, chicletes, balas, salgadinhos, material escolar, produtos para festas, embalagens descartáveis e alimentos em geral. Chocolates aparecem em fotos de gôndolas.

## Confirmar antes de apresentar como site oficial da loja

1. Nome, logo oficial, fotos autorizadas da fachada/interior e identidade visual.
2. Número de WhatsApp, se a loja aceita consultas por esse canal e o processo real de atendimento.
3. Endereço, horário, feriados e situação de uma possível segunda unidade.
4. Categorias e itens efetivamente vendidos; condições de atacarejo.
5. Aprovação da loja para uso como site oficial e definição do domínio final. Ajustar canonical, metadados e indexação quando isso ocorrer.

Não há preços, marcas, promoções, promessas de entrega ou formas de pagamento no protótipo. A foto de fachada obtida no Street View continua apenas como referência privada e não deve ser publicada.

## Arquivos

- `index.html`: página única e metadados.
- `styles/main.css`: apresentação responsiva.
- `scripts/main.js`: categorias, busca, lista, consulta e horário local.
- `assets/images/composicao-editorial-doces-festas-*.avif|webp`: imagem editorial derivada e otimizada.
- `assets/images/megamix-compartilhamento.jpg`: imagem de compartilhamento provisória da versão anterior.
- `assets/icons/favicon.svg`: favicon provisório.
- `tests/main.test.js`: testes da busca, horário, lista e link de consulta. Executar com `node --test tests/main.test.js`.

## URL do protótipo publicado

`https://marcosdevweb.com.br/projetos/mega-mix-doces-festas/`

O domínio personalizado do GitHub Pages redireciona a URL `marcbmrs.github.io` para o endereço acima. A publicação do protótipo não equivale à aprovação da loja para uso oficial.
