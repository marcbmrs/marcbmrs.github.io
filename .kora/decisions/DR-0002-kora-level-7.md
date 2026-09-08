# DR-0002: Activate KORA level 7 for Marcos Dev

Status: accepted
Date: 2026-09-05
Scope: operational-persistence
Owner: Marcos

## Context

Marcos explicitly requested activation of KORA level 7 for this repository.

Before this decision, Marcos Dev had level 6 active: local agents, skills, and evals existed, but durable eval results, experiments, capability gaps, and learning loops were intentionally not persisted unless requested.

## Decision

Marcos Dev now uses KORA through level 7.

Level 7 means supervised operational persistence is active for project-specific:

- durable decisions;
- eval results;
- experiment records;
- recurring capability gaps;
- learning recommendations;
- local improvements to agents, skills, tools, evals, or automation proposals.

## Boundaries

Level 7 does not automatically activate automations.

Each automation still requires explicit scope, approval, implementation, and activation decision from Marcos before use.

Do not persist raw transcripts, weak hypotheses, temporary notes, unnecessary personal or client data, credentials, tokens, private account access, or generic knowledge that belongs in MarcosOS or KORA Core.

## Consequences

- Future agents should use `.kora/memory/`, `.kora/experiments/`, `.kora/decisions/`, and eval records when a learning is durable enough to help future Marcos Dev work.
- Future reviews can preserve formal results when useful instead of only reporting them in chat.
- Capability gaps can be tracked locally and later converted into agents, skills, tools, evals, or automation proposals.
- The repository remains the local owner of Marcos Dev-specific operational memory.