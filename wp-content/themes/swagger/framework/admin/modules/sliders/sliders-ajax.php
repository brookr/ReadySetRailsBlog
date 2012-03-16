<?php
/* Hooks */

add_action( 'wp_ajax_slider_blvd_add_slider', 'slider_blvd_ajax_add_slider' );
add_action( 'wp_ajax_slider_blvd_save_slider', 'slider_blvd_ajax_save_slider' );
add_action( 'wp_ajax_slider_blvd_add_slide', 'slider_blvd_ajax_add_slide' );
add_action( 'wp_ajax_slider_blvd_update_table', 'slider_blvd_ajax_update_table' );
add_action( 'wp_ajax_slider_blvd_delete_slider', 'slider_blvd_ajax_delete_slider' );
add_action( 'wp_ajax_slider_blvd_edit_slider', 'slider_blvd_ajax_edit_slider' );

/* Add new slider */

function slider_blvd_ajax_add_slider () {
	
	// Make sure Satan isn't lurking
	check_ajax_referer( 'optionsframework_new_slider', 'security' );
	
	// Handle form data
	parse_str( $_POST['data'], $config );
	
	// Gather default options for slider type
	$options = slider_blvd_slider_defaults( $config['options']['slider_type'] );
		
	// Add in new slider
	if( is_array( $options ) ) {

		$args = array(	
			'post_type'			=> 'tb_slider', 
			'post_title'		=> $config['options']['slider_name'],
			'post_status' 		=> 'publish',
			'comment_status'	=> 'closed', 
			'ping_status'		=> 'closed'
		);
		
		$post_id = wp_insert_post( $args );
		
		// Set the initial post meta
		update_post_meta( $post_id, 'type', $config['options']['slider_type'] );
		update_post_meta( $post_id, 'settings', $options );
		
		// Respond with edit page for the new slider and ID
		$types = slider_blvd_recognized_sliders();
		echo $post_id.'[(=>)]';
		slider_blvd_edit( $post_id, $types );
		
	} else {
		// Return error if default options couldn't be found for type
		echo 'error_type';
	}

	die();

}

/* Save slider */

