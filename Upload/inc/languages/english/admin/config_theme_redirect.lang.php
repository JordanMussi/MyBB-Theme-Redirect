<?php
/**
 * Plugin Author: Jordan Mussi
 * File: ./inc/languages/english/admin/theme_redirect.lang.php
 *
 * Translation Author: Jordan Mussi <https://github.com/JordanMussi>
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

// Miscellaneous
$l['theme_redirect'] = "Theme Redirect";
$l['theme_redirects'] = "Theme Redirects";
$l['theme_redirect_desc'] = "Redirect the user to a custom theme demo site. Multiple sites are supported.";
$l['theme_redirect_can_manage'] = "Can manage theme redirects?";
$l['theme_redirect_confirm_deletion'] = "Are you sure you want to delete this redirect?";
$l['theme_redirect_current'] = "Current Redirects";
$l['theme_redirect_url'] = "URL";
$l['theme_redirect_controls'] = "Controls";
$l['theme_redirect_options'] = "Options";
$l['theme_redirect_edit'] = "Edit";
$l['theme_redirect_delete'] = "Delete";
$l['theme_redirect_no_redirects'] = "There are no theme redirects setup on your forum.";

// Tabs
$l['theme_redirect_redirects'] = "Redirects";
$l['theme_redirect_redirects_desc'] = "Manage existing redirects";
$l['theme_redirect_add_new'] = "Add New Redirect";
$l['theme_redirect_add_new_desc'] = "Create a new redirect";
$l['theme_redirect_sites'] = "Sites";
$l['theme_redirect_sites_desc'] = "Manage existing sites";
$l['theme_redirect_add_site'] = "Add New Site";
$l['theme_redirect_add_site_desc'] = "Create a new site";
$l['theme_redirect_edit_site'] = "Edit Site";
$l['theme_redirect_edit_site_desc'] = "Edit an existing site";
$l['theme_redirect_edit_redirect'] = "Edit Redirect";
$l['theme_redirect_edit_redirect_desc'] = "Edit an existing redirect";

// Conformation
$l['theme_redirect_confirm_redirect_deletion'] = "Are you sure you want to delete this redirect?";
$l['theme_redirect_confirm_site_deletion'] = "Are you sure you want to delete this site?";

// Success
$l['theme_redirect_success_redirect_created'] = "The redirect has been created successfully.";
$l['theme_redirect_success_redirect_updated'] = "The redirect has been updated successfully.";
$l['theme_redirect_success_redirect_deleted'] = "The redirect has been deleted successfully.";
$l['theme_redirect_success_site_created'] = "The site has been created successfully.";
$l['theme_redirect_success_site_updated'] = "The site has been updated successfully.";
$l['theme_redirect_success_site_deleted'] = "The site has been deleted successfully.";

// Error
$l['theme_redirect_error_invalid_redirect'] = "The specified redirect does not exist.";
$l['theme_redirect_error_invalid_site'] = "The specified site does not exist.";
$l['theme_redirect_error_title_in_use'] = "The specified title is already in use.";
$l['theme_redirect_error_site_in_use'] = "The specified site is being used by redircts.";
$l['theme_redirect_error_missing_tid'] = "Please enter the theme id you wish to add.";
$l['theme_redirect_error_missing_name'] = "Please enter the name you wish to add.";
$l['theme_redirect_error_missing_title'] = "Please enter the title you wish to add.";
$l['theme_redirect_error_missing_url'] = "Please enter the url you wish to add.";
$l['theme_redirect_error_missing_url_replacement'] = "Please enter \"{theme}\" in the url.";
$l['theme_redirect_error_missing_site'] = "Please enter the site you wish to add.";

// Form
$l['theme_redirect_new'] = "New Redirect";
$l['theme_redirect_editing'] = "Refine Redirect";
$l['theme_redirect_new_site'] = "New Site";
$l['theme_redirect_editing_site'] = "Refine Site";

$l['theme_redirect_tid'] = "ID";
$l['theme_redirect_tid_desc'] = "The theme ID. (Replaces {theme} in the site URL.)";
$l['theme_redirect_name'] = "Name";
$l['theme_redirect_name_desc'] = "The name of the theme.";
$l['theme_redirect_title'] = "Title";
$l['theme_redirect_title_desc'] = "The title of the theme. (?theme=title)";
$l['theme_redirect_site'] = "Site";
$l['theme_redirect_site_desc'] = "The site to redirect to.";
$l['theme_redirect_site_name_desc'] = "The name of the site";
$l['theme_redirect_site_url_desc'] = "The url of the site. {theme} is replaced with the theme ID.";

// Submit
$l['theme_redirect_save_new'] = "Save New Redirect";
$l['theme_redirect_save'] = "Save Redirect";
$l['theme_redirect_delete_redirect'] = "Delete Redirect";
$l['theme_redirect_save_new_site'] = "Save New Site";
$l['theme_redirect_save_site'] = "Save Site";
$l['theme_redirect_delete_site'] = "Delete Site";
?>