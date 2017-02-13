<?php
/*
Plugin Name: 	UrbanSchedule
Description: 	A plugin for responsive week schedule
Author: 		LawlietNick
Version: 		0.0.1
Author URI: 	https://twitter.com/LawlietNick
License:     	GNU General Public License v3 or later
License URI: 	https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: 	UrbanSchedule
*/

// ==============================================
//  Prevent Direct Access of this file
// ==============================================

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if this file is accessed directly

// ==============================================
//	Get Plugin Version
// ==============================================

function urbanws_plugin_version() {
	if ( ! function_exists( 'get_plugins' ) )
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
	$plugin_file = basename( ( __FILE__ ) );
	return $plugin_folder[$plugin_file]['Version'];
}

add_action('admin_head', 'urban_add_weekschedule_tc_button');
add_action('admin_enqueue_scripts', 'urban_weekschedule_tc_css');
function urban_add_weekschedule_tc_button() {
    global $typenow;
    // check if use can edit pages and posts
    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
   	return;
    }
    // verify post and page types
    if( ! in_array( $typenow, array( 'post', 'page' ) ) )
        return;
	// check that WYSIWYG editor is enabled
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "urban_add_tinymce_plugin");
		add_filter('mce_buttons', 'urban_register_weekschedule_tc_button');
	}
}

function urban_add_tinymce_plugin($plugin_array) {
   	$plugin_array['urban_weekschedule_tc_button'] = plugins_url( '/urbanschedule.js', __FILE__ ); // CHANGE THE BUTTON SCRIPT HERE
   	return $plugin_array;
}

function urban_register_weekschedule_tc_button($buttons) {
   array_push($buttons, "urban_weekschedule_tc_button");
   return $buttons;
}

function urban_weekschedule_tc_css() {
	wp_enqueue_style('urban-tc', plugins_url('/style.admin.css', __FILE__));
}


// Include front css to make schedule look pretty
function urban_weekschedule_front_css() {
	wp_register_style( 'urban-styles',  plugin_dir_url( __FILE__ ) . 'schedule.css' );
    wp_enqueue_style( 'urban-styles' );
}

add_action( 'wp_enqueue_scripts', 'urban_weekschedule_front_css');

// ==============================================
//	Setting up shortcodes
// ==============================================

// Shortcode: [viikko otsikko=""] $content [/viikko]
// Description: Creates a div for a class
function urban_week_wrapper_shortcode($atts, $content = null) {
	extract(shortcode_atts( array(
			'otsikko'	=> 'Otsikko'
	), $atts ));

	ob_start(); // output buffering
?>

	<div class="timetable-container flex">
        <h3 class="class-room"><?php echo $otsikko; ?></h3>
        <div class="days-wrap flex">
			<?php echo do_shortcode( $content ); ?>
		</div>
	</div>

<?php 

	$week_contents = ob_get_contents();
	ob_end_clean();
	return $week_contents;
}

add_shortcode( 'viikko', 'urban_week_wrapper_shortcode' );

// Shortcode: [paiva viikonpaiva=""] $content [/paiva]
// Description: Creates a div for a class

function urban_day_wrapper_shortcode($atts, $content = null) {
	extract(shortcode_atts( array(
		'viikonpaiva' 	=> 'Maanantai'
		), $atts ));
	
	ob_start(); // output buffering

?>

	<div class="day flex">
		<h4 class="weekday"><?php echo $viikonpaiva; ?></h4>
		<?php echo do_shortcode( $content ); ?>
	</div>

<?php

	$day_contents = ob_get_contents();
	ob_end_clean();
	return $day_contents;
}

add_shortcode( 'paiva', 'urban_day_wrapper_shortcode' );

// Shortcode: [tunti nimi="" aloitusaika="" lopetusaika="" kuvaus=""]
// Description: Creates a div for a class

function urban_class_shortcode($atts) {
		extract(shortcode_atts( array(
			'nimi' 			=> 'default',
			'aloitusaika' 	=> '16:00',
			'lopetusaika' 	=> '17:00',
			'kuvaus' 		=> 'Aikuiset alkeet'
		), $atts));

		return "
			<div class='class-container flex'>
				<span class='time'>{$aloitusaika}–{$lopetusaika}</span>
				<span class='name'>{$nimi}</span>
				<span class='level'>{$kuvaus}</span>
			</div>
		";
}

add_shortcode( 'tunti', 'urban_class_shortcode' );



