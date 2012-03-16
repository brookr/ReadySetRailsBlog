<?php
/*-----------------------------------------------------------------------------------*/
/* Run Slider Blvd - An addon to Options Framework
/*-----------------------------------------------------------------------------------*/

/* If the user can't edit theme options, no use running this plugin */

add_action( 'init', 'slider_blvd_rolescheck' );

function slider_blvd_rolescheck () {
	if ( current_user_can( 'edit_theme_options' ) ) {
		// If the user can edit theme options, let the fun begin!
		add_action( 'admin_menu', 'slider_blvd_add_page');
		add_action( 'admin_init', 'slider_blvd_init' );
	}
}

/* Initiate sliders framework */

function slider_blvd_init() {

	// Include the required files
	require_once dirname( __FILE__ ) . '/sliders-default.php';
	require_once dirname( __FILE__ ) . '/sliders-interface.php';
	require_once dirname( __FILE__ ) . '/sliders-ajax.php';
		
}

/**
 * Get default slider options when adding a new slider. 
 * This is run from the AJAX action called when saving 
 * a new slider so that when the user edits the slider 
 * for the first time, the default settings will already 
 * be saved.
 * 
 * @param $type string type of slider
 * @return $default_options array all default options 
 */

function slider_blvd_slider_defaults( $type ) {
	
	$default_options = array();
	$sliders = slider_blvd_recognized_sliders();
	$options = $sliders[$type]['options'];
	
	// Set the options
	foreach( $options as $option ) {
		if( isset( $option['std'] ) )
			$default_options[$option['id']] = $option['std'];
		else
			$default_options[$option['id']] = null;	
	}
	
	// Return an error if options weren't found
	if( empty( $default_options ) )
		$default_options = 'error';
		
	return $default_options;
	
}

/* Get the get_option id used for storing sliders */

function slider_blvd_get_option_id() {
	$of_settings = get_option( 'optionsframework' );
	$id = null;
	if( isset( $of_settings['id'] ) )
		$id = $of_settings['id'].'_sliders';
	return $id;
}

/* Add a menu page for Sliders */

if ( ! function_exists( 'slider_blvd_add_page' ) ) {
	function slider_blvd_add_page() {
		
		$icon = THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'images/icon-images.png';
		$sb_page = add_object_page( 'Slider Manager', 'Sliders', 'administrator', 'slider_blvd', 'slider_blvd_page', $icon, 31 );
		
		// Adds actions to hook in the required css and javascript
		add_action( "admin_print_styles-$sb_page", 'optionsframework_load_styles' );
		add_action( "admin_print_scripts-$sb_page", 'optionsframework_load_scripts' );
		add_action( "admin_print_styles-$sb_page", 'slider_blvd_load_styles' );
		add_action( "admin_print_scripts-$sb_page", 'slider_blvd_load_scripts' );
		add_action( "admin_print_styles-$sb_page", 'optionsframework_mlu_css', 0 );
		add_action( "admin_print_scripts-$sb_page", 'optionsframework_mlu_js', 0 );
		
	}
}

/* Loads the CSS */

function slider_blvd_load_styles() {
	wp_enqueue_style('sharedframework-style', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'css/admin-style.css');
	wp_enqueue_style('sliderframework-style', SLIDERS_FRAMEWORK_DIRECTORY . 'css/sliders-style.css');
}	

/* Loads the javascript */

function slider_blvd_load_scripts() {
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('sharedframework-scripts', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'js/shared.min.js', array('jquery'));
	wp_enqueue_script('sliderframework-scripts', SLIDERS_FRAMEWORK_DIRECTORY . 'js/sliders-custom.js', array('jquery'));
	wp_localize_script('sharedframework-scripts', 'themeblvd', themeblvd_get_admin_locals( 'js' ) );
}

/**
 * Insert the correct value for an option within a
 * slide when using the slider_blvd_edit_slide() function.
 *
 * @param string $slide_options any currently saved options for this slide.
 * @param string $key primary key for option, image, video, or custom
 * @param string $type type of option
 * @param string $element inner value element, optional
 */