function slider_blvd_ajax_save_slider () {
	
	// Make sure Satan isn't lurking
	check_ajax_referer( 'optionsframework_save_slider', 'security' );
	
	// Handle form data
	parse_str( $_POST['data'], $data );

	//  Slider ID
	$slider_id = $data['slider_id'];
		
	// Start it
	$slider_type = get_post_meta( $slider_id, 'type', true );
	$sb_sliders = slider_blvd_recognized_sliders();
	$slider = $sb_sliders[$slider_type];
	$targets = array( '_self', '_blank', 'lighbox' );
	$options = array();
	$slides = array();
	
	// Slides
	if( isset( $data['slides'] ) ) {
		
		$slides = $data['slides'];
		
		// Sanitize slides
		if( ! empty( $slides ) ) {
			foreach( $slides as $key => $slide ) {
				
				// Slide type
				if( ! array_key_exists( $slide['slide_type'], $slider['types'] ) ) {
					unset( $slides[$key] );
					continue;
				}
				
				// Image
				$no_tags = array();
				if( isset( $slides[$key]['image']['id'] ) )
					$slides[$key]['image']['id'] = wp_kses( $slide['image']['id'], $no_tags );
				if( isset( $slides[$key]['image']['url'] ) )
					$slides[$key]['image']['url'] = wp_kses( $slide['image']['url'], $no_tags );
				
				// Media position
				if( ! array_key_exists( $slide['position'], $slider['positions'] ) ) {
					unset( $slides[$key] );
					continue;
				}
				
				// Custom Content
				if( isset( $slides[$key]['custom'] ) )
					$slides[$key]['custom'] = apply_filters( 'of_sanitize_textarea', $slide['custom'] );
				
				// Elements
				if( isset( $slide['elements'] ) ) {
					foreach( $slide['elements'] as $element_key => $element ) {
						
						// Check if element should even exist
						if( ! in_array( $element_key, $slider['elements'] ) ) {
							unset( $slides[$element_key] );
							continue;
						}
						
						// Now sanitize the inner options of each element
						switch( $element_key ) {
							case 'image_link' :
								if( ! in_array( $element['target'], $targets  ) ) $element['target'] = '_self';
								$element['url'] = apply_filters( 'of_sanitize_text', $element['url'] );
								break;
								
							case 'headline' :
								$element = apply_filters( 'of_sanitize_text', $element );
								break;
								
							case 'description' :
								$element = apply_filters( 'of_sanitize_text', $element );
								break;
								
							case 'button' :
								if( ! in_array( $element['target'], $targets  ) ) $element['target'] = '_self';
								$element['url'] = apply_filters( 'of_sanitize_text', $element['url'] );
								$element['text'] = apply_filters( 'of_sanitize_text', $element['text'] );
								break;
						}
					}
				}
				
				// Remove elements that aren't needed
				if( $slide['slide_type'] != 'custom' )
					unset( $slides[$key]['custom'] );
				if( $slide['slide_type'] != 'image' )
					unset( $slides[$key]['image'] );
				if( $slide['slide_type'] != 'video' )
					unset( $slides[$key]['video'] );
				if( $slide['slide_type'] == 'custom' ) {
					unset( $slides[$key]['elements'] );
					unset( $slides[$key]['position'] );
				}

			}
		}
	}
	
	// Options
	if( isset( $data['options'] ) ) {
		
		// Sanitize options
		$clean = array();
		foreach( $slider['options'] as $option ){
			
			if ( ! isset( $option['id'] ) )
				continue;

			if ( ! isset( $option['type'] ) )
				continue;
			
			$option_id = $option['id'];
				
			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] ) {
				if( isset( $element['options'][$option_id] ) )
					$data['options'][$option_id] = '1';
				else
					$data['options'][$option_id] = '0';
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] ){
				if( isset( $data['options'][$option_id] ) ) {
					foreach ( $option['options'] as $key => $value ) {
						if( isset($value) )
							$data['options'][$option_id][$key] = '1';
					}
				}
			}
			
			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'of_sanitize_' . $option['type'] ) ) {
				$clean[$option_id] = apply_filters( 'of_sanitize_' . $option['type'], $data['options'][$option_id], $option );
			}
			
		}
		$settings = $clean;
	}
	
	// Update even they're empty
	update_post_meta( $slider_id, 'slides', $slides );
	update_post_meta( $slider_id, 'settings', $settings );
	
	// Allow plugins to hook in
	do_action( 'themeblvd_save_slider_'.$slider_type, $slider_id, $slides, $settings );
	
	// Display update message
	echo '<div id="setting-error-save_options" class="updated fade settings-error ajax-update">';
	echo '	<p><strong>'.__( 'Slider saved.' ).'</strong></p>';
	echo '</div>';
	die();
}

/* Add new slide */

function slider_blvd_ajax_add_slide() {	
	$atts = explode( '=>', $_POST['data'] );
	$slide_id = uniqid( 'slide_'.rand() );
	slider_blvd_edit_slide( $atts[0], $atts[1], $slide_id, null, 'hide' );
	die();
}

/* Update slider manager table */

function slider_blvd_ajax_update_table() {
	slider_blvd_manage();
	die();
}

/* Delete slider */

function slider_blvd_ajax_delete_slider() {
	
	// Make sure Satan isn't lurking
	check_ajax_referer( 'optionsframework_manage_sliders', 'security' );
	
	// Handle data
	parse_str( $_POST['data'], $data );

	// Only run if user selected some sliders to delete
	if( isset( $data['posts'] ) ) {

		// Delete slider posts
		foreach( $data['posts'] as $id ) {
			
			// Can still be recovered from trash 
			// if post type's admin UI is turned on.
			wp_delete_post( $id );
		
		}
		
		// Send back number of sliders
		$posts = get_posts( array( 'post_type' => 'tb_slider', 'numberposts' => -1 ) );
		echo sprintf( _n( '1 Slider', '%s Sliders', count($posts) ), number_format_i18n( count($posts) ) ).'[(=>)]';
		
		// Display update message
		echo '<div id="setting-error-delete_slider" class="updated fade settings-error ajax-update">';
		echo '	<p><strong>'.__( 'Slider(s) deleted.' ).'</strong></p>';
		echo '</div>';
	
	}
	
	die();
}

/* Edit a slider */

function slider_blvd_ajax_edit_slider() {
	$slider_id = $_POST['data'];
	$types = slider_blvd_recognized_sliders();
	$sliders = get_option( slider_blvd_get_option_id() );
	echo $slider_id.'[(=>)]';
	slider_blvd_edit( $_POST['data'], $types, $sliders );
	die();
}
