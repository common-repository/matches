<?php
/*
	Copyright 2010-2012 Anders Mårtensson <anders@nemrod.se>

	This file is part of Matches.

	Matches is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	Matches is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with Matches.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
	Plugin Name: Matches
	Plugin URI: http://nemrod.se/wordpress/matches-wordpress-plugin
	Description: This plugin makes it easy to administer and display matches (sports or otherwise) with a neat widget.
	Version: 0.6
	Author: Anders Mårtensson
	Author URI: http://nemrod.se
	License: GPLv3
*/

add_action('init', 'matches_init');
function matches_init() {
	load_plugin_textdomain('matches', 'wp-content/plugins/matches/lang', 'matches/lang');
}

global $matches_db_version;
$matches_db_version = '0.5';

function matches_db_install() {
	global $wpdb;
	global $matches_db_version;

	$matches_old_db_version = get_option('matches_db_version');

	$table_name_teams = $wpdb->prefix . 'matches_teams';
	$table_name_matches = $wpdb->prefix . 'matches_matches';

	if($wpdb->get_var("SHOW TABLES LIKE '$table_name_teams'") != $table_name_teams && $wpdb->get_var("SHOW TABLES LIKE '$table_name_matches'") != $table_name_matches) {
		$wpdb->show_errors();

		$teams_sql = "CREATE TABLE $table_name_teams (
				id SMALLINT NOT NULL AUTO_INCREMENT,
				name TINYTEXT NOT NULL,
				description TEXT NOT NULL,
				logo TINYTEXT NOT NULL,
				PRIMARY KEY (id)
			);";

		$matches_sql = "CREATE TABLE $table_name_matches (
				id SMALLINT NOT NULL AUTO_INCREMENT,
				time DATETIME NOT NULL,
				versus_team_id SMALLINT NOT NULL,
				location TINYTEXT NOT NULL,
				comment TEXT NOT NULL,
				our_score SMALLINT NOT NULL,
				opponent_score SMALLINT NOT NULL,
				link VARCHAR(255) NOT NULL,
				field_override TINYTEXT NOT NULL,
				PRIMARY KEY (id)
			);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($teams_sql);
		dbDelta($matches_sql);

		$wpdb->print_error();

		add_option('matches_db_version', $matches_db_version);
	}

	if($matches_old_db_version != $matches_db_version) {
		if('0.2' == $matches_old_db_version) {
			$wpdb->show_errors();

			$matches_sql = "ALTER TABLE $table_name_matches ADD link VARCHAR(255) NOT NULL;";
			$wpdb->query($matches_sql);

			$wpdb->print_error();
		}
		if('0.4' == $matches_old_db_version) {
			$wpdb->show_errors();

			$matches_sql = "ALTER TABLE $table_name_matches ADD field_override TINYTEXT NOT NULL;";
			$wpdb->query($matches_sql);

			$wpdb->print_error();
		}
		update_option('matches_db_version', $matches_db_version);
	}
}
register_activation_hook(__FILE__, 'matches_db_install');

/* ADMIN STUFF */
add_action('admin_menu', 'matches_admin_menu');
add_action('admin_init', 'matches_register_settings');

function matches_admin_menu() {
	add_menu_page(__('Matches', 'matches'), __('Matches', 'matches'), 'edit_pages', 'matches', 'matches_admin_matches');
	add_submenu_page('matches', __('Manage matches', 'matches'), __('Manage matches', 'matches'), 'edit_pages', 'matches', '');
	add_submenu_page('matches', __('Manage teams', 'matches'), __('Manage teams', 'matches'), 'edit_pages', 'matches-teams', 'matches_admin_teams');
	add_submenu_page('matches', __('Matches options', 'matches'), __('Options'), 'manage_options', 'matches-options', 'matches_admin_options');
}
function matches_admin_matches() {
	if(!current_user_can('edit_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	include('admin_matches.php');
}
function matches_admin_teams() {
	if(!current_user_can('edit_pages')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	include('admin_teams.php');
}
function matches_admin_options() {
	if(!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	include('admin_options.php');
}
function matches_register_settings() {
	register_setting('matches-settings-group', 'matches_upcoming_list_number');
	register_setting('matches-settings-group', 'matches_team_name');
	register_setting('matches-settings-group', 'matches_team_location');
	register_setting('matches-settings-group', 'matches_team_logo');
	register_setting('matches-settings-group', 'matches_date_format');
	register_setting('matches-settings-group', 'matches_custom_date_format');
	register_setting('matches-settings-group', 'matches_date_localize');
	register_setting('matches-settings-group', 'matches_date_timezone');
}

/* ACTUAL OUTPUT */
register_sidebar_widget('Matches', 'matches_display_widget');

function matches_display_widget($args = array()) {
	if(!empty($args)) {
		extract($args);
		echo $before_widget;
		//echo $before_title, 'Matches', $after_title;
	}
	include('widget.php');
	if(!empty($args)) {
		echo $after_widget;
	}
}

/* STYLESHEETS & SCRIPTS */
wp_enqueue_script('jquery');
add_action('wp_print_styles', 'matches_display_widget_css');
function matches_display_widget_css() {
	$url = WP_PLUGIN_URL . '/matches/widget.css';
	$file = WP_PLUGIN_DIR . '/matches/widget.css';
	if(file_exists($file)) {
		wp_register_style('matches', $url);
		wp_enqueue_style('matches');
	}
}
?>
