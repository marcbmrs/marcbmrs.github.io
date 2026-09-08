# Tool Reference: Hostinger Deploy Connector

## Purpose

Publish approved Marcos Dev project changes to Hostinger production environments.

## Supported Interfaces

- Hostinger remote MCP server: `https://mcp.hostinger.com`
- Hostinger API MCP server: `hostinger-api-mcp`
- Hostinger API: `https://developers.hostinger.com/`
- SFTP/SSH fallback when API or MCP cannot safely update the needed files.

## Private Configuration

Credentials, tokens, OAuth sessions, SFTP passwords, SSH keys, domain ownership details, server paths, and account identifiers must stay outside this public repository.

Preferred private locations:

```text
C:\KORA\.env
C:\KORA\private\hostinger\
%APPDATA%\hostinger-mcp\
```

Do not store Hostinger API tokens, FTP/SFTP passwords, SSH keys, database credentials, or client production secrets in `C:\marcbmrs.github.io`.

## Default Mode

Supervised write access.

Allowed after explicit user approval:

- list Hostinger websites/accounts available to Marcos;
- map a local project folder to a production domain and remote path;
- upload specific changed files to the approved remote path;
- deploy an approved static archive when the overwrite behavior is intended;
- verify public URLs after deployment;
- record deployment notes in local KORA when useful.

Not allowed by default:

- no automatic deploy after every local edit;
- no DNS, email, billing, subscription, ecommerce, database, or delete actions unless Marcos explicitly asks for that exact operation;
- no destructive website deletion;
- no overwriting an entire production site when only a file-level update is intended;
- no credential storage in the public repository.

## Official References

- Hostinger API MCP Server: https://www.hostinger.com/support/11079316-hostinger-api-mcp-server/
- Hostinger API Reference: https://developers.hostinger.com/
- Hostinger SFTP support: https://www.hostinger.com/support/5972689-how-to-connect-to-your-hosting-using-sftp-in-hostinger/
