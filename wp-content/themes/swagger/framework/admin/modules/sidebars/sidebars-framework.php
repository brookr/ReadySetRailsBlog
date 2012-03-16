<?php
/*-----------------------------------------------------------------------------------*/
/* Run Sidebar Blvd - An addon to Options Framework
/*-----------------------------------------------------------------------------------*/

/* If the user can't edit theme options, no use running this plugin */

add_action( 'init', 'sidebar_blvd_rolescheck' );

function sidebar_blvd_rolescheck () {
	if ( current_user_can( 'edit_theme_options' ) ) {
		// If the user can edit theme options, let the fun begin!
		add_action( 'admin_menu', 'sidebar_blvd_add_page' );
		add_action( 'admin_init', 'sidebar_blvd_hijack_submenu' );
		add_action( 'admin_init', 'sidebar_blvd_init' );
		add_action( 'widgets_admin_page', 'sidebar_blvd_widgets_admin_page' );
	}
}

/* Initiate sidebars framework */

function sidebar_blvd_init() {

	// Include the required files
	require_once dirname( __FILE__ ) . '/sidebars-interface.php';
	require_once dirname( __FILE__ ) . '/sidebars-ajax.php';
}

/* Get the get_option id used for storing sidebars */

function sidebar_blvd_get_option_id() {
	$of_settings = get_option( 'optionsframework' );
	$id = null;
	if( isset( $of_settings['id'] ) )
		$id = $of_settings['id'].'_sidebars';
	return $id;
}

/* Add a subpages for Sidebas to the appearance menu. */

if ( ! function_exists( 'sidebar_blvd_add_page' ) ) {
	function sidebar_blvd_add_page() {
		
		$title = __( 'Widget Areas', TB_GETTEXT_DOMAIN );
		$bb_page = add_theme_page( $title, $title, 'edit_theme_options', 'sidebar_blvd', 'sidebar_blvd_page' );
		
		// Adds actions to hook in the required css and javascript
		add_action( "admin_print_styles-$bb_page", 'optionsframework_load_styles' );
		add_action( "admin_print_scripts-$bb_page", 'optionsframework_load_scripts' );
		add_action( "admin_print_styles-$bb_page", 'sidebar_blvd_load_styles' );
		add_action( "admin_print_scripts-$bb_page", 'sidebar_blvd_load_scripts' );
		add_action( "admin_print_styles-$bb_page", 'optionsframework_mlu_css', 0 );
		add_action( "admin_print_scripts-$bb_page", 'optionsframework_mlu_js', 0 );
		
	}
}

/* Hack the appearance submenu a to get "Widget Areas" to show up just below "Widgets". */

if ( ! function_exists( 'sidebar_blvd_hijack_submenu' ) ) {
	function sidebar_blvd_hijack_submenu() {
		global $submenu;
		$new_submenu = array();
		if( $submenu ) {
			// Find the current "Widget Areas" array, copy it, and remove it.
			foreach( $submenu['themes.php'] as $key => $value ) {
				if( $value[2] == 'sidebar_blvd' ) {
					$widget_areas = $value;
					unset( $submenu['themes.php'][$key] );
				}
			}
			// Recontruct the new submenu
			if( isset( $widget_areas ) ) {
				foreach( $submenu['themes.php'] as $key => $value ) {
					// Add original item to new menu
					$new_submenu[$key] = $value;
					// If this is the "Widgets" item, add in our 
					// Widget Areas item directly after.
					if( $value[2] == 'widgets.php' )
						$new_submenu[] = $widget_areas;
				}
			}
			// Replace old submenu with new submenu
			$submenu['themes.php'] = $new_submenu;
		}
	}
}

/* Loads the CSS */

function sidebar_blvd_load_styles() {
	wp_enqueue_style('sharedframework-style', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'css/admin-style.css');
	// wp_enqueue_style('sidebarsframework-style', SIDEBARS_FRAMEWORK_DIRECTORY . 'css/sidebars-style.css');
}	

/* Loads the javascript */

function sidebar_blvd_load_scripts() {
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('sharedframework-scripts', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'js/shared.min.js', array('jquery'));
	wp_enqueue_script('sidebarsframework-scripts', SIDEBARS_FRAMEWORK_DIRECTORY . 'js/sidebars-custom.js', array('jquery'));
	wp_localize_script('sharedframework-scripts', 'themeblvd', themeblvd_get_admin_locals( 'js' ) );
}

/* Message for Widgets page. */

