# Skill: Inspect Renan Panel In Browser

## Purpose

Use a browser-visible review to inspect the Renan Reformas panel as it is actually rendered and collect UX improvements before production.

## Procedure

1. Confirm the target is local or explicitly approved for review, usually `http://localhost:8000/projetos/renan-reformas/painel/`.
2. Identify the workflow to inspect: dashboard, budget creation, budget detail, contracts, work tracking, update form, or client page.
3. Use available browser tooling when present; otherwise use screenshots/source review and state the limitation.
4. Check mobile first, then desktop when relevant:
   - first visible action;
   - tap target comfort;
   - scrolling and overflow;
   - modal behavior;
   - form labels, validation, and save feedback;
   - navigation and back path;
   - empty/error/success states;
   - private data exposure.
5. Do not submit destructive actions, publish, deploy, delete records, migrate data, or alter production during inspection.
6. Convert observations into a prioritized local change list.

## Output

- reviewed URL and viewport;
- flow inspected;
- top UX findings;
- recommended local changes;
- validation or testing limits.
