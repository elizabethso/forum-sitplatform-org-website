<?php
/** 
*
* @package karmamod (install)
* @version $Id: install_main.php,v 8598.4 2009/05/05 15:12:18 m157y Exp $
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
		'module_title'		=> 'KARMA',
		'module_filename'	=> substr(basename(__FILE__), 0, -strlen($phpEx)-1),
		'module_order'		=> 0,
		'module_subs'		=> array('INTRO', 'LICENSE', 'SUPPORT'),
		'module_stages'		=> '',
		'module_reqs'		=> ''
	);
}

/**
* Main Tab - Installation
* @package karmamod (install)
*/
class install_main extends module
{
	function install_main(&$p_master)
	{
		$this->p_master = &$p_master;
	}

	function main($mode, $sub)
	{
		global $user, $template, $language;

		switch ($sub)
		{
			case 'intro' :
				$title = $user->lang['INSTALL_KARMA_INTRO'];
				$body = $user->lang['INSTALL_KARMA_INTRO_BODY'];
			break;

			case 'license' :
				$title = $user->lang['GPL'];
				$body = implode("<br/>\n", file('../docs/COPYING'));
			break;

			case 'support' :
				$title = $user->lang['INSTALL_KARMA_SUPPORT'];
				$body = $user->lang['INSTALL_KARMA_SUPPORT_BODY'];
			break;
		}

		$this->tpl_name = 'install_main';
		$this->page_title = $title;

		$template->assign_vars(array(
			'TITLE'		=> $title,
			'BODY'		=> $body,
		));
	}
}

?>