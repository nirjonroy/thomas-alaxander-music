# Publishing API

All publishing API requests use Sanctum bearer-token authentication.

Do not place real tokens in scripts, documents, screenshots, or tickets.

## Standalone Media Upload

Use standalone media when automation needs to upload an image once and reuse it across Blog and Event publishing requests.

Required ability: `publishing:media.upload`.

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/media \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: unique-media-key-001" \
  -H "Accept: application/json" \
  -F "file=@/path/to/portrait.jpg" \
  -F "purpose=blog_featured" \
  -F "alt_text=Thomas Alexander portrait" \
  -F "caption=Promotional portrait"
```

Accepted canonical media files:

- JPEG, PNG, and WEBP only.
- Maximum file size: 10 MB.
- Maximum image dimensions: 8000 px on either side and 64 megapixels total.
- SVG, executable filenames, PHP/HTML/JavaScript files, corrupt images, and extension/content mismatches are rejected.

The API stores canonical media under `uploads/publishing-media/` with a server-generated `publishing-{uuid}` filename. Responses expose relative URLs only; absolute server paths and bearer tokens are never returned.

If a newly uploaded image has the same SHA-256 checksum as an existing active media record, the upload is still accepted and `duplicate_of_media_id` is returned so clients can choose whether to reuse the original media.

## Media Metadata

```bash
curl -X GET \
  https://thomasalexanderthevoice.com/api/v1/publishing/media/MEDIA_UUID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

The metadata response includes the media ID, UUID, purpose, MIME type, extension, dimensions, checksum, public URL, alt text, caption, status, timestamps, and safe attachment references.

## Media Delete

```bash
curl -X DELETE \
  https://thomasalexanderthevoice.com/api/v1/publishing/media/MEDIA_UUID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

Deleting media removes only the canonical API-owned file and its media record. If the media is attached to any Blog or Event, the delete request returns `409 Conflict`. Replacing media on a Blog or Event detaches the old attachment but does not delete the canonical media file.

## Blog Draft

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/blogs \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: unique-blog-key-001" \
  -H "Accept: application/json" \
  -F "title=Example Blog" \
  -F "description=<p>Example content</p>" \
  -F "image=@/path/to/cover.jpg"
```

The create endpoint always creates a draft with `status = 0`. Publishing requires the dedicated publish endpoint.

To reuse standalone media instead of uploading a multipart image directly:

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/blogs \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: unique-blog-media-key-001" \
  -H "Accept: application/json" \
  -F "title=Example Blog" \
  -F "description=<p>Example content</p>" \
  -F "featured_media_id=123" \
  -F "meta_media_id=124"
```

`featured_media_id` accepts media with purpose `blog_featured` or `general`. `meta_media_id` accepts purpose `blog_meta` or `general`. Direct multipart `image` and `meta_image` uploads remain supported and take precedence when present.

## Blog Preview

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/preview/blog \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -F "title=Example Blog" \
  -F "description=<p>Example content</p>"
```

Preview validates and normalizes content, proposes a slug and URL, and reports publish readiness. It does not create or update a Blog.

When `featured_media_id` or `meta_media_id` is supplied to preview, the response includes `proposed_media`. Preview does not create attachment rows and does not derive or copy image files.

## Blog Update

```bash
curl -X PATCH \
  https://thomasalexanderthevoice.com/api/v1/publishing/blogs/123 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -F "title=Updated Example Blog" \
  -F "description=<p>Updated content</p>"
```

## Blog Publish

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/blogs/123/publish \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: publish-blog-123-v1" \
  -H "Accept: application/json"
```

Publishing validates the persisted Blog record before setting `status = 1`.

## Blog Unpublish

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/blogs/123/unpublish \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: unpublish-blog-123-v1" \
  -H "Accept: application/json"
```

Unpublish sets `status = 0`; it does not delete the Blog.

