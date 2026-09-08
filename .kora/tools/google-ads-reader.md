# Tool Reference: Google Ads Reader

## Purpose

Read authorized Google Ads data for Marcos Dev campaign intelligence and proposal drafting.

## Runtime

```text
C:\KORA\runtime\tools\google-ads-reader\generate_proposal.ps1
```

## Private Configuration

```text
C:\KORA\.env
C:\KORA\private\google-ads\google-ads-reader.json
```

The public repository must not contain developer tokens, account IDs, customer IDs, service account keys, raw reports, or exported private campaign data.

## Default Mode

Read-only.

Allowed:

- check access configuration;
- query campaign metrics using Google Ads API search/read endpoints;
- generate local proposal Markdown in KORA runtime;
- summarize findings for Marcos.

Not allowed:

- no Google Ads `:mutate` endpoints;
- no campaign/ad/budget/bid/keyword/targeting/conversion changes;
- no automated execution;
- no publishing or account modifications without explicit approval.

## Related KORA Core Records

- `C:\KORA\tools\google-ads-read-connector.md`
- `C:\KORA\integrations\google-ads-api.md`
