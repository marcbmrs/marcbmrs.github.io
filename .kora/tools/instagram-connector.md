# Tool: Instagram Connector

## Purpose

Read authorized Instagram profile and media information for Marcos Dev through the KORA-owned private runtime connector.

## Owner

Current executable:

```text
C:\KORA\runtime\tools\instagram-connector\
```

## Local Configuration

Current private secrets location:

```text
C:\KORA\.env
```

Expected Marcos Dev variables:

```dotenv
INSTAGRAM_MARCOS_DEV_ACCESS_TOKEN=<token-de-acesso>
INSTAGRAM_MARCOS_DEV_ACCOUNT_ID=<id-da-conta-profissional>
```

Do not copy, expose, commit, or persist token values, account IDs, raw API responses, or private account data in this repository. Secrets are stored in the approved private KORA environment file, never in `.kora/`.

## Approved Read Commands

Current commands, run from `C:\KORA`:

```powershell
py -3 .\runtime\tools\instagram-connector\health_check.py --profile marcos-dev
py -3 .\runtime\tools\instagram-connector\read_media.py --profile marcos-dev --limit 12
```

The connector is read-only. It does not publish content, send messages, respond to comments, alter profile data, download media, or create recurring sync.

## Verified Local State

On 2026-09-05, the KORA runtime `health_check.py --profile marcos-dev` returned authorized read access with scope `leitura_minima_de_perfil`.

On the same date, the KORA runtime `read_media.py --profile marcos-dev --limit 3` returned sanitized post data successfully.

## Limits

Current media output includes basic post fields only: type, format, published timestamp, caption excerpt, permalink, like count, and comment count.

Insights such as reach, impressions, saves, shares, follower demographics, profile visits, and ad-related data require separate confirmation of endpoint support and permissions before implementation.
## External Documentation Checked

Checked on 2026-09-05:

- Meta Instagram API collection: https://www.postman.com/meta/instagram/documentation/6yqw8pt/instagram-api
- Meta Instagram Insights collection: https://www.postman.com/meta/instagram/folder/23987686-f659d7d1-d74c-44e4-9192-9b1e8694c511
- Meta permissions reference: https://developers.facebook.com/docs/permissions

Operational interpretation: keep the current connector read-only for profile/media reads. Add insights only after confirming current endpoint support and requesting the minimum required permission for the chosen login path.

## Migration Status

Status: migrated to KORA private runtime on 2026-09-05.

Legacy MarcosOS connector is no longer required for Marcos Dev Instagram profile/media reads on this machine, provided `C:\KORA\.env` remains available.
