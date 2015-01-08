<?php
/*
Plugin Name: Verdon's MCE Formats
Plugin URI: http://verdon.ca/
Description: A plugin to replace the wp formats menu in the MCE Editor with a custom one
Version: 0.1
Author: Verdon Vaillancourt
Author URI: http://verdon.ca/
License: GPL2
*/

	function vv_mce_mod( $init ) {

		// example of how to customize the choices in the WP Format button
		// $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;' . $hi_format;
		
		// how to build an array of items to add or replace those in the MCE Style Select / Formats button
		$style_formats = array (
			array ( 'title' => 'Custom', 'items' => array( 
				// array( 'title' => 'Bold text', 'inline' => 'b' ),
				// array( 'title' => 'Red text', 'inline' => 'span', 'styles' => array( 'color' => '#ff0000' ) ),
				// array( 'title' => 'Paragraph', 'block' => 'p' ),
				// array( 'title' => 'Heading 2', 'block' => 'h2' ),
				// array( 'title' => 'Heading 3', 'block' => 'h3' ),
				// array( 'title' => 'Heading 4', 'block' => 'h4' ),
				// array( 'title' => 'Hanging Indent', 'block' => 'p', 'styles' => array( 'margin-left' => '3em', 'text-indent' => '-3em' ) )
				array( 'title' => 'Hanging Indent', 'block' => 'p', 'classes' => 'vv_hanging_indent' )
			))
		);
		$init['style_formats'] = json_encode( $style_formats );

		// whether to merge the above array with the default MCE Style Select, or replace it
		$init['style_formats_merge'] = true;

		return $init;
	}

//	hook to do the above before the editor is initialized
	add_filter('tiny_mce_before_init', 'vv_mce_mod');

//	function to add the MCE Style Select button to the 2nd toolbar array
	function vv_mce_add_buttons( $buttons ){
		array_splice( $buttons, 1, 0, 'styleselect' );
		// this will now remove the WP Formats button
		unset($buttons[0]);
		return $buttons;
	}

//	hook to do the above
	add_filter( 'mce_buttons_2', 'vv_mce_add_buttons' );

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

?>
