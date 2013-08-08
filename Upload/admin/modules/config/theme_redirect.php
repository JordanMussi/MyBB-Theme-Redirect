<?php
/**
 * Author: Jordan Mussi
 * File: ./admin/modules/config/theme_redirect.php
 *
 * License:
 *
 *  This program is free software: you can redistribute it and/or modify it under
 *  the terms of the GNU General Public License as published by the Free Software
 *  Foundation, either version 3 of the License, or (at your option) any later
 *  version.
 *
 *  This program is distributed in the hope that it will be useful, but WITHOUT ANY
 *  WARRANTY; without even the implied warranty of MERCHANTABILITY or
 *  FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License
 *  for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// Disallow direct access to this file for security reasons
if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$lang->load('config_theme_redirect');

$page->add_breadcrumb_item($lang->theme_redirects, 'index.php?module=config-theme_redirect');

$sub_tabs = array(
	"home" => array(
		'title' => $lang->theme_redirect_redirects,
		'link' => 'index.php?module=config-theme_redirect',
		'description' => $lang->theme_redirect_redirects_desc
	),
	"add_redirect" => array(
		'title'=> $lang->theme_redirect_add_new,
		'link' => 'index.php?module=config-theme_redirect&amp;action=add',
		'description' => $lang->theme_redirect_add_new_desc
	),
	"sites" => array(
		'title'=> $lang->theme_redirect_sites,
		'link' => 'index.php?module=config-theme_redirect&amp;action=sites',
		'description' => $lang->theme_redirect_sites_desc
	),
	"add_site" => array(
		'title'=> $lang->theme_redirect_add_site,
		'link' => 'index.php?module=config-theme_redirect&amp;action=add_site',
		'description' => $lang->theme_redirect_add_site_desc
	)
);

if($mybb->input['action'] == 'add')
{

	if($mybb->request_method == 'post')
	{
		if(trim($mybb->input['tid']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_tid;
		}

		if(trim($mybb->input['name']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_name;
		}

		if(trim($mybb->input['title']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_title;
		}

		if(trim($mybb->input['site']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_site;
		}

		if(!$errors)
		{
			$title = $db->escape_string($mybb->input['title']);
			$query = $db->simple_select('theme_redirect', "rid", "title = '{$title}'");

			if($db->num_rows($query) > 0)
			{
				flash_message($lang->theme_redirect_error_title_in_use, 'error');
				admin_redirect('index.php?module=config-theme_redirect');
			}

			$new_redirect = array(
				'tid'		=> $db->escape_string($mybb->input['tid']),
				'name'		=> $db->escape_string($mybb->input['name']),
				'title'		=> $db->escape_string($mybb->input['title']),
				'site'		=> (int) $mybb->input['site']
			);

			$id = $db->insert_query('theme_redirect', $new_redirect);

			// Log admin action
			log_admin_action($id, $mybb->input['title']);

			flash_message($lang->theme_redirect_success_redirect_created, 'success');
			admin_redirect('index.php?module=config-theme_redirect');
		}
	}

	$page->add_breadcrumb_item($lang->theme_redirect_add_new);
	$page->output_header($lang->theme_redirects." - ".$lang->theme_redirect_add_new);
	$page->output_nav_tabs($sub_tabs, 'add_redirect');

	$form = new Form('index.php?module=config-theme_redirect&amp;action=add', 'post');

	if($errors)
	{
		$page->output_inline_error($errors);
	}
	else
	{
		$mybb->input['tid'] = '';
		$mybb->input['name'] = '';
		$mybb->input['title'] = '';
		$mybb->input['site'] = '';
	}

	// Find the sites
	$query = $db->simple_select('theme_redirect_sites', "sid, name");

	while($redirect_site = $db->fetch_array($query))
	{
		$sites[$redirect_site['sid']] = $redirect_site['name'];
	}

	$form_container = new FormContainer($lang->theme_redirect_new);
	$form_container->output_row($lang->theme_redirect_tid.' <em>*</em>', $lang->theme_redirect_tid_desc, $form->generate_text_box('tid', $mybb->input['tid'], array('id' => 'tid')), 'tid');
	$form_container->output_row($lang->theme_redirect_name.' <em>*</em>', $lang->theme_redirect_name_desc, $form->generate_text_box('name', $mybb->input['name'], array('id' => 'name')), 'name');
	$form_container->output_row($lang->theme_redirect_title.' <em>*</em>', $lang->theme_redirect_title_desc, $form->generate_text_box('title', $mybb->input['title'], array('id' => 'title')), 'title');
	$form_container->output_row($lang->theme_redirect_site.' <em>*</em>', $lang->theme_redirect_site_desc, $form->generate_select_box('site', $sites, $mybb->input['site'], array('id' => 'site')), 'site');
	$form_container->end();

	$buttons[] = $form->generate_submit_button($lang->theme_redirect_save_new);

	$form->output_submit_wrapper($buttons);
	$form->end();

	$page->output_footer();
}

if($mybb->input['action'] == 'edit')
{
	$mybb->input['rid'] = (int) $mybb->input['rid'];

	if(!$mybb->input['rid'])
	{
		flash_message($lang->theme_redirect_error_invalid_redirect, 'error');
		admin_redirect('index.php?module=config-theme_redirect');
	}

	$query = $db->simple_select('theme_redirect', "*", "rid = '{$mybb->input['rid']}'");
	$redirect = $db->fetch_array($query);

	if(!$redirect['rid'])
	{
		flash_message($lang->theme_redirect_error_invalid_redirect, 'error');
		admin_redirect('index.php?module=config-theme_redirect');
	}

	if($mybb->request_method == 'post')
	{
		if(trim($mybb->input['tid']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_tid;
		}

		if(trim($mybb->input['name']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_name;
		}

		if(trim($mybb->input['title']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_title;
		}

		if(trim($mybb->input['site']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_site;
		}

		if(!$errors)
		{
			$title = $db->escape_string($mybb->input['title']);
			$query = $db->simple_select('theme_redirect', "rid", "title = '{$title}' AND rid != '{$redirect['rid']}'");

			if($db->num_rows($query) > 0)
			{
				flash_message($lang->theme_redirect_error_title_in_use, 'error');
				admin_redirect('index.php?module=config-theme_redirect');
			}

			$update = array(
				'tid'		=>	$db->escape_string($mybb->input['tid']),
				'name'		=>	$db->escape_string($mybb->input['name']),
				'title'		=>	$db->escape_string($mybb->input['title']),
				'site'		=> (int) $mybb->input['site']
			);

			$db->update_query('theme_redirect', $update, "rid = '{$mybb->input['rid']}'");

			// Log admin action
			log_admin_action($mybb->input['rid'], $mybb->input['title']);

			flash_message($lang->theme_redirect_success_redirect_updated, 'success');
			admin_redirect('index.php?module=config-theme_redirect');
		}
	}

	$page->add_breadcrumb_item($lang->theme_redirect_edit_redirect);
	$page->output_header($lang->theme_redirect.' - '.$lang->theme_redirect_edit_redirect);

	$sub_tabs['edit'] = array(
		"title" => $lang->theme_redirect_edit_redirect,
		"link" => "index.php?module=config-theme_redirect&amp;action=edit&amp;rid={$redirect['rid']}",
		"description" => $lang->theme_redirect_edit_redirect_desc
	);
	$page->output_nav_tabs($sub_tabs, "edit");

	$form = new Form('index.php?module=config-theme_redirect&amp;action=edit', 'post');
	echo $form->generate_hidden_field('rid', $mybb->input['rid']);

	if($errors)
	{
		$page->output_inline_error($errors);
	}
	$mybb->input['tid'] = $redirect['tid'];
	$mybb->input['name'] = $redirect['name'];
	$mybb->input['title'] = $redirect['title'];

	// Find the sites
	$query = $db->simple_select('theme_redirect_sites', "sid, name");

	while($redirect_site = $db->fetch_array($query))
	{
		$sites[$redirect_site['sid']] = $redirect_site['name'];
	}

	$form_container = new FormContainer($lang->theme_redirect_editing);
	$form_container->output_row($lang->theme_redirect_tid.' <em>*</em>', $lang->theme_redirect_tid_desc, $form->generate_text_box('tid', $mybb->input['tid'], array('id' => 'tid')), 'tid');
	$form_container->output_row($lang->theme_redirect_name.' <em>*</em>', $lang->theme_redirect_name_desc, $form->generate_text_box('name', $mybb->input['name'], array('id' => 'name')), 'name');
	$form_container->output_row($lang->theme_redirect_title.' <em>*</em>', $lang->theme_redirect_title_desc, $form->generate_text_box('title', $mybb->input['title'], array('id' => 'title')), 'title');
	$form_container->output_row($lang->theme_redirect_site.' <em>*</em>', $lang->theme_redirect_site_desc, $form->generate_select_box('site', $sites, $mybb->input['site'], array('id' => 'site')), 'site');
	$form_container->end();

	$buttons[] = $form->generate_submit_button($lang->theme_redirect_save);

	$form->output_submit_wrapper($buttons);
	$form->end();

	$page->output_footer();
}

if($mybb->input['action'] == 'delete')
{
	$mybb->input['rid'] = (int) $mybb->input['rid'];
	if(!$mybb->input['rid'])
	{
		flash_message($lang->theme_redirect_error_invalid_redirect, 'error');
		admin_redirect('index.php?module=config-theme_redirect');
	}

	$query = $db->simple_select('theme_redirect', "*", "rid = '{$mybb->input['rid']}'");
	$redirect = $db->fetch_array($query);

	if(!$redirect['rid'])
	{
		flash_message($lang->theme_redirect_error_invalid_redirect, 'error');
		admin_redirect('index.php?module=config-theme_redirect');
	}

	// User clicked no
	if($mybb->input['no'])
	{
		admin_redirect('index.php?module=config-theme_redirect');
	}

	if($mybb->request_method == 'post')
	{
		// Delete redirect
		$db->delete_query('theme_redirect', "rid='{$redirect['rid']}'");

		// Log admin action
		log_admin_action($redirect['rid'], $redirect['name']);

		flash_message($lang->theme_redirect_success_redirect_deleted, 'success');
		admin_redirect('index.php?module=config-theme_redirect');
	}
	else
	{
		$page->output_confirm_action("index.php?module=config-theme_redirect&amp;action=delete&amp;rid={$mybb->input['rid']}", $lang->theme_redirect_confirm_redirect_deletion);
	}
}

if($mybb->input['action'] == 'add_site')
{

	if($mybb->request_method == 'post')
	{
		if(trim($mybb->input['name']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_name;
		}

		if(trim($mybb->input['url']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_url;
		}

		if(strpos($mybb->input['url'], '{theme}') === false)
		{
			$errors[] = $lang->theme_redirect_error_missing_url_replacement;
		}

		if(!$errors)
		{
			$new_site = array(
				'name'		=> $db->escape_string($mybb->input['name']),
				'url'		=> $db->escape_string($mybb->input['url'])
			);

			$id = $db->insert_query('theme_redirect_sites', $new_site);

			// Log admin action
			log_admin_action($id, $mybb->input['name']);

			flash_message($lang->theme_redirect_success_site_created, 'success');
			admin_redirect('index.php?module=config-theme_redirect&amp;action=sites');
		}
	}

	$page->add_breadcrumb_item($lang->theme_redirect_add_site);
	$page->output_header($lang->theme_redirects." - ".$lang->theme_redirect_add_site);
	$page->output_nav_tabs($sub_tabs, 'add_site');

	$form = new Form('index.php?module=config-theme_redirect&amp;action=add_site', 'post');

	if($errors)
	{
		$page->output_inline_error($errors);
	}
	else
	{
		$mybb->input['name'] = '';
		$mybb->input['url'] = '';
	}

	$form_container = new FormContainer($lang->theme_redirect_new_site);
	$form_container->output_row($lang->theme_redirect_name.' <em>*</em>', $lang->theme_redirect_site_name_desc, $form->generate_text_box('name', $mybb->input['name'], array('id' => 'name')), 'name');
	$form_container->output_row($lang->theme_redirect_url.' <em>*</em>', $lang->theme_redirect_site_url_desc, $form->generate_text_box('url', $mybb->input['url'], array('id' => 'url')), 'url');
	$form_container->end();

	$buttons[] = $form->generate_submit_button($lang->theme_redirect_save_new_site);

	$form->output_submit_wrapper($buttons);
	$form->end();

	$page->output_footer();
}

if($mybb->input['action'] == 'edit_site')
{
	$mybb->input['sid'] = (int) $mybb->input['sid'];

	if(!$mybb->input['sid'])
	{
		flash_message($lang->theme_redirect_error_invalid_site, 'error');
		admin_redirect('index.php?module=config-theme_redirect&amp;action=sites');
	}

	$query = $db->simple_select('theme_redirect_sites', "*", "sid = '{$mybb->input['sid']}'");
	$site = $db->fetch_array($query);

	if(!$site['sid'])
	{
		flash_message($lang->theme_redirect_error_invalid_site, 'error');
		admin_redirect('index.php?module=config-theme_redirect&amp;action=sites');
	}

	if($mybb->request_method == 'post')
	{
		if(trim($mybb->input['name']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_name;
		}

		if(trim($mybb->input['url']) == '')
		{
			$errors[] = $lang->theme_redirect_error_missing_url;
		}

		if(strpos($mybb->input['url'], '{theme}') === false)
		{
			$errors[] = $lang->theme_redirect_error_missing_url_replacement;
		}

		if(!$errors)
		{
			$update_site = array(
				'name'		=>	$db->escape_string($mybb->input['name']),
				'url'		=>	$db->escape_string($mybb->input['url'])
			);

			$db->update_query('theme_redirect_sites', $update_site, "sid = '{$mybb->input['sid']}'");

			// Log admin action
			log_admin_action($mybb->input['sid'], $mybb->input['name']);

			flash_message($lang->theme_redirect_success_site_updated, 'success');
			admin_redirect('index.php?module=config-theme_redirect&amp;action=sites');
		}
	}

	$page->add_breadcrumb_item($lang->theme_redirect_edit_site);
	$page->output_header($lang->theme_redirect.' - '.$lang->theme_redirect_edit_site);

	$sub_tabs['edit_site'] = array(
		"title" => $lang->theme_redirect_edit_site,
		"link" => "index.php?module=config-theme_redirect&amp;action=edit_site&amp;sid={$site['sid']}",
		"description" => $lang->theme_redirect_edit_site_desc
	);
	$page->output_nav_tabs($sub_tabs, "edit_site");

	$form = new Form('index.php?module=config-theme_redirect&amp;action=edit_site', 'post');
	echo $form->generate_hidden_field('sid', $mybb->input['sid']);

	if($errors)
	{
		$page->output_inline_error($errors);
	}
	$mybb->input['name'] = $site['name'];
	$mybb->input['url'] = $site['url'];

	$form_container = new FormContainer($lang->theme_redirect_editing_site);
	$form_container->output_row($lang->theme_redirect_name.' <em>*</em>', $lang->theme_redirect_site_name_desc, $form->generate_text_box('name', $mybb->input['name'], array('id' => 'name')), 'name');
	$form_container->output_row($lang->theme_redirect_url.' <em>*</em>', $lang->theme_redirect_site_url_desc, $form->generate_text_box('url', $mybb->input['url'], array('id' => 'url')), 'url');
	$form_container->end();

	$buttons[] = $form->generate_submit_button($lang->theme_redirect_save_site);

	$form->output_submit_wrapper($buttons);
	$form->end();

	$page->output_footer();
}

if($mybb->input['action'] == 'delete_site')
{
	$mybb->input['sid'] = (int) $mybb->input['sid'];

	if(!$mybb->input['sid'])
	{
		flash_message($lang->theme_redirect_error_invalid_site, 'error');
		admin_redirect('index.php?module=config-theme_redirect&amp;action=sites');
	}

	$query = $db->simple_select('theme_redirect_sites', "sid, name", "sid = '{$mybb->input['sid']}'");
	$site = $db->fetch_array($query);

	if(!$site['sid'])
	{
		flash_message($lang->theme_redirect_error_invalid_site, 'error');
		admin_redirect('index.php?module=config-theme_redirect&amp;action=sites');
	}

	$query = $db->simple_select('theme_redirect', "rid", "site = '{$mybb->input['sid']}'");
	if($db->num_rows($query) > 0)
	{
		flash_message($lang->theme_redirect_error_site_in_use, 'error');
		admin_redirect('index.php?module=config-theme_redirect&amp;action=sites');
	}
	// User clicked no
	if($mybb->input['no'])
	{
		admin_redirect('index.php?module=config-theme_redirect&amp;action=sites');
	}

	if($mybb->request_method == 'post')
	{
		// Delete redirect
		$db->delete_query('theme_redirect_sites', "sid='{$site['sid']}'");

		// Log admin action
		log_admin_action($site['sid'], $site['name']);

		flash_message($lang->theme_redirect_success_site_deleted, 'success');
		admin_redirect('index.php?module=config-theme_redirect&amp;action=sites');
	}
	else
	{
		$page->output_confirm_action("index.php?module=config-theme_redirect&amp;action=delete_site&amp;sid={$mybb->input['sid']}", $lang->theme_redirect_confirm_site_deletion);
	}
}

if($mybb->input['action'] == "sites")
{
	$page->output_header($lang->theme_redirect_sites);
	$page->output_nav_tabs($sub_tabs, 'sites');
	$table = new Table;
	$table->construct_header($lang->theme_redirect_name, array('width' => '40%'));
	$table->construct_header($lang->theme_redirect_url, array('width' => '50%'));
	$table->construct_header($lang->theme_redirect_controls, array('width' => '10%', 'class' => 'align_center'));

	$query = $db->simple_select('theme_redirect_sites', "*", "", array("order_by" => "name", "order_dir" => "asc"));

	while($site = $db->fetch_array($query))
	{
			$table->construct_cell($site['name']);
			$table->construct_cell("<input type=\"text\" value=\"{$site['url']}\" size=\"75%\">");
			$popup = new PopupMenu("site_{$site['sid']}", $lang->theme_redirect_options);
			$popup->add_item($lang->theme_redirect_edit, "index.php?module=config-theme_redirect&amp;action=edit_site&amp;sid={$site['sid']}");
			$popup->add_item($lang->theme_redirect_delete, "index.php?module=config-theme_redirect&amp;action=delete_site&amp;sid={$site['sid']}&amp;my_post_key={$mybb->post_code}", "return AdminCP.deleteConfirmation(this, '{$lang->theme_redirect_confirm_site_deletion}')");
			$table->construct_cell($popup->fetch(), array("class" => "align_center"));

			$table->construct_row();
	}
	if($table->num_rows() == 0)
	{
		$table->construct_cell($lang->theme_redirect_no_redirects, array('colspan' => 4));
		$table->construct_row();
	}

	$table->output($lang->theme_redirect_current);
	$page->output_footer();
}

if(!$mybb->input['action'])
{
	$page->output_header($lang->theme_redirect);
	$page->output_nav_tabs($sub_tabs, 'home');
	$table = new Table;
	$table->construct_header($lang->theme_redirect_name, array('width' => '20%'));
	$table->construct_header($lang->theme_redirect_title, array('width' => '20%'));
	$table->construct_header($lang->theme_redirect_url, array('width' => '50%'));
	$table->construct_header($lang->theme_redirect_controls, array('width' => '10%', 'class' => 'align_center'));

	require_once MYBB_ROOT."/inc/plugins/theme_redirect.php";
	$redirector_location = REDIRECTOR_LOCATION;
	$link = $mybb->settings['bburl']."/".$redirector_location."?theme=";
	$query = $db->simple_select('theme_redirect', "*", "", array("order_by" => "title", "order_dir" => "asc"));

	while($redirect = $db->fetch_array($query))
	{
			$table->construct_cell($redirect['name']);
			$table->construct_cell($redirect['title']);
			$table->construct_cell("<input type=\"text\" value=\"{$link}{$redirect['title']}\" size=\"75%\">");
			$popup = new PopupMenu("redirect_{$redirect['rid']}", $lang->theme_redirect_options);
			$popup->add_item($lang->theme_redirect_edit, "index.php?module=config-theme_redirect&amp;action=edit&amp;rid={$redirect['rid']}");
			$popup->add_item($lang->theme_redirect_delete, "index.php?module=config-theme_redirect&amp;action=delete&amp;rid={$redirect['rid']}&amp;my_post_key={$mybb->post_code}", "return AdminCP.deleteConfirmation(this, '{$lang->theme_redirect_confirm_deletion}')");
			$table->construct_cell($popup->fetch(), array("class" => "align_center"));

			$table->construct_row();
	}
	if($table->num_rows() == 0)
	{
		$table->construct_cell($lang->theme_redirect_no_redirects, array('colspan' => 4));
		$table->construct_row();
	}

	$table->output($lang->theme_redirect_current);
	$page->output_footer();
}
?>