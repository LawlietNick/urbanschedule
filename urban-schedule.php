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



