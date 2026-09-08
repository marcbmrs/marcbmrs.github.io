# Skill: Collect Google Ads Campaign Intelligence

## Purpose

Use authorized Google Ads read access to understand campaign performance and produce careful recommendations for Marcos Dev social/traffic/sales work.

## When To Use

Use when Marcos asks to inspect Google Ads, evaluate campaign performance, produce optimization ideas, compare campaigns, or prepare a human-reviewed action plan.

## Inputs

- Objective of the campaign or offer when known.
- Authorized Google Ads account configuration in KORA private runtime.
- Current official Google Ads API documentation when integration behavior may change.

## Workflow

1. Confirm the work is read-only unless Marcos explicitly asks for execution.
2. Check current official Google Ads API docs for authentication, read queries, API version, and mutation boundary when needed.
3. Run the KORA Google Ads reader tool only from `C:\KORA\runtime\tools\google-ads-reader\`.
4. Review generated metrics without exposing account IDs, tokens, private keys, or raw sensitive exports in public repo files.
5. Interpret signals through campaign objective, landing page, offer fit, conversion tracking quality, and lead quality.
6. Separate observations, hypotheses, recommendations, and actions requiring approval.
7. Persist only durable public-safe process decisions in `.kora/`; keep private outputs in KORA runtime.

## Evaluation Criteria

- Recommendation is grounded in observed metrics and campaign objective.
- Low-confidence conclusions are labeled as hypotheses.
- Conversion quality is not inferred from clicks alone.
- Budget or bid changes are not recommended without risk and observation window.
- No external changes are made without explicit approval.

## Output Shape

Return:

- period analyzed;
- campaigns observed;
- strongest and weakest signals;
- risks or tracking gaps;
- recommended next actions;
- items requiring Marcos approval before execution.
