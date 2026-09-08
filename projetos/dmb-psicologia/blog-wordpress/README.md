# Blog da DMB — ambiente local

Esta pasta contém o tema personalizado do blog da DMB Psicologia e Desenvolvimento Humano. Ele foi preparado para uma instalação independente do WordPress em `/blog`, mantendo o site institucional separado, leve e protegido.

## Objetivo

- A área pública do blog ficará em `dmbpsicologia.com.br/blog/`.
- Daniela poderá escrever, salvar rascunhos, editar e publicar artigos pelo painel do WordPress.
- O tema mantém a identidade visual da DMB: verde-esmeralda, off-white, dourado suave e tipografia editorial.
- O conteúdo do blog deve ser educativo e informativo, sem expor pacientes, prometer resultados ou substituir atendimento profissional.

## Estrutura

- `tema-dmb/`: tema a ser instalado no WordPress.
- `modelo-de-artigo.md`: guia simples para cada nova publicação.

## Como testar localmente

1. Criar uma instalação local do WordPress com LocalWP, XAMPP ou equivalente.
2. Copiar `tema-dmb` para `wp-content/themes/` dessa instalação.
3. No painel, ativar **DMB Blog** em **Aparência → Temas**.
4. Em **Configurações → Leitura**, definir a página inicial para mostrar as últimas publicações.
5. Criar uma categoria e um artigo de teste; depois adicionar uma imagem destacada.
6. Em **Aparência → Personalizar**, enviar o logo oficial da DMB quando o arquivo final estiver disponível.

## Antes de publicar na Hostinger

- Definir o domínio definitivo e instalar o WordPress dentro da pasta `/blog`.
- Criar o usuário da Daniela com perfil de **Editora** (ela publica artigos, mas não altera plugins, tema ou configurações).
- Ativar SSL, backup, atualizações e proteção contra tentativas de login.
- Configurar links permanentes como `/%postname%/`.
- Criar as páginas de Política de Privacidade e Contato do blog.
- Revisar o primeiro conteúdo e conectar os links entre o blog e o site institucional.

## Estado

Base do tema criada localmente. Não publicada na Hostinger.
