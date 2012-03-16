<?php
/*-----------------------------------------------------------------------------------*/
/* Run Builder Blvd - An addon to Options Framework
/*-----------------------------------------------------------------------------------*/

/* If the user can't edit theme options, no use running this plugin */

add_action( 'init', 'builder_blvd_rolescheck' );

function builder_blvd_rolescheck () {
	if ( current_user_can( 'edit_theme_options' ) ) {
		// If the user can edit theme options, let the fun begin!
		add_action( 'admin_menu', 'builder_blvd_add_page');
		add_action( 'admin_init', 'builder_blvd_init' );
	}
}

/* Initiate builder framework */

function builder_blvd_init() {

	// Include the required files
	require_once dirname( __FILE__ ) . '/builder-default.php';
	require_once dirname( __FILE__ ) . '/builder-samples.php';
	require_once dirname( __FILE__ ) . '/builder-interface.php';
	require_once dirname( __FILE__ ) . '/builder-ajax.php';
		
}

/* Get the get_option id used for storing builder */

function builder_blvd_get_option_id() {
	$of_settings = get_option( 'optionsframework' );
	$id = null;
	if( isset( $of_settings['id'] ) )
		$id = $of_settings['id'].'_builder';
	return $id;
}

/* Add a menu page for Builder */

if ( ! function_exists( 'builder_blvd_add_page' ) ) {
	function builder_blvd_add_page() {
	
		$icon = THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'images/icon-builder.png';
		$bb_page = add_object_page( 'Layout Builder', 'Builder', 'administrator', 'builder_blvd', 'builder_blvd_page', $icon, 30 );
		
		// Adds actions to hook in the required css and javascript
		add_action( "admin_print_styles-$bb_page", 'optionsframework_load_styles' );
		add_action( "admin_print_scripts-$bb_page", 'optionsframework_load_scripts' );
		add_action( "admin_print_styles-$bb_page", 'builder_blvd_load_styles' );
		add_action( "admin_print_scripts-$bb_page", 'builder_blvd_load_scripts' );
		add_action( "admin_print_styles-$bb_page", 'optionsframework_mlu_css', 0 );
		add_action( "admin_print_scripts-$bb_page", 'optionsframework_mlu_js', 0 );
		
	}
}

/* Loads the CSS */

function builder_blvd_load_styles() {
	wp_enqueue_style('sharedframework-style', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'css/admin-style.css');
	wp_enqueue_style('builderframework-style', BUILDER_FRAMEWORK_DIRECTORY . 'css/builder-style.css');
}	

/* Loads the javascript */

function builder_blvd_load_scripts() {
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('sharedframework-scripts', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'js/shared.min.js', array('jquery'));
	wp_enqueue_script('builderframework-scripts', BUILDER_FRAMEWORK_DIRECTORY . 'js/builder-custom.js', array('jquery'));
	wp_localize_script('sharedframework-scripts', 'themeblvd', themeblvd_get_admin_locals( 'js' ) );
}

/**
 * Insert the correct value for an option within a
 * slide when using the builder_blvd_edit_slide() function.
 *
 * @param string $slide_options any currently saved options for this slide.
 * @param string $key primary key for option, image, video, or custom
 * @param string $type type of option
 * @param string $element inner value element, optional
 */
/*
function builder_blvd_slide_value( $slide_options, $type, $sub_type = null ) {
	
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
    	case 'image-link' :
			if( $sub_type == 'target' ) {
				if( isset( $slide_options['elements']['config']['image-link']['target'] ) )
    				$value = $slide_options['elements']['config']['image-link']['target'];
			} else if( $sub_type == 'url' ) {
				if( isset( $slide_options['elements']['config']['image-link']['url'] ) )
    				$value = $slide_options['elements']['config']['image-link']['url'];
			}
			break;

		// Button	
		case 'button' :
			if( $sub_type == 'text' ) {
				if( isset( $slide_options['elements']['config']['button']['text'] ) )
    				$value = stripslashes( $slide_options['elements']['config']['button']['text'] );
			} else if( $sub_type == 'target' ) {
				if( isset( $slide_options['elements']['config']['button']['target']) )
    				$value = $slide_options['elements']['config']['button']['target'];
			} else if( $sub_type == 'url' ) {
				if( isset( $slide_options['elements']['config']['button']['url'] ) )
    				$value = $slide_options['elements']['config']['button']['url'];
			}
			break;
		
		// Headline
    	case 'headline' :
			if( isset( $slide_options['elements']['config']['headline'] ) )
				$value = stripslashes( $slide_options['elements']['config']['headline'] );
			break;
			
		// Description	
		case 'description' :
			if( isset( $slide_options['elements']['config']['description'] ) )
				$value = stripslashes( $slide_options['elements']['config']['description'] );
			break;
			
		// Custom Content	
		case 'custom' :
			if( isset( $slide_options['custom'] ) )
				$value = stripslashes( $slide_options['custom'] );
			break;
	
	} // End switch $type
	
	return $value;

}
*/

