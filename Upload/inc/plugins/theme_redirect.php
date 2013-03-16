<?php
/***************************************************************************
 *
 *	Author:	Jordan Mussi
 *	File:	./inc/plugins/theme_redirect.php
 *  
 *	License:
 *  
 *	This program is free software: you can redistribute it and/or modify
 *	the terms of the GNU General Public License as published by the Free Software 
 *	Foundation, either version 3 of the License, or (at your option) any later 
 *	version.
 *	
 *	This program is distributed in the hope that it will be useful, but WITHOUT ANY 
 *	WARRANTY; without even the implied warranty of MERCHANTABILITY or 
 *	FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License 
 *	for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *	
 ***************************************************************************/
/**
 * Default is "theme_redirect.php" because the file is called "theme_redirect.php"
 * Make sure you chnage the value of this when you change the name of the redirect script
 */
 
define(REDIRECTOR_LOCATION, "theme_redirect.php");

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

if(defined("IN_ADMINCP"))
{
	$plugins->add_hook("admin_config_menu", "theme_redirect_admin_config_menu");
	$plugins->add_hook("admin_config_action_handler", "theme_redirect_admin_config_action_handler");
	$plugins->add_hook("admin_config_permissions", "theme_redirect_admin_config_permissions");
}

function theme_redirect_info()
{
	global $lang;
	$lang->load("config_theme_redirect");
	
	return array(
		"name"			=> $lang->theme_redirect,
		"description"	=> $lang->theme_redirect_desc,
		"website"		=> "http://demonate.com/",
		"author"		=> "Jordan Mussi",
		"authorsite"	=> "https://github.com/JordanMussi",
		"guid"			=> "6b6f3964304dc148ca4f5ae9b2f800c0",
		"version"		=> "1",
		"compatibility" => "16*"
	);
}


function theme_redirect_is_installed()
{
	global $db;
	return $db->table_exists('theme_redirect');
}

function theme_redirect_install()
{
	global $db, $cache;
	// Make sure everything has been removed 
	theme_redirect_uninstall();
	// Add new db tables
	if(!$db->table_exists('theme_redirect'))
	{
		$collation = $db->build_create_table_collation();
		$db->write_query("CREATE TABLE ".TABLE_PREFIX."theme_redirect(
			rid INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			tid INT(10) NOT NULL,
			name VARCHAR(120) NOT NULL,
			title VARCHAR(120) NOT NULL UNIQUE,
			site INT(10) NOT NULL
			) ENGINE=MyISAM{$collation};");
	}
	if(!$db->table_exists('theme_redirect_sites'))
	{
		$collation = $db->build_create_table_collation();
		$db->write_query("CREATE TABLE ".TABLE_PREFIX."theme_redirect_sites(
			sid INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(120) NOT NULL,
			url VARCHAR(120) NOT NULL
			) ENGINE=MyISAM{$collation};");
	}
	// Insert examples
	$example_redirect = array(
				'tid'		=> 1,
				'name'		=> "Example",
				'title'		=> "example",
				'site'		=> 1
			);		
	$db->insert_query('theme_redirect', $example_redirect);
	
	$example_site = array(
				'name'		=> "Example",
				'url'		=> "http://www.mybb.com/index.php?action=mytheme&style={theme}"
			);		
	$db->insert_query('theme_redirect_sites', $example_site);
	
	$info = theme_redirect_info();
	$JM_Plugins = $cache->read('JM_Plugins');
	$JM_Plugins['theme_redirect'] = array(
		'name' => $info['name'],
		'version' => $info['version']
	);
	$cache->update('JM_Plugins', $JM_Plugins);
}

function theme_redirect_uninstall()
{
	global $db;
	if($db->table_exists('theme_redirect'))
	{
		$db->drop_table('theme_redirect');
	}
	if($db->table_exists('theme_redirect_sites'))
	{
		$db->drop_table('theme_redirect_sites');
	}
}

function theme_redirect_activate()
{
}

function theme_redirect_deactivate()
{
}

function theme_redirect_admin_config_menu(&$sub_menu)
{
	global $lang;
	$lang->load("config_theme_redirect");

	$sub_menu[] = array(
		"id"	=>	"theme_redirect",
		"title"	=>	$lang->theme_redirect,
		"link"	=>	"index.php?module=config-theme_redirect"
	);
	return $sub_menu;
}

function theme_redirect_admin_config_action_handler(&$actions)
{
	$actions['theme_redirect'] = array(
		"active"	=>	"theme_redirect",
		"file"		=>	"theme_redirect.php"
	);
	return $actions;
}

function theme_redirect_admin_config_permissions(&$admin_permissions)
{
	global $lang;
	$lang->load("config_theme_redirect");
	$admin_permissions['theme_redirect'] = $lang->theme_redirect_can_manage;
	return $admin_permissions;
}
?>