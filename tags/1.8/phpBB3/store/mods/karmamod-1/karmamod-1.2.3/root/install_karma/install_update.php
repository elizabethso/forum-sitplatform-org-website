<?php
/** 
*
* @package karmamod (install)
* @version $Id: install_update.php,v 9041.9187.32 2009/10/17 23:38:32 m157y Exp $
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
		'module_title'		=> 'UPDATE',
		'module_filename'	=> substr(basename(__FILE__), 0, -strlen($phpEx)-1),
		'module_order'		=> 10,
		'module_subs'		=> '',
		'module_stages'		=> array('INTRO', 'REQUIREMENTS', 'UPDATE', 'FINAL'),
		'module_reqs'		=> ''
	);
}

/**
* Updating
* @package karmamod (install)
*/
class install_update extends module
{
	function install_update(&$p_master)
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
					'BODY'			=> $user->lang['INSTALL_KARMA_INTRO_BODY_UPDATE'],
					'L_SUBMIT'		=> $user->lang['NEXT_STEP'],
					'U_ACTION'		=> $this->p_master->module_url . "?mode=$mode&amp;sub=requirements",
				));

			break;

			case 'requirements' :
				$this->check_forum_requirements($mode, $sub);

			break;

			case 'update':
				$this->update($mode, $sub);
			break;

			case 'final' :
				$this->clear_template_cache($mode, $sub);
				$this->clear_theme_cache($mode, $sub);
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
		global $user, $config, $db, $auth, $template, $karmamod, $dbms, $phpbb_root_path;

		$this->page_title = $user->lang['INSTALL_KARMA_STAGE_REQUIREMENTS'];

		$template->assign_vars(array(
			'TITLE'		=> $user->lang['INSTALL_KARMA_REQUIREMENTS_UPDATE_TITLE'],
			'BODY'		=> $user->lang['INSTALL_KARMA_REQUIREMENTS_UPDATE_EXPLAIN'],
		));

		$passed = array('karmamod' => false, 'phpbb' => false, 'db' => false, 'files' => false);

		// Test for basic Karma MOD settings
		$template->assign_block_vars('checks', array(
			'S_LEGEND'			=> true,
			'LEGEND'			=> $user->lang['INSTALL_KARMA_SETTINGS'],
			'LEGEND_EXPLAIN'	=> $user->lang['INSTALL_KARMA_SETTINGS_EXPLAIN'],
		));

		// Check current Karma MOD version
		if (version_compare($karmamod->version, $this->version, '<'))
		{
			$result = '<strong style="color:green">' . $user->lang['YES'] . '</strong>';
			$passed['karmamod'] = true;
		}
		else
		{
			$result = '<strong style="color:red">' . $user->lang['NO'] . '</strong>';
		}

		$template->assign_block_vars('checks', array(
			'TITLE'			=> $user->lang['INSTALL_KARMA_VERSION'],
			'RESULT'		=> $karmamod->version,

			'S_EXPLAIN'		=> false,
			'S_LEGEND'		=> false,
		));

		$template->assign_block_vars('checks', array(
			'TITLE'			=> $user->lang['INSTALL_KARMA_VERSION_CURRENT'],
			'RESULT'		=> $this->version,

			'S_EXPLAIN'		=> false,
			'S_LEGEND'		=> false,
		));

		$template->assign_block_vars('checks', array(
			'TITLE'			=> $user->lang['INSTALL_KARMA_VERSION_NEED_UPDATE'],
			'RESULT'		=> $result,

			'S_EXPLAIN'		=> false,
			'S_LEGEND'		=> false,
		));

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

			$result = '<strong style="color:green">' . $user->lang['YES'] . '</strong>';
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
		if(!$auth->acl_get('u_savedrafts'))
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
				if ($row['theme_path'] == 'prosilver')
				{
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
		$url = (!in_array(false, $passed)) ? $this->p_master->module_url . "?mode=$mode&amp;sub=update" : $this->p_master->module_url . "?mode=$mode&amp;sub=requirements";
		$submit = (!in_array(false, $passed)) ? $user->lang['INSTALL_KARMA_UDPATE_START'] : $user->lang['INSTALL_KARMA_UDPATE_TEST'];


		$template->assign_vars(array(
			'L_SUBMIT'	=> $submit,
			'U_ACTION'	=> $url,
		));
	}

	/**
	* Load the contents of the schema into the database and then alter it based on what has been input during the installation
	*/
	function update($mode, $sub)
	{
		global $dbms, $db, $user, $template, $karmamod, $table_prefix, $config, $auth, $cache;

		$this->page_title = $user->lang['INSTALL_KARMA_STAGE_UPDATE'];

		// Determine mapping database type
		switch ($db->sql_layer)
		{
			case 'mysql':
				$map_dbms = 'mysql_40';
			break;

			case 'mysql4':
				if (version_compare($db->sql_server_info(true), '4.1.3', '>='))
				{
					$map_dbms = 'mysql_41';
				}
				else
				{
					$map_dbms = 'mysql_40';
				}
			break;

			case 'mysqli':
				$map_dbms = 'mysql_41';
			break;

			case 'mssql':
			case 'mssql_odbc':
				$map_dbms = 'mssql';
			break;

			default:
				$map_dbms = $db->sql_layer;
			break;
		}

		$error_ary = array();
		$errored = false;

		$current_version = str_replace('rc', 'RC', strtolower($config['karma_version']));
		$latest_version = str_replace('rc', 'RC', strtolower($this->version));
		$orig_version = $config['karma_version'];

		// We go through the schema changes from the lowest to the highest version
		// We try to also include versions 'in-between'...
		$no_updates = true;
		$versions = array_keys($this->database_update_info);
		for ($i = 0; $i < sizeof($versions); $i++)
		{
			
			$version = $versions[$i];
			$schema_changes = $this->database_update_info[$version];

			$next_version = (isset($versions[$i + 1])) ? $versions[$i + 1] : $this->version;

			// If the installed version to be updated to is < than the current version, and if the current version is >= as the version to be updated to next, we will skip the process
			if (version_compare($version, $current_version, '<') && version_compare($current_version, $next_version, '>='))
			{
				continue;
			}

			if (!sizeof($schema_changes))
			{
				continue;
			}

			$no_updates = false;

			// Change columns?
			if (!empty($schema_changes['change_columns']))
			{
				foreach ($schema_changes['change_columns'] as $table => $columns)
				{
					foreach ($columns as $column_name => $column_data)
					{
						$this->sql_column_change($map_dbms, $table, $column_name, $column_data);
					}
				}
			}

			// Add columns?
			if (!empty($schema_changes['add_columns']))
			{
				foreach ($schema_changes['add_columns'] as $table => $columns)
				{
					foreach ($columns as $column_name => $column_data)
					{
						// Only add the column if it does not exist yet
						if (!$this->column_exists($map_dbms, $table, $column_name))
						{
							$this->sql_column_add($map_dbms, $table, $column_name, $column_data);
						}
					}
				}
			}

			// Remove keys?
			if (!empty($schema_changes['drop_keys']))
			{
				foreach ($schema_changes['drop_keys'] as $table => $indexes)
				{
					foreach ($indexes as $index_name)
					{
						$this->sql_index_drop($map_dbms, $index_name, $table);
					}
				}
			}

			// Drop columns?
			if (!empty($schema_changes['drop_columns']))
			{
				foreach ($schema_changes['drop_columns'] as $table => $columns)
				{
					foreach ($columns as $column)
					{
						$this->sql_column_remove($map_dbms, $table, $column);
					}
				}
			}

			// Add primary keys?
			if (!empty($schema_changes['add_primary_keys']))
			{
				foreach ($schema_changes['add_primary_keys'] as $table => $columns)
				{
					$this->sql_create_primary_key($map_dbms, $table, $columns);
				}
			}

			// Add unqiue indexes?
			if (!empty($schema_changes['add_unique_index']))
			{
				foreach ($schema_changes['add_unique_index'] as $table => $index_array)
				{
					foreach ($index_array as $index_name => $column)
					{
						$this->sql_create_unique_index($map_dbms, $index_name, $table, $column);
					}
				}
			}

			// Add indexes?
			if (!empty($schema_changes['add_index']))
			{
				foreach ($schema_changes['add_index'] as $table => $index_array)
				{
					foreach ($index_array as $index_name => $column)
					{
						$this->sql_create_index($map_dbms, $index_name, $table, $column);
					}
				}
			}
		}

		$no_updates = true;
		$versions = array_keys($this->database_update_info);

		// some code magic
		for ($i = 0; $i < sizeof($versions); $i++)
		{
			$version = $versions[$i];
			$next_version = (isset($versions[$i + 1])) ? $versions[$i + 1] : $this->version;

			// If the installed version to be updated to is < than the current version, and if the current version is >= as the version to be updated to next, we will skip the process
			if (version_compare($version, $current_version, '<') && version_compare($current_version, $next_version, '>='))
			{
				continue;
			}

			$this->change_database_data($no_updates, $version);
		}

		// update the version
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '{$this->version}'
			WHERE config_name = 'karma_version'";
		$this->_sql($sql, $errored, $error_ary);

		// Add database update to log
		add_log('admin', 'LOG_KARMA_UPDATED', $orig_version, $this->version);

		// clear the config table cache now
		$cache->destroy('_global');

		$submit = $user->lang['NEXT_STEP'];

		$url = $this->p_master->module_url . "?mode=$mode&amp;sub=final";

		$template->assign_vars(array(
			'BODY'		=> $user->lang['INSTALL_KARMA_STAGE_UPDATE_EXPLAIN'],
			'L_SUBMIT'	=> $submit,
			'U_ACTION'	=> $url,
		));
	}

	/**
	* Populate the module tables
	*/
	function add_modules()
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
	* Add a note to the log and show congrats
	*/
	function congrats($mode, $sub)
	{
		global $config, $user, $template, $phpbb_root_path, $phpEx;

		$this->page_title = $user->lang['STAGE_FINAL'];

		// OK, Now that we've reached this point we can be confident that everything
		// is installed and working......I hope :)

		$template->assign_vars(array(
			'TITLE'		=> $user->lang['INSTALL_KARMA_CONGRATS_UPDATE'],
			'BODY'		=> sprintf($user->lang['INSTALL_KARMA_CONGRATS_UPDATE_EXPLAIN'], $this->version),
			'L_SUBMIT'	=> $user->lang['ACP'],
			'U_ACTION'	=> append_sid($phpbb_root_path . 'adm/index.' . $phpEx),
		));
	}

	/**
	* Some help functions placed below
	*/
	/**
	* Function for triggering an sql statement
	*/
	function _sql($sql, &$errored, &$error_ary)
	{
		global $db;
	
		$db->sql_return_on_error(true);
	
		$result = $db->sql_query($sql);
		if ($db->sql_error_triggered)
		{
			$errored = true;
			$error_ary['sql'][] = $db->sql_error_sql;
			$error_ary['error_code'][] = $db->_sql_error();
		}

		$db->sql_return_on_error(false);
	
		return $result;
	}
	
	/**
	* Check if a specified column exist
	*/
	function column_exists($dbms, $table, $column_name)
	{
		global $db;
	
		switch ($dbms)
		{
			case 'mysql_40':
			case 'mysql_41':
				$sql = "SHOW COLUMNS
					FROM $table";
				$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result))
				{
					// lower case just in case
					if (strtolower($row['Field']) == $column_name)
					{
						$db->sql_freeresult($result);
						return true;
					}
				}
				$db->sql_freeresult($result);
				return false;
			break;
	
			// PostgreSQL has a way of doing this in a much simpler way but would
			// not allow us to support all versions of PostgreSQL
			case 'postgres':
				$sql = "SELECT a.attname
					FROM pg_class c, pg_attribute a
					WHERE c.relname = '{$table}'
						AND a.attnum > 0
						AND a.attrelid = c.oid";
				$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result))
				{
					// lower case just in case
					if (strtolower($row['attname']) == $column_name)
					{
						$db->sql_freeresult($result);
						return true;
					}
				}
				$db->sql_freeresult($result);
				return false;
			break;
	
			// same deal with PostgreSQL, we must perform more complex operations than
			// we technically could
			case 'mssql':
				$sql = "SELECT c.name
					FROM syscolumns c
					LEFT JOIN sysobjects o ON c.id = o.id
					WHERE o.name = '{$table}'";
				$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result))
				{
					// lower case just in case
					if (strtolower($row['name']) == $column_name)
					{
						$db->sql_freeresult($result);
						return true;
					}
				}
				$db->sql_freeresult($result);
				return false;
			break;
	
			case 'oracle':
				$sql = "SELECT column_name
					FROM user_tab_columns
					WHERE table_name = '{$table}'";
				$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result))
				{
					// lower case just in case
					if (strtolower($row['column_name']) == $column_name)
					{
						$db->sql_freeresult($result);
						return true;
					}
				}
				$db->sql_freeresult($result);
				return false;
			break;
	
			case 'firebird':
				$sql = "SELECT RDB\$FIELD_NAME as FNAME
					FROM RDB\$RELATION_FIELDS
					WHERE RDB\$RELATION_NAME = '{$table}'";
				$result = $db->sql_query($sql);
				while ($row = $db->sql_fetchrow($result))
				{
					// lower case just in case
					if (strtolower($row['fname']) == $column_name)
					{
						$db->sql_freeresult($result);
						return true;
					}
				}
				$db->sql_freeresult($result);
				return false;
			break;
	
			// ugh, SQLite
			case 'sqlite':
				$sql = "SELECT sql
					FROM sqlite_master
					WHERE type = 'table'
						AND name = '{$table}'";
				$result = $db->sql_query($sql);
	
				if (!$result)
				{
					return false;
				}
	
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
	
				preg_match('#\((.*)\)#s', $row['sql'], $matches);
	
				$cols = trim($matches[1]);
				$col_array = preg_split('/,(?![\s\w]+\))/m', $cols);
	
				foreach ($col_array as $declaration)
				{
					$entities = preg_split('#\s+#', trim($declaration));
					if ($entities[0] == 'PRIMARY')
					{
						continue;
					}
	
					if (strtolower($entities[0]) == $column_name)
					{
						return true;
					}
				}
				return false;
			break;
		}
	}
	
	/**
	* Function to prepare some column information for better usage
	*/
	function prepare_column_data($dbms, $column_data, $table_name, $column_name)
	{
		$dbms_type_map = $this->dbms_type_map;
		$unsigned_types = $this->unsigned_types;
	
		// Get type
		if (strpos($column_data[0], ':') !== false)
		{
			list($orig_column_type, $column_length) = explode(':', $column_data[0]);
	
			if (!is_array($dbms_type_map[$dbms][$orig_column_type . ':']))
			{
				$column_type = sprintf($dbms_type_map[$dbms][$orig_column_type . ':'], $column_length);
			}
			else
			{
				if (isset($dbms_type_map[$dbms][$orig_column_type . ':']['rule']))
				{
					switch ($dbms_type_map[$dbms][$orig_column_type . ':']['rule'][0])
					{
						case 'div':
							$column_length /= $dbms_type_map[$dbms][$orig_column_type . ':']['rule'][1];
							$column_length = ceil($column_length);
							$column_type = sprintf($dbms_type_map[$dbms][$orig_column_type . ':'][0], $column_length);
						break;
					}
				}
	
				if (isset($dbms_type_map[$dbms][$orig_column_type . ':']['limit']))
				{
					switch ($dbms_type_map[$dbms][$orig_column_type . ':']['limit'][0])
					{
						case 'mult':
							$column_length *= $dbms_type_map[$dbms][$orig_column_type . ':']['limit'][1];
							if ($column_length > $dbms_type_map[$dbms][$orig_column_type . ':']['limit'][2])
							{
								$column_type = $dbms_type_map[$dbms][$orig_column_type . ':']['limit'][3];
							}
							else
							{
								$column_type = sprintf($dbms_type_map[$dbms][$orig_column_type . ':'][0], $column_length);
							}
						break;
					}
				}
			}
			$orig_column_type .= ':';
		}
		else
		{
			$orig_column_type = $column_data[0];
			$column_type = $dbms_type_map[$dbms][$column_data[0]];
		}
	
		// Adjust default value if db-dependant specified
		if (is_array($column_data[1]))
		{
			$column_data[1] = (isset($column_data[1][$dbms])) ? $column_data[1][$dbms] : $column_data[1]['default'];
		}
	
		$sql = '';
		$return_array = array();
	
		switch ($dbms)
		{
			case 'firebird':
				$sql .= " {$column_type} ";
	
				if (!is_null($column_data[1]))
				{
					$sql .= 'DEFAULT ' . ((is_numeric($column_data[1])) ? $column_data[1] : "'{$column_data[1]}'") . ' ';
				}
	
				$sql .= 'NOT NULL';
	
				// This is a UNICODE column and thus should be given it's fair share
				if (preg_match('/^X?STEXT_UNI|VCHAR_(CI|UNI:?)/', $column_data[0]))
				{
					$sql .= ' COLLATE UNICODE';
				}
	
			break;
	
			case 'mssql':
				$sql .= " {$column_type} ";
				$sql_default = " {$column_type} ";
	
				// For adding columns we need the default definition
				if (!is_null($column_data[1]))
				{
					// For hexadecimal values do not use single quotes
					if (strpos($column_data[1], '0x') === 0)
					{
						$sql_default .= 'DEFAULT (' . $column_data[1] . ') ';
					}
					else
					{
						$sql_default .= 'DEFAULT (' . ((is_numeric($column_data[1])) ? $column_data[1] : "'{$column_data[1]}'") . ') ';
					}
				}
	
				$sql .= 'NOT NULL';
				$sql_default .= 'NOT NULL';
	
				$return_array['column_type_sql_default'] = $sql_default;
			break;
	
			case 'mysql_40':
			case 'mysql_41':
				$sql .= " {$column_type} ";
	
				// For hexadecimal values do not use single quotes
				if (!is_null($column_data[1]) && substr($column_type, -4) !== 'text' && substr($column_type, -4) !== 'blob')
				{
					$sql .= (strpos($column_data[1], '0x') === 0) ? "DEFAULT {$column_data[1]} " : "DEFAULT '{$column_data[1]}' ";
				}
				$sql .= 'NOT NULL';
	
				if (isset($column_data[2]))
				{
					if ($column_data[2] == 'auto_increment')
					{
						$sql .= ' auto_increment';
					}
					else if ($dbms === 'mysql_41' && $column_data[2] == 'true_sort')
					{
						$sql .= ' COLLATE utf8_unicode_ci';
					}
				}
	
			break;
	
			case 'oracle':
				$sql .= " {$column_type} ";
				$sql .= (!is_null($column_data[1])) ? "DEFAULT '{$column_data[1]}' " : '';
	
				// In Oracle empty strings ('') are treated as NULL.
				// Therefore in oracle we allow NULL's for all DEFAULT '' entries
				// Oracle does not like setting NOT NULL on a column that is already NOT NULL (this happens only on number fields)
				if (preg_match('/number/i', $column_type))
				{
					$sql .= ($column_data[1] === '') ? '' : 'NOT NULL';
				}
			break;
	
			case 'postgres':
				$return_array['column_type'] = $column_type;
	
				$sql .= " {$column_type} ";
	
				if (isset($column_data[2]) && $column_data[2] == 'auto_increment')
				{
					$default_val = "nextval('{$table_name}_seq')";
				}
				else if (!is_null($column_data[1]))
				{
					$default_val = "'" . $column_data[1] . "'";
					$return_array['null'] = 'NOT NULL';
					$sql .= 'NOT NULL ';
				}
	
				$return_array['default'] = $default_val;
	
				$sql .= "DEFAULT {$default_val}";
	
				// Unsigned? Then add a CHECK contraint
				if (in_array($orig_column_type, $unsigned_types))
				{
					$return_array['constraint'] = "CHECK ({$column_name} >= 0)";
					$sql .= " CHECK ({$column_name} >= 0)";
				}
			break;
	
			case 'sqlite':
				if (isset($column_data[2]) && $column_data[2] == 'auto_increment')
				{
					$sql .= ' INTEGER PRIMARY KEY';
				}
				else
				{
					$sql .= ' ' . $column_type;
				}
	
				$sql .= ' NOT NULL ';
				$sql .= (!is_null($column_data[1])) ? "DEFAULT '{$column_data[1]}'" : '';
			break;
		}
	
		$return_array['column_type_sql'] = $sql;
	
		return $return_array;
	}
	
	/**
	* Add new column
	*/
	function sql_column_add($dbms, $table_name, $column_name, $column_data)
	{
		global $errored, $error_ary;
	
		$column_data = $this->prepare_column_data($dbms, $column_data, $table_name, $column_name);
	
		switch ($dbms)
		{
			case 'firebird':
				$sql = 'ALTER TABLE "' . $table_name . '" ADD "' . $column_name . '" ' . $column_data['column_type_sql'];
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mssql':
				$sql = 'ALTER TABLE [' . $table_name . '] ADD [' . $column_name . '] ' . $column_data['column_type_sql_default'];
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mysql_40':
			case 'mysql_41':
				$sql = 'ALTER TABLE `' . $table_name . '` ADD COLUMN `' . $column_name . '` ' . $column_data['column_type_sql'];
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'oracle':
				$sql = 'ALTER TABLE ' . $table_name . ' ADD ' . $column_name . ' ' . $column_data['column_type_sql'];
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'postgres':
				$sql = 'ALTER TABLE ' . $table_name . ' ADD COLUMN "' . $column_name . '" ' . $column_data['column_type_sql'];
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'sqlite':
				if (version_compare(sqlite_libversion(), '3.0') == -1)
				{
					global $db;
					$sql = "SELECT sql
						FROM sqlite_master
						WHERE type = 'table'
							AND name = '{$table_name}'
						ORDER BY type DESC, name;";
					$result = $db->sql_query($sql);
	
					if (!$result)
					{
						break;
					}
	
					$row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
	
					$db->sql_transaction('begin');
	
					// Create a backup table and populate it, destroy the existing one
					$db->sql_query(preg_replace('#CREATE\s+TABLE\s+"?' . $table_name . '"?#i', 'CREATE TEMPORARY TABLE ' . $table_name . '_temp', $row['sql']));
					$db->sql_query('INSERT INTO ' . $table_name . '_temp SELECT * FROM ' . $table_name);
					$db->sql_query('DROP TABLE ' . $table_name);
	
					preg_match('#\((.*)\)#s', $row['sql'], $matches);
	
					$new_table_cols = trim($matches[1]);
					$old_table_cols = preg_split('/,(?![\s\w]+\))/m', $new_table_cols);
					$column_list = array();
	
					foreach ($old_table_cols as $declaration)
					{
						$entities = preg_split('#\s+#', trim($declaration));
						if ($entities[0] == 'PRIMARY')
						{
							continue;
						}
						$column_list[] = $entities[0];
					}
	
					$columns = implode(',', $column_list);
	
					$new_table_cols = $column_name . ' ' . $column_data['column_type_sql'] . ',' . $new_table_cols;
	
					// create a new table and fill it up. destroy the temp one
					$db->sql_query('CREATE TABLE ' . $table_name . ' (' . $new_table_cols . ');');
					$db->sql_query('INSERT INTO ' . $table_name . ' (' . $columns . ') SELECT ' . $columns . ' FROM ' . $table_name . '_temp;');
					$db->sql_query('DROP TABLE ' . $table_name . '_temp');
	
					$db->sql_transaction('commit');
				}
				else
				{
					$sql = 'ALTER TABLE ' . $table_name . ' ADD ' . $column_name . ' [' . $column_data['column_type_sql'] . ']';
					$this->_sql($sql, $errored, $error_ary);
				}
			break;
		}
	}
	
	/**
	* Drop column
	*/
	function sql_column_remove($dbms, $table_name, $column_name)
	{
		global $errored, $error_ary;
	
		switch ($dbms)
		{
			case 'firebird':
				$sql = 'ALTER TABLE "' . $table_name . '" DROP "' . $column_name . '"';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mssql':
				$sql = 'ALTER TABLE [' . $table_name . '] DROP COLUMN [' . $column_name . ']';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mysql_40':
			case 'mysql_41':
				$sql = 'ALTER TABLE `' . $table_name . '` DROP COLUMN `' . $column_name . '`';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'oracle':
				$sql = 'ALTER TABLE ' . $table_name . ' DROP ' . $column_name;
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'postgres':
				$sql = 'ALTER TABLE ' . $table_name . ' DROP COLUMN "' . $column_name . '"';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'sqlite':
				if (version_compare(sqlite_libversion(), '3.0') == -1)
				{
					global $db;
					$sql = "SELECT sql
						FROM sqlite_master
						WHERE type = 'table'
							AND name = '{$table_name}'
						ORDER BY type DESC, name;";
					$result = $db->sql_query($sql);
	
					if (!$result)
					{
						break;
					}
	
					$row = $db->sql_fetchrow($result);
					$db->sql_freeresult($result);
	
					$db->sql_transaction('begin');
	
					// Create a backup table and populate it, destroy the existing one
					$db->sql_query(preg_replace('#CREATE\s+TABLE\s+"?' . $table_name . '"?#i', 'CREATE TEMPORARY TABLE ' . $table_name . '_temp', $row['sql']));
					$db->sql_query('INSERT INTO ' . $table_name . '_temp SELECT * FROM ' . $table_name);
					$db->sql_query('DROP TABLE ' . $table_name);
	
					preg_match('#\((.*)\)#s', $row['sql'], $matches);
	
					$new_table_cols = trim($matches[1]);
					$old_table_cols = preg_split('/,(?![\s\w]+\))/m', $new_table_cols);
					$column_list = array();
	
					foreach ($old_table_cols as $declaration)
					{
						$entities = preg_split('#\s+#', trim($declaration));
						if ($entities[0] == 'PRIMARY' || $entities[0] === $column_name)
						{
							continue;
						}
						$column_list[] = $entities[0];
					}
	
					$columns = implode(',', $column_list);
	
					$new_table_cols = $new_table_cols = preg_replace('/' . $column_name . '[^,]+(?:,|$)/m', '', $new_table_cols);
	
					// create a new table and fill it up. destroy the temp one
					$db->sql_query('CREATE TABLE ' . $table_name . ' (' . $new_table_cols . ');');
					$db->sql_query('INSERT INTO ' . $table_name . ' (' . $columns . ') SELECT ' . $columns . ' FROM ' . $table_name . '_temp;');
					$db->sql_query('DROP TABLE ' . $table_name . '_temp');
	
					$db->sql_transaction('commit');
				}
				else
				{
					$sql = 'ALTER TABLE ' . $table_name . ' DROP COLUMN ' . $column_name;
					$this->_sql($sql, $errored, $error_ary);
				}
			break;
		}
	}
	
	function sql_index_drop($dbms, $index_name, $table_name)
	{
		global $dbms_type_map, $db;
		global $errored, $error_ary;
	
		switch ($dbms)
		{
			case 'mssql':
				$sql = 'DROP INDEX ' . $table_name . '.' . $index_name;
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mysql_40':
			case 'mysql_41':
				$sql = 'DROP INDEX ' . $index_name . ' ON ' . $table_name;
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'firebird':
			case 'oracle':
			case 'postgres':
			case 'sqlite':
				$sql = 'DROP INDEX ' . $table_name . '_' . $index_name;
				$this->_sql($sql, $errored, $error_ary);
			break;
		}
	}
	
	function sql_create_primary_key($dbms, $table_name, $column)
	{
		global $dbms_type_map, $db;
		global $errored, $error_ary;
	
		switch ($dbms)
		{
			case 'firebird':
			case 'postgres':
				$sql = 'ALTER TABLE ' . $table_name . ' ADD PRIMARY KEY (' . implode(', ', $column) . ')';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mssql':
				$sql = "ALTER TABLE [{$table_name}] WITH NOCHECK ADD ";
				$sql .= "CONSTRAINT [PK_{$table_name}] PRIMARY KEY  CLUSTERED (";
				$sql .= '[' . implode("],\n\t\t[", $column) . ']';
				$sql .= ') ON [PRIMARY]';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mysql_40':
			case 'mysql_41':
				$sql = 'ALTER TABLE ' . $table_name . ' ADD PRIMARY KEY (' . implode(', ', $column) . ')';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'oracle':
				$sql = 'ALTER TABLE ' . $table_name . 'add CONSTRAINT pk_' . $table_name . ' PRIMARY KEY (' . implode(', ', $column) . ')';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'sqlite':
				$sql = "SELECT sql
					FROM sqlite_master
					WHERE type = 'table'
						AND name = '{$table_name}'
					ORDER BY type DESC, name;";
				$result = $this->_sql($sql, $errored, $error_ary);
	
				if (!$result)
				{
					break;
				}
	
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
	
				$db->sql_transaction('begin');
	
				// Create a backup table and populate it, destroy the existing one
				$db->sql_query(preg_replace('#CREATE\s+TABLE\s+"?' . $table_name . '"?#i', 'CREATE TEMPORARY TABLE ' . $table_name . '_temp', $row['sql']));
				$db->sql_query('INSERT INTO ' . $table_name . '_temp SELECT * FROM ' . $table_name);
				$db->sql_query('DROP TABLE ' . $table_name);
	
				preg_match('#\((.*)\)#s', $row['sql'], $matches);
	
				$new_table_cols = trim($matches[1]);
				$old_table_cols = preg_split('/,(?![\s\w]+\))/m', $new_table_cols);
				$column_list = array();
	
				foreach ($old_table_cols as $declaration)
				{
					$entities = preg_split('#\s+#', trim($declaration));
					if ($entities[0] == 'PRIMARY')
					{
						continue;
					}
					$column_list[] = $entities[0];
				}
	
				$columns = implode(',', $column_list);
	
				// create a new table and fill it up. destroy the temp one
				$db->sql_query('CREATE TABLE ' . $table_name . ' (' . $new_table_cols . ', PRIMARY KEY (' . implode(', ', $column) . '));');
				$db->sql_query('INSERT INTO ' . $table_name . ' (' . $columns . ') SELECT ' . $columns . ' FROM ' . $table_name . '_temp;');
				$db->sql_query('DROP TABLE ' . $table_name . '_temp');
	
				$db->sql_transaction('commit');
			break;
		}
	}
	
	function sql_create_unique_index($dbms, $index_name, $table_name, $column)
	{
		global $dbms_type_map, $db;
		global $errored, $error_ary;
	
		switch ($dbms)
		{
			case 'firebird':
			case 'postgres':
			case 'oracle':
			case 'sqlite':
				$sql = 'CREATE UNIQUE INDEX ' . $table_name . '_' . $index_name . ' ON ' . $table_name . '(' . implode(', ', $column) . ')';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mysql_40':
			case 'mysql_41':
				$sql = 'CREATE UNIQUE INDEX ' . $index_name . ' ON ' . $table_name . '(' . implode(', ', $column) . ')';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mssql':
				$sql = 'CREATE UNIQUE INDEX ' . $index_name . ' ON ' . $table_name . '(' . implode(', ', $column) . ') ON [PRIMARY]';
				$this->_sql($sql, $errored, $error_ary);
			break;
		}
	}
	
	function sql_create_index($dbms, $index_name, $table_name, $column)
	{
		global $dbms_type_map, $db;
		global $errored, $error_ary;
	
		switch ($dbms)
		{
			case 'firebird':
			case 'postgres':
			case 'oracle':
			case 'sqlite':
				$sql = 'CREATE INDEX ' . $table_name . '_' . $index_name . ' ON ' . $table_name . '(' . implode(', ', $column) . ')';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mysql_40':
			case 'mysql_41':
				$sql = 'CREATE INDEX ' . $index_name . ' ON ' . $table_name . '(' . implode(', ', $column) . ')';
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mssql':
				$sql = 'CREATE INDEX ' . $index_name . ' ON ' . $table_name . '(' . implode(', ', $column) . ') ON [PRIMARY]';
				$this->_sql($sql, $errored, $error_ary);
			break;
		}
	}
	
	/**
	* Change column type (not name!)
	*/
	function sql_column_change($dbms, $table_name, $column_name, $column_data)
	{
		global $dbms_type_map, $db;
		global $errored, $error_ary;
	
		$column_data = $this->prepare_column_data($dbms, $column_data, $table_name, $column_name);
	
		switch ($dbms)
		{
			case 'firebird':
				// Change type...
				$sql = 'ALTER TABLE "' . $table_name . '" ALTER COLUMN "' . $column_name . '" TYPE ' . ' ' . $column_data['column_type_sql'];
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mssql':
				$sql = 'ALTER TABLE [' . $table_name . '] ALTER COLUMN [' . $column_name . '] ' . $column_data['column_type_sql'];
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'mysql_40':
			case 'mysql_41':
				$sql = 'ALTER TABLE `' . $table_name . '` CHANGE `' . $column_name . '` `' . $column_name . '` ' . $column_data['column_type_sql'];
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'oracle':
				$sql = 'ALTER TABLE ' . $table_name . ' MODIFY ' . $column_name . ' ' . $column_data['column_type_sql'];
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'postgres':
				$sql = 'ALTER TABLE ' . $table_name . ' ';
	
				$sql_array = array();
				$sql_array[] = 'ALTER COLUMN ' . $column_name . ' TYPE ' . $column_data['column_type'];
	
				if (isset($column_data['null']))
				{
					if ($column_data['null'] == 'NOT NULL')
					{
						$sql_array[] = 'ALTER COLUMN ' . $column_name . ' SET NOT NULL';
					}
					else if ($column_data['null'] == 'NULL')
					{
						$sql_array[] = 'ALTER COLUMN ' . $column_name . ' DROP NOT NULL';
					}
				}
	
				if (isset($column_data['default']))
				{
					$sql_array[] = 'ALTER COLUMN ' . $column_name . ' SET DEFAULT ' . $column_data['default'];
				}
	
				// we don't want to double up on constraints if we change different number data types
				if (isset($column_data['constraint']))
				{
					$constraint_sql = "SELECT consrc as constraint_data
								FROM pg_constraint, pg_class bc
								WHERE conrelid = bc.oid
									AND bc.relname = '{$table_name}'
									AND NOT EXISTS (
										SELECT *
											FROM pg_constraint as c, pg_inherits as i
											WHERE i.inhrelid = pg_constraint.conrelid
												AND c.conname = pg_constraint.conname
												AND c.consrc = pg_constraint.consrc
												AND c.conrelid = i.inhparent
									)";
	
					$constraint_exists = false;
	
					$result = $db->sql_query($constraint_sql);
					while ($row = $db->sql_fetchrow($result))
					{
						if (trim($row['constraint_data']) == trim($column_data['constraint']))
						{
							$constraint_exists = true;
							break;
						}
					}
					$db->sql_freeresult($result);
	
					if (!$constraint_exists)
					{
						$sql_array[] = 'ADD ' . $column_data['constraint'];
					}
				}
	
				$sql .= implode(', ', $sql_array);
	
				$this->_sql($sql, $errored, $error_ary);
			break;
	
			case 'sqlite':
	
				$sql = "SELECT sql
					FROM sqlite_master
					WHERE type = 'table'
						AND name = '{$table_name}'
					ORDER BY type DESC, name;";
				$result = $this->_sql($sql, $errored, $error_ary);
	
				if (!$result)
				{
					break;
				}
	
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
	
				$db->sql_transaction('begin');
	
				// Create a temp table and populate it, destroy the existing one
				$db->sql_query(preg_replace('#CREATE\s+TABLE\s+"?' . $table_name . '"?#i', 'CREATE TEMPORARY TABLE ' . $table_name . '_temp', $row['sql']));
				$db->sql_query('INSERT INTO ' . $table_name . '_temp SELECT * FROM ' . $table_name);
				$db->sql_query('DROP TABLE ' . $table_name);
	
				preg_match('#\((.*)\)#s', $row['sql'], $matches);
	
				$new_table_cols = trim($matches[1]);
				$old_table_cols = preg_split('/,(?![\s\w]+\))/m', $new_table_cols);
				$column_list = array();
	
				foreach ($old_table_cols as $key => $declaration)
				{
					$entities = preg_split('#\s+#', trim($declaration));
					$column_list[] = $entities[0];
					if ($entities[0] == $column_name)
					{
						$old_table_cols[$key] = $column_name . ' ' . $column_data['column_type_sql'];
					}
				}
	
				$columns = implode(',', $column_list);
	
				// create a new table and fill it up. destroy the temp one
				$db->sql_query('CREATE TABLE ' . $table_name . ' (' . implode(',', $old_table_cols) . ');');
				$db->sql_query('INSERT INTO ' . $table_name . ' (' . $columns . ') SELECT ' . $columns . ' FROM ' . $table_name . '_temp;');
				$db->sql_query('DROP TABLE ' . $table_name . '_temp');
	
				$db->sql_transaction('commit');
	
			break;
		}
	}

	/**
	* Database column types mapping
	*/
	var $dbms_type_map = array(
		'mysql_41'	=> array(
			'INT:'		=> 'int(%d)',
			'BINT'		=> 'bigint(20)',
			'UINT'		=> 'mediumint(8) UNSIGNED',
			'UINT:'		=> 'int(%d) UNSIGNED',
			'TINT:'		=> 'tinyint(%d)',
			'USINT'		=> 'smallint(4) UNSIGNED',
			'BOOL'		=> 'tinyint(1) UNSIGNED',
			'VCHAR'		=> 'varchar(255)',
			'VCHAR:'	=> 'varchar(%d)',
			'CHAR:'		=> 'char(%d)',
			'XSTEXT'	=> 'text',
			'XSTEXT_UNI'=> 'varchar(100)',
			'STEXT'		=> 'text',
			'STEXT_UNI'	=> 'varchar(255)',
			'TEXT'		=> 'text',
			'TEXT_UNI'	=> 'text',
			'MTEXT'		=> 'mediumtext',
			'MTEXT_UNI'	=> 'mediumtext',
			'TIMESTAMP'	=> 'int(11) UNSIGNED',
			'DECIMAL'	=> 'decimal(5,2)',
			'VCHAR_UNI'	=> 'varchar(255)',
			'VCHAR_UNI:'=> 'varchar(%d)',
			'VCHAR_CI'	=> 'varchar(255)',
			'VARBINARY'	=> 'varbinary(255)',
		),

		'mysql_40'	=> array(
			'INT:'		=> 'int(%d)',
			'BINT'		=> 'bigint(20)',
			'UINT'		=> 'mediumint(8) UNSIGNED',
			'UINT:'		=> 'int(%d) UNSIGNED',
			'TINT:'		=> 'tinyint(%d)',
			'USINT'		=> 'smallint(4) UNSIGNED',
			'BOOL'		=> 'tinyint(1) UNSIGNED',
			'VCHAR'		=> 'varbinary(255)',
			'VCHAR:'	=> 'varbinary(%d)',
			'CHAR:'		=> 'binary(%d)',
			'XSTEXT'	=> 'blob',
			'XSTEXT_UNI'=> 'blob',
			'STEXT'		=> 'blob',
			'STEXT_UNI'	=> 'blob',
			'TEXT'		=> 'blob',
			'TEXT_UNI'	=> 'blob',
			'MTEXT'		=> 'mediumblob',
			'MTEXT_UNI'	=> 'mediumblob',
			'TIMESTAMP'	=> 'int(11) UNSIGNED',
			'DECIMAL'	=> 'decimal(5,2)',
			'VCHAR_UNI'	=> 'blob',
			'VCHAR_UNI:'=> array('varbinary(%d)', 'limit' => array('mult', 3, 255, 'blob')),
			'VCHAR_CI'	=> 'blob',
			'VARBINARY'	=> 'varbinary(255)',
		),

		'firebird'	=> array(
			'INT:'		=> 'INTEGER',
			'BINT'		=> 'DOUBLE PRECISION',
			'UINT'		=> 'INTEGER',
			'UINT:'		=> 'INTEGER',
			'TINT:'		=> 'INTEGER',
			'USINT'		=> 'INTEGER',
			'BOOL'		=> 'INTEGER',
			'VCHAR'		=> 'VARCHAR(255) CHARACTER SET NONE',
			'VCHAR:'	=> 'VARCHAR(%d) CHARACTER SET NONE',
			'CHAR:'		=> 'CHAR(%d) CHARACTER SET NONE',
			'XSTEXT'	=> 'BLOB SUB_TYPE TEXT CHARACTER SET NONE',
			'STEXT'		=> 'BLOB SUB_TYPE TEXT CHARACTER SET NONE',
			'TEXT'		=> 'BLOB SUB_TYPE TEXT CHARACTER SET NONE',
			'MTEXT'		=> 'BLOB SUB_TYPE TEXT CHARACTER SET NONE',
			'XSTEXT_UNI'=> 'VARCHAR(100) CHARACTER SET UTF8',
			'STEXT_UNI'	=> 'VARCHAR(255) CHARACTER SET UTF8',
			'TEXT_UNI'	=> 'BLOB SUB_TYPE TEXT CHARACTER SET UTF8',
			'MTEXT_UNI'	=> 'BLOB SUB_TYPE TEXT CHARACTER SET UTF8',
			'TIMESTAMP'	=> 'INTEGER',
			'DECIMAL'	=> 'DOUBLE PRECISION',
			'VCHAR_UNI'	=> 'VARCHAR(255) CHARACTER SET UTF8',
			'VCHAR_UNI:'=> 'VARCHAR(%d) CHARACTER SET UTF8',
			'VCHAR_CI'	=> 'VARCHAR(255) CHARACTER SET UTF8',
			'VARBINARY'	=> 'CHAR(255) CHARACTER SET NONE',
		),

		'mssql'		=> array(
			'INT:'		=> '[int]',
			'BINT'		=> '[float]',
			'UINT'		=> '[int]',
			'UINT:'		=> '[int]',
			'TINT:'		=> '[int]',
			'USINT'		=> '[int]',
			'BOOL'		=> '[int]',
			'VCHAR'		=> '[varchar] (255)',
			'VCHAR:'	=> '[varchar] (%d)',
			'CHAR:'		=> '[char] (%d)',
			'XSTEXT'	=> '[varchar] (1000)',
			'STEXT'		=> '[varchar] (3000)',
			'TEXT'		=> '[varchar] (8000)',
			'MTEXT'		=> '[text]',
			'XSTEXT_UNI'=> '[varchar] (100)',
			'STEXT_UNI'	=> '[varchar] (255)',
			'TEXT_UNI'	=> '[varchar] (4000)',
			'MTEXT_UNI'	=> '[text]',
			'TIMESTAMP'	=> '[int]',
			'DECIMAL'	=> '[float]',
			'VCHAR_UNI'	=> '[varchar] (255)',
			'VCHAR_UNI:'=> '[varchar] (%d)',
			'VCHAR_CI'	=> '[varchar] (255)',
			'VARBINARY'	=> '[varchar] (255)',
		),

		'oracle'	=> array(
			'INT:'		=> 'number(%d)',
			'BINT'		=> 'number(20)',
			'UINT'		=> 'number(8)',
			'UINT:'		=> 'number(%d)',
			'TINT:'		=> 'number(%d)',
			'USINT'		=> 'number(4)',
			'BOOL'		=> 'number(1)',
			'VCHAR'		=> 'varchar2(255)',
			'VCHAR:'	=> 'varchar2(%d)',
			'CHAR:'		=> 'char(%d)',
			'XSTEXT'	=> 'varchar2(1000)',
			'STEXT'		=> 'varchar2(3000)',
			'TEXT'		=> 'clob',
			'MTEXT'		=> 'clob',
			'XSTEXT_UNI'=> 'varchar2(300)',
			'STEXT_UNI'	=> 'varchar2(765)',
			'TEXT_UNI'	=> 'clob',
			'MTEXT_UNI'	=> 'clob',
			'TIMESTAMP'	=> 'number(11)',
			'DECIMAL'	=> 'number(5, 2)',
			'VCHAR_UNI'	=> 'varchar2(765)',
			'VCHAR_UNI:'=> array('varchar2(%d)', 'limit' => array('mult', 3, 765, 'clob')),
			'VCHAR_CI'	=> 'varchar2(255)',
			'VARBINARY'	=> 'raw(255)',
		),

		'sqlite'	=> array(
			'INT:'		=> 'int(%d)',
			'BINT'		=> 'bigint(20)',
			'UINT'		=> 'INTEGER UNSIGNED', //'mediumint(8) UNSIGNED',
			'UINT:'		=> 'INTEGER UNSIGNED', // 'int(%d) UNSIGNED',
			'TINT:'		=> 'tinyint(%d)',
			'USINT'		=> 'INTEGER UNSIGNED', //'mediumint(4) UNSIGNED',
			'BOOL'		=> 'INTEGER UNSIGNED', //'tinyint(1) UNSIGNED',
			'VCHAR'		=> 'varchar(255)',
			'VCHAR:'	=> 'varchar(%d)',
			'CHAR:'		=> 'char(%d)',
			'XSTEXT'	=> 'text(65535)',
			'STEXT'		=> 'text(65535)',
			'TEXT'		=> 'text(65535)',
			'MTEXT'		=> 'mediumtext(16777215)',
			'XSTEXT_UNI'=> 'text(65535)',
			'STEXT_UNI'	=> 'text(65535)',
			'TEXT_UNI'	=> 'text(65535)',
			'MTEXT_UNI'	=> 'mediumtext(16777215)',
			'TIMESTAMP'	=> 'INTEGER UNSIGNED', //'int(11) UNSIGNED',
			'DECIMAL'	=> 'decimal(5,2)',
			'VCHAR_UNI'	=> 'varchar(255)',
			'VCHAR_UNI:'=> 'varchar(%d)',
			'VCHAR_CI'	=> 'varchar(255)',
			'VARBINARY'	=> 'blob',
		),

		'postgres'	=> array(
			'INT:'		=> 'INT4',
			'BINT'		=> 'INT8',
			'UINT'		=> 'INT4', // unsigned
			'UINT:'		=> 'INT4', // unsigned
			'USINT'		=> 'INT2', // unsigned
			'BOOL'		=> 'INT2', // unsigned
			'TINT:'		=> 'INT2',
			'VCHAR'		=> 'varchar(255)',
			'VCHAR:'	=> 'varchar(%d)',
			'CHAR:'		=> 'char(%d)',
			'XSTEXT'	=> 'varchar(1000)',
			'STEXT'		=> 'varchar(3000)',
			'TEXT'		=> 'varchar(8000)',
			'MTEXT'		=> 'TEXT',
			'XSTEXT_UNI'=> 'varchar(100)',
			'STEXT_UNI'	=> 'varchar(255)',
			'TEXT_UNI'	=> 'varchar(4000)',
			'MTEXT_UNI'	=> 'TEXT',
			'TIMESTAMP'	=> 'INT4', // unsigned
			'DECIMAL'	=> 'decimal(5,2)',
			'VCHAR_UNI'	=> 'varchar(255)',
			'VCHAR_UNI:'=> 'varchar(%d)',
			'VCHAR_CI'	=> 'varchar_ci',
			'VARBINARY'	=> 'bytea',
		),
	);

	/**
	* A list of types being unsigned for better reference in some db's
	*/
	var $unsigned_types = array('UINT', 'UINT:', 'USINT', 'BOOL', 'TIMESTAMP');

	/**
	* Define the module structure so that we can populate the database without
	* needing to hard-code module_id values
	*/
	var $modules = array();

	/**
	* Only an example, but also commented out
	*/
	var $database_update_info = array(
		// Changes from 1.0.0b1 to 1.0.0b2
		'1.0.B1' => array(
			// Change the following columns
			'change_columns'		=> array(
				KARMA_TABLE	=> array(
					'bbcode_uid'		=> array('VCHAR:8', ''),
				),
			),
		),
		// No changes from 1.0.0b2 to 1.0.0b3
		'1.0.B2' => array(),
		// No changes from 1.0.0b3 to 1.0.0
		'1.0.B3' => array(),
		// No changes from 1.0.0 to 1.0.1
		'1.0.0' => array(),
		// No changes from 1.0.1 to 1.0.2
		'1.0.1' => array(),
		// No changes from 1.0.2 to 1.0.3
		'1.0.2' => array(),
		// No changes from 1.0.3 to 1.0.4
		'1.0.3' => array(),
		// No changes from 1.0.4 to 1.0.5
		'1.0.4' => array(),
		// Changes from 1.0.5
		'1.0.5' => array(
			// Add the following columns
			'add_columns' => array(
				USERS_TABLE	=> array(
					'user_karma_comments_self'	=> array('TINT:1', '0'),
					'user_karma_comments_show_days' => array('USINT', '0'),
					'user_karma_comments_sortby_type'	=> array('VCHAR:1', 't'),
					'user_karma_comments_sortby_dir'	=> array('VCHAR:1', 'd'),
				),
			),
		),
		// No changes from 1.1.0 to 1.1.1
		'1.1.0b1' => array(),
		// No changes from 1.1.1 to 1.1.2
		'1.1.1' => array(),
		// No changes from 1.1.2 to 1.1.3
		'1.1.2' => array(),
		// No changes from 1.1.3 to 1.1.4
		'1.1.3' => array(),
		// No changes from 1.1.4 to 1.2.0
		'1.1.4' => array(),
		// No changes from 1.2.0 to 1.2.1
		'1.2.0' => array(),
		// No changes from 1.2.1 to 1.2.2
		'1.2.1' => array(),
		// No changes from 1.2.2 to 1.2.3
		'1.2.2' => array(),
	);

	/**
	* Function where all data changes are executed
	*/
	function change_database_data(&$no_updates, $version)
	{
		global $db, $map_dbms, $errored, $error_ary, $config, $phpbb_root_path, $phpEx;

		switch ($version)
		{
			// No changes from 1.0.0b1 to 1.0.0b2
			case '1.0.B1':
			break;

			// No changes from 1.0.0b2 to 1.0.0b3
			case '1.0.B2':
			break;

			// Changes from 1.0.0b3 to 1.0.0
			case '1.0.B3':
				set_config('karma_zebra', '1');
				set_config('karma_anonym_increase', '0');
				set_config('karma_anonym_decrease', '0');

				$no_updates = false;
			break;

			// No changes from 1.0.0 to 1.0.1
			case '1.0.0':
			break;

			// No changes from 1.0.1 to 1.0.2
			case '1.0.1':
			break;

			// Changes from 1.0.2 to 1.0.3
			case '1.0.2':
				set_config('karma_minimum', '0');

				$no_updates = false;
			break;

			// Changes from 1.0.3 to 1.0.4
			case '1.0.3':
				set_config('karma_updater', '1');

				$no_updates = false;
			break;

			// Changes from 1.0.4 to 1.0.5
			case '1.0.4':
				// Delete not used variable
				$sql = 'DELETE FROM ' . CONFIG_TABLE . "
						WHERE config_name = 'karma_need'";
				$this->_sql($sql, $errored, $error_ary);

				$no_updates = false;
			break;

			// Changes from 1.0.5 to 1.1.0b1
			case '1.0.5':
				// Update karma decrease icons filenames
				$sql = 'UPDATE ' . STYLES_IMAGESET_DATA_TABLE . '
						SET image_filename = \'icon_karma_decrease.gif\'
						WHERE image_filename = \'icon_rate_bad.gif\'';
				$this->_sql($sql, $errored, $error_ary);

				// Update karma increase icons filenames
				$sql = 'UPDATE ' . STYLES_IMAGESET_DATA_TABLE . '
						SET image_filename = \'icon_karma_increase.gif\'
						WHERE image_filename = \'icon_rate_good.gif\'';
				$this->_sql($sql, $errored, $error_ary);

				/**
				* Define the module structure so that we can populate the database without
				* needing to hard-code module_id values
				*/
				$this->modules['acp'] = array(
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
				);

				$this->add_modules();

				$no_updates = false;
			break;

			// No changes from 1.1.0 to 1.1.1
			case '1.1.0b1':
			break;

			// No changes from 1.1.1 to 1.1.2
			case '1.1.1':
			break;

			// Changes from 1.1.2 to 1.1.3
			case '1.1.2':
				$sql = 'DELETE FROM ' . CONFIG_TABLE . "
					WHERE config_name = 'karma_updater'";
				$this->_sql($sql, $errored, $error_ary);

				// Reset permissions
				$sql = 'UPDATE ' . USERS_TABLE . "
					SET user_permissions = '',
						user_perm_from = 0";
				$this->_sql($sql, $errored, $error_ary);

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
						$result = $this->_sql($sql, $errored, $error_ary);
	
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
						$result = $this->_sql($sql, $errored, $error_ary);
	
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
						$result = $this->_sql($sql, $errored, $error_ary);
	
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
						$result = $this->_sql($sql, $errored, $error_ary);
	
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

				$no_updates = false;
			break;

			// No changes from 1.1.3 to 1.1.4
			case '1.1.3':
			break;
			
			// Changes from 1.1.4 to 1.2.0
			case '1.1.4':
				set_config('karma_ban', '0');
				set_config('karma_ban_value', '-100');
				set_config('karma_ban_reason', 'Automatically banned by Karma MOD');
				set_config('karma_ban_give_reason', 'Automatically banned by Karma MOD');
				set_config('karma_updater_beta', '1');

				$no_updates = false;
			break;

			// No changes from 1.2.0 to 1.2.1
			case '1.2.0':
			break;

			// No changes from 1.2.1 to 1.2.2
			case '1.2.1':
			break;

			// No changes from 1.2.2 to 1.2.3
			case '1.2.2':
			break;
		}
	}
}
?>