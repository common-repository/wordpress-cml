<?php
/*
Plugin Name: Wordpress CML Plugin Lite
Plugin URI: http://www.wordpress-cml.com
Description: Adds multilanguage capabilities and user permissions to pages and posts, based on the users language. Also adds corporate capabilities to allow posts to sit in two concurrent states (such as published and draft)
Author: Wordpress CML
Version: 1.0
Author URI: http://www.wordpress-cml.com
*/

define("QRY_GET_ACTIVE_COUNTRIES", "select * from wpcml_countries where isActive=1 order by printable_name asc");
define('ML_PLUGIN_FOLDER', dirname(__FILE__) . "/");
define('ML_IMAGE_FOLDER', "../wp-content/plugins/wordpress_cml/package_ml/images");
define('CM_IMAGE_FOLDER', "../wp-content/plugins/wordpress_cml/images");
define('ML_FILE_FOLDER', "../wp-content/plugins/wordpress_cml");
register_activation_hook( __FILE__,'wordpresscml_ml_activate');
register_deactivation_hook( __FILE__,'wordpresscml_ml_deactivate');

// **************************************************************** //
// adds new columns to pages and posts overview screen (one for the language option, one to only show base language pages/posts) //
// **************************************************************** //
add_filter('manage_pages_columns', 'ml_newpagecol');
add_action('manage_pages_custom_column', 'ml_newpagecol_data', 1, 2);
add_filter('manage_posts_columns', 'ml_newpagecol');
add_action('manage_posts_custom_column', 'ml_newpagecol_data', 1, 2);
add_filter('posts_where','ml_handle_posts_where');


// **************************************************************** //
// add and edits meta boxes //
// **************************************************************** //
add_action('admin_menu', 'ml_language_add_custom_box'); // Language and page info
add_action('do_meta_boxes', 'ml_customposttype_image_box'); // For authors - restrict access box

// **************************************************************** //
// When a post is saved, carries out the function //
// **************************************************************** //
add_action('save_post', 'ml_saving_post_page');

// **************************************************************** //
// Adds functionality for a user to be set as a certain language //
// **************************************************************** //
add_action('register_form', 'ml_newFields');
add_action('show_user_profile', 'ml_newFields');
add_action('edit_user_profile', 'ml_newFields');
add_action('profile_update', 'ml_updateUserCountry');

// **************************************************************** //
// Adds the sidebar menus //
// **************************************************************** //
add_action('admin_menu', 'wpcml_ml_add_pages');


// **************************************************************** //
// Ran at the beginning of the page //
// **************************************************************** //
add_action('admin_print_styles', 'ml_page_run');

function wpcml_ml_add_pages() {
    add_menu_page('WPCML ML', 'WPCML ML', '1', 'ml_handle', 'ml_about_page');
        add_submenu_page('ml_handle', 'ml_setup', 'Setup', '1', 'ml_setup', 'ml_setup_page');
}

include(ML_PLUGIN_FOLDER . "package_ml/incs/page_posts_columns.php");
include(ML_PLUGIN_FOLDER . "package_ml/incs/useradmin.php");
include(ML_PLUGIN_FOLDER . "package_ml/incs/side_widgets.php");
include(ML_PLUGIN_FOLDER . "package_ml/incs/saving_post.php");
include(ML_PLUGIN_FOLDER . "package_ml/incs/menu_pages.php");
include(ML_PLUGIN_FOLDER . "package_ml/incs/page_run.php");
include(ML_PLUGIN_FOLDER . "package_ml/scripts/functions.php");

include(ML_PLUGIN_FOLDER . "scripts/cm_functions.php");


// THE CORPORATE SIDE OF THINGS //

define('TC_PLUGIN_FOLDER', dirname(__FILE__) . "/");
define('TC_IMAGE_FOLDER', "../wp-content/plugins/wordpress_cml/tc/images");
define('TC_FILE_FOLDER', "../wp-content/plugins/wordpress_cml");
register_activation_hook( __FILE__,'wordpresscml_tc_activate');
register_deactivation_hook( __FILE__,'wordpresscml_tc_deactivate');

// **************************************************************** //
// adds new columns to pages and posts overview screen //
// **************************************************************** //
add_filter('manage_pages_columns', 'tc_newpagecol');
add_action('manage_pages_custom_column', 'tc_newpagecol_data', 1, 2);
add_filter('manage_posts_columns', 'tc_newpagecol');
add_action('manage_posts_custom_column', 'tc_newpagecol_data', 1, 2);
add_filter('posts_where','tc_handle_posts_where');
add_filter('the_title','tc_post_title');


// **************************************************************** //
// add and edits meta boxes //
// **************************************************************** //
add_action('admin_menu', 'tc_page_info_box');
add_action('do_meta_boxes', 'tc_custom_submit_div');


// **************************************************************** //
// When a post is saved, carries out the function //
// **************************************************************** //
add_action('save_post', 'tc_saving_post_page');


// **************************************************************** //
// Ran at the beginning of the page //
// **************************************************************** //
add_action('admin_print_styles', 'tc_page_run');
add_action('init', 'tc_admin_head');
add_action('admin_footer', 'tc_admin_footer');


include(TC_PLUGIN_FOLDER . "package_tc/incs/page_posts_columns.php");
include(TC_PLUGIN_FOLDER . "package_tc/incs/side_widgets.php");
include(TC_PLUGIN_FOLDER . "package_tc/incs/saving_post.php");
include(TC_PLUGIN_FOLDER . "package_tc/incs/page_run.php");
include(TC_PLUGIN_FOLDER . "package_tc/scripts/functions.php");
?>