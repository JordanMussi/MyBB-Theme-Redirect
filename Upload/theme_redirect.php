<?php
/***************************************************************************
 *
 *	Author:	Jordan Mussi
 *	File:	./theme_redirect.php
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
define("IN_MYBB", 1);
define('NO_ONLINE',1);

require_once "./inc/plugins/theme_redirect.php";
define('THIS_SCRIPT', REDIRECTOR_LOCATION);

require_once "./global.php";

$lang->load("theme_redirect");

if(!isset($mybb->input['theme']))
{
	error($lang->theme_redirect_error_empty);
}

if(isset($mybb->input['theme']) && $mybb->request_method == "get")
{
	$theme_title = $db->escape_string($mybb->input['theme']);
}

$query = $db->simple_select('theme_redirect', "*", "title = '".$theme_title."'");

$redirect = $db->fetch_array($query);

if(!$redirect['name'])
{
	error($lang->sprintf($lang->theme_redirect_error, $mybb->input['theme']));
}

$query = $db->simple_select('theme_redirect_sites', "url", "sid = '".$redirect['site']."'");
$site = $db->fetch_array($query);

$redirect['url'] = str_replace('{theme}', $redirect['tid'], $site['url']);

redirect($redirect['url'], $lang->sprintf($lang->theme_redirect_success, $redirect['name']));
?>