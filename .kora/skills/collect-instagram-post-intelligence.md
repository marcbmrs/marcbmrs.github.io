# Skill: Collect Instagram Post Intelligence

## Purpose

Collect and analyze authorized Instagram post information for Marcos Dev without publishing, messaging, comment management, or profile changes.

Use this skill when Marcos asks to read the Marcos Dev Instagram account, inspect recent posts, summarize content patterns, compare formats, prepare editorial recommendations, or use Instagram evidence in a Marcos Dev content/offer decision.

Do not use this skill for potential clients or third-party profiles. For third-party profiles, use only public evidence provided by the user and do not use Marcos Dev credentials.

## Required Local Tool

Use the KORA-owned private runtime connector documented at:

```text
C:\KORA\runtime\tools\instagram-connector\README.md
```

The connector reads secrets from `C:\KORA\.env`, an approved private local environment file ignored by Git. Secrets must remain outside this public repository and must never be printed, copied into chat, committed, or summarized.

Current commands, from `C:\KORA`:

```powershell
py -3 .\runtime\tools\instagram-connector\health_check.py --profile marcos-dev
py -3 .\runtime\tools\instagram-connector\read_media.py --profile marcos-dev --limit 12
```

## Current Verified Capability

As of 2026-09-05, the local connector returned:

```json
{"profile":"marcos-dev","authorized":true,"scope":"leitura_minima_de_perfil"}
```

A test read of 5 media items returned sanitized fields: `type`, `format`, `published_at`, `caption`, `caption_truncated`, `permalink`, `likes`, and `comments`.

## MarcosOS Decommissioning

This skill no longer depends on MarcosOS for Marcos Dev profile/media reads on this machine. If the KORA runtime or `C:\KORA\.env` is missing, report that the private KORA connector is not configured, then use any already-provided safe evidence instead of scraping Instagram or recovering secrets.

## Workflow

1. Confirm the requested profile is `marcos-dev` unless Marcos explicitly authorizes a different owned profile with its own isolated configuration.
2. Run the health check before media reads when the current session has not already verified authorization.
3. Read only the minimum number of posts needed for the question. Use a small limit for diagnosis and a larger limit only when trend analysis requires it.
4. Treat returned data as operational evidence, not as a complete analytics warehouse. Captions may be truncated by the connector.
5. Analyze by content theme, post format, date cadence, CTA, proof, offer clarity, audience fit, engagement signals, and reuse opportunities.
6. Separate facts from interpretation. Do not infer reach, saves, shares, followers, profile visits, ad impact, or algorithm behavior unless those fields are actually available from an authorized insights query.
7. Do not publish, delete, comment, reply to messages, download media files, scrape pages, or create recurring sync without explicit approval and a separate tool/policy.
8. Under KORA level 7, persist only durable learning: repeated content patterns, approved editorial decisions, capability gaps, or formal eval results. Do not persist raw post dumps by default.

## Meta API Notes

The current local connector uses the Instagram API with Instagram Login host `graph.instagram.com` and API version `v26.0` by default.

For read-only profile/media access, the minimum local configuration is:

```dotenv
INSTAGRAM_MARCOS_DEV_ACCESS_TOKEN=<token-de-acesso>
INSTAGRAM_MARCOS_DEV_ACCOUNT_ID=<id-da-conta-profissional>
```

For broader insights, verify the current official Meta documentation before implementation. The permission commonly associated with Instagram Login insights is `instagram_business_manage_insights`; the Facebook Login path uses different permissions such as `instagram_basic`, `instagram_manage_insights`, and `pages_read_engagement`.

## Output

Return a concise Instagram intelligence note with:

- scope and retrieval date;
- number of posts inspected;
- source command or connector used, without secrets;
- observed facts;
- strategic/content interpretation;
- recommended next actions;
- limitations and whether a KORA level 7 record was saved.
## External Documentation Checked

Checked on 2026-09-05:

- Meta Instagram API collection: https://www.postman.com/meta/instagram/documentation/6yqw8pt/instagram-api
- Meta Instagram Insights collection: https://www.postman.com/meta/instagram/folder/23987686-f659d7d1-d74c-44e4-9192-9b1e8694c511
- Meta permissions reference: https://developers.facebook.com/docs/permissions

Operational interpretation: keep the current connector read-only for profile/media reads. Add insights only after confirming current endpoint support and requesting the minimum required permission for the chosen login path.