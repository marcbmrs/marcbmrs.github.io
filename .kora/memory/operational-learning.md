# Operational Learning

This file stores durable, Marcos Dev-specific learning records created under KORA level 7.

## Record Format

```text
Date:
Scope:
Source/Evidence:
Learning:
Decision or Recommendation:
Follow-up:
```

## Records

### 2026-09-05 - Instagram read connector available for Marcos Dev

Date: 2026-09-05
Scope: instagram-read-operations
Source/Evidence: `C:\MarcosOS\tools\instagram-connector\health_check.py --profile marcos-dev` returned authorized read access; `read_media.py --profile marcos-dev --limit 5` returned sanitized media data.
Learning: Marcos Dev already has an authorized read-only Instagram connector in MarcosOS. The local KORA layer should use it instead of duplicating API scripts or credentials in this public repository.
Decision or Recommendation: Use `.kora/skills/collect-instagram-post-intelligence.md` for post analysis and `.kora/tools/instagram-connector.md` as the local reference to the MarcosOS connector.
Follow-up: Implement broader insights only after verifying current Meta endpoints and required permissions.
### 2026-09-05 - Instagram post evaluation needs an agent layer

Date: 2026-09-05
Scope: social-media-analysis
Source/Evidence: Marcos asked for a KORA capability to evaluate Marcos Dev Instagram posts and help social media decisions; existing local skill/tool already handle collection.
Learning: Post evaluation requires editorial judgment across audience, offer, CTA, format, visual/copy fit, repetition, and learning value. This is better represented as an agent than as another collection skill.
Decision or Recommendation: Use `.kora/agents/instagram-content-analyst.md` with `.kora/skills/collect-instagram-post-intelligence.md` for future Marcos Dev Instagram reviews.
Follow-up: If reviews become frequent and need scoring consistency, create a separate eval such as `instagram-content-quality.md`.
### 2026-09-05 - Instagram content production needs a local KORA loop

Date: 2026-09-05
Scope: social-media-production
Source/Evidence: Marcos stated that Marcos Dev should not depend on MarcosOS long-term and approved creating local KORA support for posts, videos, storytelling, and methodology.
Learning: Social media support for Marcos Dev now needs a full local loop: collect recent post evidence, analyze content patterns, generate new ideas/scripts from strategy, and evaluate quality before review.
Decision or Recommendation: Use `collect-instagram-post-intelligence`, `instagram-content-analyst`, `plan-instagram-content-from-strategy`, and `instagram-content-quality` together for future Instagram planning.
Follow-up: Migrate a KORA-owned/private executable connector and secret location before removing MarcosOS.
### 2026-09-05 - Instagram connector migrated to KORA runtime

Date: 2026-09-05
Scope: instagram-read-operations
Source/Evidence: Created `C:\KORA\runtime\tools\instagram-connector\`, copied read-only scripts without secrets, created `C:\KORA\.env` with Marcos Dev Instagram variables only, added `C:\KORA\.gitignore`, and verified `health_check.py --profile marcos-dev` plus `read_media.py --profile marcos-dev --limit 3` through the KORA runtime.
Learning: Marcos Dev Instagram profile/media reads no longer require the MarcosOS executable on this machine. The private KORA runtime and private KORA `.env` are now the operational path.
Decision or Recommendation: Use `C:\KORA\runtime\tools\instagram-connector\` for future Marcos Dev Instagram reads. Keep `.kora/tools/instagram-connector.md` as the public-safe project reference.
Follow-up: Add broader Insights only after official Meta documentation review and explicit scope approval.
## 2026-09-05 - Google Ads Read Capability

Marcos Dev now has a KORA-owned Google Ads read/proposal capability migrated from legacy MarcosOS. The safe default is read-only campaign intelligence through `googleAds:searchStream`; write/mutation execution is intentionally separate and not active.

Operational rule: when analyzing Google Ads, use `.kora/skills/collect-google-ads-campaign-intelligence.md`, `.kora/agents/google-ads-performance-analyst.md`, and `.kora/evals/google-ads-proposal-quality.md`. Keep private reports and credentials in `C:\KORA`, not in this public repository.

### 2026-09-06 - Renan panel file-level Hostinger deploy

Date: 2026-09-06
Scope: renan-reformas-production-deploy
Source/Evidence: Marcos approved publishing the client private work-feed design update. Hostinger MCP resolved `renanreformas.com.br` as an active `other` website and TUS file-level upload returned `201`/`204` for `painel/obra-cliente.php` and `painel/styles/main.css`. Public checks confirmed the CSS contained the new client summary/gallery rules and `obra-cliente.php` returned the expected private-page 404 for an invalid token without fatal errors.
Learning: Small PHP/CSS updates in `projetos/renan-reformas/painel/` can be safely published through Hostinger file-level upload, avoiding full archive deploy for the entire production site.
Decision or Recommendation: Keep file-level upload as the default for focused Renan panel changes. Use full archive deploy only when Marcos explicitly approves a whole-site replacement.
Follow-up: For future client-page visual deploys, verify public CSS cache with a cache-busting query and test `obra-cliente.php` with an invalid token to catch PHP fatal errors without exposing private client links.

### 2026-09-06 - Renan full panel UX deploy by file upload

Date: 2026-09-06
Scope: renan-reformas-panel-ux-production-deploy
Source/Evidence: Marcos approved publishing all local Renan panel UX changes. PHP lint passed for `painel.php`, `orcamentos.php`, `orcamento.php`, `contratos.php`, `editar-contrato.php`, `editar-modelo-contrato.php`, `obras.php`, `atualizar-obra.php`, and `obra-cliente.php`; `node --check` passed for the five panel scripts. Hostinger TUS file-level upload returned `201`/`204` for all approved files, cache clearing was accepted, production CSS and scripts returned `200`, key protected panel URLs returned `200`, and invalid private obra token returned expected `404` without fatal error.
Learning: The Renan panel can receive broader UX updates safely through explicit file-level upload of the touched PHP/CSS/JS files, while avoiding database, DNS, credential, and full-site overwrite risk.
Decision or Recommendation: For future broad panel publishes, prepare a named file manifest, lint PHP/JS first, upload only the approved manifest, clear cache, then verify public CSS/script assets plus protected page status.
Follow-up: When production login is available in-browser, add a post-deploy visual smoke test for logged-in dashboard, orçamento, contratos, obras, atualizar obra, and cliente private page.
