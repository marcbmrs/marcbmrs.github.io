# DR-0003: Use MarcosOS Instagram connector for Marcos Dev post intelligence

Status: accepted
Date: 2026-09-05
Scope: instagram-read-operations
Owner: Marcos

## Context

Marcos asked whether Marcos Dev should create an agent or skill to access its Instagram and collect post information.

The MarcosOS already contains a read-only Instagram connector at `C:\MarcosOS\tools\instagram-connector\`, with secrets stored only in `C:\MarcosOS\.env`.

A local health check for `marcos-dev` returned authorized read access on 2026-09-05. A short media read returned sanitized recent post data.

## Decision

Marcos Dev will not duplicate the Instagram API connector inside this public website repository.

The repository will instead define:

- local skill `.kora/skills/collect-instagram-post-intelligence.md` for the analysis workflow;
- local tool reference `.kora/tools/instagram-connector.md` pointing to the MarcosOS read-only connector.

## Boundaries

The connector may be used only for authorized owned profiles with isolated configuration.

For potential clients or third-party Instagram profiles, use only public evidence supplied by the user. Do not use Marcos Dev credentials for third-party analysis.

No publishing, messages, comment management, scraping, downloading, recurring sync, or storage of raw post dumps is authorized by this decision.

## Consequences

Future Instagram analysis for Marcos Dev can use the existing authorized tool and a repeatable KORA skill.

Broader insights require separate confirmation of current Meta permissions and endpoints before implementation.