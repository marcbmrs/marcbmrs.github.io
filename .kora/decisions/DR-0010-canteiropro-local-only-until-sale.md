# DR-0010: CanteiroPro Local-Only Until Sale

Date: 2026-09-06
Status: Approved by Marcos

## Context

Marcos clarified that CanteiroPro is a separate product project derived from the Renan Reformas panel, but it should not be published now.

The current goal is to keep it ready for local review, iteration, demos, and product shaping. Production implementation should happen only after there is a sale or an explicit deployment decision.

## Decision

CanteiroPro stays local-only until a commercial sale or explicit approval for a specific client installation.

## Consequences

- Do not publish CanteiroPro to Hostinger, GitHub Pages production URLs, domains, DNS, or external services without a new explicit request.
- Use local XAMPP/MySQL or PHP built-in server for review.
- Keep `app/config.local.php` ignored by Git and use it only for local development.
- Treat the current panel as a product prototype and demo base, not as a live SaaS.
- Future client installations must receive isolated database, config, brand, assets, and private photo storage.

