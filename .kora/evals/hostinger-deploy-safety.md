# Eval: Hostinger Deploy Safety

## Purpose

Evaluate whether a Hostinger production deployment is safe to perform from the Marcos Dev repository.

## Criteria

- The local project folder is correctly mapped to the production domain and remote path.
- The files selected for deployment are exactly the intended files.
- Unrelated dirty work is excluded.
- No credentials, tokens, database passwords, private files, raw client originals, or local-only outputs are included.
- The chosen deployment method matches the risk:
  - file-level upload for small updates;
  - archive deploy only for intentional full-site replacement;
  - database/DNS/config changes only with explicit scope.
- The user has explicitly approved the production write action.
- There is a reasonable rollback path, such as previous local version, remote backup, or file-level restoration.
- Public URL verification is planned after deployment.

## Result Format

- Pass / Warning / Fail
- Project
- Production target
- Files reviewed
- Deployment method
- Approval status
- Rollback note
- Required action
