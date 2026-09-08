# Renan Reformas

Site institucional da Renan Reformas, com apresentação de serviços, portfólio e pedido de orçamento via WhatsApp.

## Canal oficial

- Instagram: [@renan.reformas.rs](https://www.instagram.com/renan.reformas.rs/)

## Estado

Site público ativo em domínio próprio na Hostinger. O painel interno em PHP já possui fluxo de orçamentos, autenticação de equipe, obras, acompanhamento privado, contratos e geração de página A4 para salvar orçamento em PDF.

## Arquivos principais

- `index.html`: página principal.
- `styles/main.css`: identidade visual e responsividade.
- `scripts/main.js`: navegação e pedido de orçamento pelo WhatsApp.
- `painel/index.php`: interface mobile para montar um orçamento.
- `painel/app/catalogo-inicial.php`: catálogo inicial configurável de serviços de mão de obra e equipamentos de locação para o painel PHP.
- `painel/app/config.example.php`: modelo de configuração privada para banco e diretório de fotos fora de `public_html`.
- `painel/database/schema.sql`: estrutura MySQL de clientes, orçamentos, revisões e itens salvos.
- `painel/contexto-painel.md`: histórico operacional e decisões do painel.
- `conteudos/orientacoes-e-cuidados-na-construcao.md`: banco de pautas educativas para Instagram, pendentes de validação técnica antes da publicação.
- `politica-de-privacidade/index.html`: aviso de privacidade do formulário.
- `sitemap.xml`: URLs do projeto para descoberta pelos mecanismos de busca.
- `robots.txt`: regra pronta para reaproveitar caso o projeto use domínio próprio.

## URLs públicas

- Produção: https://renanreformas.com.br/
- Painel: https://renanreformas.com.br/painel/
- GitHub Pages/revisão: https://marcbmrs.github.io/projetos/renan-reformas/

## Observações de publicação

- O MCP da Hostinger confirmou `renanreformas.com.br` como site ativo em 06/09/2026.
- Deploy em produção deve ser supervisionado e preferencialmente por arquivo alterado.
- Não publicar `painel/app/config.local.php`, credenciais, fotos privadas de obra ou arquivos locais de origem.


