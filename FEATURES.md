# mai_news — Feature Reference

## 1. News Record

Table `tx_mainews_news`. All fields below are part of the domain model
`Maispace\MaiNews\Domain\Model\News`.

| Field | Type | Required | Notes |
| --- | --- | --- | --- |
| `title` | varchar(255) | yes | Plain text, trimmed. Used as the article heading. |
| `teaser` | text | no | Short summary. Rendered in the list view and as the RSS `<description>`. |
| `body` | mediumtext | no | Rich-text body. Rendered with `f:format.html` (RTE-processed HTML). |
| `date` | int(11) UNIX ts | yes | Publication date. Default sort order: `date DESC`. |
| `images` | FAL relation | no | Multiple images. List shows only the first; detail shows all. |
| `categories` | sys_category MM | no | Zero or more `sys_category` records. |
| `tags` | tx_mainews_tag MM | no | Zero or more free-form tags (separate table). |
| `slug` | varchar(2048) | no | Auto-generated URL segment. Not yet exposed in controller routes. |

---

## 2. sys_category Integration

`mai_news` uses `sys_category` for category assignment — the TYPO3 built-in category
system. No custom category table is created or used.

- TCA field type: `category` (TYPO3 native), relationship `manyToMany`.
- Relations stored in `sys_category_record_mm` (no custom MM table).
- The same shared `sys_category` tree is used by `mai_faq`, `mai_gallery`,
  `mai_testimonials`, and `mai_timeline` — do **not** add a custom category table
  for record-based extensions.

**Architecture rule:** adding a `tx_mainews_category` (or similar) table is
explicitly forbidden.

---

## 3. Tags

Tags are free-form labels stored in the extension-owned `tx_mainews_tag` table.
Unlike categories, tags are not shared across extensions.

- Domain model: `Maispace\MaiNews\Domain\Model\Tag` — single `name` field.
- Repository: `Maispace\MaiNews\Domain\Repository\TagRepository` — inherits
  `findAll()`, no custom ordering.
- The MM join table `tx_mainews_news_tag_mm` owns the relationship.
- Tags can be filtered in the list plugin via the FlexForm `settings.tagUid` field.

---

## 4. Content Element Plugins

Two CTypes are registered:

| CType | Plugin identifier | Action | Backend group |
| --- | --- | --- | --- |
| `maispace_news_list` | `tx_maijobs_list` | `list`, `detail` | `maispace_feature` |
| `maispace_news_rss` | `tx_mainews_rss` | `rss` | `maispace_feature` |

Both CTypes include a default header palette, a language tab, and an access tab.
Each has its own FlexForm (see section 7).

---

## 5. Frontend Rendering

### 5.1 `listAction` — News list with pagination

Template: `News/List.html`

| Template variable | Value |
| --- | --- |
| `news` | Full `QueryResultInterface` (unfiltered) |
| `paginator` | `ArrayPaginator` output (`.paginatedItems` is the page slice) |
| `pagination` | Metadata: `numberOfPages`, `previousPageNumber`, `nextPageNumber` |
| `settings` | Raw FlexForm settings array |

**Query priority** (first matching branch wins):

1. Storage pages + category UID → `findFromPagesByCategoryUid()`
2. Storage pages + tag UID → `findFromPagesByTagUid()`
3. Storage pages only → `findFromPages()`
4. Category UID only → `findByCategoryUid()`
5. Tag UID only → `findByTagUid()`
6. No filters → `findAll()`

Results are always ordered `date DESC` (set in `NewsRepository::$defaultOrderings`).

Pagination is driven by `currentPage` GET parameter. Items per page is controlled by
`settings.itemsPerPage` (default 10).

### 5.2 `detailAction` — Single article

Template: `News/Detail.html`

| Template variable | Value |
| --- | --- |
| `news` | Single `News` entity resolved from `news` GET/POST argument |
| `settings` | Raw FlexForm settings array |

The detail action is registered on the **list plugin** (`maispace_news_list`), not a
separate plugin. It is reached via `f:link.action` with `action="detail"` and
`pageUid="{settings.detailPid}"` pointing to the detail page.

A "Back to list" link uses `f:link.action action="list"` without a `pageUid`.

---

## 6. RSS 2.0 Feed

The RSS feed is implemented as a dedicated content element (`maispace_news_rss`) with
its own Fluid template and an explicit `Content-Type` response header.

### 6.1 HTTP response

```
Content-Type: application/rss+xml; charset=utf-8
```

### 6.2 Channel metadata

The RSS `<channel>` block contains:

| RSS element | Source |
| --- | --- |
| `<title>` | Translation key `news.rss.title` (default: "News") |
| `<link>` | `f:uri.page absolute="1"` — URL of the page hosting the RSS plugin |
| `<description>` | Translation key `news.rss.description` (default: "Latest news") |
| `<language>` | `settings.language` (FlexForm), falls back to `'de'` if empty |

### 6.3 Item elements

Each `<item>` contains:

| RSS element | Source |
| --- | --- |
| `<title>` | `{newsItem.title}` |
| `<link>` | `f:uri.action action="detail" pageUid="{settings.detailPid}"` (absolute) |
| `<description>` | `{newsItem.teaser}` (plain text) |
| `<pubDate>` | `{newsItem.date}` formatted as RFC 2822 (`f:format.date format="r"`) — only rendered if `newsItem.date` is set |
| `<guid isPermaLink="true">` | Same absolute URL as `<link>` |

