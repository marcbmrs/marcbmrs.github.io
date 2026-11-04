# Megamix – Doces & Festas

## Finalidade

Proposta de site para o Megamix Doces & Festas, atacarejo de doces, artigos de festa e material escolar no Guajuviras, em Canoas (RS). A página é pensada primeiro para celular. Ela mostra se a loja está aberta, o que se encontra lá e como chegar, e leva o visitante ao WhatsApp com uma lista de compras já montada.

## Direção (versão 4, 04/11/2026)

Formato de loja virtual, pedido pelo titular a partir da referência de um concorrente local de doces e festas em Canoas. Da referência veio só o padrão de navegação, que é comum em lojas virtuais; identidade, textos e imagens são do Megamix. Os elementos são estes:

- faixa de avisos no topo;
- cabeçalho laranja com menu, WhatsApp, logo, rota e cestinha;
- busca;
- banner deslizante;
- categorias em círculos;
- vitrines de cards com botão;
- WhatsApp flutuante.

No lugar de "Comprar", cada card tem **+ Lista**. A cestinha vira "Minha lista", que é enviada pelo WhatsApp e fica salva no navegador da pessoa. A busca filtra as vitrines. Quando não encontra nada, ela oferece perguntar à loja no WhatsApp se o produto existe. Não há preços, marcas nem promoções, porque nada disso foi confirmado. Os itens das vitrines são tipos de produto ligados às categorias da placa e precisam ser revisados com a loja. Os produtos ficam na lista `PRODUCTS` de `scripts/main.js`.

Histórico: as versões 1 a 3 (03/11/2026) passaram por avaliação do Codex e pela skill `design-mobile-web-experience` da KORA e alinharam a identidade com a fachada real. O titular achou o resultado estranho e pediu a linguagem de loja virtual.

- Marca da fachada: o nome escrito "MEGAMIX" em letras limão `#E4E62B` com contorno escuro, "Doces" em ferrugem `#C4532F`, "&" laranja e "Festas" em verde `#1F9D4A`, sobre placas laranja `#EE7A3A`. No site, o logo é reconstruído em texto dentro de um selo branco e não é o arquivo oficial.
- Apoio visual: cabeçalho laranja, faixa verde-escura `#157A36`, botões "+ Lista" verdes, rodapé ferrugem. Títulos em Kanit e texto em Inter. As ilustrações dos produtos são SVGs próprios, sem marcas.
- A imagem de compartilhamento (fachada ilustrada) continua sendo a da versão 3.

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