## Event Draft

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/events \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: event-draft-001" \
  -H "Accept: application/json" \
  -F "name=Thomas Alexander Live at Alvin's Jazz Club" \
  -F "description=<p>Live Motown, Soul and Jazz.</p>"
```

The create endpoint always creates an Event draft with `status = 0`. Draft Events may omit `date`, `time`, and `location`; missing date/time values are stored as `NULL`, not placeholder values.

Direct `status`, `id`, timestamp, `start_date`, `end_date`, and `date_range` fields are rejected.

To reuse standalone media for Event images:

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/events \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: unique-event-media-key-001" \
  -H "Accept: application/json" \
  -F "name=Thomas Alexander Live" \
  -F "featured_media_id=123" \
  -F "meta_media_id=124"
```

`featured_media_id` accepts media with purpose `event_featured` or `general`. `meta_media_id` accepts purpose `event_meta` or `general`. Direct multipart `image` and `meta_image` uploads remain supported and take precedence when present.

## Event Preview

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/preview/event \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -F "name=Example Event" \
  -F "description=<p>Preview content</p>"
```

Preview validates and normalizes Event content, proposes a slug and public URL, and reports publish readiness. It does not create or update an Event and does not permanently upload images.

When `featured_media_id` or `meta_media_id` is supplied to preview, the response includes `proposed_media`. Preview does not create attachment rows and does not derive or copy image files.

Example response shape:

```json
{
  "success": true,
  "message": "Event preview validated.",
  "data": {
    "valid": true,
    "publishable": false,
    "slug": "example-event",
    "proposed_url": "https://thomasalexanderthevoice.com/event/example-event",
    "normalized": {
      "name": "Example Event",
      "date": null,
      "time": null
    },
    "publish_errors": {
      "date": ["The event date is required before publishing."],
      "time": ["The event time is required before publishing."],
      "location": ["The event location is required before publishing."]
    }
  }
}
```

## Event Update

```bash
curl -X PATCH \
  https://thomasalexanderthevoice.com/api/v1/publishing/events/123 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -F "date=2026-12-15" \
  -F "time=20:00" \
  -F "location=Edmonton, Alberta"
```

Updating a published Event does not automatically unpublish it. Use the unpublish endpoint for status changes.

## Event Publish

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/events/123/publish \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: publish-event-123-v1" \
  -H "Accept: application/json"
```

Publishing validates the persisted Event record before setting `status = 1`. Required publish fields are `name`, unique `slug`, `date`, `time`, and `location`. Event times are stored without timezone information because the current Event schema stores a single time value only.

## Event Unpublish

```bash
curl -X POST \
  https://thomasalexanderthevoice.com/api/v1/publishing/events/123/unpublish \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: unpublish-event-123-v1" \
  -H "Accept: application/json"
```

Unpublish sets `status = 0`; it does not delete the Event.

## Event Image Uploads

Event `image` uploads accept JPEG, PNG, and WEBP. The API saves compatible Event image variants under the existing `uploads/custom-images` and `uploads/custom-images2` directories and stores the generated filename on the Event record. Event `meta_image` is stored under `uploads/website-images`.

Only API-managed files with generated `publishing-...` names are removed during replacement. Shared or admin-uploaded media is not deleted.

## Event Date Model Limitation

The current Event schema supports one `date` and one `time` per Event. Multi-day events are not represented as date ranges in this API. Automation should create separate Event records or follow the project's existing content convention for multi-day events.

Rollback note: the nullable date/time migration cannot be safely reverted while draft Events have `NULL` in `date` or `time`. Publish or delete/reconcile those drafts before rolling that migration back.

## Final Automation Workflow

Recommended server-to-server publishing sequence:

1. Verify API authentication with `GET /api/v1/publishing/me`.
2. Upload media with `POST /api/v1/publishing/media`.
3. Preview Blog or Event content.
4. Create a draft Blog or Event.
5. Read and review the created draft.
6. Publish explicitly with the publish endpoint.
7. Use the returned public URL.

`POST /blogs` and `POST /events` always create drafts. They do not publish directly.