if ( ! function_exists( 'sidebar_blvd_widgets_admin_page' ) ) {
	function sidebar_blvd_widgets_admin_page() {
		// Kind of a sloppy w/all the yucky inline styles, but otherwise, 
		// we'd have to enqueue an entire stylesheet just for the widgets 
		// page of the admin panel.
		echo '<div style="width:300px;float:right;position:relative;z-index:1000"><p class="description" style="padding-left:5px">';
		_e( 'In the <a href="themes.php?page=sidebar_blvd">Widget Area Manager</a>, you can create and manage widget areas for specific pages of your website to override the default locations you see below.', TB_GETTEXT_DOMAIN);
		echo '</p></div>';
	}
} 

/* Builds out the header for all sidebars pages. */

if ( ! function_exists( 'sidebar_blvd_page_header' ) ) {
	function sidebar_blvd_page_header() {
		?>
		<div id="sidebar_blvd">
			<div id="optionsframework" class="wrap">
			    <?php screen_icon( 'themes' ); ?>
			    <h2 class="nav-tab-wrapper">
			        <a href="#manage_sidebars" id="manage_sidebars-tab" class="nav-tab" title="<?php _e( 'Custom Widget Areas', TB_GETTEXT_DOMAIN ); ?>"><?php _e( 'Custom Widget Areas', TB_GETTEXT_DOMAIN ); ?></a>
			        <a href="#add_sidebar" id="add_sidebar-tab" class="nav-tab" title="<?php _e( 'Add New', TB_GETTEXT_DOMAIN ); ?>"><?php _e( 'Add New', TB_GETTEXT_DOMAIN ); ?></a>
			        <a href="#edit_sidebar" id="edit_sidebar-tab" class="nav-tab nav-edit-sidebar" title="<?php _e( 'Edit', TB_GETTEXT_DOMAIN ); ?>"><?php _e( 'Edit', TB_GETTEXT_DOMAIN ); ?></a>
			    </h2>
	    <?php
	}	
}

/* Builds out the footer for all sidebars pages. */

if ( ! function_exists( 'sidebar_blvd_page_footer' ) ) {
	function sidebar_blvd_page_footer() {
		?>
			</div> <!-- #optionsframework (end) -->
		</div><!-- #sidebar_blvd (end) -->
	    <?php
	}	
}

/* Builds out the full admin page. */

if ( ! function_exists( 'sidebar_blvd_page' ) ) {
	function sidebar_blvd_page() {
	
		sidebar_blvd_page_header();
		?>
    	<!-- MANAGE SIDEBARS (start) -->
    	
    	<div id="manage_sidebars" class="group">
	    	<form id="manage_current_sidebars">	
	    		<?php 
	    		$manage_nonce = wp_create_nonce( 'optionsframework_manage_sidebars' );
				echo '<input type="hidden" name="option_page" value="optionsframework_manage_sidebars" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$manage_nonce.'" />';
				?>
				<div class="ajax-mitt"><?php sidebar_blvd_manage(); ?></div>
			</form><!-- #manage_sidebars (end) -->
		</div><!-- #manage (end) -->
		
		<!-- MANAGE SIDEBARS (end) -->
		
		<!-- ADD SIDEBAR (start) -->
		
		<div id="add_sidebar" class="group">
			<form id="add_new_sidebar">
				<?php
				$add_nonce = wp_create_nonce( 'optionsframework_new_sidebar' );
				echo '<input type="hidden" name="option_page" value="optionsframework_add_sidebars" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$add_nonce.'" />';
				sidebar_blvd_add();
				?>
			</form><!-- #add_new_sidebars (end) -->
		</div><!-- #manage (end) -->
		
		<!-- ADD SIDEBAR (end) -->
		
		<!-- EDIT SIDEBAR (start) -->
		
		<div id="edit_sidebar" class="group">
			<form id="edit_current_sidebar" method="post">
				<?php
				$edit_nonce = wp_create_nonce( 'optionsframework_save_sidebar' );
				echo '<input type="hidden" name="action" value="update" />';
				echo '<input type="hidden" name="option_page" value="optionsframework_edit_sidebars" />';
				echo '<input type="hidden" name="_wpnonce" value="'.$edit_nonce.'" />';
				?>
				<div class="ajax-mitt"><!-- AJAX inserts edit sidebars page here. --></div>
			</form>
		</div><!-- #manage (end) -->
	
		<!-- EDIT SIDEBAR (end) -->
		<?php
		sidebar_blvd_page_footer();
	}
}
