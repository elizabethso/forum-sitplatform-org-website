/*

 $Id: oracle_schema.sql,v 1.6 2009/10/04 23:57:36 m157y Exp $

*/

/*
	Table: 'phpbb_karma'
*/
CREATE TABLE phpbb_karma (
	karma_id number(8) NOT NULL,
	forum_id number(8) DEFAULT '0' NOT NULL,
	topic_id number(8) DEFAULT '0' NOT NULL,
	post_id number(8) DEFAULT '0' NOT NULL,
	user_id number(8) DEFAULT '0' NOT NULL,
	poster_id number(8) DEFAULT '0' NOT NULL,
	icon_id number(8) DEFAULT '0' NOT NULL,
	poster_ip varchar2(40) DEFAULT '',
	karma_time number(11) DEFAULT '0' NOT NULL,
	karma_approved number(1) DEFAULT '1' NOT NULL,
	enable_bbcode number(1) DEFAULT '1' NOT NULL,
	enable_smilies number(1) DEFAULT '1' NOT NULL,
	enable_magic_url number(1) DEFAULT '1' NOT NULL,
	comment_text clob DEFAULT '',
	comment_checksum varchar2(32) DEFAULT '',
	bbcode_bitfield varchar2(255) DEFAULT '',
	bbcode_uid varchar2(8) DEFAULT '',
	karma_action varchar2(1) DEFAULT '+' NOT NULL,
	karma_power number(8) DEFAULT '1' NOT NULL,
	CONSTRAINT pk_phpbb_karma PRIMARY KEY (karma_id)
	CONSTRAINT u_phpbb_karma_forum_topic_post_user_poster_id UNIQUE (forum_id, topic_id, post_id, user_id, poster_id)
)
/

CREATE SEQUENCE phpbb_karma_seq
/

CREATE OR REPLACE TRIGGER t_phpbb_karma
BEFORE INSERT ON phpbb_karma
FOR EACH ROW WHEN (
	new.karma_id IS NULL OR new.karma_id = 0
)
BEGIN
	SELECT phpbb_karma_seq.nextval
	INTO :new.karma_id
	FROM dual;
END;


/*
	Table: 'phpbb_posts'
*/
ALTER TABLE phpbb_posts
	ADD post_karma number(8) DEFAULT '0' NOT NULL,
	ADD post_karma_powered number(8) DEFAULT '0' NOT NULL,
	ADD post_karma_count number(8) DEFAULT '0' NOT NULL,
	ADD post_karma_search number(8) DEFAULT '0' NOT NULL,
	ADD post_karma_search_powered number(8) DEFAULT '0' NOT NULL;
/


/*
	Table: 'phpbb_topics'
*/
ALTER TABLE phpbb_topics
	ADD topic_karma number(8) DEFAULT '0' NOT NULL,
	ADD topic_karma_powered number(8) DEFAULT '0' NOT NULL,
	ADD topic_karma_count number(8) DEFAULT '0' NOT NULL
	ADD topic_karma_search number(8) DEFAULT '0' NOT NULL,
	ADD topic_karma_search_powered number(8) DEFAULT '0' NOT NULL;
/


/*
	Table: 'phpbb_users'
*/
ALTER TABLE phpbb_users
	ADD user_karma number(8) DEFAULT '0' NOT NULL,
	ADD user_karma_powered number(8) DEFAULT '0' NOT NULL,
	ADD user_karma_enable number(1) DEFAULT '1' NOT NULL,
	ADD user_karma_notify_email number(1) DEFAULT '0' NOT NULL,
	ADD user_karma_notify_pm number(1) DEFAULT '1' NOT NULL,
	ADD user_karma_notify_jabber number(1) DEFAULT '0' NOT NULL,
	ADD user_karma_toplist number(1) DEFAULT '1' NOT NULL,
	ADD user_karma_toplist_users number(8) DEFAULT '3' NOT NULL,
	ADD user_karma_comments_per_page number(3) DEFAULT '15' NOT NULL,
	ADD user_karma_comments_self number(1) DEFAULT '1' NOT NULL,
	ADD user_karma_comments_show_days number(4) DEFAULT '0' NOT NULL,
	ADD user_karma_comments_sortby_type varchar2(1) DEFAULT 't' NOT NULL,
	ADD user_karma_comments_sortby_dir varchar2(1) DEFAULT 'd' NOT NULL;
/