## Request ID

Publishing API responses include a `request_id` field and an `X-Request-ID` response header. Automation may send a valid `X-Request-ID` header for correlation. If it is missing or invalid, the API generates a UUID.

Use the request ID when matching automation failures to application logs and publishing audit rows.

## Full Publishing Token Preset

Recommended automation abilities:

```text
publishing:blogs.read
publishing:blogs.write
publishing:blogs.publish
publishing:events.read
publishing:events.write
publishing:events.publish
publishing:media.upload
```

Create a full publishing token:

```bash
php artisan publishing:token \
  --email=publishing@example.com \
  --create-admin \
  --preset=full-publishing \
  --expires-in-days=90
```

The preset maps only to the abilities listed above. It does not grant wildcard `*`.

Token lifecycle commands:

```bash
php artisan publishing:token-list
php artisan publishing:token-revoke {token-id}
php artisan publishing:token-revoke {token-id} --force
php artisan publishing:token-rotate {token-id} --expires-in-days=90
php artisan publishing:token-rotate {token-id} --revoke-old
```

Existing plaintext tokens are never displayed. A replacement token is displayed once during rotation so it can be saved in the automation platform secret store.

Sanctum `last_used_at` is available in the installed Sanctum version and is shown by `publishing:token-list` when populated.

## Complete Blog Workflow Example

Health/auth check:

```bash
curl -X GET https://thomasalexanderthevoice.com/api/v1/publishing/me \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

Upload image:

```bash
curl -X POST https://thomasalexanderthevoice.com/api/v1/publishing/media \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: media-job-123-image-v1" \
  -H "Accept: application/json" \
  -F "file=@/secure/local/path/image.jpg" \
  -F "purpose=blog_featured"
```

Use the returned `data.id` as `featured_media_id`.

Preview Blog:

```bash
curl -X POST https://thomasalexanderthevoice.com/api/v1/publishing/preview/blog \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -d '{
    "title": "Opening to Abundance: Daily Affirmations for Healing, Connection, and Growth",
    "description": "<p>...</p>",
    "featured_media_id": 123,
    "seo_title": "Opening to Abundance | Thomas Alexander",
    "seo_description": "Transform your mindset with affirmations...",
    "seo_keywords": "Thomas Alexander, affirmations, healing, abundance",
    "seo_author": "Thomas Alexander",
    "seo_publisher": "Thomas Alexander - The Voice"
  }'
```

Create Blog draft:

```bash
curl -X POST https://thomasalexanderthevoice.com/api/v1/publishing/blogs \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: blog-job-123-create-v1" \
  -H "Accept: application/json" \
  -d '{ "...": "same reviewed payload" }'
```

Read created Blog:

```bash
curl -X GET https://thomasalexanderthevoice.com/api/v1/publishing/blogs/123 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

Publish Blog:

```bash
curl -X POST https://thomasalexanderthevoice.com/api/v1/publishing/blogs/123/publish \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: blog-123-publish-v1" \
  -H "Accept: application/json"
```

The publish response includes `data.url`.

## Complete Event Workflow Example

Example Event payload:

```json
{
  "name": "Thomas Alexander Live at Alvin's Jazz Club",
  "date": "2026-08-28",
  "time": "19:00",
  "location": "176 Mahogany Center SE, Calgary",
  "description": "<p>Join Thomas Alexander for an evening of Motown, Soul, and Jazz.</p>",
  "featured_media_id": 456,
  "seo_title": "Thomas Alexander Live at Alvin's Jazz Club",
  "seo_description": "Thomas Alexander performs live at Alvin's Jazz Club in Calgary."
}
```

One Event record currently supports one date/time.

Workflow:

