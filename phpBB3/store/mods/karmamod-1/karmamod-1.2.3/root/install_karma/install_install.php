<?php
/** 
*
* @package karmamod (install)
* @version $Id: install_install.php,v 9041.22 2009/10/13 20:55:55 m157y Exp $
* @copyright (c) 2005-2007 phpBB Group, (c) 2007, 2009 David Lawson, m157y, A_Jelly_Doughnut
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
*
* Based on original installer from phpBB3
*
* Modified by m157y
*
* @package karmamod
*/

/**
*/
if (!defined('IN_INSTALL'))
{
	// Someone has tried to access the file direct. This is not a good idea, so exit
	exit;
}

if (!empty($setmodules))
{
	$module[] = array(
		'module_type'		=> 'install',
		'module_title'		=> 'INSTALL',
		'module_filename'	=> substr(basename(__FILE__), 0, -strlen($phpEx)-1),
		'module_order'		=> 10,
		'module_subs'		=> '',
		'module_stages'		=> array('INTRO', 'REQUIREMENTS', 'ADMINISTRATOR', 'CREATE_TABLE', 'FINAL'),
		'module_reqs'		=> ''
	);
}

/**
* Installation
* @package karmamod (install)
*/
class install_install extends module
{
	function install_install(&$p_master)
	{
		$this->p_master = &$p_master;
	}

	function main($mode, $sub)
	{
		global $auth, $user, $template;

		if (!$auth->acl_get('a_'))
		{
			$this->error($user->lang['INSTALL_KARMA_ADMIN_ONLY'], __LINE__, __FILE__);
		}

		switch ($sub)
		{
			case 'intro' :
				$this->page_title = $user->lang['INSTALL_KARMA_INTRO'];

				$template->assign_vars(array(
					'TITLE'			=> $user->lang['INSTALL_KARMA_INTRO'],
					'BODY'			=> $user->lang['INSTALL_KARMA_INTRO_BODY_INSTALL'],
					'L_SUBMIT'		=> $user->lang['NEXT_STEP'],
					'U_ACTION'		=> $this->p_master->module_url . "?mode=$mode&amp;sub=requirements",
				));

			break;

			case 'requirements' :
				$this->check_forum_requirements($mode, $sub);

			break;

			case 'administrator' :
				$this->obtain_admin_settings($mode, $sub);

			break;

			case 'create_table':
				$this->load_schema($mode, $sub);
			break;

			case 'final' :
				$this->add_modules($mode, $sub);
				$this->add_permissions($mode, $sub);
				$this->clear_template_cache($mode, $sub);
				$this->clear_theme_cache($mode, $sub);
				$this->clear_imageset_cache($mode, $sub);
				$this->congrats($mode, $sub);
			break;
		}

		$this->tpl_name = 'install_install';
	}

