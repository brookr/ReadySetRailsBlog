<?php
/*-----------------------------------------------------------------------------------*/
/* General Admin Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Initialize the entire admin panel, only showing 
 * supported features.
 *
 * @since 2.0.0
 */
 
function themeblvd_admin_init() {
	
	/*------------------------------------------------------*/
	/* Admin Modules
	/*------------------------------------------------------*/
	
	// Common Assets
	define( 'THEMEBLVD_ADMIN_ASSETS_URL', TB_FRAMEWORK_URL . '/admin/assets/' );
	define( 'THEMEBLVD_ADMIN_ASSETS_DIRECTORY', TB_FRAMEWORK_DIRECTORY . '/admin/assets/');
	
	if( themeblvd_supports( 'admin', 'options' ) ) {
		
		// Options Framework Setup
		define( 'OPTIONS_FRAMEWORK_URL', TB_FRAMEWORK_URL . '/admin/modules/options/' );
		define( 'OPTIONS_FRAMEWORK_DIRECTORY', TB_FRAMEWORK_DIRECTORY . '/admin/modules/options/');
		include( OPTIONS_FRAMEWORK_URL . 'options-framework.php');
		
		// Sliders Framework Setup (Dependent on Options Framework)
		if( themeblvd_supports( 'admin', 'sliders' ) ) {
			define( 'SLIDERS_FRAMEWORK_URL', TB_FRAMEWORK_URL . '/admin/modules/sliders/' );
			define( 'SLIDERS_FRAMEWORK_DIRECTORY', TB_FRAMEWORK_DIRECTORY . '/admin/modules/sliders/');
			include( SLIDERS_FRAMEWORK_URL . 'sliders-framework.php' );
		}
		
		// Builder Framework Setup (Dependent on Options Framework)
		if( themeblvd_supports( 'admin', 'builder' ) ) {
			define( 'BUILDER_FRAMEWORK_URL', TB_FRAMEWORK_URL . '/admin/modules/builder/' );
			define( 'BUILDER_FRAMEWORK_DIRECTORY', TB_FRAMEWORK_DIRECTORY . '/admin/modules/builder/');
			include( BUILDER_FRAMEWORK_URL . 'builder-framework.php' );
		}
		
		// Sidebar Framework Setup (Dependent on Options Framework)
		if( themeblvd_supports( 'admin', 'sidebars' ) ) {
			define( 'SIDEBARS_FRAMEWORK_URL', TB_FRAMEWORK_URL . '/admin/modules/sidebars/' );
			define( 'SIDEBARS_FRAMEWORK_DIRECTORY', TB_FRAMEWORK_DIRECTORY . '/admin/modules/sidebars/');
			include( SIDEBARS_FRAMEWORK_URL . 'sidebars-framework.php' );
		}
	
	}
}

/**
 * Non-modular Admin Assets
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_non_modular_assets' ) ) {
	function themeblvd_non_modular_assets() {
		global $pagenow;
		if( $pagenow == 'post-new.php' || $pagenow == 'post.php' ) {
			wp_enqueue_style( 'tb_meta_box-styles', THEMEBLVD_ADMIN_ASSETS_DIRECTORY.'css/meta-box.css', false, false, 'screen' );
			wp_enqueue_script( 'tb_meta_box-scripts', THEMEBLVD_ADMIN_ASSETS_DIRECTORY . 'js/meta-box.min.js', array('jquery') );
		}
	}
}

/**
 * On activation of the theme, redirect user to the theme options
 * panel.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_theme_activation' ) ) {
	function themeblvd_theme_activation(){
		global $pagenow;
		if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) )
			header( 'Location: '.admin_url( 'themes.php?page=options-framework' ) );
	}
}

/**
 * Setup all possible assignments (i.e. WordPress conditionals) 
 * that could be assigned to an item. An example where this is 
 * currently used is to assign custom sidebars to various WP 
 * conditionals.
 *
 * @since 2.0.0
 */
 
