# CanteiroPro

Produto em estruturação da Marcos Dev para transformar o aprendizado do painel Renan Reformas em uma oferta reutilizável para prestadores de obra e serviços técnicos.

## Estado

Exploração de produto. Ainda não é uma landing page publicada, SaaS aberto ou sistema multiempresa.

## Nome de trabalho

`CanteiroPro`

Nome escolhido provisoriamente por comunicar rotina de obra, execução e profissionalização sem limitar a oferta a um único ofício.

Antes de uso público forte, validar:

- disponibilidade de domínio;
- busca básica de marca;
- conflito com produtos existentes;
- clareza com o público real.

## Público inicial

- Pedreiros e pequenas equipes de reforma.
- Empreiteiros com algumas obras simultâneas.
- Pintores, gesseiros, instaladores de drywall, eletricistas, encanadores, telhadistas e colocadores de piso.
- Pequenas empresas de manutenção predial e acabamento.

## Promessa de trabalho

Um painel simples para organizar orçamentos, contratos e atualizações de obra, com aparência profissional para o cliente.

## Produto Base

A primeira versão deve ser vendida como implantação configurada, não como SaaS self-service.

Entregáveis prováveis:

- painel com login;
- cadastro de cliente;
- criação de orçamento;
- catálogo de serviços e locações adaptado;
- controle de status do orçamento;
- PDF do orçamento;
- minuta de contrato com aviso de revisão jurídica;
- criação de obra a partir de orçamento aprovado;
- atualização da obra com fotos;
- página privada para o cliente acompanhar;
- ajustes de marca, cores e textos do prestador.

## Escopo comercial

A primeira hipotese comercial esta documentada em:

```text
escopo-comercial-v1.md
```

Resumo atual:

- Start: R$ 997 de implantacao + R$ 197/mês + Pacote Infraestrutura;
- Pro: R$ 1.497 de implantacao + R$ 247/mês + Pacote Infraestrutura;
- Custom: a partir de R$ 2.500 de implantacao + mensalidade sob medida + infraestrutura.

Dominio, hospedagem, SSL e recursos tecnicos devem ficar no Pacote Infraestrutura, separados do preco principal.

## Base white label

A primeira extração técnica vive em:

```text
projetos/canteiropro/painel/
```

Ela nasceu a partir do painel Renan Reformas, mas deve evoluir como base neutra:

- sem `config.local.php`;
- com sessão/cookie próprios;
- com banco sugerido `canteiropro_painel`;
- com diretório privado sugerido `C:\MarcosDev-Originais\canteiropro\images\obras`;
- com textos e marca CanteiroPro;
- com dados demonstrativos fictícios quando usados.

Próximo nível de white label: mover nome, subtítulo, logo, caminhos de imagem, contratada, prefixo de PDF e mensagens de WhatsApp para uma configuração central.

## Limites

- Não prometer validade jurídica automática de contratos.
- Não prometer contabilidade, emissão fiscal ou assinatura eletrônica sem integração específica.
- Não vender como multiempresa antes de separar configuração, dados, marca e instalação.
- Não usar dados reais da Renan Reformas como demonstração pública sem aprovação.

## Relação com Renan Reformas

O painel Renan Reformas é a prova prática e referência de fluxo. O CanteiroPro deve nascer como versão neutra, configurável e vendável pela Marcos Dev.

## URL Esperada

Ainda não definida.

Possíveis caminhos:

- página de oferta dentro do site Marcos Dev;
- demonstração privada em `/projetos/canteiropro/`;
- domínio próprio futuro, se o produto for validado.
