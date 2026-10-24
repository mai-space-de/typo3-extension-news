CREATE TABLE tx_mainews_tag (
    name varchar(255) NOT NULL DEFAULT ''
);

CREATE TABLE tx_mainews_pages_tag_mm (
    uid_local int(11) unsigned NOT NULL DEFAULT 0,
    uid_foreign int(11) unsigned NOT NULL DEFAULT 0,
    sorting int(11) unsigned NOT NULL DEFAULT 0,
    sorting_foreign int(11) unsigned NOT NULL DEFAULT 0
);

# News doktype (120) metadata on pages — Content BGM-20
CREATE TABLE pages (
    tx_mainews_date int(11) DEFAULT 0 NOT NULL,
    tx_mainews_teaser text,
    tx_mainews_images int(11) unsigned DEFAULT '0' NOT NULL,
    tx_mainews_tags int(11) unsigned DEFAULT '0' NOT NULL,
    tx_mainews_legacy_news_uid int(11) DEFAULT 0 NOT NULL
);
