# Megamix – Doces & Festas

## Finalidade

Proposta de site para o Megamix Doces & Festas, atacarejo de doces, artigos de festa e material escolar no Guajuviras, em Canoas (RS). A página é pensada primeiro para celular. Ela mostra se a loja está aberta, o que se encontra lá e como chegar, e leva o visitante ao WhatsApp com uma lista de compras já montada.

## Direção (versão 3, 03/11/2026)

Conceito "Da lista à prateleira": o Megamix é apresentado como a loja de bairro de gôndolas cheias onde a pessoa organiza o que procura. A direção veio de uma avaliação crítica do Codex sobre a versão 1, que parecia um template de festa infantil, e foi aplicada com a skill `design-mobile-web-experience` da KORA. A versão 3 alinhou a identidade com a fachada real da loja (imagem do Street View, guardada só como referência local).

- Marca da fachada: o nome escrito "MEGAMIX" em letras limão `#E4E62B` com contorno escuro, "Doces" em ferrugem `#C4532F`, "&" laranja e "Festas" em verde `#1F9D4A`, sobre placas laranja `#EE7A3A`. A versão no site é reconstruída em texto e não é o arquivo oficial do logo.
- Apoio visual: papel creme `#FFF9EB`, tinta `#251C2A`, laranja escuro `#B84A18` nos botões (contraste com texto branco), amarelo de etiqueta `#FFD43B`, verde WhatsApp `#087A43` reservado para contato. Títulos em Barlow Condensed e texto em Inter.
- Topo: ilustração em SVG da fachada (placa, porta dos doces e vitrine de festas), para a pessoa reconhecer a loja na rua. Não usa a foto do Google.
- Fluxo: motivo da compra (festa, produção, revenda, um produto) → prateleiras marcáveis → detalhes → mensagem pronta no WhatsApp. A barra fixa do celular envia a mesma lista.
- Removidos: a seção "Como funciona" (processo não confirmado), o mapa incorporado e a nota do Google em destaque.

## Estado

Protótipo para apresentar a um cliente em potencial. A loja ainda não aprovou o site e ele permanece com `noindex, nofollow`.

Dados do perfil público no Google e da placa da fachada, 03/11/2026:

- Endereço: Av. Dezessete de Abril, 79 – Guajuviras, Canoas – RS, 92415-000.
- Telefone: (51) 99548-1422, também pintado na placa. Usado como WhatsApp (falta confirmar que o número tem WhatsApp).
- Horário: segunda a sexta, 9h–19h; sábado, 9h–12h e 14h–19h; domingo fechado.
- A placa se apresenta como "Atacarejo" e lista: pirulitos, chicletes, balas, salgadinhos, material escolar, produtos para festas, embalagens descartáveis e alimentos em geral. Chocolates aparecem nas fotos das gôndolas.

Falta confirmar com a loja antes de publicar:

- As condições de atacarejo. O site usa a palavra, mas não fala em preço, pedido mínimo ou desconto.
- O processo de pedido: se a loja aceita consulta e encomenda pelo WhatsApp, se faz entrega e quais formas de pagamento aceita.
- Fotos autorizadas da loja, arquivo oficial do logo e Instagram.
- A segunda unidade que aparece em fontes públicas (R. Florianópolis, Mathias Velho). Confirmar se ainda existe.

## Arquivos principais

- `index.html`: página única.
- `styles/main.css`: identidade baseada na fachada e layout mobile-first.
- `scripts/main.js`: selo de aberto/fechado no fuso de São Paulo, destaque do dia e montagem da mensagem do WhatsApp por motivo de compra.
- `assets/icons/favicon.svg`: ícone provisório com as cores da placa.
- `assets/images/megamix-compartilhamento.jpg`: imagem de 1200 × 630 que aparece ao compartilhar o link no WhatsApp e em outras redes. A fonte editável (`compartilhamento-megamix.html`) e o PNG renderizado ficam em `C:\MarcosDev-Originais\mega-mix-doces-festas\images\`.
- `tests/main.test.js`: testes da lógica de horário e da mensagem (`node --test tests/main.test.js`).

## URL pública esperada

`https://marcbmrs.github.io/projetos/mega-mix-doces-festas/`

Imagens-fonte e referências ficam em `C:\MarcosDev-Originais\mega-mix-doces-festas\images\`, fora do repositório. A foto da fachada (`referencia-fachada-street-view.webp`) é do Street View e não deve ser publicada.
