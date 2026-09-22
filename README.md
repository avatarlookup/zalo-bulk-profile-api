# Zalo Bulk Number Profile — `zalo_profile_batch` | AvatarLookup

Bulk Zalo number profile: the avatar plus nickname, account id and a set of appearance attributes the provider infers from the picture — gender, age band, skin tone, avatar type and how many people the picture shows. Pick the country the numbers belong to when you upload.

This is the official AvatarLookup example repository for **one** bulk product, `zalo_profile_batch`. It is an asynchronous task: upload a file, get a task id immediately, poll the task, and download the result file when it finishes.

- **Product page:** https://avatarlookup.com/products/zalo_profile_batch
- **API documentation:** https://avatarlookup.com/api-docs
- **API base URL:** `https://avatarlookup.com`
- **Authentication:** `X-API-Key`
- **Get an API key:** https://avatarlookup.com/register

## What is it usually used for?

- List segmentation
- Contact data enrichment
- Audience review

## What does the result file contain?

| Column | Example | Meaning |
|---|---|---|
| `number` | `84912345678` | The submitted number, exactly as it appeared in your file. |
| `activated` | `yes` | Whether the number is registered on Zalo. |
| `uid` | `440975967` | Telegram user id. |
| `nickname` | `Minh Anh` | Public display name on the profile. |
| `avatar` | `https://s160-26-ava-talk.zadn.vn/example.jpg` | Avatar URL. |
| `avatar_type` | `Single Person` | What the avatar picture appears to contain, e.g. a single person. |
| `person_count` | `1` | How many people were detected in the avatar. |
| `gender` | `female` | Gender estimated from the avatar. |
| `age` | `28` | Age band estimated from the avatar. |
| `skin_color` | `asian` | Skin tone estimated from the avatar. |

The result is a **point-in-time signal**, not a verdict, and not identity data. It describes what the provider reported at the moment the task ran.

## How do I submit a task?

Upload a `.txt` or `.csv` with **one phone number per line**, 1,000–100,000 valid entries. A number list must come from a **single country**, given as `country` — the upstream uses it to expand bare national numbers.

```bash
curl -X POST 'https://avatarlookup.com/api/v1/bulk-tasks' \
  -H 'X-API-Key: YOUR_API_KEY' \
  -F 'product=zalo_profile_batch' \
  -F 'country=US' \
  -F 'file=@numbers.txt'
```

The response returns the task id and `status=processing`, along with the server-side quote taken before processing starts.

## How do I poll a task and download the result?

```bash
curl -fsS 'https://avatarlookup.com/api/v1/bulk-tasks/TASK_ID' -H 'X-API-Key: YOUR_API_KEY'
```

`status` is `processing`, `success` or `failed` — there is no progress percentage to poll for. **Do not poll more often than once every 30 seconds.** When the task succeeds the response carries the result-file download link.

## Bulk task or realtime check?

`POST /api/v1/bulk-tasks` (this repository) takes a file of 1,000–100,000 entries and answers later — that is the shape for list cleaning, campaign preparation and enrichment runs. A **realtime** check (`POST /api/v1/check`, or `POST /api/v1/batch-check` for up to 100 identifiers) answers inside the same HTTP response, for a signup form or a live lookup. The two are separate endpoints and are not interchangeable; see the other repositories under [avatarlookup](https://github.com/avatarlookup).

## What about billing?

The whole file is reserved on submit. When the task finishes you are charged only for the entries that were actually checked and the remainder is returned. A failed task is refunded in full.

## What are the limits and error codes?

| Limit | Value |
|---|---|
| Entries per task | 1,000–100,000 |
| File formats | `.txt`, `.csv`, one entry per line |
| Products per task | 1 (`zalo_profile_batch`) |
| Country | required, exactly one per task |
| Poll interval | no more than once per 30 seconds |

`40000`/`40001` are request or file errors; `40100` is an invalid key; `40200` is insufficient balance; `42900` is rate limited — honour `Retry-After`; `50300` is temporary maintenance. `GET /api/v1/balance` returns the current balance in `data.balance_micros`.

Keep the key on a trusted server and read it from `AVATARLOOKUP_API_KEY`. Never commit it or expose it in production browser code.

## Runnable examples in seven languages

| Language | Example |
|---|---|
| Python | [`examples/python`](examples/python) |
| Node.js | [`examples/nodejs`](examples/nodejs) |
| Go | [`examples/go`](examples/go) |
| Java | [`examples/java`](examples/java) |
| C# | [`examples/csharp`](examples/csharp) |
| PHP | [`examples/php`](examples/php) |
| Shell / curl | [`examples/shell`](examples/shell) |

## Official resources and responsible use

- **This product:** https://avatarlookup.com/products/zalo_profile_batch
- **All products:** https://avatarlookup.com/products
- **Documentation:** https://avatarlookup.com/api-docs
- **Registration:** https://avatarlookup.com/register
- **Pricing:** https://avatarlookup.com/pricing
- **OpenAPI contract:** [`openapi.yaml`](openapi.yaml)
- **License:** [MIT](LICENSE) for the sample code

Submit only data you are authorized to process, and comply with applicable privacy laws and platform terms. Report the signal you received as a signal; do not relabel it downstream as a conclusion it does not support.

---

*Last reviewed: 2026-09-22 · Maintained by AvatarLookup. Canonical documentation: https://avatarlookup.com/api-docs*
