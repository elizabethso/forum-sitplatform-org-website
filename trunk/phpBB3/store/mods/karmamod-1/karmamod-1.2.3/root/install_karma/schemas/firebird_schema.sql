#
# $Id: firebird_schema.sql,v 1.6 2009/10/04 22:24:55 m157y Exp $
#


# Table: 'phpbb_karma'
CREATE TABLE phpbb_karma (
	karma_id INTEGER NOT NULL,
	forum_id INTEGER DEFAULT 0 NOT NULL,
	topic_id INTEGER DEFAULT 0 NOT NULL,
	post_id INTEGER DEFAULT 0 NOT NULL,
	user_id INTEGER DEFAULT 0 NOT NULL,
	poster_id INTEGER DEFAULT 0 NOT NULL,
	icon_id INTEGER DEFAULT 0 NOT NULL,
	poster_ip VARCHAR(40) CHARACTER SET NONE DEFAULT '' NOT NULL,
	karma_time INTEGER DEFAULT 0 NOT NULL,
	karma_approved INTEGER DEFAULT 1 NOT NULL,
	enable_bbcode INTEGER DEFAULT 1 NOT NULL,
	enable_smilies INTEGER DEFAULT 1 NOT NULL,
	enable_magic_url INTEGER DEFAULT 1 NOT NULL,
	comment_text BLOB SUB_TYPE TEXT CHARACTER SET UTF8 DEFAULT '' NOT NULL,
	comment_checksum VARCHAR(32) CHARACTER SET NONE DEFAULT '' NOT NULL,
	bbcode_bitfield VARCHAR(255) CHARACTER SET NONE DEFAULT '' NOT NULL,
	bbcode_uid VARCHAR(8) CHARACTER SET NONE DEFAULT '' NOT NULL,
	karma_action VARCHAR(1) DEFAULT '+' NOT NULL,
	karma_power INTEGER DEFAULT 1 NOT NULL
);;

ALTER TABLE phpbb_karma ADD PRIMARY KEY (karma_id);;

CREATE UNIQUE INDEX phpbb_karma_forum_topic_post_user_poster_id ON phpbb_karma(forum_id, topic_id, post_id, user_id, poster_id);;

CREATE GENERATOR phpbb_karma_gen;;
SET GENERATOR phpbb_karma_gen TO 0;;

CREATE TRIGGER t_phpbb_karma FOR phpbb_karma
BEFORE INSERT
AS
BEGIN
	NEW.karma_id = GEN_ID(phpbb_karma_gen, 1);
END;;


# Table: 'phpbb_posts'
ALTER TABLE phpbb_posts
	ADD post_karma INTEGER DEFAULT 0 NOT NULL,
	ADD post_karma_powered INTEGER DEFAULT 0 NOT NULL,
	ADD post_karma_count INTEGER DEFAULT 0 NOT NULL,
	ADD post_karma_search INTEGER DEFAULT 0 NOT NULL,
	ADD post_karma_search_powered INTEGER DEFAULT 0 NOT NULL;


# Table: 'phpbb_topics'
ALTER TABLE phpbb_topics
	ADD topic_karma INTEGER DEFAULT 0 NOT NULL,
	ADD topic_karma_powered INTEGER DEFAULT 0 NOT NULL,
	ADD topic_karma_count INTEGER DEFAULT 0 NOT NULL,
	ADD topic_karma_search INTEGER DEFAULT 0 NOT NULL,
	ADD topic_karma_search_powered INTEGER DEFAULT 0 NOT NULL;


# Table: 'phpbb_users'
ALTER TABLE phpbb_users
	ADD user_karma INTEGER DEFAULT 0 NOT NULL,
	ADD user_karma_powered INTEGER DEFAULT 0 NOT NULL,
	ADD user_karma_enable INTEGER DEFAULT 1 NOT NULL,
	ADD user_karma_notify_email INTEGER DEFAULT 0 NOT NULL,
	ADD user_karma_notify_pm INTEGER DEFAULT 1 NOT NULL,
	ADD user_karma_notify_jabber INTEGER DEFAULT 0 NOT NULL,
	ADD user_karma_toplist INTEGER DEFAULT 1 NOT NULL,
	ADD user_karma_toplist_users INTEGER DEFAULT 3 NOT NULL,
	ADD user_karma_comments_per_page INTEGER DEFAULT 15 NOT NULL,
	ADD user_karma_comments_self INTEGER DEFAULT 1 NOT NULL,
	ADD user_karma_comments_show_days INTEGER DEFAULT 0 NOT NULL,
	ADD user_karma_comments_sortby_type VARCHAR(1) CHARACTER SET NONE DEFAULT 't' NOT NULL,
	ADD user_karma_comments_sortby_dir VARCHAR(1) CHARACTER SET NONE DEFAULT 'd' NOT NULL;

