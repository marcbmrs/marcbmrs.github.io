# DR-0002: Upgrade do uso da KORA instalada

Status: accepted
Date: 2026-09-07
Scope: local-kora-binding
Owner: Marcos

## Context

Marcos Dev ja possui uma instalacao local da KORA em `.kora/`, com binding, contexto e capacidades locais.

KORA Core evoluiu para a versao 1.4.0 com suporte a registro central de instalacoes e uso mais forte dentro de outros repositorios.

## Decision

Marcos Dev adotara o padrao KORA 1.4.0 para uso instalado em repositorios operacionais.

## Consequences

- `.kora/binding.md` registra a versao da Core e o comportamento de KORA instalada.
- `.kora/README.md` passa a ser a entrada local da KORA no projeto.
- `AGENTS.md` explicita como usar KORA neste repositorio.
- A instalacao deve aparecer em `C:\KORA\projects\INSTALLED-KORA.md`.

## Related

```text
C:\KORA\skills\use-installed-kora.md
C:\KORA\skills\check-installed-kora.md
C:\KORA\projects\INSTALLED-KORA.md
.kora/binding.md
.kora/README.md
AGENTS.md
```
