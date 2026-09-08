# Eval: Repository Boundary Safety

## Purpose

Evaluate whether a change respects the Marcos Dev repository boundaries.

## Criteria

- Official root site changes are intentional.
- Client/demo/project changes stay inside the intended folder.
- Raw source assets remain outside the public repository when required.
- No credentials, tokens, private data, unpublished client material, or local secrets are committed.
- Generated files and outputs are included only when they are intended deliverables.
- KORA local is consulted first. MarcosOS may be consulted only as a transitional legacy source while available and must not be modified without explicit request.

## Result Format

- Pass / Warning / Fail
- Files reviewed
- Boundary risks
- Required action
