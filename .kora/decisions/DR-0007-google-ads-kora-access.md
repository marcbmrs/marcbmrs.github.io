# DR-0007: Google Ads Read Access In KORA

Date: 2026-09-05
Status: approved by implementation

## Context

Marcos asked to create the same kind of KORA-backed capability for Google Ads that was created for Instagram, using existing access and official documentation.

A legacy MarcosOS tool existed under `C:\MarcosOS\tools\google-ads-optimizer\`. Its proposal generator was read-only and used Google Ads API `googleAds:searchStream`; its separate executor used `:mutate` and write credentials.

Official Google Ads documentation confirms that API access requires appropriate account/developer-token credentials, while resource changes are performed through `:mutate` endpoints with create, update, or remove operations.

## Decision

Create a KORA-owned Google Ads read/proposal capability and migrate only the read-only proposal runtime into `C:\KORA`.

Do not migrate or activate the write executor as part of this decision.

## Created Capability

- Local tool reference: `.kora/tools/google-ads-reader.md`
- Local skill: `.kora/skills/collect-google-ads-campaign-intelligence.md`
- Local agent: `.kora/agents/google-ads-performance-analyst.md`
- Local eval: `.kora/evals/google-ads-proposal-quality.md`
- KORA Core tool: `C:\KORA\tools\google-ads-read-connector.md`
- KORA Core integration: `C:\KORA\integrations\google-ads-api.md`
- KORA runtime: `C:\KORA\runtime\tools\google-ads-reader\`

## Safety Boundary

The migrated capability is read-only. It may query metrics and generate local Markdown proposals, but it must not create, update, remove, pause, or change campaigns, ads, budgets, bids, keywords, targeting, audiences, assets, or conversions.

## Private Material

Read credentials and service-account key are stored only under KORA private paths and must remain ignored by Git.

## Future Work

If Marcos later wants supervised execution of approved Google Ads changes, create a separate write capability with dry-run, explicit approval, execution log, rollback plan, and current official docs review.
