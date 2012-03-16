<?php
/*-----------------------------------------------------------------------------------*/
/* Slider Blvd Configuration Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Get recognized sliders.
 *
 * Returns an array of all recognized sliders.
 * Sliders included with a particular theme can 
 * be edited by adding a filter through this array.
 *
 * @return array
 */
 
function slider_blvd_recognized_sliders() {
	
	/**
	 * For each slider type, there are then types of 
	 * individual slides it supports.
	 */
	
	$standard_types = array(
		'image' => array(
			'name' => __( 'Image Slide', TB_GETTEXT_DOMAIN ),
			'main_title' => __( 'Setup Image', TB_GETTEXT_DOMAIN )
		),
		'video' => array(
			'name' => __( 'Video Slide', TB_GETTEXT_DOMAIN ),
			'main_title' => __( 'Video Link', TB_GETTEXT_DOMAIN )
		),
		'custom' => array(
			'name' => __( 'Custom Slide', TB_GETTEXT_DOMAIN ),
			'main_title' => __( 'Setup Custom Content', TB_GETTEXT_DOMAIN )
		)
	);
	$carrousel_types = array(
		'image' => array(
			'name' => __( 'Image Slide', TB_GETTEXT_DOMAIN ),
			'main_title' => __( 'Setup Image', TB_GETTEXT_DOMAIN )
		)
	);
	
	/**
	 * For each slider type, there are positions its media
	 * can be placed.
	 */
	
	$standard_positions = array(
		'full' 			=> 'Full-Size',
		'align-left' 	=> 'Aligned Left',
		'align-right' 	=> 'Aligned Right'
	);
	$carrousel_positions = array(
		'full' 			=> 'Full-Size'
	);
	
	/**
	 * For each slider type, these are the different elements
	 * the user can choose to include in a slide.
	 */
	
	$standard_elements = array( 'image_link', 'headline', 'description', 'button', 'custom_content' );
	$carrousel_elements = array( 'image_link' );
	
	/**
	 * For each slider type, these are settings.
	 * ALL options must have a default value 'std'.
	 */
	
	$standard_options = array(
		array(
			'id'		=> 'fx',
			'name'		=> __( 'How to transition between slides?', TB_GETTEXT_DOMAIN ),
			'std'		=> 'fade',
			'type'		=> 'select',
			'options'		=> array(
	            'fade' 	=> 'Fade',
				'slide'	=> 'Slide'
			)
		),
		array(
			'id'		=> 'timeout',
			'name' 		=> __( 'Seconds between each transition?', TB_GETTEXT_DOMAIN ),
			'std'		=> '5',
			'type'		=> 'text'
	    ),
		array(
			'id'		=> 'nav_standard',
			'name'		=> __( 'Show standard slideshow navigation?', TB_GETTEXT_DOMAIN ),
			'std'		=> '1',
			'type'		=> 'select',
			'options'		=> array(
	            '1'	=> __( 'Yes, show navigation.', TB_GETTEXT_DOMAIN ),
	            '0'	=> __( 'No, don\'t show it.', TB_GETTEXT_DOMAIN )
			)
		),
		array(
			'id'		=> 'nav_arrows',
			'name'		=> __( 'Show next/prev arrows?', TB_GETTEXT_DOMAIN ),
			'std'		=> '1',
			'type'		=> 'select',
			'options'		=> array(
	            '1'	=> __( 'Yes, show arrows.', TB_GETTEXT_DOMAIN ),
	            '0'	=> __( 'No, don\'t show them.', TB_GETTEXT_DOMAIN )
			)
		),
		array(
			'id'		=> 'pause_play',
			'name'		=> __( 'Show pause/play button?', TB_GETTEXT_DOMAIN ),
			'std'		=> '1',
			'type'		=> 'select',
			'options'		=> array(
	            '1'	=> __( 'Yes, show pause/play button.', TB_GETTEXT_DOMAIN ),
	            '0'	=> __( 'No, don\'t show it.', TB_GETTEXT_DOMAIN )
			)
		)
	);
	$carrousel_options = array(
		array(
			'id'		=> 'nav_arrows',
			'name'		=> __( 'Show next/prev arrows?', TB_GETTEXT_DOMAIN ),
			'std'		=> '1',
			'type'		=> 'select',
			'options'		=> array(
	            '1'	=> __( 'Yes, show arrows.', TB_GETTEXT_DOMAIN ),
	            '0'	=> __( 'No, don\'t show them.', TB_GETTEXT_DOMAIN )
			)
		)
	);
	
	// Final array (which is filterable from outside)
	$default = array(
		'standard' => array(
			'name' 		=> 'Standard',
			'id'		=> 'standard',
			'types'		=> $standard_types,
			'positions'	=> $standard_positions,
			'elements'	=> $standard_elements,
			'options'	=> $standard_options
		),
		'carrousel' => array(
			'name' 		=> 'Carrousel 3D',
			'id'		=> 'carrousel',
			'types'		=> $carrousel_types,
			'positions'	=> $carrousel_positions,
			'elements'	=> $carrousel_elements,
			'options'	=> $carrousel_options
		)
	);
	
	return apply_filters( 'slider_blvd_recognized_sliders', $default );
}