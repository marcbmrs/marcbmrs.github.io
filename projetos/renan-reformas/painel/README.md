# Painel de orçamentos — Renan Reformas

Módulo PHP privado para preparar propostas comerciais e gerar PDFs personalizados.

## Estado

Fundação visual e de segurança inicial criada. Ainda não há autenticação, banco de dados ou geração de PDF; portanto, o painel não deve receber dados reais nem ser publicado como ferramenta operacional.

## Estrutura inicial

- `index.php`: página de entrada do painel.
- `app/bootstrap.php`: configura sessões, cabeçalhos de segurança e a função `e()` para escapar texto em HTML.
- `app/auth.php`: funções para registrar, verificar e encerrar a sessão de um usuário autenticado.
- `app/database.php`: cria a conexão PDO compartilhada com o MySQL.
- `app/config.local.example.php`: modelo de configuração local; a cópia real é ignorada pelo Git.
- `login.php` e `logout.php`: entrada e saída segura do painel.
- `database/create-admin.php`: comando local para criar o primeiro usuário com senha protegida.
- `database/schema.sql`: estrutura inicial do banco MySQL, sem dados ou credenciais.
- `styles/main.css`: estilos exclusivos do painel.

## Próximas etapas

1. Login e controle de sessão.
2. Banco MySQL para usuários, clientes, propostas e itens.
3. Editor de proposta, histórico e geração de PDF.
4. Revisão de segurança, backup e preparação para Hostinger.

## Publicação futura

Na Hostinger, o módulo ficará no mesmo domínio do site, em `/painel/`. Dados de conexão e outros segredos serão definidos fora da pasta pública de arquivos e não entram neste repositório.
