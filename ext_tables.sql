CREATE TABLE tx_mainews_news (
    title varchar(255) NOT NULL DEFAULT '',
    teaser text NOT NULL DEFAULT '',
    body mediumtext,
    date int(11) NOT NULL DEFAULT 0,
    images int(11) unsigned NOT NULL DEFAULT 0,
    categories int(11) unsigned NOT NULL DEFAULT 0,
    tags int(11) unsigned NOT NULL DEFAULT 0,
    slug varchar(2048) NOT NULL DEFAULT ''
);

CREATE TABLE tx_mainews_tag (
    name varchar(255) NOT NULL DEFAULT ''
);

CREATE TABLE tx_mainews_news_tag_mm (
    uid_local int(11) unsigned NOT NULL DEFAULT 0,
    uid_foreign int(11) unsigned NOT NULL DEFAULT 0,
    sorting int(11) unsigned NOT NULL DEFAULT 0,
    sorting_foreign int(11) unsigned NOT NULL DEFAULT 0
);