	/**
	* Checks that the forum we are installing on meets the requirements for running Karma MOD
	*/
	function check_forum_requirements($mode, $sub)
	{
		global $user, $config, $db, $auth, $template, $dbms, $phpbb_root_path;

		$this->page_title = $user->lang['INSTALL_KARMA_STAGE_REQUIREMENTS'];

		$template->assign_vars(array(
			'TITLE'		=> $user->lang['INSTALL_KARMA_REQUIREMENTS_TITLE'],
			'BODY'		=> $user->lang['INSTALL_KARMA_REQUIREMENTS_EXPLAIN'],
		));

		$passed = array('phpbb' => false, 'db' => false, 'files' => false);

		// Test for basic phpBB settings
		$template->assign_block_vars('checks', array(
			'S_LEGEND'			=> true,
			'LEGEND'			=> $user->lang['INSTALL_KARMA_PHPBB_SETTINGS'],
			'LEGEND_EXPLAIN'	=> $user->lang['INSTALL_KARMA_PHPBB_SETTINGS_EXPLAIN'],
		));

		// Test the minimum phpBB version
		if (version_compare($config['version'], '3.0.4', '<='))
		{
			$result = '<strong style="color:red">' . $user->lang['NO'] . '</strong>';
		}
		else
		{
			$passed['phpbb'] = true;

			$result = '<strong style="color:green">' . $user->lang['YES'];
			$result .= '</strong>';
		}

		$template->assign_block_vars('checks', array(
			'TITLE'			=> $user->lang['INSTALL_KARMA_PHPBB_VERSION_REQD'],
			'RESULT'		=> $result,

			'S_EXPLAIN'		=> false,
			'S_LEGEND'		=> false,
		));

		// Check for email being enabled
		if(!$config['email_enable'])
		{
			$result = '<strong style="color:red">' . $user->lang['NO'] . '</strong>';
		}
		else
		{
			$result = '<strong style="color:green">' . $user->lang['YES'];
			$result .= '</strong>';
		}

		$template->assign_block_vars('checks', array(
			'TITLE'			=> $user->lang['INSTALL_KARMA_PHPBB_EMAIL'],
			'TITLE_EXPLAIN'	=> $user->lang['INSTALL_KARMA_PHPBB_EMAIL_EXPLAIN'],
			'RESULT'		=> $result,

			'S_EXPLAIN'		=> true,
			'S_LEGEND'		=> false,
		));

		// Check for privmsgs being enabled
		if(!$config['allow_privmsg'])
		{
			$result = '<strong style="color:red">' . $user->lang['NO'] . '</strong>';
		}
		else
		{
			$result = '<strong style="color:green">' . $user->lang['YES'];
			$result .= '</strong>';
		}

		$template->assign_block_vars('checks', array(
			'TITLE'			=> $user->lang['INSTALL_KARMA_PHPBB_PRIVMSGS'],
			'TITLE_EXPLAIN'	=> $user->lang['INSTALL_KARMA_PHPBB_PRIVMSGS_EXPLAIN'],
			'RESULT'		=> $result,

			'S_EXPLAIN'		=> true,
			'S_LEGEND'		=> false,
		));

		// Check for jabber being enabled
		if(!$config['jab_enable'])
		{
			$result = '<strong style="color:red">' . $user->lang['NO'] . '</strong>';
		}
		else
		{
			$result = '<strong style="color:green">' . $user->lang['YES'];
			$result .= '</strong>';
		}

		$template->assign_block_vars('checks', array(
			'TITLE'			=> $user->lang['INSTALL_KARMA_PHPBB_JABBER'],
			'TITLE_EXPLAIN'	=> $user->lang['INSTALL_KARMA_PHPBB_JABBER_EXPLAIN'],
			'RESULT'		=> $result,

			'S_EXPLAIN'		=> true,
			'S_LEGEND'		=> false,
		));

		// Check for drafts being enabled
		if (!$auth->acl_get('u_savedrafts'))
		{
			$result = '<strong style="color:red">' . $user->lang['NO'] . '</strong>';
		}
		else
		{
			$result = '<strong style="color:green">' . $user->lang['YES'];
			$result .= '</strong>';
		}

		$template->assign_block_vars('checks', array(
			'TITLE'			=> $user->lang['INSTALL_KARMA_PHPBB_DRAFTS'],
			'TITLE_EXPLAIN'	=> $user->lang['INSTALL_KARMA_PHPBB_DRAFTS_EXPLAIN'],
			'RESULT'		=> $result,

			'S_EXPLAIN'		=> true,
			'S_LEGEND'		=> false,
		));

		// Check uploaded files/directories we need use
		$template->assign_block_vars('checks', array(
			'S_LEGEND'			=> true,
			'LEGEND'			=> $user->lang['INSTALL_KARMA_FILES_REQUIRED'],
			'LEGEND_EXPLAIN'	=> $user->lang['INSTALL_KARMA_FILES_REQUIRED_EXPLAIN'],
		));

		$files = array(
			'adm/style/acp_karma_users.html',
			'adm/style/acp_karma.html',
			'includes/mods/acp/acp_karma.php',
			'includes/mods/acp/info/acp_karma.php',
			'includes/mods/functions_karma.php',
			'includes/mods/ucp/ucp_karma.php',
			'includes/mods/ucp/info/ucp_karma.php',
			'karma.php',
		);

		$sql = 'SELECT lang_dir
			FROM ' . LANG_TABLE;
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				$files[] = 'language/' . $row['lang_dir'] . '/email/karma_notify_decrease_anonym_powered.txt';
				$files[] = 'language/' . $row['lang_dir'] . '/email/karma_notify_decrease_anonym.txt';
				$files[] = 'language/' . $row['lang_dir'] . '/email/karma_notify_decrease_powered.txt';
				$files[] = 'language/' . $row['lang_dir'] . '/email/karma_notify_decrease.txt';
				$files[] = 'language/' . $row['lang_dir'] . '/email/karma_notify_increase_anonym_powered.txt';
				$files[] = 'language/' . $row['lang_dir'] . '/email/karma_notify_increase_anonym.txt';
				$files[] = 'language/' . $row['lang_dir'] . '/email/karma_notify_increase_powered.txt';
				$files[] = 'language/' . $row['lang_dir'] . '/email/karma_notify_increase.txt';
				$files[] = 'language/' . $row['lang_dir'] . '/mods/karma_faq.php';
				$files[] = 'language/' . $row['lang_dir'] . '/mods/karma.php';
			}
			while ($row = $db->sql_fetchrow($result));
		}

		$sql = 'SELECT i.imageset_path, t.template_path, th.theme_path
			FROM ' . STYLES_TABLE  . ' s, ' . STYLES_IMAGESET_TABLE . ' i, ' . STYLES_TEMPLATE_TABLE . ' t, ' . STYLES_THEME_TABLE . ' th
			WHERE i.imageset_id = s.imageset_id
				AND t.template_id = s.template_id
				AND th.theme_id = s.theme_id';
		$result = $db->sql_query($sql);
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				$files[] = 'styles/' . $row['template_path'] . '/imageset/icon_karma_decrease.gif';
				$files[] = 'styles/' . $row['template_path'] . '/imageset/icon_karma_increase.gif';
				$files[] = 'styles/' . $row['template_path'] . '/template/karma_body.html';
				$files[] = 'styles/' . $row['template_path'] . '/template/karma_ucp.html';
				$files[] = 'styles/' . $row['template_path'] . '/template/karma_view.html';
				if ($row['template_path'] == 'prosilver') {
					$files[] = 'styles/' . $row['theme_path'] . '/theme/karma.css';
				}
			}
			while ($row = $db->sql_fetchrow($result));
		}

		$passed['files'] = true;
		foreach ($files as $file)
		{
			$exists = $write = false;

			// Now really check
			if (file_exists($phpbb_root_path . $file))
			{
				$exists = true;
			}

			$passed['files'] = ($exists && $passed['files']) ? true : false;

			$exists = ($exists) ? '<strong style="color:green">' . $user->lang['FOUND'] . '</strong>' : '<strong style="color:red">' . $user->lang['NOT_FOUND'] . '</strong>';

			$template->assign_block_vars('checks', array(
				'TITLE'		=> $file,
				'RESULT'	=> $exists . $write,

				'S_EXPLAIN'	=> false,
				'S_LEGEND'	=> false,
			));
		}

		// Test for available database modules
		$template->assign_block_vars('checks', array(
			'S_LEGEND'			=> true,
			'LEGEND'			=> $user->lang['PHP_SUPPORTED_DB'],
			'LEGEND_EXPLAIN'	=> $user->lang['PHP_SUPPORTED_DB_EXPLAIN'],
		));

		$available_dbms = get_available_dbms(false, true);
		$passed['db'] = $available_dbms['ANY_DB_SUPPORT'];
		unset($available_dbms['ANY_DB_SUPPORT']);

		foreach ($available_dbms as $db_name => $db_ary)
		{
			if (!$db_ary['AVAILABLE'])
			{
				$template->assign_block_vars('checks', array(
					'TITLE'		=> $user->lang['DLL_' . strtoupper($db_name)],
					'RESULT'	=> '<span style="color:red">' . $user->lang['UNAVAILABLE'] . '</span>',

					'S_EXPLAIN'	=> false,
					'S_LEGEND'	=> false,
				));
			}
			else
			{
				$template->assign_block_vars('checks', array(
					'TITLE'		=> $user->lang['DLL_' . strtoupper($db_name)],
					'RESULT'	=> '<strong style="color:green">' . $user->lang['AVAILABLE'] . (($dbms == $db_name) ? $user->lang['INSTALL_KARMA_DB_USED'] : '') . '</strong>',

					'S_EXPLAIN'	=> false,
					'S_LEGEND'	=> false,
				));
			}
		}

		// And finally where do we want to go next (well today is taken isn't it :P)
		$url = (!in_array(false, $passed)) ? $this->p_master->module_url . "?mode=$mode&amp;sub=administrator" : $this->p_master->module_url . "?mode=$mode&amp;sub=requirements";
		$submit = (!in_array(false, $passed)) ? $user->lang['INSTALL_START'] : $user->lang['INSTALL_TEST'];


		$template->assign_vars(array(
			'L_SUBMIT'	=> $submit,
			'U_ACTION'	=> $url,
		));
	}

	/**
	* Obtain the settings of MOD
	*/
	function obtain_admin_settings($mode, $sub)
	{
		global $user, $template;

		$this->page_title = $user->lang['INSTALL_KARMA_STAGE_ADMINISTRATOR'];

		foreach ($this->admin_config_options as $config_key => $vars)
		{
			if (!is_array($vars) && strpos($config_key, 'legend') === false)
			{
				continue;
			}

			if (strpos($config_key, 'legend') !== false)
			{
				$template->assign_block_vars('options', array(
					'S_LEGEND'		=> true,
					'LEGEND'		=> $user->lang[$vars])
				);

				continue;
			}

			$options = isset($vars['options']) ? $vars['options'] : '';

			$template->assign_block_vars('options', array(
				'KEY'			=> $config_key,
				'TITLE'			=> $user->lang[$vars['lang']],
				'S_EXPLAIN'		=> $vars['explain'],
				'S_LEGEND'		=> false,
				'TITLE_EXPLAIN'	=> ($vars['explain']) ? $user->lang[$vars['lang'] . '_EXPLAIN'] : '',
				'CONTENT'		=> $this->p_master->input_field($config_key, $vars['type'], $vars['value'], $options) . ((isset($vars['append']) && isset($user->lang[$vars['append']])) ? ' ' . $user->lang[$vars['append']] : ''),
				)
			);
		}

		$submit = $user->lang['NEXT_STEP'];

		$url = $this->p_master->module_url . "?mode=$mode&amp;sub=create_table";

		$template->assign_vars(array(
			'L_SUBMIT'	=> $submit,
			'U_ACTION'	=> $url,
		));
	}

	/**
	* Load the contents of the schema into the database and then alter it based on what has been input during the installation
	*/
	function load_schema($mode, $sub)
	{
		global $dbms, $db, $user, $template, $table_prefix, $config, $auth, $cache;

		$this->page_title = $user->lang['INSTALL_KARMA_STAGE_CREATE_TABLE'];
		$s_hidden_fields = '';

		// Obtain any submitted data
		foreach ($this->request_vars as $var)
		{
			$$var = request_var($var, '');
		}

		if (!$karma_time)
		{
			$karma_time = $this->admin_config_options['karma_time']['value'];
		}

		if (!$karma_posts)
		{
			$karma_posts = $this->admin_config_options['karma_posts']['value'];
		}

		// If we get here and the extension isn't loaded it should be safe to just go ahead and load it
		$available_dbms = get_available_dbms($dbms);

		// If mysql is chosen, we need to adjust the schema filename slightly to reflect the correct version. ;)
		if ($dbms == 'mysql')
		{
			if (version_compare($db->sql_server_version, '4.1.3', '>='))
			{
				$available_dbms[$dbms]['SCHEMA'] .= '_41';
			}
			else
			{
				$available_dbms[$dbms]['SCHEMA'] .= '_40';
			}
		}

		// Ok we have the db info go ahead and read in the relevant schema
		// and work on building the table
		$dbms_schema = 'schemas/' . $available_dbms[$dbms]['SCHEMA'] . '_schema.sql';

		// How should we treat this schema?
		$remove_remarks = $available_dbms[$dbms]['COMMENTS'];
		$delimiter = $available_dbms[$dbms]['DELIM'];

		$sql_query = @file_get_contents($dbms_schema);

		$sql_query = preg_replace('#phpbb_#i', $table_prefix, $sql_query);

		$remove_remarks($sql_query);

		$sql_query = split_sql_file($sql_query, $delimiter);

		foreach ($sql_query as $sql)
		{
			//$sql = trim(str_replace('|', ';', $sql));
			if (!$db->sql_query($sql))
			{
				$error = $db->sql_error();
				$this->p_master->db_error($error['message'], $sql, __LINE__, __FILE__);
			}
		}
		unset($sql_query);

		// Ok tables have been built, let's fill in the basic information
		$sql_query = file_get_contents('schemas/schema_data.sql');

		// Deal with any special comments
		switch ($dbms)
		{
			case 'mssql':
			case 'mssql_odbc':
				$sql_query = preg_replace('#\# MSSQL IDENTITY (phpbb_[a-z_]+) (ON|OFF) \##s', 'SET IDENTITY_INSERT \1 \2;', $sql_query);
			break;

			case 'postgres':
				$sql_query = preg_replace('#\# POSTGRES (BEGIN|COMMIT) \##s', '\1; ', $sql_query);
			break;
		}

		// Change prefix
		$sql_query = preg_replace('#phpbb_#i', $table_prefix, $sql_query);

		// Change language strings...
		$sql_query = preg_replace_callback('#\{L_([A-Z0-9\-_]*)\}#s', 'adjust_language_keys_callback', $sql_query);

		// Since there is only one schema file we know the comment style and are able to remove it directly with remove_remarks
		remove_remarks($sql_query);
		$sql_query = split_sql_file($sql_query, ';');

		foreach ($sql_query as $sql)
		{
			//$sql = trim(str_replace('|', ';', $sql));
			if (!$db->sql_query($sql))
			{
				$error = $db->sql_error();
				$this->p_master->db_error($error['message'], $sql, __LINE__, __FILE__);
			}
		}
		unset($sql_query);

		// Set default config and post data, this applies to all DB's
		$sql_ary = array(
			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_version', '" . $db->sql_escape($this->version) . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_enabled_ucp', '" . $db->sql_escape($karma_enabled_ucp) . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_notify_pm', '" . $config['allow_privmsg'] . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_notify_email', '" . $config['email_enable'] . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_notify_jabber', '" . $config['jab_enable'] . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_drafts', '" . $auth->acl_get('u_savedrafts') . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_icons', '" . $db->sql_escape($karma_icons) . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_toplist', '" . $db->sql_escape($karma_toplist) . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_toplist_users', '" . $db->sql_escape($karma_toplist_users) . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_time', '" . $db->sql_escape($karma_time) . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_posts', '" . $db->sql_escape($karma_posts) . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_comments_per_page', '" . $db->sql_escape($karma_comments_per_page) . "')",

			'INSERT INTO ' . $table_prefix . "config (config_name, config_value)
				VALUES ('karma_power', '" . $db->sql_escape($karma_power) . "')",
		);

		foreach ($sql_ary as $sql)
		{
			//$sql = trim(str_replace('|', ';', $sql));

			if (!$db->sql_query($sql))
			{
				$error = $db->sql_error();
				$this->p_master->db_error($error['message'], $sql, __LINE__, __FILE__);
			}
		}

		// clear the config table cache now
		$cache->destroy('_global');

		foreach ($this->request_vars as $var)
		{
			$s_hidden_fields .= '<input type="hidden" name="' . $var . '" value="' . $$var . '" />';
		}

		$submit = $user->lang['NEXT_STEP'];

		$url = $this->p_master->module_url . "?mode=$mode&amp;sub=final";

		$template->assign_vars(array(
			'BODY'		=> $user->lang['STAGE_CREATE_TABLE_EXPLAIN'],
			'L_SUBMIT'	=> $submit,
			'S_HIDDEN'	=> $s_hidden_fields,
			'U_ACTION'	=> $url,
		));
	}

	/**
	* Populate the module tables
	*/
	function add_modules($mode, $sub)
	{
		global $db, $user, $phpbb_root_path, $phpEx;

		include_once($phpbb_root_path . 'includes/acp/acp_modules.' . $phpEx);

		$_module = &new acp_modules();
		$module_classes = array('acp', 'mcp', 'ucp');

		// Add categories
		foreach ($module_classes as $module_class)
		{
			$_module->module_class = $module_class;

			if (isset($this->modules[$module_class]))
			{
				foreach ($this->modules[$module_class] as $cat_name => $mods)
				{
					$sql = 'SELECT module_id, left_id, right_id, module_auth
						FROM ' . MODULES_TABLE . " 
						WHERE module_langname = '" . $db->sql_escape($cat_name) . "'
							AND module_class = '" . $db->sql_escape($module_class) . "'";
					$result = $db->sql_query_limit($sql, 1);
					$row2 = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);

					foreach ($mods as $mod)
					{
						$mod_name = $mod[0];
						$mod_mode = $mod[1];

						$module_data = array(
							'module_basename'	=> 'karma',
							'module_enabled'	=> 1,
							'module_display'	=> 1,
							'parent_id'			=> (int) $row2['module_id'],
							'module_class'		=> $module_class,
							'module_langname'	=> $mod_name,
							'module_mode'		=> $mod_mode,
							'module_auth'		=> $row2['module_auth'],
						);

						$_module->update_module_data($module_data, true);

						// Check for last sql error happened
						if ($db->sql_error_triggered)
						{
							$error = $db->sql_error($db->sql_error_sql);
							$this->p_master->db_error($error['message'], $db->sql_error_sql, __LINE__, __FILE__);
						}
					}
				}
			}

			$_module->remove_cache_file();
		}
	}

	/**
	* Add permissions groups
	*/
	function add_permissions($mode, $sub)
	{
		global $phpbb_root_path, $phpEx, $db, $errored, $error_ary;

		// Reset permissions
		$sql = 'UPDATE ' . USERS_TABLE . "
			SET user_permissions = '',
				user_perm_from = 0";
		$db->sql_query($sql);

		// Add new permissions u_karma_can, u_karma_view, f_karma_can and f_karma_view
		// and duplicate settings from u_viewprofile
		include_once($phpbb_root_path . 'includes/acp/auth.' . $phpEx);
		$auth_admin = new auth_admin();

		// Add u_karma_can permission, only if it doesn not already exist
		if (!isset($auth_admin->acl_options['id']['u_karma_can']))
		{
			$auth_admin->acl_add_option(array('global' => array('u_karma_can')));

			// Now the tricky part, filling the permission
			$old_id = $auth_admin->acl_options['id']['u_viewprofile'];
			$new_id = $auth_admin->acl_options['id']['u_karma_can'];

			$tables = array(ACL_GROUPS_TABLE, ACL_ROLES_DATA_TABLE, ACL_USERS_TABLE);

			foreach ($tables as $table)
			{
				$sql = 'SELECT *
					FROM ' . $table . '
					WHERE auth_option_id = ' . $old_id;
				$result = $db->sql_query($sql);

				$sql_ary = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$row['auth_option_id'] = $new_id;
					$sql_ary[] = $row;
				}
				$db->sql_freeresult($result);

				if (sizeof($sql_ary))
				{
					$db->sql_multi_insert($table, $sql_ary);
				}
			}

			// Remove any old permission entries
			$auth_admin->acl_clear_prefetch();
		}

		// Add u_karma_view permission, only if it doesn not already exist
		if (!isset($auth_admin->acl_options['id']['u_karma_view']))
		{
			$auth_admin->acl_add_option(array('global' => array('u_karma_view')));

			// Now the tricky part, filling the permission
			$old_id = $auth_admin->acl_options['id']['u_viewprofile'];
			$new_id = $auth_admin->acl_options['id']['u_karma_view'];

			$tables = array(ACL_GROUPS_TABLE, ACL_ROLES_DATA_TABLE, ACL_USERS_TABLE);

			foreach ($tables as $table)
			{
				$sql = 'SELECT *
					FROM ' . $table . '
					WHERE auth_option_id = ' . $old_id;
				$result = $db->sql_query($sql);

				$sql_ary = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$row['auth_option_id'] = $new_id;
					$sql_ary[] = $row;
				}
				$db->sql_freeresult($result);

				if (sizeof($sql_ary))
				{
					$db->sql_multi_insert($table, $sql_ary);
				}
			}

			// Remove any old permission entries
			$auth_admin->acl_clear_prefetch();
		}

		// Add f_karma_can permission, only if it doesn not already exist
		if (!isset($auth_admin->acl_options['id']['f_karma_can']))
		{
			$auth_admin->acl_add_option(array('local' => array('f_karma_can')));

			// Now the tricky part, filling the permission
			$old_id = $auth_admin->acl_options['id']['f_reply'];
			$new_id = $auth_admin->acl_options['id']['f_karma_can'];

			$tables = array(ACL_GROUPS_TABLE, ACL_ROLES_DATA_TABLE, ACL_USERS_TABLE);

			foreach ($tables as $table)
			{
				$sql = 'SELECT *
					FROM ' . $table . '
					WHERE auth_option_id = ' . $old_id;
				$result = $db->sql_query($sql);

				$sql_ary = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$row['auth_option_id'] = $new_id;
					$sql_ary[] = $row;
				}
				$db->sql_freeresult($result);

				if (sizeof($sql_ary))
				{
					$db->sql_multi_insert($table, $sql_ary);
				}
			}

			// Remove any old permission entries
			$auth_admin->acl_clear_prefetch();
		}

		// Add f_karma_view permission, only if it doesn not already exist
		if (!isset($auth_admin->acl_options['id']['f_karma_view']))
		{
			$auth_admin->acl_add_option(array('local' => array('f_karma_view')));

			// Now the tricky part, filling the permission
			$old_id = $auth_admin->acl_options['id']['f_read'];
			$new_id = $auth_admin->acl_options['id']['f_karma_view'];

			$tables = array(ACL_GROUPS_TABLE, ACL_ROLES_DATA_TABLE, ACL_USERS_TABLE);

			foreach ($tables as $table)
			{
				$sql = 'SELECT *
					FROM ' . $table . '
					WHERE auth_option_id = ' . $old_id;
				$result = $db->sql_query($sql);

				$sql_ary = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$row['auth_option_id'] = $new_id;
					$sql_ary[] = $row;
				}
				$db->sql_freeresult($result);

				if (sizeof($sql_ary))
				{
					$db->sql_multi_insert($table, $sql_ary);
				}
			}

			// Remove any old permission entries
			$auth_admin->acl_clear_prefetch();
		}
	}

	/**
	* Clear template cache for modified files
	*/
	function clear_template_cache($mode, $sub)
	{
		global $phpbb_root_path, $phpEx, $db;

		include_once($phpbb_root_path . 'includes/acp/acp_styles.' . $phpEx);

		$_styles = &new acp_styles();

		$file_ary = array(
			'index_body',
			'jumpbox',
			'memberlist_view',
			'viewtopic_body',
		);

		$sql = 'SELECT *
			FROM ' . STYLES_TEMPLATE_TABLE;
		$result = $db->sql_query($sql);
		while ($template_row = $db->sql_fetchrow($result))
		{
			// We wants to delete all modified files ...
			$_styles->clear_template_cache($template_row, $file_ary);
		}
		$db->sql_freeresult($result);
	}

	/**
	* Update db css cache
	*/
	function clear_theme_cache($mode, $sub)
	{
		global $phpbb_root_path, $phpEx, $db, $cache;

		include_once($phpbb_root_path . 'includes/acp/acp_styles.' . $phpEx);

		$_styles = &new acp_styles();

		$sql = 'SELECT s.style_id, st.theme_id, st.theme_storedb, st.theme_path
			FROM ' . STYLES_TABLE . ' s, ' . STYLES_THEME_TABLE . ' st
			WHERE s.theme_id = st.theme_id';
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$path = $row['theme_path'];
			$sql_ary = array(
				'theme_storedb'	=> $row['theme_storedb'],
				'theme_data'	=> ($row['theme_storedb']) ? $_styles->db_theme_data($row, false) : '',
				'theme_mtime'	=> filemtime("{$phpbb_root_path}styles/$path/theme/stylesheet.css")
			);

			$sql = 'UPDATE ' . STYLES_THEME_TABLE . '
				SET ' . $db->sql_build_array('UPDATE', $sql_ary) . '
				WHERE theme_id = ' . $row['theme_id'];
			if (!$db->sql_query($sql))
			{
				$error = $db->sql_error();
				$this->p_master->db_error($error['message'], $sql, __LINE__, __FILE__);
			}
		}
		$cache->destroy('sql', STYLES_THEME_TABLE);
	}

	/**
	* Update db imageset cache
	*/
	function clear_imageset_cache($mode, $sub)
	{
		global $db, $cache;

		$sql = 'SELECT imageset_id
			FROM ' . STYLES_IMAGESET_TABLE;
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$imageset_id = $row['imageset_id'];

			foreach ($this->imageset_data as $data)
			{
				$sql_ary = array(
					'image_name'		=> $data['name'],
					'image_filename'	=> $data['filename'],
					'image_lang'		=> $data['lang'],
					'image_height'		=> $data['height'],
					'image_width'		=> $data['width'],
					'imageset_id'		=> $imageset_id
				);
				$sql = 'INSERT INTO ' . STYLES_IMAGESET_DATA_TABLE . '
					' . $db->sql_build_array('INSERT', $sql_ary);
				if (!$db->sql_query($sql))
				{
					$error = $db->sql_error();
					$this->p_master->db_error($error['message'], $sql, __LINE__, __FILE__);
				}
			}
		}

		$cache->destroy('sql', STYLES_IMAGESET_DATA_TABLE);
	}

	/**
	* Add a note to the log and show congrats
	*/
	function congrats($mode, $sub)
	{
		global $config, $user, $template, $phpbb_root_path, $phpEx;

		$this->page_title = $user->lang['STAGE_FINAL'];

		// OK, Now that we've reached this point we can be confident that everything
		// is installed and working......I hope :)
		// And add a note to the log
		add_log('admin', 'LOG_KARMA_INSTALLED', $config['karma_version']);

		$template->assign_vars(array(
			'TITLE'		=> $user->lang['INSTALL_KARMA_CONGRATS'],
			'BODY'		=> sprintf($user->lang['INSTALL_KARMA_CONGRATS_EXPLAIN'], $config['karma_version']),
			'L_SUBMIT'	=> $user->lang['ACP'],
			'U_ACTION'	=> append_sid($phpbb_root_path . 'adm/index.' . $phpEx),
		));
	}

	/**
	* The variables that we will be passing between pages
	* Used to retrieve data quickly on each page
	*/
	var $request_vars = array('karma_enabled_ucp', 'karma_notify_pm', 'karma_notify_email', 'karma_drafts', 'karma_icons', 'karma_toplist', 'karma_toplist_users', 'karma_time', 'karma_posts', 'karma_comments_per_page', 'karma_power');

	/**
	* The information below will be used to build the input fields presented to the user
	*/
	var $admin_config_options = array(
		'legend1'					=> 'ACP_KARMA_SETTINGS',
		'karma_power'				=> array('lang' => 'ACP_KARMA_POWER',				'validate' => 'bool',	'type' => 'radio:yes_no',	'value'	=> '1',	'explain' => false),
		'karma_enabled_ucp'			=> array('lang' => 'ACP_KARMA_ENABLED_UCP',			'validate' => 'bool',	'type' => 'radio:yes_no',	'value'	=> '0',	'explain' => true),
		'karma_icons'				=> array('lang' => 'ACP_KARMA_ICONS',				'validate' => 'bool',	'type' => 'radio:yes_no',	'value'	=> '0',	'explain' => true),
		'karma_toplist'				=> array('lang' => 'ACP_KARMA_TOPLIST',				'validate' => 'bool',	'type' => 'radio:yes_no',	'value'	=> '1',	'explain' => true),
		'karma_toplist_users'		=> array('lang' => 'ACP_KARMA_TOPLIST_USERS',		'validate' => 'int',	'type' => 'text:3:4',		'value'	=> '20','explain' => true),
		'karma_time'				=> array('lang' => 'ACP_KARMA_TIME',				'validate' => 'string',	'type' => 'text:3:4',		'value'	=> '6',	'explain' => true,	'append' => 'ACP_KARMA_APPEND_TIME'),
		'karma_posts'				=> array('lang' => 'ACP_KARMA_POSTS',				'validate' => 'int',	'type' => 'text:3:4',		'value'	=> '15','explain' => true,	'append' => 'ACP_KARMA_APPEND_POSTS'),
		'karma_comments_per_page'	=> array('lang' => 'ACP_KARMA_COMMENTS_PER_PAGE',	'validate' => 'int',	'type' => 'text:3:4',		'value'	=> '15','explain' => false,	'append' => 'ACP_KARMA_APPEND_COMMENTS'),
	);

	/**
	* Define the module structure so that we can populate the database without
	* needing to hard-code module_id values
	*/
	var $modules = array(
		'acp'	=> array(
			'ACP_BOARD_CONFIGURATION' => array(
				array(
					'ACP_KARMA',
					'karma',
				),
			),
			'ACP_FORUM_LOGS' => array(
				array(
					'ACP_KARMA_HISTORY',
					'history',
				),
			),
			'ACP_AUTOMATION' => array(
				array(
					'ACP_KARMA_VERSION_CHECK_MENU',
					'updater',
				),
			),
		),
		'ucp'	=> array(
			'UCP_PREFS'	=> array(
				array(
					'UCP_KARMA',
					'karma',
				),
			),
		),
	);

	/**
	* Define the imageset data so the we can more easy populate the database
	*/
	var $imageset_data = array(
		array(
			'name'		=> 'icon_karma_decrease',
			'filename'	=> 'icon_karma_decrease.gif',
			'lang'		=> '',
			'height'	=> '20',
			'width'		=> '20',
		),
		array(
			'name'		=> 'icon_karma_increase',
			'filename'	=> 'icon_karma_increase.gif',
			'lang'		=> '',
			'height'	=> '20',
			'width'		=> '20',
		),
	);
}

?>