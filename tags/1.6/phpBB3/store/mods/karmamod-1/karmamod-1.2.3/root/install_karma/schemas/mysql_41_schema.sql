#
# $Id: mysql_41_schema.sql,v 1.5 2009/05/07 15:59:25 m157y Exp $
#

# Table: 'phpbb_karma'
CREATE TABLE phpbb_karma (
	karma_id mediumint(8) UNSIGNED NOT NULL auto_increment,
	forum_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	poster_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	icon_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	poster_ip varchar(40) DEFAULT '' NOT NULL ,
	karma_time int(11) UNSIGNED DEFAULT '0' NOT NULL,
	karma_approved tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	enable_bbcode tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	enable_smilies tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	enable_magic_url tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	comment_text mediumtext NOT NULL,
	comment_checksum varchar(32) DEFAULT '' NOT NULL,
	bbcode_bitfield varchar(255) DEFAULT '' NOT NULL,
	bbcode_uid varchar(8) DEFAULT '' NOT NULL,
	karma_action varchar(1) DEFAULT '+' NOT NULL,
	karma_power mediumint(8) UNSIGNED DEFAULT '1' NOT NULL,
	PRIMARY KEY (karma_id),
	UNIQUE KEY forum_topic_post_user_poster_id (forum_id, topic_id, post_id, user_id, poster_id)
) CHARACTER SET `utf8` COLLATE `utf8_bin`;


# Table: 'phpbb_posts'
ALTER TABLE phpbb_posts
	ADD post_karma mediumint(8) DEFAULT '0' NOT NULL,
	ADD post_karma_powered mediumint(8) DEFAULT '0' NOT NULL,
	ADD post_karma_count mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	ADD post_karma_search mediumint(8) DEFAULT '0' NOT NULL,
	ADD post_karma_search_powered mediumint(8) DEFAULT '0' NOT NULL;


# Table: 'phpbb_topics'
ALTER TABLE phpbb_topics
	ADD topic_karma mediumint(8) DEFAULT '0' NOT NULL,
	ADD topic_karma_powered mediumint(8) DEFAULT '0' NOT NULL,
	ADD topic_karma_count mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	ADD topic_karma_search mediumint(8) NOT NULL DEFAULT '0',
	ADD topic_karma_search_powered mediumint(8) NOT NULL DEFAULT '0';


# Table: 'phpbb_users'
ALTER TABLE phpbb_users
	ADD user_karma mediumint(8) DEFAULT '0' NOT NULL,
	ADD user_karma_powered mediumint(8) DEFAULT '0' NOT NULL,
	ADD user_karma_enable tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	ADD user_karma_notify_email tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	ADD user_karma_notify_pm tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	ADD user_karma_notify_jabber tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	ADD user_karma_toplist tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	ADD user_karma_toplist_users mediumint(8) UNSIGNED DEFAULT '3' NOT NULL,
	ADD user_karma_comments_per_page smallint(3) UNSIGNED DEFAULT '15' NOT NULL,
	ADD user_karma_comments_self tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	ADD user_karma_comments_show_days smallint(4) UNSIGNED DEFAULT '0' NOT NULL,
	ADD user_karma_comments_sortby_type varchar(1) DEFAULT 't' NOT NULL,
	ADD user_karma_comments_sortby_dir varchar(1) DEFAULT 'd' NOT NULL;


