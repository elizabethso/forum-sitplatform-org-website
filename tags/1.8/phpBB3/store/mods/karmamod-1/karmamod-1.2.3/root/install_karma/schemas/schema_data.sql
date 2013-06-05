#
# $Id: schema_data.sql,v 22 2009/09/21 23:28:26 m157y Exp $
#

# POSTGRES BEGIN #

# -- Config
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_enabled', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_comments', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_comments_reqd', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_viewprofile', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_power_show', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_power_max', '5');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_per_day', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_zebra',  '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_anonym_increase',  '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_anonym_decrease',  '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_minimum',  '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_ban',  '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_ban_value',  '-100');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_ban_reason',  'Automatically banned by Karma MOD');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_ban_give_reason',  'Automatically banned by Karma MOD');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('karma_updater_beta',  '1');

# POSTGRES COMMIT #