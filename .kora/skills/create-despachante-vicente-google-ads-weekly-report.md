# Skill: Create Despachante Vicente Google Ads Weekly Report

## Purpose

Answer requests to verify, prepare, create or review the weekly Google Ads report for the Despachante Vicente campaign, preserving the established Gamma presentation pattern and the read-only Google Ads boundary.

## Trigger

Use when Marcos asks about weekly reports, their periods, their status in Gamma, or asks to create a new report for Despachante Vicente.

## Required Context

- Campaign: `Pesquisa | Gestão de Frotas B2B | Teste 01`.
- Landing page and offer: `projetos/despachante-vicente/gestao-de-frotas/` and B2B operational fleet management.
- Public-safe content model: `projetos/despachante-vicente/RELATORIO-GOOGLE-ADS.md`.
- Metric collection: `.kora/skills/collect-google-ads-campaign-intelligence.md` and `C:\KORA\runtime\tools\google-ads-reader\read_weekly_metrics.ps1`.
- Analysis agent: `.kora/agents/google-ads-performance-analyst.md`.
- Quality checks: `.kora/evals/google-ads-proposal-quality.md` and `.kora/evals/despachante-vicente-google-ads-report-quality.md`.
- Private Gamma reference registry: `C:\KORA\private\marcos-dev\despachante-vicente-google-ads-reporting.md`.

## Procedure

1. Confirm whether the request is only to check history/status or to create a report. Creation in Gamma is an external account action; do not share, export, publish or change access unless Marcos explicitly asks.
2. Identify the exact completed seven-day period and the immediately preceding seven-day comparison period. Do not silently shift the dates.
3. Read both periods using the approved KORA Google Ads reader in read-only mode. Never use Google Ads mutation endpoints or change campaign configuration.
4. Check the active Gamma library for the Despachante Vicente reference reports and read the latest applicable reference before creating a new one. Use the private registry for the approved source references.
5. Use a Gamma template when the reference has been converted to a Gamma template. If it remains a regular Gamma, preserve its documented narrative order, visual direction and components in a new Gamma; the connector cannot edit or clone a regular Gamma in place.
6. Build the report in Brazilian Portuguese, 16:9, with the established visual system: Despachante Vicente and Marcos Dev logos, light professional deck, navy/blue accents, metric cards, comparison table, charts, operational transparency and closing card.
7. Keep the standard sequence unless the period requires a justified adaptation: cover; executive summary; funnel/metric explanation; glossary; weekly scorecards; weekly comparison; comparative charts; daily evolution; operational changes; strategic reading; next steps; closing.
8. Reconcile every displayed metric and variation against the read-only output. If dates have no returned row, explain that as missing API activity/processing data, not as an invented zero.
9. State clearly that a WhatsApp-click conversion is a technical signal, not proof of a conversation, qualified lead, sale, ROI or ROAS. Treat small volumes as directional only.
10. Run both required quality checks. Review the generated Gamma visually and factually before treating it as ready for Marcos's approval.
11. Store only the Gamma URL/ID, reporting period, source-read date and approval/export status in the private KORA registry. Do not put account identifiers, raw exports, credentials or client-private reporting data in the public repository.

## Standard Facts And Boundaries

- The campaign began on 13/08/2026 and initially used Maximize Clicks (`TARGET_SPEND`).
- WhatsApp-click tracking was prepared/tested on 24/08/2026; historical zero conversions before validation cannot be interpreted as zero contacts.
- No recommendation or campaign change is approved merely because it appears in a report. Budget, bids, keywords, terms, targeting, ads, locations, conversion setup and landing-page changes require separate explicit approval.
- Do not promise sales, leads, ranking, ROAS, ROI or results.

## Output

For a status request, return the available periods and distinguish saved Gamma reports from data merely available in Google Ads.

For a creation request, return the Gamma URL or generation status, the period and comparison period, a concise review status, and any item awaiting Marcos's approval.
