# DR-0006: Add Instagram content planning skill and quality eval

Status: accepted
Date: 2026-09-05
Scope: social-media-production
Owner: Marcos

## Context

Marcos wants KORA to help generate ideas for posts, videos, storytelling, methodology, and social media content without depending on MarcosOS long-term.

The existing Instagram Content Analyst agent evaluates posts, but content production needs a repeatable skill and a quality rubric.

## Decision

Create:

- `.kora/skills/plan-instagram-content-from-strategy.md`
- `.kora/evals/instagram-content-quality.md`

The skill stores the essential Marcos Dev-specific social content workflow locally. The eval gives a consistent quality check before publishing or packaging content.

## Boundaries

This does not authorize posting, scheduling, sending Directs, comment automation, scraping, or external account changes.

The skill should use recent post history when available and should persist only durable approved learning under KORA level 7.

## Consequences

Marcos Dev can request ideas, scripts, carousels, and storytelling variations directly from KORA local.

Remaining MarcosOS dependency for deeper strategy should be migrated gradually only where it is specific and durable for Marcos Dev.