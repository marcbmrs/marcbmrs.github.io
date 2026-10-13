# DR-0011: Despachante Vicente Weekly Google Ads Reporting Standard

Date: 2026-09-19
Status: approved by Marcos

## Context

Weekly Google Ads reporting for Despachante Vicente already has two Gamma presentations with a consistent visual and narrative pattern. The project previously had a public-safe Markdown report model and a read-only Google Ads capability, but no local KORA procedure that linked verified data collection, the established Gamma references and delivery review.

## Decision

Create a Marcos Dev-local KORA skill and evaluation for Despachante Vicente weekly Google Ads reports. Use the private KORA registry as the source of Gamma reference IDs/URLs and reporting history.

The workflow must query Google Ads only through the approved read-only KORA reader, preserve the existing Gamma reporting system, and require factual plus visual review before a report is considered ready.

## Created Capability

- Skill: `.kora/skills/create-despachante-vicente-google-ads-weekly-report.md`
- Eval: `.kora/evals/despachante-vicente-google-ads-report-quality.md`
- Private registry: `C:\KORA\private\marcos-dev\despachante-vicente-google-ads-reporting.md`

## Boundaries

- Google Ads changes, Gamma external sharing, exports and publishing remain separate actions requiring explicit instruction.
- Gamma references and report links remain private KORA project records.
- The public repository keeps only public-safe procedures and the reusable content model; it must not store raw account data, credentials or private report artifacts.

## Implementation Update

On 2026-09-19, Marcos authorized conversion of the preferred reference into the private Gamma workspace template `Modelo — Relatório Semanal de Tráfego Pago | Despachante Vicente`. Its private ID and URL are recorded in the private KORA registry. Future reports must be created from that template.
