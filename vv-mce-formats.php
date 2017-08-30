<?php
/*
Plugin Name: Verdon's MCE Formats
Plugin URI: http://verdon.ca/
Description: A plugin to replace the wp formats menu in the MCE Editor with a custom one
Version: 0.2
Author: Verdon Vaillancourt
Author URI: http://verdon.ca/
License: GPL2
*/

	function vv_mce_mod( $init ) {
		// build an array of items to add or replace those in the MCE Style Select / Formats drop-down
		$style_formats = array (
			array ( 'title' => 'Custom', 'items' => array( 
				array( 'title' => 'Callout', 'block' => 'blockquote', 'wrapper' => true, 'classes' => 'vv_callout' ),
				array( 'title' => 'Hanging Indent - Clear Floats', 'block' => 'p', 'classes' => 'vv_hanging_indent' ),
				array( 'title' => 'Hanging Indent - No Clear', 'block' => 'p', 'classes' => 'vv_hanging_indent_noclear' )
			))
		);
		$init['style_formats'] = json_encode( $style_formats );

		// whether to merge the above array with the default MCE Style Select, or replace it
		global $wp_version;
		if ($wp_version >= 4.7) {
			$init['style_formats_merge'] = false;
		} else {
			$init['style_formats_merge'] = true;
		}

		return $init;
	}

//	hook to do the above before the editor is initialized
	add_filter('tiny_mce_before_init', 'vv_mce_mod');

//	function to add the MCE Style Select to the toolbar array
	function vv_mce_add_buttons( $buttons ){
		global $wp_version;
		if ($wp_version >= 4.7) {
			array_splice( $buttons, 0, 0, 'styleselect' );
		} else {
			array_splice( $buttons, 1, 0, 'styleselect' );
			// this will now remove the WP Formats button
			unset($buttons[0]);
		}
		return $buttons;
	}

//	hook to do the above
	if ($wp_version >= 4.7) {
		add_filter( 'mce_buttons_2', 'vv_mce_add_buttons' );
	} else {
		add_filter( 'mce_buttons_2', 'vv_mce_add_buttons' );
	}
	
// function and hook to add css
	function wptuts_styles_with_the_lot(){
		// Register the style
		wp_register_style( 'vv_mce-custom-style', plugins_url( '/css/vv-mce-formats.css', __FILE__ ), array(), '20150106', 'all' );
		// For either a plugin or a theme, you can then enqueue the style:
		wp_enqueue_style( 'vv_mce-custom-style' );
	}
	add_action( 'wp_enqueue_scripts', 'wptuts_styles_with_the_lot' );

// function and hook to add css to editor
	function vv_mce_mod_editor_styles() {
		$editor_style_url = plugins_url( '/css/vv-mce-formats.css', __FILE__ );
		add_editor_style( $editor_style_url );
	}
	add_action( 'after_setup_theme', 'vv_mce_mod_editor_styles' );

	// disable the removal of div and br tags when toggling from HTML to Visual
	function vv_change_mce_init_options( $init ) {
		$add = 'div[*],br[*]';
		if ( isset( $init['extended_valid_elements'] ) ) {
			$init['extended_valid_elements'] .= ',' . $add;
		} else {
			$init['extended_valid_elements'] = $add;
		}
		return $init;
	}
	add_filter('tiny_mce_before_init', 'vv_change_mce_init_options');

?>