function slider_blvd_slide_value( $slide_options, $type, $sub_type = null ) {
	
	$value = null;
	
	switch( $type ) {
		
		// Slide Type
		case 'slide_type' :
    		if( isset( $slide_options['slide_type'] ) )
    			$value = $slide_options['slide_type'];
			break;
		
    	// Image position	
    	case 'position' :
    		if( isset( $slide_options['position'] ) )
    			$value = $slide_options['position'];
    		break;
    	
    	// Included elements	
    	case 'include' :
    		if( isset( $slide_options['elements']['include'] ) && in_array( $sub_type, $slide_options['elements']['include'] ) )
    			$value = ' checked="checked"';
    		break;
    		
    	// Image
    	case 'image' :
    		if( isset( $slide_options['image'] ) )
    			$value = $slide_options['image'];
    		break;
    	
    	// Video
    	case 'video' :
    		if( isset( $slide_options['video'] ) )
    			$value = $slide_options['video'];
    		break;
		
		// Image link
    	case 'image_link' :
			if( $sub_type == 'target' ) {
				if( isset( $slide_options['elements']['image_link']['target'] ) )
    				$value = $slide_options['elements']['image_link']['target'];
			} else if( $sub_type == 'url' ) {
				if( isset( $slide_options['elements']['image_link']['url'] ) )
    				$value = $slide_options['elements']['image_link']['url'];
			}
			break;

		// Button	
		case 'button' :
			if( $sub_type == 'text' ) {
				if( isset( $slide_options['elements']['button']['text'] ) )
    				$value = stripslashes( $slide_options['elements']['button']['text'] );
			} else if( $sub_type == 'target' ) {
				if( isset( $slide_options['elements']['button']['target']) )
    				$value = $slide_options['elements']['button']['target'];
			} else if( $sub_type == 'url' ) {
				if( isset( $slide_options['elements']['button']['url'] ) )
    				$value = $slide_options['elements']['button']['url'];
			}
			break;
		
		// Headline
    	case 'headline' :
			if( isset( $slide_options['elements']['headline'] ) )
				$value = stripslashes( $slide_options['elements']['headline'] );
			break;
			
		// Description	
		case 'description' :
			if( isset( $slide_options['elements']['description'] ) )
				$value = stripslashes( $slide_options['elements']['description'] );
			break;
			
		// Custom Content	
		case 'custom' :
			if( isset( $slide_options['custom'] ) )
				$value = stripslashes( $slide_options['custom'] );
			break;
	
	} // End switch $type
	
	return $value;

}

/* Builds out the header for all slider pages. */

if ( ! function_exists( 'slider_blvd_page_header' ) ) {
	function slider_blvd_page_header() {
		?>
		<div id="slider_blvd">
			<div id="optionsframework" class="wrap">
			    <?php screen_icon( 'themes' ); ?>
			    <h2 class="nav-tab-wrapper">
			        <a href="#manage" id="manage-tab" class="nav-tab" title="<?php _e( 'Manage Sliders', TB_GETTEXT_DOMAIN ); ?>"><?php _e( 'Manage Sliders', TB_GETTEXT_DOMAIN ); ?></a>
			        <a href="#add" id="add-tab" class="nav-tab" title="<?php _e( 'Add New Slider', TB_GETTEXT_DOMAIN ); ?>"><?php _e( 'Add Slider', TB_GETTEXT_DOMAIN ); ?></a>
			        <a href="#edit" id="edit-tab" class="nav-tab nav-edit-slider" title="<?php _e( 'Edit Slider', TB_GETTEXT_DOMAIN ); ?>"><?php _e( 'Edit Slider', TB_GETTEXT_DOMAIN ); ?></a>
			    </h2>
	    <?php
	}	
}

/* Builds out the footer for all slider pages. */

if ( ! function_exists( 'slider_blvd_page_footer' ) ) {
	function slider_blvd_page_footer() {
		?>
			</div> <!-- #optionsframework (end) -->
		</div><!-- #slider_blvd (end) -->
	    <?php
	}	
}

/* Builds out the full admin page. */

if ( ! function_exists( 'slider_blvd_page' ) ) {
	function slider_blvd_page() {
		$types = slider_blvd_recognized_sliders();
		$sliders = get_option( slider_blvd_get_option_id() );
		slider_blvd_page_header();
		?>
    	<!-- MANAGE SLIDER (start) -->
    	
    	<div id="manage" class="group">
	    	<form id="manage_sliders">	
	    		<?php 
	    		$manage_nonce = wp_create_nonce( 'optionsframework_manage_sliders' );
				echo '<input type="hidden" name="option_page" value="optionsframework_manage_sliders" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$manage_nonce.'" />';
				?>
				<div class="ajax-mitt"><?php slider_blvd_manage( $types, $sliders ); ?></div>
			</form><!-- #manage_sliders (end) -->
		</div><!-- #manage (end) -->
		
		<!-- MANAGE SLIDER (end) -->
		
		<!-- ADD SLIDER (start) -->
		
		<div id="add" class="group">
			<form id="add_new_slider">
				<?php
				$add_nonce = wp_create_nonce( 'optionsframework_new_slider' );
				echo '<input type="hidden" name="option_page" value="optionsframework_add_slider" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$add_nonce.'" />';
				slider_blvd_add( $types );
				?>
			</form><!-- #add_new_slider (end) -->
		</div><!-- #manage (end) -->
		
		<!-- ADD SLIDER (end) -->
		
		<!-- EDIT SLIDER (start) -->
		
		<div id="edit" class="group">
			<form id="edit_slider" method="post">
				<?php
				$edit_nonce = wp_create_nonce( 'optionsframework_save_slider' );
				echo '<input type="hidden" name="action" value="update" />';
				echo '<input type="hidden" name="option_page" value="optionsframework_edit_slider" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$edit_nonce.'" />';
				?>
				<div class="ajax-mitt"><!-- AJAX inserts edit slider page here. --></div>
			</form>
		</div><!-- #manage (end) -->
	
		<!-- EDIT SLIDER (end) -->
		<?php
		slider_blvd_page_footer();
	}
}
