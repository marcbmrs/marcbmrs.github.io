# Skill: Deploy Project To Hostinger

## Purpose

Safely publish approved local Marcos Dev project changes to the matching Hostinger production website.

## Procedure

1. Identify the requested project, local folder, public production URL, and Hostinger remote path.
2. Read the project README and `.kora/context/hostinger-production-map.md`.
3. Separate intended changed files from unrelated dirty work using `git status --short` and file inspection.
4. Run the relevant local review skill or eval before publication:
   - `review-client-project-page` for client pages;
   - `review-publish-readiness` for public page changes;
   - `hostinger-deploy-safety` for production deploy risk.
5. Confirm no secrets, private client data, local-only files, raw originals, or unrelated outputs are included.
6. Choose the safest deploy method:
   - file-level upload for specific changed files;
   - static archive deploy only for intentional full-site replacement;
   - SFTP/SSH fallback only when MCP/API cannot support the required file operation.
7. Present a concise approval summary to Marcos before any remote write:
   - project;
   - domain;
   - remote path;
   - exact files to upload;
   - backup or rollback note;
   - expected public URLs to verify.
8. After explicit approval, publish only the approved files or archive.
9. Verify the public URL, key page rendering, and obvious broken assets or errors.
10. Record durable deployment mapping changes or lessons in `.kora/` when useful and safe.

## Output

Deploy checklist with status:

- `hold`: missing mapping, credentials, approval, validation, or rollback clarity;
- `ready for approval`: safe plan prepared, no remote writes done;
- `deployed`: remote update completed and verified;
- `failed`: attempted approved deploy failed, with next recovery step.