function themeblvd_conditionals_config() {
	$conditionals = array(
		'pages' => array(
			'id'	=> 'pages',
			'name'	=> __( 'Pages', TB_GETTEXT_DOMAIN ),
			'empty'	=> __( 'No pages to display.', TB_GETTEXT_DOMAIN )
		),
		'posts' => array(
			'id'	=> 'posts',
			'name'	=> __( 'Posts', TB_GETTEXT_DOMAIN ),
			'empty'	=> __( 'No posts to display.', TB_GETTEXT_DOMAIN )
		),
		'posts_in_category' => array(
			'id'	=> 'posts_in_category',
			'name'	=> __( 'Posts in Category', TB_GETTEXT_DOMAIN ),
			'empty'	=> __( 'No categories to display.', TB_GETTEXT_DOMAIN )
		),
		'categories' => array(
			'id'	=> 'categories',
			'name'	=> __( 'Category Archives', TB_GETTEXT_DOMAIN ),
			'empty'	=> __( 'No categories to display.', TB_GETTEXT_DOMAIN )
		),
		'tags' => array(
			'id'	=> 'tags',
			'name'	=> __( 'Tag Archives', TB_GETTEXT_DOMAIN ),
			'empty'	=> __( 'No tags to display.', TB_GETTEXT_DOMAIN )
		),
		'top' => array(
			'id'	=> 'top',
			'name'	=> __( 'Hierarchy', TB_GETTEXT_DOMAIN ),
			'items'	=> array(
				'home' 			=> __( 'Homepage', TB_GETTEXT_DOMAIN ),
				'posts' 		=> __( 'All Posts', TB_GETTEXT_DOMAIN ),
				'pages' 		=> __( 'All Pages', TB_GETTEXT_DOMAIN ),
				'archives' 		=> __( 'All Archives', TB_GETTEXT_DOMAIN ),
				'categories'	=> __( 'All Category Archives', TB_GETTEXT_DOMAIN ),
				'tags' 			=> __( 'All Tag Archives', TB_GETTEXT_DOMAIN ),
				'authors' 		=> __( 'All Author Archives', TB_GETTEXT_DOMAIN ),
				'search' 		=> __( 'Search Results', TB_GETTEXT_DOMAIN ),
				'404' 			=> __( '404 Page', TB_GETTEXT_DOMAIN )
			)
		)
	);
	return apply_filters( 'themeblvd_conditionals_config', $conditionals );
}

/**
 * Set allowed tags in the admin panel. This works by 
 * adding the framework's allowed admin tags to WP's 
 * global $allowedtags.
 *
 * @since 2.0.0
 *
 * @param $type string type of field 'text' or 'textarea'
 */

function themeblvd_allowed_tags( $extended = false ) {
	
	global $allowedtags;
	$tags = $allowedtags;
	
	// Tags to add
	$addons = array(
		'a' => array(
			'href' => array(),
			'title' => array(),
			'class' => array(),
			'id'	=> array(),
			'style' => array()
		),
		'img' => array(
			'alt' => array(),
			'src' => array(),
			'class' => array(),
			'id'	=> array(),
			'style' => array()
		)
	);
	
	// Add in extended HTML tags
	if( $extended ) {
		$extended_addons = array(
			'br' => array(),
			'h1' => array(
				'class' => array(),
				'id'	=> array(),
				'style' => array()
			),
			'h2' => array(
				'class' => array(),
				'id'	=> array(),
				'style' => array()
			),
			'h3' => array(
				'class' => array(),
				'id'	=> array(),
				'style' => array()
			),
			'h4' => array(
				'class' => array(),
				'id'	=> array(),
				'style' => array()
			),
			'h5' => array(
				'class' => array(),
				'id'	=> array(),
				'style' => array()
			),
			'h6' => array(
				'class' => array(),
				'id'	=> array(),
				'style' => array()
			),
			'script' => array(
				'type' => array()
			),
			'div' => array(
				'class' => array(),
				'id'	=> array(),
				'style' => array()
			),
			'p' => array(
				'class' => array(),
				'id'	=> array(),
				'style' => array()
			),
			'ul' => array(
				'class' => array(),
				'id'	=> array(),
				'style' => array()
			),
			'li' => array(
				'class' => array(),
				'id'	=> array(),
				'style' => array()
			),
			'iframe' => array(
				'style' => array(),
				'width' => array(),
				'height' => array(),
				'src' => array(),
				'frameborder' => array(),
				'allowfullscreen' => array(),
				'webkitAllowFullScreen' => array(),
				'mozallowfullscreen' => array()
			)
		);
		$addons = array_merge( $addons, $extended_addons );
	}
	
	// Add the addons to the final array
	$tags = array_merge( $tags, $addons );
	
	return apply_filters( 'themeblvd_allowed_tags', $tags );
	
}

