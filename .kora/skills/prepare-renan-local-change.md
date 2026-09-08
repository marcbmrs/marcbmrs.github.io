# Skill: Prepare Renan Local Change

## Purpose

Make small, reviewable local changes to the Renan Reformas panel with validation before Marcos decides on production.

## Procedure

1. Identify the exact panel screen, user goal, and files in scope.
2. Read the relevant PHP, CSS, JS, README/context, and KORA agent/eval when the change affects UX or production safety.
3. Keep the change local, focused, and reversible by ordinary editing.
4. Preserve existing data contracts, statuses, routes, and private-file boundaries unless the requested change requires a migration.
5. Validate with the narrowest useful checks:
   - PHP syntax for changed PHP files;
   - JS syntax for changed JS files;
   - source search for new selectors/classes when useful.
6. Report the local review URL and what changed. Do not deploy without explicit approval.

## Output

A concise implementation note with changed flow, files touched, validation results, and review path.
