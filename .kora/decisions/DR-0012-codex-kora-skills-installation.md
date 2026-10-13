# DR-0012: KORA skills available in Codex

Date: 2026-09-27
Status: implemented following Marcos's request to install the listed KORA skills in Codex

## Decision

Expose the 19 Marcos Dev local skills and 71 KORA Core procedures as personal Codex skills in the current user's Codex skills directory.

Each installed directory contains a `SKILL.md` adapter with a scoped description and a link to the maintained procedure. Local adapters use the `marcos-dev-` prefix; Core adapters use `kora-`. The weekly Vicente reporting adapter is named `marcos-dev-vicente-google-ads-weekly-report` to fit the skill-name limit.

## Source ownership

The procedures remain in this repository's `.kora/skills/` and in `C:\KORA\skills\`. Adapters read those sources when invoked, resolve references from the appropriate project or Core root, and report a missing source rather than inventing its workflow.

This is a machine-local installation, not a portable copy of KORA. Moving the source repositories requires updating the adapter paths. New or renamed source skills and changes to their discovery descriptions require refreshing the installation.

The machine-local installation manifest is `kora-skills-installation.json` in the user's Codex home. It records adapter names, source paths and source hashes at installation time.

## Validation and boundaries

- All 90 adapters passed the skill-creator `quick_validate.py` validator.
- All 90 source procedure files existed at validation time.
- Existing Codex skills were preserved; installation refused name collisions.
- Runtime workflows and external connectors were not executed or authenticated as part of installation.
- Installation does not activate automations or authorize external actions.
- Recognition by the running Codex session is checked on the next turn; file validation alone does not establish runtime discovery.
