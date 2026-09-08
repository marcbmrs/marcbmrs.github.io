# DR-0005: Treat MarcosOS as legacy source and migrate Marcos Dev operations into KORA

Status: accepted
Date: 2026-09-05
Scope: kora-independence
Owner: Marcos

## Context

Marcos stated that Marcos Dev should not depend on MarcosOS because MarcosOS will be discontinued.

The local KORA layer already contains project agents, skills, evals, tool references, memory, and decisions. Some active instructions and the Instagram connector still point to `C:\MarcosOS`.

## Decision

For Marcos Dev, KORA local becomes the primary operational knowledge and capability layer.

MarcosOS is now a legacy/transitional source. It may be consulted while available to migrate relevant Marcos Dev-specific context, but future workflows should not require MarcosOS to exist.

## Migration Rules

- Migrate only Marcos Dev-specific context, decisions, procedures, tool specs, eval criteria, and durable learning.
- Do not copy the entire MarcosOS or generic reusable knowledge into this repository.
- Keep credentials, tokens, account IDs, private exports, and raw platform data outside the public repository.
- When a MarcosOS tool is still needed, mark it as transitional and create a KORA-owned/private replacement plan.
- Do not activate automations or external writes as part of migration without explicit approval.

## Immediate Impact

The Instagram connector was migrated on 2026-09-05 to the KORA-owned private runtime at `C:\KORA\runtime\tools\instagram-connector\`, with secrets stored in `C:\KORA\.env` and ignored by Git.

Future content, social media, offer, and web decisions for Marcos Dev should be persisted first in `.kora/`.

## Follow-up Update

On 2026-09-05, the Marcos Dev Instagram read connector was verified through the KORA runtime. The legacy MarcosOS executable is no longer required for Marcos Dev profile/media reads on this machine.
