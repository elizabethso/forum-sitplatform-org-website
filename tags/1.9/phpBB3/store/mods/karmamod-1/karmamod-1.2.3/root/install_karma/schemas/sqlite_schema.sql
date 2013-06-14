#
# $Id: sqlite_schema.sql,v 1.5 2009/05/07 16:01:05 m157y Exp $
#

BEGIN TRANSACTION;

# Table: 'phpbb_karma'
CREATE TABLE phpbb_karma (
	karma_id INTEGER PRIMARY KEY NOT NULL,
	forum_id INTEGER UNSIGNED DEFAULT '0' NOT NULL,
	topic_id INTEGER UNSIGNED DEFAULT '0' NOT NULL,
	post_id INTEGER UNSIGNED DEFAULT '0' NOT NULL,
	user_id INTEGER UNSIGNED DEFAULT '0' NOT NULL,
	poster_id INTEGER UNSIGNED DEFAULT '0' NOT NULL,
	icon_id INTEGER UNSIGNED DEFAULT '0' NOT NULL,
	poster_ip varchar(40) DEFAULT '' NOT NULL,
	karma_time INTEGER UNSIGNED DEFAULT '0' NOT NULL,
	karma_approved INTEGER UNSIGNED DEFAULT '1' NOT NULL,
	enable_bbcode INTEGER UNSIGNED DEFAULT '1' NOT NULL,
	enable_smilies INTEGER UNSIGNED DEFAULT '1' NOT NULL,
	enable_magic_url INTEGER UNSIGNED DEFAULT '1' NOT NULL,
	comment_text mediumtext(16777215) DEFAULT '' NOT NULL,
	comment_checksum varchar(32) DEFAULT '' NOT NULL,
	bbcode_bitfield varchar(255) DEFAULT '' NOT NULL,
	bbcode_uid varchar(8) DEFAULT '' NOT NULL,
	karma_action varchar(1) DEFAULT '+' NOT NULL,
	karma_power INTEGER UNSIGNED DEFAULT '1' NOT NULL
);

CREATE UNIQUE INDEX phpbb_karma_forum_topic_post_user_poster_id ON phpbb_karma (forum_id, topic_id, post_id, user_id, poster_id);

# Table: 'phpbb_posts'
ALTER TABLE phpbb_posts
	ADD post_karma INTEGER DEFAULT '0' NOT NULL,
	ADD post_karma_powered INTEGER DEFAULT '0' NOT NULL,
	ADD post_karma_count INTEGER UNSIGNED DEFAULT '0' NOT NULL
	ADD post_karma_search INTEGER DEFAULT '0' NOT NULL,
	ADD post_karma_search_powered INTEGER DEFAULT '0' NOT NULL;


# Table: 'phpbb_topics'
ALTER TABLE phpbb_topics
	ADD topic_karma INTEGER DEFAULT '0' NOT NULL,
	ADD topic_karma_powered INTEGER DEFAULT '0' NOT NULL,
	ADD topic_karma_count INTEGER UNSIGNED DEFAULT '0' NOT NULL
	ADD topic_karma_search INTEGER DEFAULT '0' NOT NULL,
	ADD topic_karma_search_powered INTEGER DEFAULT '0' NOT NULL;


# Table: 'phpbb_users'
ALTER TABLE phpbb_users
	ADD user_karma INTEGER DEFAULT '0' NOT NULL,
	ADD user_karma_powered INTEGER DEFAULT '0' NOT NULL,
	ADD user_karma_enable INTEGER UNSIGNED DEFAULT '1' NOT NULL,
	ADD user_karma_notify_email INTEGER UNSIGNED DEFAULT '0' NOT NULL,
	ADD user_karma_notify_pm INTEGER UNSIGNED DEFAULT '1' NOT NULL,
	ADD user_karma_notify_jabber INTEGER UNSIGNED DEFAULT '0' NOT NULL,
	ADD user_karma_toplist INTEGER UNSIGNED DEFAULT '1' NOT NULL,
	ADD user_karma_toplist_users INTEGER UNSIGNED DEFAULT '3' NOT NULL,
	ADD user_karma_comments_per_page INTEGER UNSIGNED DEFAULT '15' NOT NULL,
	ADD user_karma_comments_self INTEGER UNSIGNED DEFAULT '1' NOT NULL,
	ADD user_karma_comments_show_days INTEGER UNSIGNED NOT NULL DEFAULT '0',
	ADD user_karma_comments_sortby_type varchar(1) NOT NULL DEFAULT 't',
	ADD user_karma_comments_sortby_dir varchar(1) NOT NULL DEFAULT 'd';


COMMIT;