# DR-0008: Hostinger Production Deploy Capability

Date: 2026-09-06
Status: approved by initial implementation

## Context

Marcos confirmed that several Marcos Dev repository projects are already live in Hostinger. For those projects, future production updates should happen by changing local files and then publishing the approved changed files to Hostinger.

Hostinger provides an official MCP option, including a remote MCP server at `https://mcp.hostinger.com`, and an API with hosting, website, file, deploy, DNS, database, and related operations. Hostinger also supports SFTP/SSH on eligible hosting plans.

The Hostinger API includes both narrow file upload capabilities and broader static archive deploy capabilities. Archive deployment can overwrite existing website contents, so it must not be the default for small updates.

## Decision

Create a KORA-owned Hostinger deploy capability for Marcos Dev with supervised production writes.

The default workflow is:

1. edit local files;
2. review the changed scope;
3. prepare a deploy plan;
4. request explicit approval from Marcos;
5. publish only the approved files or approved archive;
6. verify production URLs.

Do not enable automatic deploy-on-edit.

## Created Capability

- Local tool reference: `.kora/tools/hostinger-deploy-connector.md`
- Local skill: `.kora/skills/deploy-project-to-hostinger.md`
- Local agent: `.kora/agents/production-deploy-guardian.md`
- Local eval: `.kora/evals/hostinger-deploy-safety.md`
- Local context map: `.kora/context/hostinger-production-map.md`

## Safety Boundary

Production writes require explicit user approval each time unless Marcos later defines a narrower approved automation.

This capability must not perform DNS, database, email, billing, subscription, ecommerce, website deletion, or destructive overwrite operations without explicit scope-specific approval.

## Private Material

Hostinger OAuth credentials, API tokens, SSH/SFTP credentials, database credentials, account IDs, and sensitive server paths must remain outside the public repository.

Preferred private storage is under KORA private paths or the Hostinger MCP credential store, not under `C:\marcbmrs.github.io`.

## Future Work

- Configure the actual Hostinger MCP connection or API/SFTP fallback outside the public repository.
- Fill `.kora/context/hostinger-production-map.md` with safe project mappings.
- Pilot the workflow on `projetos/renan-reformas/`.
- Consider a later deploy log format after the first successful supervised deployment.
## Implementation Progress

As of 2026-09-06:

- Installed `@hostinger/mcp@1.57.0` globally on the Windows machine.
- Confirmed `hostinger-api-mcp.cmd` is available under `%APPDATA%\npm`.
- Completed Hostinger OAuth login; credentials are stored by the Hostinger MCP credential store outside the public repository.
- Added `mcp_servers.hostinger_api` to `C:\Users\pbsma\.codex\config.toml` using `cmd.exe /c hostinger-api-mcp.cmd` and no repository-stored secrets.
- Current Codex session did not dynamically expose the new MCP tools after config edit; a fresh Codex session may be required to load the Hostinger MCP namespace.