### 6.4 Query logic

The `rssAction()` queries:

1. Storage pages defined in FlexForm → `newsRepository->findFromPages($pageUids)`
2. No storage pages → `newsRepository->findForRss($limit)` — respects `settings.limit`
   (default 20)

Results always sorted `date DESC`.

### 6.5 Configuration

To wire up the RSS feed:

1. Add the `maispace_news_rss` content element to a dedicated page (e.g. `/news.rss`).
2. Set the page's `HTTP Response Header` TypoScript or rely on the automatic
   `application/rss+xml` response header returned by `rssAction()`.
3. Set `settings.detailPid` in the FlexForm to the page that hosts the
   `maispace_news_list` plugin — this builds the absolute `<link>` / `<guid>` URLs.
4. Optionally set `settings.pages` to restrict items to specific storage folders.
5. Optionally set `settings.limit` (default 20) to control the item count.

---

## 7. FlexForm Configuration

### News List plugin (`maispace_news_list`)

| Field | Settings key | Type | Default | Notes |
| --- | --- | --- | --- | --- |
| Storage pages | `settings.pages` | group (pages) | — | Up to 20 page UIDs; restricts query scope |
| Category filter | `settings.categoryUid` | category (many-to-many) | — | Filters by `sys_category` UID |
| Tag filter | `settings.tagUid` | select (single) | 0 | Selects from `tx_mainews_tag` |
| Items per page | `settings.limit` | number | 10 | Maximum items per pagination page |
| Detail page | `settings.detailPid` | group (pages, 1 max) | — | Page UID for `detailAction` links |

### RSS plugin (`maispace_news_rss`)

| Field | Settings key | Type | Default | Notes |
| --- | --- | --- | --- | --- |
| Storage pages | `settings.pages` | group (pages) | — | Up to 20 page UIDs; restricts query scope |
| Item limit | `settings.limit` | number | 20 | Max items returned by `findForRss()` |

---

## 8. TypoScript Configuration

### Constants block

```typoscript
plugin {
    tx_mainews {
        view {
            templateRootPath = EXT:mai_news/Resources/Private/Templates/
            partialRootPath  = EXT:mai_news/Resources/Private/Partials/
            layoutRootPath   = EXT:mai_news/Resources/Private/Layouts/
        }
    }
    tx_mainews_list {
        view {
            templateRootPath =    # override — inherits tx_mainews base at priority 0
            partialRootPath  =
            layoutRootPath   =
        }
        persistence {
            storagePid =          # default storage page for the list plugin
        }
    }
    tx_mainews_rss {
        view {
            templateRootPath =    # override — inherits tx_mainews base at priority 0
            partialRootPath  =
            layoutRootPath   =
        }
        persistence {
            storagePid =          # default storage page for the RSS plugin
        }
    }
}
```

### Setup defaults (from setup.typoscript)

```typoscript
plugin.tx_mainews_list.settings {
    limit       = 10     # pagination page size
    itemsPerPage = 10
    detailPid   =        # page UID for detail view links
}

plugin.tx_mainews_rss.settings {
    limit = 20           # max feed items
}
```

---

## 9. Database Tables

### `tx_mainews_news`

| Column | Type | Notes |
| --- | --- | --- |
| `title` | varchar(255) NOT NULL | Default '' |
| `teaser` | text NOT NULL | Default '' |
| `body` | mediumtext | Nullable |
| `date` | int(11) NOT NULL | UNIX timestamp. Default 0. |
| `images` | int(11) unsigned NOT NULL | FAL relation count. Default 0. |
| `categories` | int(11) unsigned NOT NULL | MM count. Default 0. |
| `tags` | int(11) unsigned NOT NULL | MM count. Default 0. |
| `slug` | varchar(2048) NOT NULL | Default '' |

TYPO3 system columns (`uid`, `pid`, `deleted`, `hidden`, `crdate`, `tstamp`,
`sys_language_uid`, `l18n_parent`, `l18n_diffsource`, `starttime`, `endtime`) are
added automatically by `setDefaultConfig()`.

Default sort: `ORDER BY date DESC`.

### `tx_mainews_tag`

| Column | Type | Notes |
| --- | --- | --- |
| `name` | varchar(255) NOT NULL | Unique, trimmed. Default '' |

TYPO3 system columns added automatically. Supports manual sorting (`sorting` field).

### `tx_mainews_news_tag_mm`

| Column | Type | Notes |
| --- | --- | --- |
| `uid_local` | int(11) unsigned | News UID |
| `uid_foreign` | int(11) unsigned | Tag UID |
| `sorting` | int(11) unsigned | Tag order on a news item |
| `sorting_foreign` | int(11) unsigned | News order under a tag |

No TYPO3 system columns — pure MM join table.

---

## 10. Architecture Constraints

- **No custom category table.** Use `sys_category` for all categorisation needs.
- **No mail dispatch.** If job-related notifications are ever added, delegate to
  `mai_mail` — do not import `symfony/mailer` here.
- **Tags are extension-local.** `tx_mainews_tag` is not shared; do not reference it
  from other extensions.
- **RSS is content-element-based.** There is no standalone route or middleware for the
  feed. Place the `maispace_news_rss` content element on a dedicated page and set the
  desired URL via site routing.
- **Detail page is configured per plugin.** The `settings.detailPid` FlexForm field
  must point to the page hosting `maispace_news_list`; without it, all detail and RSS
  item links are broken.
