/*

 $Id: mssql_schema.sql,v 1.6 2009/10/04 23:56:27 m157y Exp $

*/

BEGIN TRANSACTION
GO

/*
	Table: 'phpbb_karma'
*/
CREATE TABLE [phpbb_karma] (
	[karma_id] [int] IDENTITY (1, 1) NOT NULL ,
	[forum_id] [int] DEFAULT (0) NOT NULL ,
	[topic_id] [int] DEFAULT (0) NOT NULL ,
	[post_id] [int] DEFAULT (0) NOT NULL ,
	[user_id] [int] DEFAULT (0) NOT NULL ,
	[poster_id] [int] DEFAULT (0) NOT NULL ,
	[icon_id] [int] DEFAULT (0) NOT NULL,
	[poster_ip] [varchar] (40) DEFAULT ('') NOT NULL ,
	[karma_time] [int] DEFAULT (0) NOT NULL ,
	[karma_approved] [int] DEFAULT (1) NOT NULL ,
	[enable_bbcode] [int] DEFAULT (1) NOT NULL ,
	[enable_smilies] [int] DEFAULT (1) NOT NULL ,
	[enable_magic_url] [int] DEFAULT (1) NOT NULL ,
	[comment_text] [text] DEFAULT ('') NOT NULL ,
	[comment_checksum] [varchar] (32) DEFAULT ('') NOT NULL ,
	[bbcode_bitfield] [varchar] (255) DEFAULT ('') NOT NULL ,
	[bbcode_uid] [varchar] (8) DEFAULT ('') NOT NULL ,
	[karma_action] [varchar] DEFAULT ('+') NOT NULL ,
	[karma_power] [int] DEFAULT (1) NOT NULL 
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

ALTER TABLE [phpbb_karma] WITH NOCHECK ADD 
	CONSTRAINT [PK_phpbb_karma] PRIMARY KEY  CLUSTERED 
	(
		[karma_id]
	)  ON [PRIMARY] 
GO

CREATE  UNIQUE  INDEX [forum_topic_post_user_poster_id] ON [phpbb_karma]([forum_id], [topic_id], [post_id], [user_id], [poster_id]) ON [PRIMARY]
GO


/*
	Table: 'phpbb_posts'
*/
ALTER TABLE [phpbb_posts] (
	ADD [post_karma] [int] DEFAULT (0) NOT NULL ,
	ADD [post_karma_powered] [int] DEFAULT (0) NOT NULL ,
	ADD [post_karma_count] [int] DEFAULT (0) NOT NULL ,
	ADD [post_karma_search] [int] DEFAULT (0) NOT NULL ,
	ADD [post_karma_search_powered] [int] DEFAULT (0) NOT NULL
) ON [PRIMARY]
GO


/*
	Table: 'phpbb_topics'
*/
ALTER TABLE [phpbb_posts] (
	ADD [topic_karma] [int] DEFAULT (0) NOT NULL ,
	ADD [topic_karma_powered] [int] DEFAULT (0) NOT NULL ,
	ADD [topic_karma_count] [int] DEFAULT (0) NOT NULL ,
	ADD [topic_karma_search] [int] DEFAULT (0) NOT NULL ,
	ADD [topic_karma_search_powered] [int] DEFAULT (0) NOT NULL
) ON [PRIMARY]
GO


/*
	Table: 'phpbb_users'
*/
ALTER TABLE [phpbb_users] (
	ADD [user_karma] [int] DEFAULT (0) NOT NULL ,
	ADD [user_karma_powered] [int] DEFAULT (0) NOT NULL ,
	ADD [user_karma_enable] [int] DEFAULT (1) NOT NULL ,
	ADD [user_karma_notify_email] [int]  DEFAULT (0) NOT NULL ,
	ADD [user_karma_notify_pm] [int] DEFAULT (1) NOT NULL ,
	ADD [user_karma_notify_jabber] [int] DEFAULT (0) NOT NULL ,
	ADD [user_karma_toplist] [int] DEFAULT (1) NOT NULL ,
	ADD [user_karma_toplist_users] [int] DEFAULT (3) NOT NULL ,
	ADD [user_karma_comments_per_page] [int] DEFAULT (15) NOT NULL ,
	ADD [user_karma_comments_self] [int] DEFAULT (1) NOT NULL ,
	ADD [user_karma_comments_show_days] [int] DEFAULT (0) NOT NULL ,
	ADD [user_karma_comments_sortby_type] [varchar] (1) DEFAULT ('t') NOT NULL ,
	ADD [user_karma_comments_sortby_dir] [varchar] (1) DEFAULT ('d') NOT NULL
) ON [PRIMARY]
GO


COMMIT
GO