/* Builds out the header for all builder pages. */

if ( ! function_exists( 'builder_blvd_page_header' ) ) {
	function builder_blvd_page_header() {
		?>
		<div id="builder_blvd">
			<div id="optionsframework" class="wrap">
			    <?php screen_icon( 'themes' ); ?>
			    <h2 class="nav-tab-wrapper">
			        <a href="#manage_layouts" id="manage_layouts-tab" class="nav-tab" title="<?php _e( 'Manage Layouts', TB_GETTEXT_DOMAIN ); ?>"><?php _e( 'Manage Layouts', TB_GETTEXT_DOMAIN ); ?></a>
			        <a href="#add_layout" id="add_layout-tab" class="nav-tab" title="<?php _e( 'Add New Layout', TB_GETTEXT_DOMAIN ); ?>"><?php _e( 'Add New Layout', TB_GETTEXT_DOMAIN ); ?></a>
			        <a href="#edit_layout" id="edit_layout-tab" class="nav-tab nav-edit-builder" title="<?php _e( 'Edit Layout', TB_GETTEXT_DOMAIN ); ?>"><?php _e( 'Edit Layout', TB_GETTEXT_DOMAIN ); ?></a>
			    </h2>
	    <?php
	}	
}

/* Builds out the footer for all builder pages. */

if ( ! function_exists( 'builder_blvd_page_footer' ) ) {
	function builder_blvd_page_footer() {
		?>
			</div> <!-- #optionsframework (end) -->
		</div><!-- #builder_blvd (end) -->
	    <?php
	}	
}

/* Builds out the full admin page. */

if ( ! function_exists( 'builder_blvd_page' ) ) {
	function builder_blvd_page() {

		builder_blvd_page_header();
		?>
    	<!-- MANAGE LAYOUT (start) -->
    	
    	<div id="manage_layouts" class="group">
	    	<form id="manage_builder">	
	    		<?php 
	    		$manage_nonce = wp_create_nonce( 'optionsframework_manage_builder' );
				echo '<input type="hidden" name="option_page" value="optionsframework_manage_builder" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$manage_nonce.'" />';
				?>
				<div class="ajax-mitt"><?php builder_blvd_manage(); ?></div>
			</form><!-- #manage_builder (end) -->
		</div><!-- #manage (end) -->
		
		<!-- MANAGE LAYOUT (end) -->
		
		<!-- ADD LAYOUT (start) -->
		
		<div id="add_layout" class="group">
			<form id="add_new_builder">
				<?php
				$add_nonce = wp_create_nonce( 'optionsframework_new_builder' );
				echo '<input type="hidden" name="option_page" value="optionsframework_add_builder" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$add_nonce.'" />';
				builder_blvd_add( null );
				?>
			</form><!-- #add_new_builder (end) -->
		</div><!-- #manage (end) -->
		
		<!-- ADD LAYOUT (end) -->
		
		<!-- EDIT LAYOUT (start) -->
		
		<div id="edit_layout" class="group">
			<form id="edit_builder" method="post">
				<?php
				$edit_nonce = wp_create_nonce( 'optionsframework_save_builder' );
				echo '<input type="hidden" name="action" value="update" />';
				echo '<input type="hidden" name="option_page" value="optionsframework_edit_builder" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$edit_nonce.'" />';
				?>
				<div class="ajax-mitt"><!-- AJAX inserts edit builder page here. --></div>				
			</form>
		</div><!-- #manage (end) -->
	
		<!-- EDIT LAYOUT (end) -->
		<?php
		builder_blvd_page_footer();
	}
}
