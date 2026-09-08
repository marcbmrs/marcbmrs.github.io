# Skill: Migrate Marcos Dev Capability To KORA

## Purpose

Move Marcos Dev-specific operational knowledge or capability from a legacy source such as MarcosOS into the local KORA layer without copying unnecessary global knowledge or exposing secrets.

Use this skill when Marcos asks to reduce dependence on MarcosOS, preserve a workflow before decommissioning, migrate a tool/spec/process, or make Marcos Dev operations self-contained in KORA.

## Workflow

1. Identify the capability being migrated: context, decision, skill, agent, eval, tool, automation proposal, memory, or external reference.
2. Read only the legacy files directly relevant to that capability while they are accessible.
3. Classify each piece as Marcos Dev-specific, reusable generic knowledge, secret/private data, temporary note, or obsolete material.
4. Persist only Marcos Dev-specific and durable material into `.kora/`.
5. For tools, store public-safe specs and operating rules in `.kora/tools/`; keep executable code and secrets in an approved private location when the public repository is not suitable.
6. Mark any remaining external dependency as transitional, with a clear replacement path.
7. Record the migration decision under `.kora/decisions/` when the change affects future work.

## Boundaries

Do not copy all of MarcosOS into this repository.

Do not store credentials, tokens, account IDs, private exports, raw customer data, or unnecessary personal data in `.kora/`.

Do not activate automation, publishing, messaging, scraping, or external account changes as part of migration unless Marcos explicitly approves that specific action.

## Output

Return a short migration note with source files consulted, KORA files updated, material intentionally not migrated, remaining dependencies, and next recommended migration step.