/**
 * Gather all assignments for posts into a single 
 * array organized by post ID.
 *
 * @since 2.0.0
 *
 * @param $posts array all posts from WP's get_posts()
 * @return $assignments array assignments from all posts organized by ID
 */

function themeblvd_get_assignment_conflicts( $posts ) {
	
	// Setup $conflicts/$non_conflicts arrays
	$non_conflicts = array();
	$conflicts = array();
	$locations = themeblvd_get_sidebar_locations();
	foreach( $locations as $location) {
		$conflicts[$location['location']['id']] = array();
		$non_conflicts[$location['location']['id']] = array();
	}

	// Loop through sidebar posts to construct two arrays side-by-side.
	// As we build the $non_conflicts arrays, we will be able to build
	// the $conflicts arrays off to the side by checking if items already
	// exist in the $non_conflicts.
	foreach( $posts as $post ) {
		
		// Determine location sidebar is assigned to.
		$location = get_post_meta( $post->ID, 'location', true );
		
		// Only run check if a location exists and this 
		// is not a floating widget area.
		if( $location && $location != 'floating' ) {
			$assignments = get_post_meta( $post->ID, 'assignments', true );
			if( is_array( $assignments ) && ! empty( $assignments ) ) {
				foreach( $assignments as $key => $assignmnet ) {
					if( in_array( $key, $non_conflicts[$location] ) ) {
						if( ! in_array( $key, $conflicts[$location] ) ) {
							$conflicts[$location][] = $key;
						}	
					} else {
						$non_conflicts[$location][] = $key;
					}
				}
			}
			
			
		}
	}
	return $conflicts;
}

/**
 * Hijack and modify default WP's "Page Attributes" 
 * meta box.
 *
 * @since 2.0.0
 */ 
 
function themeblvd_hijack_page_atts() {
	if( themeblvd_supports( 'meta', 'hijack_atts' ) ) {
		remove_meta_box( 'pageparentdiv', 'page', 'side' );
		add_meta_box( 'themeblvd_pageparentdiv', __( 'Page Attributes', TB_GETTEXT_DOMAIN ), 'themeblvd_page_attributes_meta_box', 'page', 'side', 'core' );
	}
}

/**
 * Saved data from Hi-jacked "Page Attributes"
 * meta box.
 *
 * @since 2.0.0
 */ 
 
function themeblvd_save_page_atts( $post_id ) {
	if( themeblvd_supports( 'meta', 'hijack_atts' ) ) {
		// Save sidebar layout
		if( isset( $_POST['_tb_sidebar_layout'] ) )
			update_post_meta( $post_id, '_tb_sidebar_layout', $_POST['_tb_sidebar_layout'] );
		// Save custom layout
		if( isset( $_POST['_tb_custom_layout'] ) )
			update_post_meta( $post_id, '_tb_custom_layout', $_POST['_tb_custom_layout'] );
	}
}