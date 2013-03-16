<?php
/***************************************************************************
 *
 *	Author:	Jordan Mussi
 *	File:	./admin/modules/config/theme_redirect.php
 *  
 *	License:
 *  
 *	This program is free software: you can redistribute it and/or modify it under 
 *	the terms of the GNU General Public License as published by the Free Software 
 *	Foundation, either version 3 of the License, or (at your option) any later 
 *	version.
 *	
 *	This program is distributed in the hope that it will be useful, but WITHOUT ANY 
 *	WARRANTY; without even the implied warranty of MERCHANTABILITY or 
 *	FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License 
 *	for more details.
 *	
 ***************************************************************************/

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
		
		if(!$errors)
		{
			$new_redirect = array(
				'tid'		=> $db->escape_string($mybb->input['tid']),
				'name'		=> $db->escape_string($mybb->input['name']),
				'title'		=> $db->escape_string($mybb->input['title'])
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
	}
	
	$form_container = new FormContainer($lang->theme_redirect_new);
	$form_container->output_row($lang->theme_redirect_tid.' <em>*</em>', $lang->theme_redirect_tid_desc, $form->generate_text_box('tid', $mybb->input['tid'], array('id' => 'tid')), 'tid');
	$form_container->output_row($lang->theme_redirect_name.' <em>*</em>', $lang->theme_redirect_name_desc, $form->generate_text_box('name', $mybb->input['name'], array('id' => 'name')), 'name');
	$form_container->output_row($lang->theme_redirect_title.' <em>*</em>', $lang->theme_redirect_title_desc, $form->generate_text_box('title', $mybb->input['title'], array('id' => 'title')), 'title');
	$form_container->end();
	
	$buttons[] = $form->generate_submit_button($lang->theme_redirect_save_new);
	
	$form->output_submit_wrapper($buttons);
	$form->end();
	
	$page->output_footer();
}

if($mybb->input['action'] == 'edit')
{
	$mybb->input['id'] = (int) $mybb->input['id'];
	$query = $db->simple_select('theme_redirect', "*", "id = '{$mybb->input['id']}'");
	$redirect = $db->fetch_array($query);
	
	if(!$redirect['id'])
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
		
		if(!$errors)
		{
			$update = array(
				'tid'		=>	$db->escape_string($mybb->input['tid']),
				'name'		=>	$db->escape_string($mybb->input['name']),
				'title'		=>	$db->escape_string($mybb->input['title'])
			);
						
			$db->update_query('theme_redirect', $update, "id = '{$mybb->input['id']}'");
			
			// Log admin action
			log_admin_action($mybb->input['id'], $mybb->input['title']);
			
			flash_message($lang->theme_redirect_success_redirect_updated, 'success');
			admin_redirect('index.php?module=config-theme_redirect');
		}
	}
	
	$page->add_breadcrumb_item($lang->theme_redirect_edit_redirect);
	$page->output_header($lang->theme_redirect.' - '.$lang->theme_redirect_edit_redirect);

	// Setup the edit prefix tab
//	unset($sub_tabs);
	$sub_tabs['edit'] = array(
		"title" => $lang->theme_redirect_edit_redirect,
		"link" => "index.php?module=config-theme_redirect&amp;action=edit&amp;id={$redirect['id']}",
		"description" => $lang->theme_redirect_edit_redirect_desc
	);
	$page->output_nav_tabs($sub_tabs, "edit");

	$form = new Form('index.php?module=config-theme_redirect&amp;action=edit', 'post');
	echo $form->generate_hidden_field('id', $mybb->input['id']);
	
	if($errors)
	{
		$page->output_inline_error($errors);
	}
	$mybb->input['tid'] = $redirect['tid'];
	$mybb->input['name'] = $redirect['name'];
	$mybb->input['title'] = $threadprefix['title'];
		
	$form_container = new FormContainer($lang->sprintf($lang->theme_redirect_editing, $redirect['name']));
	$form_container->output_row($lang->theme_redirect_tid.' <em>*</em>', $lang->theme_redirect_tid_desc, $form->generate_text_box('tid', $mybb->input['tid'], array('id' => 'tid')), 'tid');
	$form_container->output_row($lang->theme_redirect_name.' <em>*</em>', $lang->theme_redirect_name_desc, $form->generate_text_box('name', $mybb->input['name'], array('id' => 'name')), 'name');
	$form_container->output_row($lang->theme_redirect_title.' <em>*</em>', $lang->theme_redirect_title_desc, $form->generate_text_box('title', $mybb->input['title'], array('id' => 'title')), 'title');
	$form_container->end();
	
	$buttons[] = $form->generate_submit_button($lang->theme_redirect_save);
	
	$form->output_submit_wrapper($buttons);
	$form->end();
	
	$page->output_footer();
}

if($mybb->input['action'] == 'delete')
{
	$mybb->input['id'] = (int) $mybb->input['id'];
	$query = $db->simple_select('theme_redirect', "*", "id = '{$mybb->input['id']}'");
	$redirect = $db->fetch_array($query);
	
	if(!$redirect['id'])
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
		$db->delete_query('theme_redirect', "id='{$redirect['id']}'");
		
		// Log admin action
		log_admin_action($redirect['id'], $redirect['name']);
		
		flash_message($lang->theme_redirect_success_redirect_deleted, 'success');
		admin_redirect('index.php?module=config-theme_redirect');
	}
	else
	{
		$page->output_confirm_action("index.php?module=config-theme_redirect&amp;action=delete&amp;id={$mybb->input['id']}", $lang->theme_redirect_confirm_redirect_deletion);
	}
}

if(!$mybb->input['action'])
{
	$page->output_header($lang->theme_redirect);
	$page->output_nav_tabs($sub_tabs, 'home');
	$table = new Table;
	$table->construct_header($lang->theme_redirect_name, array('width' => '20%'));
	$table->construct_header($lang->theme_redirect_title, array('width' => '20%'));
	$table->construct_header($lang->theme_redirect_url, array('width' => '50%'));
	$table->construct_header($lang->theme_redirect_controls, array('width' => '10%'));
	
	require_once MYBB_ROOT."/inc/plugins/theme_redirect.php";
	$redirector_location = REDIRECTOR_LOCATION;
	$link = $mybb->settings['bburl']."/".$redirector_location."?theme=";
	$query = $db->simple_select('theme_redirect', "*", "", array("order_by" => "title", "order_dir" => "desc"));

	while($redirect = $db->fetch_array($query))
	{
			$table->construct_cell($redirect['name']);
			$table->construct_cell($redirect['title']);
			$table->construct_cell("<input type=\"text\" value=\"{$link}{$redirect['title']}\" size=\"75%\">");
			$popup = new PopupMenu("redirect_{$redirect['id']}", $lang->theme_redirect_options);
			$popup->add_item($lang->theme_redirect_edit, "index.php?module=config-theme_redirect&amp;action=edit&amp;id={$redirect['id']}");
			$popup->add_item($lang->theme_redirect_delete, "index.php?module=config-theme_redirect&amp;action=delete&amp;id={$redirect['id']}&amp;my_post_key={$mybb->post_code}", "return AdminCP.deleteConfirmation(this, '{$lang->theme_redirect_confirm_deletion}')");
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