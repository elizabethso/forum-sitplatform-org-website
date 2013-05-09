/*

 $Id: postgres_schema.sql,v 1.6 2009/05/07 16:02:48 m157y Exp $

*/

BEGIN;

/*
	Table: 'phpbb_karma'
*/
CREATE SEQUENCE phpbb_karma_seq;

CREATE TABLE phpbb_karma (
	karma_id INT4 DEFAULT nextval('phpbb_karma_seq'),
	forum_id INT4 DEFAULT '0' NOT NULL CHECK (forum_id >= 0),
	topic_id INT4 DEFAULT '0' NOT NULL CHECK (topic_id >= 0),
	post_id INT4 DEFAULT '0' NOT NULL CHECK (post_id >= 0),
	user_id INT4 DEFAULT '0' NOT NULL CHECK (user_id >= 0),
	poster_id INT4 DEFAULT '0' NOT NULL CHECK (poster_id >= 0),
	icon_id INT4 DEFAULT '0' NOT NULL CHECK (icon_id >= 0),
	poster_ip varchar(40) DEFAULT '' NOT NULL,
	karma_time INT4 DEFAULT '0' NOT NULL CHECK (karma_time >= 0),
	karma_approved INT2 DEFAULT '1' NOT NULL CHECK (karma_approved >= 0),
	enable_bbcode INT2 DEFAULT '1' NOT NULL CHECK (enable_bbcode >= 0),
	enable_smilies INT2 DEFAULT '1' NOT NULL CHECK (enable_smilies >= 0),
	enable_magic_url INT2 DEFAULT '1' NOT NULL CHECK (enable_magic_url >= 0),
	comment_text TEXT DEFAULT '' NOT NULL,
	comment_checksum varchar(32) DEFAULT '' NOT NULL,
	bbcode_bitfield varchar(255) DEFAULT '' NOT NULL,
	bbcode_uid varchar(8) DEFAULT '' NOT NULL,
	karma_action varchar(1) DEFAULT '+' NOT NULL,
	karma_power INT4 DEFAULT '1' NOT NULL CHECK (karma_power >= 1),
	PRIMARY KEY (karma_id)
);

CREATE UNIQUE INDEX phpbb_karma_forum_topic_post_user_poster_id ON phpbb_karma (forum_id, topic_id, post_id, user_id, poster_id);


/*
	Table: 'phpbb_posts'
*/
ALTER TABLE phpbb_posts
	ADD post_karma INT4 DEFAULT '0' NOT NULL,
	ADD post_karma_powered INT4 DEFAULT '0' NOT NULL,
	ADD post_karma_count INT4 DEFAULT '0' NOT NULL CHECK (post_karma_count >= 0),
	ADD post_karma_search INT4 DEFAULT '0' NOT NULL,
	ADD post_karma_search_powered INT4 DEFAULT '0' NOT NULL;


/*
	Table: 'phpbb_topics'
*/
ALTER TABLE phpbb_topics
	ADD topic_karma INT4 DEFAULT '0' NOT NULL,
	ADD topic_karma_powered INT4 DEFAULT '0' NOT NULL,
	ADD topic_karma_count INT4 DEFAULT '0' NOT NULL CHECK (topic_karma_count >= 0),
	ADD topic_karma_search INT4 DEFAULT '0' NOT NULL,
	ADD topic_karma_search_powered INT4 DEFAULT '0' NOT NULL;


/*
	Table: 'phpbb_users'
*/
ALTER TABLE phpbb_users
	ADD user_karma INT4 DEFAULT '0' NOT NULL,
	ADD user_karma_powered INT4 DEFAULT '0' NOT NULL,
	ADD user_karma_enable INT2 DEFAULT '1' NOT NULL CHECK (user_karma_enable >= 0),
	ADD user_karma_notify_email INT2 DEFAULT '0' NOT NULL CHECK (user_karma_notify_email >= 0),
	ADD user_karma_notify_pm INT2 DEFAULT '1' NOT NULL CHECK (user_karma_notify_pm >= 0),
	ADD user_karma_notify_jabber INT2 DEFAULT '0' NOT NULL CHECK (user_karma_notify_jabber >= 0),
	ADD user_karma_toplist INT2 DEFAULT '1' NOT NULL CHECK (user_karma_toplist >= 0),
	ADD user_karma_toplist_users INT4 DEFAULT '3' NOT NULL CHECK (user_karma_toplist_users >= 1),
	ADD user_karma_comments_per_page INT3 DEFAULT '15' NOT NULL CHECK (user_karma_comments_per_page >= 1),
	ADD user_karma_comments_self INT2 DEFAULT '1' NOT NULL CHECK (user_karma_comments_self >= 0),
	ADD user_karma_comments_show_days INT2 DEFAULT '0' NOT NULL CHECK (user_karma_comments_show_days >= 0),
	ADD user_karma_comments_sortby_type varchar(1) DEFAULT 't' NOT NULL,
	ADD user_karma_comments_sortby_dir varchar(1) DEFAULT 'd' NOT NULL;


COMMIT;