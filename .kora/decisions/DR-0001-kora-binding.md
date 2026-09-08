# DR-0001: Marcos Dev uses KORA binding

Status: accepted
Date: 2026-09-05
Scope: project-binding
Owner: Marcos

## Context

Marcos Dev is the first project/business context connected to KORA.

The operational repository is:

```text
C:\marcbmrs.github.io
```

KORA Core is:

```text
C:\KORA
```

## Decision

Marcos Dev will use a local `.kora/` binding to store project-specific context, decisions, memory, agents, skills, tools, evals, automations, and experiments.

## Reasoning

This keeps KORA Core clean and reusable while allowing Marcos Dev to develop its own local capabilities.

## Consequences

- KORA Core stores only a lightweight registry for Marcos Dev.
- Marcos Dev-specific details stay inside this repository's `.kora/` layer.
- Reusable patterns may be proposed back to KORA Core only after scope analysis and approval.
- No integrations, automations, account access, publishing, or external changes are created by this decision.
