# Experiment: CanteiroPro Brand Direction

Date: 2026-09-06
Status: concept direction

## Context

Marcos asked for the colors and logo direction for CanteiroPro. A first raster logo concept was generated and saved for reference.

## Saved Concept

- public project reference: `projetos/canteiropro/assets/images/logo-conceito-canteiropro-v1.png`
- private originals folder: `C:\MarcosDev-Originais\canteiropro\images\logo-conceito-canteiropro-v1.png`

## Direction

CanteiroPro should feel:

- practical;
- trustworthy;
- mobile-first;
- construction-aware without looking generic;
- product-like, not just a contractor logo.

## Palette

- graphite: `#181A1B`;
- progress green: `#1F8A5B`;
- tool yellow: `#F2B705`;
- concrete light: `#F4F1EA`;
- white: `#FFFFFF`;
- support gray: `#6B6F72`.

## Logo Idea

Use a simple vector-friendly symbol combining:

- construction site / floorplan / structure;
- progress check;
- clean app icon behavior.

Avoid:

- helmets;
- crossed tools;
- heavy 3D effects;
- excessive glow;
- mascot style;
- looking too close to Renan Reformas.

## Next Design Step

Convert the concept into a clean SVG logo set:

- horizontal logo;
- standalone app icon;
- dark-background version;
- light-background version;
- small-size favicon/app mark.

## 2026-09-06 Product Page Identity Pass

Created the first local product page using the CanteiroPro identity:

- `projetos/canteiropro/index.html`;
- `projetos/canteiropro/styles/main.css`;
- `projetos/canteiropro/assets/images/logo-canteiropro.svg`;
- `projetos/canteiropro/assets/images/hero-canteiropro-v1.png`.

Also aligned the local panel visual base:

- replaced the inherited Renan-style gold/brown accents in `projetos/canteiropro/painel/styles/main.css`;
- updated `projetos/canteiropro/painel/assets/images/logo-canteiropro.svg` to the cleaner green/yellow/graphite logo.

Validation:

- local product page returned HTTP 200;
- local panel login returned HTTP 200;
- product and panel CSS/logo assets returned HTTP 200;
- no main inherited Renan gold/brown palette references remained in the checked product/panel identity files.

Note:

Automated Playwright screenshot validation was attempted, but `npx playwright` stalled while installing. Manual browser review is still recommended before treating the identity as final.

## Visual Asset Rule

Marcos approved a stronger visual direction:

- prioritize real product mockups and screenshots of the actual local app when presenting CanteiroPro;
- use generated bitmap images for hero visuals, product scenes, icons, illustrations, and richer interface elements when they improve perceived quality;
- avoid weak decorative SVG illustrations and generic placeholder graphics;
- keep SVG mainly for clean logos, simple interface marks, and technical vector assets that need to stay crisp;
- when a mockup is needed, show the real CanteiroPro interface inside the device/screen instead of inventing fake dashboard visuals.

Implication:

Future CanteiroPro landing/design passes should first capture or compose real local-panel screens, then place them into polished product mockups.