```bash
curl -X POST https://thomasalexanderthevoice.com/api/v1/publishing/preview/event \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" \
  -d '{ "...": "event payload" }'

curl -X POST https://thomasalexanderthevoice.com/api/v1/publishing/events \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: event-job-456-create-v1" \
  -H "Accept: application/json" \
  -d '{ "...": "event payload" }'

curl -X GET https://thomasalexanderthevoice.com/api/v1/publishing/events/456 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

curl -X POST https://thomasalexanderthevoice.com/api/v1/publishing/events/456/publish \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Idempotency-Key: event-456-publish-v1" \
  -H "Accept: application/json"
```

## Idempotency Strategy

Recommended key examples:

- Media: `media-{external-job-id}-{image-version}`
- Blog creation: `blog-{external-job-id}-create-v1`
- Blog publish: `blog-{blog-id}-publish-v1`
- Event create: `event-{external-job-id}-create-v1`
- Event publish: `event-{event-id}-publish-v1`

Never casually reuse the same idempotency key with changed content. Changed content should use a new version/key. If a network timeout happens after create or publish, retry the same request with the same `Idempotency-Key` so the API can replay the completed response.

## Automation Failure Recovery

Media uploaded, Blog/Event create fails:

- The media remains available.
- Fix validation errors.
- Retry creation with the correct idempotent request.
- Do not re-upload the image unless the image changed.

Draft created, publish fails validation:

- Patch the draft.
- Preview again.
- Publish again with a new publish idempotency key if request semantics changed.

Network timeout after create or publish:

- Retry the same request with the same `Idempotency-Key`.

## Secret Handling

The bearer token must be stored in the automation platform's secret or credential manager.

Do not store tokens in JavaScript frontend code, public repositories, Blade files, database content fields, request URLs, screenshots, documentation, WhatsApp/chat messages, or tickets.

Use `Authorization: Bearer TOKEN` over HTTPS only.

## CORS Note

The current project has broad `api/*` CORS behavior. The Publishing API is intended for server-to-server use. CORS is not an authentication boundary; bearer token authentication and Sanctum abilities remain the security boundary.

## Operational Commands

Safe smoke test:

```bash
php artisan publishing:smoke-test
```

This checks configuration, tables, routes, storage, and services without creating content.

Explicit temporary content test:

```bash
php artisan publishing:smoke-test --run-content-test
```

This creates clearly named temporary Blog/Event drafts, publishes and unpublishes them, verifies public URL generation, and removes temporary API-owned files. Do not run it automatically during deployment.

Expired idempotency cleanup:

```bash
php artisan publishing:idempotency-cleanup --dry-run
php artisan publishing:idempotency-cleanup --force
```

Recommended schedule: once daily. The command defaults to dry-run, deletes only expired records when `--force` is supplied, and never truncates the table.

Media orphan report:

```bash
php artisan publishing:media-orphans
```

Default behavior is report only. Optional cleanup removes only unattached canonical API-owned media after confirmation:

```bash
php artisan publishing:media-orphans --cleanup
```

Audit review:

```bash
php artisan publishing:audit --limit=50
php artisan publishing:audit --type=blog
php artisan publishing:audit --type=event
php artisan publishing:audit --type=media
php artisan publishing:audit --action=blog.published
```

Audit output never displays bearer tokens or Authorization headers.

## Scheduled Publishing Finding

Blog currently uses `status` for publication state and does not have a reliable scheduled publication column in this API.

Event supports future event dates through `date` and `time`, but that is event timing, not delayed publication. Scheduled publishing should be a future step with explicit schema and workflow additions.

## Production Setup Checklist

1. Deploy code.
2. Run migrations.
3. Clear and rebuild Laravel caches as appropriate.
4. Create the Publishing Automation service account.
5. Create a `full-publishing` token.
6. Set a 90-day expiration.
7. Save the token in the external automation secret manager.
8. Call `/api/v1/publishing/health` or `/api/v1/publishing/me`.
9. Perform draft-only smoke test.
10. Verify audit log.
11. Test media upload.
12. Create a real draft.
13. Manually review the draft.
14. Publish explicitly.
15. Verify live URL.
16. Schedule token rotation before expiry.
