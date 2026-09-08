# Experiment: Renan Panel Productization

Date: 2026-09-06
Scope: Marcos Dev offer development / construction-service operational panel

## Hypothesis

The Renan Reformas panel can become a sellable Marcos Dev productized service for small contractors, bricklayers, renovation teams, painters, installers, and similar field-service professionals who need to organize budgets, approvals, contracts, work updates, and client communication.

## Current Evidence

- The panel already supports mobile-first budget creation, service/equipment catalog, client data, saved budgets, status flow, PDF generation, contract draft workflow, work tracking, private client page, photos, WhatsApp messaging, and team login.
- The product solves an operational pain common to small construction providers: work is usually managed across WhatsApp, paper notes, photos, informal quotes, and memory.
- The strongest differentiator is not generic CRM behavior; it is the full flow from quote to client-facing work update.

## Recommended Positioning

Sell first as a configured operational panel for local service providers, not as an open self-serve SaaS.

Suggested framing:

> Um painel simples para organizar orçamentos, contratos e atualizações de obra, com aparência profissional para o cliente.

## Best-Fit Audience

- Pedreiros and small renovation teams.
- Empreiteiros with a few simultaneous jobs.
- Painters, tilers, drywall/installment crews, roofers, electricians, plumbers, and finishing specialists.
- Small construction/maintenance companies that rely heavily on WhatsApp.

## Success Criteria

- A prospect understands the offer in under one minute.
- The demo shows at least one complete flow: create quote, approve, generate contract draft, create work, publish client update.
- The panel can be configured for a new business without changing Renan-specific names manually across many files.
- The offer can be sold as setup + monthly support/hosting without promising legal or accounting automation.

## Main Risks

- Current code is client-specific and still contains Renan-specific brand/copy.
- Multi-tenant SaaS is not ready; each installation should be separate at first.
- Contract wording needs legal review before being sold as contract automation.
- Production photos and client data require clear privacy/security handling.
- Pricing, tax/invoicing, electronic signature, and advanced CRM are not implemented.

## Next Recommended Action

Create a reusable demo version under a neutral project name, extract brand/business settings into configuration, and build a Marcos Dev sales page aimed at small construction-service providers.

## 2026-09-06 Update

Created an initial white-label extraction at `projetos/canteiropro/painel/`.

What was done:

- copied the Renan panel flow into the CanteiroPro project;
- removed copied `config.local.php`;
- changed session and remember-cookie names to CanteiroPro-specific values;
- changed default local private photo directory to `C:\MarcosDev-Originais\canteiropro\images\obras`;
- changed the schema database name to `canteiropro_painel`;
- replaced executable Renan-specific brand/copy with CanteiroPro or neutral prestador language;
- added `logo-canteiropro.svg`;
- removed copied Renan OG PNGs and pointed the client page to a neutral SVG OG image;
- added `projetos/canteiropro/painel/README.md`.

Validation:

- all copied top-level PHP files in `projetos/canteiropro/painel/` passed `php -l`;
- all copied JS files in `projetos/canteiropro/painel/scripts/` passed `node --check`;
- remaining Renan references are documentary context only, explaining source/origin and privacy limits.

Next technical recommendation:

Centralize brand/client settings in a white-label configuration layer before creating public demos or additional client installations.
