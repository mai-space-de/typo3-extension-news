#
# Table structure for extension 'mai_news'
#

CREATE TABLE tx_mainews_domain_model_newsarticle (
    title varchar(255) DEFAULT '' NOT NULL,
    teaser text,
    body_text mediumtext,
    author varchar(255) DEFAULT '' NOT NULL,
    publish_date int(11) DEFAULT 0 NOT NULL,
    tags varchar(1024) DEFAULT '' NOT NULL,
    image int(11) DEFAULT 0 NOT NULL,
    categories int(11) DEFAULT 0 NOT NULL,
    slug varchar(2048),
    sorting int(11) unsigned DEFAULT 0 NOT NULL
);

CREATE TABLE tx_mainews_domain_model_newscategory (
    title varchar(255) DEFAULT '' NOT NULL,
    description text,
    slug varchar(2048)
);

CREATE TABLE tx_mainews_article_category_mm (
    uid_local int(11) unsigned DEFAULT 0 NOT NULL,
    uid_foreign int(11) unsigned DEFAULT 0 NOT NULL,
    sorting int(11) unsigned DEFAULT 0 NOT NULL,
    sorting_foreign int(11) unsigned DEFAULT 0 NOT NULL,
    KEY uid_local (uid_local),
    KEY uid_foreign (uid_foreign)
);
