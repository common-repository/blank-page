<?php
/*
 * Plugin Name:       Blank Page
 * Description:       Display a blank page to non-authenticated visitors
 * Version:           1.0
 * Requires at least: 6.1
 * Requires PHP:      5.3.0
 * Author:            Swartwerk Media Design, Inc.
 * Author URI:        http://www.swartwerk.com/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function blankpage_filter($buffer) {
	// we want to display a blank page, so we create one - ignoring everything wordpress worked so hard to build
	$o = file_get_contents( dirname(__FILE__) . '/blankpage.tpl' );
	$o = str_replace('{{title}}', esc_html( get_option('blankpage_title') ), $o);
	$o = str_replace('{{body}}', wp_kses_post( get_option('blankpage_body') ), $o);
	return $o;

}

function blankpage_blankify() {
	if ( !is_user_logged_in() && !is_admin() && !is_login() ) {

		// as soon as wordpress boots up, we start buffering all output so nothing goes to the browser
		ob_start('blankpage_filter');

		// after everything is done, we send all the markup to our filter so we can control the output
		add_action('shutdown', function() { ob_end_flush(); }, 999);

	}
}

function blankpage_settings_page() {
	add_options_page(
		'Blank Page Settings', // page title
		'Blank Page', // menu title
		'activate_plugins', // capability
		'blankpage_options', // slug
		'blankpage_options_output' // callback
	);
}

function blankpage_options_output() {
	echo '<form method="post" action="options.php">';
	settings_fields('blankpage_options');
	do_settings_sections('blankpage_options');
	submit_button();
	echo '</form>';
}

function blankpage_wrap_settings() {
	add_settings_section(
		'blankpage_settings_section',
		'Settings for Blank Page plugin',
		'blankpage_settings_callback',
		'blankpage_options'
	);

	add_settings_field(
		'blankpage_title_setting', // id
		'Heading', // title
		'blankpage_title_field', // callback
		'blankpage_options', // page
		'blankpage_settings_section' // section
	);
	register_setting('blankpage_options', 'blankpage_title');

	add_settings_field(
		'blankpage_body_setting', // id
		'Body', // title
		'blankpage_body_field', // callback
		'blankpage_options', // page
		'blankpage_settings_section' // section
	);
	register_setting('blankpage_options', 'blankpage_body');
}

function blankpage_settings_callback() {
	echo 'By default, this plugin displays a blank page to anonymous users. If you want to show a message instead, you can do that!';
}
function blankpage_title_field() {
	echo '<input name="blankpage_title" id="blankpage_title" type="text" size="40" value="'.esc_attr(get_option('blankpage_title')).'" />';
}
function blankpage_body_field() {
	wp_editor(esc_textarea(get_option('blankpage_body')), 'blankpage_body');
}

add_action( 'init', 'blankpage_blankify', -999);
add_action( 'admin_init', 'blankpage_wrap_settings');
add_action( 'admin_menu', 'blankpage_settings_page');

?>
