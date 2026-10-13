# Hostinger Production Map

This file records Marcos Dev project-to-Hostinger production mappings that are safe to keep in the repository.

Do not store Hostinger credentials, tokens, account IDs, SFTP passwords, SSH keys, database credentials, private server paths that reveal sensitive account details, or client private data here.

## Known Production Projects

### Renan Reformas

- Local folder: `projetos/renan-reformas/`
- Production status: live on Hostinger, confirmed by Marcos.
- Production domain: `renanreformas.com.br`
- Public URL: `https://renanreformas.com.br/`
- Remote public path: Hostinger public web root for `renanreformas.com.br`; exact account path intentionally not stored here.
- Panel path: `https://renanreformas.com.br/painel/`
- Default deploy method: supervised file-level upload after approval.
- Hostinger MCP check: `hosting_listWebsitesV1` found the domain active on 2026-09-06; detected website type is `other`.
- DNS snapshots: Hostinger returned restore points from 2026-08-30, including CDN enablement.
- Notes: legacy `entrega-hostinger/` package was removed because production is updated directly from changed project files. Keep `painel/app/config.local.php` and any production credentials out of Git and out of deployment packages.

### Despachante Vicente

- Local folder: `projetos/despachante-vicente/`
- Production status: live on Hostinger; read access and a focused report upload verified on 19/09/2026.
- Production domain: `despachantevicente.com.br`
- Public URL: `https://despachantevicente.com.br/`
- Panel path: `https://despachantevicente.com.br/painel/`
- Report storage: protected panel storage, where PDF reports are discovered automatically by the panel; exact server path intentionally not stored here.
- Default deploy method: supervised file-level upload of a validated PDF only, after explicit approval. Do not deploy a static archive or alter the panel code for routine reporting.
- Notes: report PDFs use the filename pattern `relatorio-trafego-pago-despachante-vicente-AAAA-MM-DD-a-AAAA-MM-DD.pdf`. The first verified publication was the period 07/09/2026–13/09/2026, which appeared with the two prior reports and preserved the authenticated panel login.

## Mapping Template

```text
### Project Name

- Local folder:
- Production status:
- Production domain:
- Remote public path:
- App/panel path:
- Default deploy method:
- Notes:
```


