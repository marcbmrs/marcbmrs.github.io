# DR-0004: Add Instagram Content Analyst agent

Status: accepted
Date: 2026-09-05
Scope: social-media-analysis
Owner: Marcos

## Context

Marcos asked whether KORA should create an agent or skill to evaluate Marcos Dev Instagram posts and help with social media decisions.

The repository already has `.kora/skills/collect-instagram-post-intelligence.md` for authorized post collection and `.kora/tools/instagram-connector.md` for the MarcosOS read-only connector reference.

The missing capability is editorial judgment: interpreting posts in relation to strategy, offer, audience, CTA, visual direction, learning, and safe next actions.

## Decision

Create `.kora/agents/instagram-content-analyst.md` as a local KORA agent for Marcos Dev.

The agent evaluates Marcos Dev Instagram posts and social evidence using authorized data, manually imported official Insights when available, and MarcosOS strategy/identity references.

## Boundaries

The agent does not access Instagram directly. It uses the existing skill/tool when data collection is needed.

The agent does not publish, comment, send Directs, scrape, download media, manage comments, or activate recurring sync.

The agent must not use Marcos Dev credentials to analyze third-party profiles.

## Consequences

Future social media work can separate execution layers clearly:

- tool: read authorized Instagram data;
- skill: collect and structure post intelligence;
- agent: evaluate posts and recommend content decisions;
- eval: optional future rubric if post evaluation becomes frequent enough to require scoring consistency.