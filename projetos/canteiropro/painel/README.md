# CanteiroPro Painel

Base white label do painel operacional para prestadores de obra.

## Estado

Extração inicial a partir do painel Renan Reformas. Não usar como SaaS multiempresa ainda.

## Como configurar

1. Copiar `app/config.example.php` para `app/config.local.php`.
2. Criar um banco MySQL para a instalação.
3. Ajustar `database`, `username`, `password` e `diretorio_fotos_obras`.
4. Rodar `database/schema.sql` em uma instalação nova.
5. Criar o usuário inicial com `database/criar-usuario-inicial.php`.

## Como rodar localmente

Este projeto deve rodar apenas localmente enquanto nao houver venda ou aprovacao explicita de implantacao.

Ambiente local preparado:

- banco: `canteiropro_painel`;
- usuario MySQL local: `root`;
- senha local: vazia;
- fotos privadas locais: `C:/MarcosDev-Originais/canteiropro/images/obras`.

Com o MySQL do XAMPP ligado, executar:

```powershell
cd C:\marcbmrs.github.io
C:\xampp\mysql\bin\mysql.exe -u root < projetos\canteiropro\painel\database\schema.sql
C:\xampp\php\php.exe projetos\canteiropro\painel\database\popular-demonstracao-completa.php
C:\xampp\php\php.exe projetos\canteiropro\painel\database\criar-usuario-inicial.php "Marcos Dev" "marcos@local.test" "canteiropro123"
C:\xampp\php\php.exe -S localhost:8010 -t C:\marcbmrs.github.io
```

Depois abrir:

```text
http://localhost:8010/projetos/canteiropro/painel/login.php
```

Login local sugerido:

- e-mail: `marcos@local.test`;
- senha: `canteiropro123`.

## Arquivos principais

- `index.php`: criação de orçamento.
- `painel.php`: início do painel.
- `orcamentos.php`: lista de orçamentos.
- `orcamento.php`: detalhe e status do orçamento.
- `contratos.php`: fluxo de contratos.
- `obras.php`: acompanhamento de obras.
- `obra-cliente.php`: página privada do cliente.
- `styles/main.css`: visual mobile-first.
- `app/catalogo-inicial.php`: catálogo inicial de serviços e locações.

## Segurança

Não publicar:

- `app/config.local.php`;
- fotos privadas reais;
- tokens reais de clientes;
- dumps de banco com dados reais;
- credenciais de hospedagem.

## Próximas extrações

- Centralizar configuração de marca.
- Separar modelo de contrato por cliente.
- Criar dados demo neutros.
- Criar checklist de implantação.
- Criar landing page de venda pela Marcos Dev.
