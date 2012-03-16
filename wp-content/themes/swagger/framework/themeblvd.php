<?php
/**
 * Theme Blvd WordPress Framework
 * 
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */

// Check to make sure location has been defined in functions.php
if( ! defined( 'TB_FRAMEWORK_URL' ) ) die('Framework location not defined in functions.php');

// Run framework
if( is_admin() ) {
	
	/*------------------------------------------------------*/
	/* Admin Hooks, Filters, and Files
	/*------------------------------------------------------*/
	
	// Include files
	require_once( TB_FRAMEWORK_URL . '/admin/functions/meta.php' );
	require_once( TB_FRAMEWORK_URL . '/admin/functions/display.php' );
	require_once( TB_FRAMEWORK_URL . '/admin/functions/locals.php' );
	require_once( TB_FRAMEWORK_URL . '/sidebars/sidebars.php' );
	require_once( TB_FRAMEWORK_URL . '/sidebars/widgets/tb-widget-contact.php' );
	require_once( TB_FRAMEWORK_URL . '/sidebars/widgets/tb-widget-video.php' );
	require_once( TB_FRAMEWORK_URL . '/sidebars/widgets/tb-widget-twitter.php' );
	require_once( TB_FRAMEWORK_URL . '/shortcodes/tinymce/tinymce_shortcodes.php' );
	require_once( TB_FRAMEWORK_URL . '/admin/functions/general.php' );
	
	// Apply initial hooks
	add_action( 'admin_enqueue_scripts', 'themeblvd_non_modular_assets' );
	add_action( 'admin_init','themeblvd_theme_activation' );
	add_action( 'after_setup_theme', 'themeblvd_register_posts' );
	add_action( 'wp_before_admin_bar_render', 'themeblvd_admin_menu_bar' );
	
	// Apply other hooks after theme has had a chance to add filters
	add_action( 'after_setup_theme', 'themeblvd_admin_init', 1000 );
	add_action( 'after_setup_theme', 'themeblvd_add_theme_support', 1000 );
	add_action( 'after_setup_theme', 'themeblvd_register_navs', 1000 );
	add_action( 'after_setup_theme', 'themeblvd_register_sidebars', 1000 );
	add_action( 'admin_menu', 'themeblvd_hijack_page_atts' );
	add_action( 'add_meta_boxes', 'themeblvd_add_meta_boxes' );
	add_action( 'save_post', 'themeblvd_save_page_atts' );
	add_action( 'save_post', 'themeblvd_save_meta_boxes' );

} else {
	
	/*------------------------------------------------------*/
	/* Front-end Hooks, Filters, and Files
	/*------------------------------------------------------*/
	
	// Include files
	require_once( TB_FRAMEWORK_URL . '/frontend/functions/sliders.php' );
	require_once( TB_FRAMEWORK_URL . '/frontend/functions/builder.php' );
	require_once( TB_FRAMEWORK_URL . '/frontend/functions/parts.php' );
	require_once( TB_FRAMEWORK_URL . '/frontend/functions/actions.php' );
	require_once( TB_FRAMEWORK_URL . '/frontend/functions/locals.php' );
	require_once( TB_FRAMEWORK_URL . '/frontend/functions/helpers.php' );
	require_once( TB_FRAMEWORK_URL . '/frontend/functions/display.php' );
	require_once( TB_FRAMEWORK_URL . '/frontend/functions/general.php' );
	require_once( TB_FRAMEWORK_URL . '/shortcodes/shortcodes.php' );
	require_once( TB_FRAMEWORK_URL . '/sidebars/sidebars.php' );
	require_once( TB_FRAMEWORK_URL . '/sidebars/widgets/tb-widget-contact.php' );
	require_once( TB_FRAMEWORK_URL . '/sidebars/widgets/tb-widget-video.php' );
	require_once( TB_FRAMEWORK_URL . '/sidebars/widgets/tb-widget-twitter.php' );
	
	// Filters
	add_filter( 'body_class', 'themeblvd_body_class' );
	add_filter( 'oembed_result', 'themeblvd_youtube_wmode_transparent', 10, 2 );
	add_filter( 'oembed_result', 'themeblvd_oembed_result', 10, 2 );
	
	// Apply initial hooks
	add_action( 'after_setup_theme', 'themeblvd_register_posts' );
	add_action( 'after_setup_theme', 'themeblvd_add_theme_support' );
	add_action( 'wp_enqueue_scripts', 'themeblvd_include_scripts' );
	add_action( 'wp_print_styles', 'themeblvd_include_styles' );
	add_action( 'wp_before_admin_bar_render', 'themeblvd_admin_menu_bar' );

	// Apply other hooks after theme has had a chance to add filters
	add_action( 'after_setup_theme', 'themeblvd_register_navs', 1000 );
	add_action( 'after_setup_theme', 'themeblvd_register_sidebars', 1000 );
	add_action( 'template_redirect', 'themeblvd_frontend_init', 5 ); // This needs to run before any plugins hook into it
	
	// <head> hooks
	add_action( 'themeblvd_title', 'themeblvd_title_default' );
	
	// Header hooks
	add_action( 'themeblvd_header_above', 'themeblvd_header_above_default' );
	add_action( 'themeblvd_header_content', 'themeblvd_header_content_default' );
	add_action( 'themeblvd_header_logo', 'themeblvd_header_logo_default' );
	add_action( 'themeblvd_header_menu', 'themeblvd_header_menu_default' );
	
	// Sidebars
	add_action( 'themeblvd_fixed_sidebar_before', 'themeblvd_fixed_sidebar_before_default' );
	add_action( 'themeblvd_fixed_sidebar_after', 'themeblvd_fixed_sidebar_after_default' );
	
	// Featured area hooks
	add_action( 'themeblvd_featured_start', 'themeblvd_featured_start_default' );
	add_action( 'themeblvd_featured_end', 'themeblvd_featured_end_default' );
	
	// Main content area hooks
	add_action( 'themeblvd_main_start', 'themeblvd_main_start_default' );
	add_action( 'themeblvd_main_top', 'themeblvd_main_top_default' );
	add_action( 'themeblvd_main_bottom', 'themeblvd_main_bottom_default' );
	add_action( 'themeblvd_main_end', 'themeblvd_main_end_default' );
	add_action( 'themeblvd_breadcrumbs', 'themeblvd_breadcrumbs_default' );
	
	// Footer
	add_action( 'themeblvd_footer_above', 'themeblvd_footer_above_default' );
	add_action( 'themeblvd_footer_content', 'themeblvd_footer_content_default' );
	add_action( 'themeblvd_footer_sub_content', 'themeblvd_footer_sub_content_default' );
	add_action( 'themeblvd_footer_below', 'themeblvd_footer_below_default' );
	add_action( 'themeblvd_footer_below', 'themeblvd_footer_below_default' );
	add_action( 'wp_footer', 'themeblvd_analytics', 999 );
	
	// Content
	add_action( 'themeblvd_blog_meta', 'themeblvd_blog_meta_default' );
	add_action( 'themeblvd_blog_tags', 'themeblvd_blog_tags_default' );
	add_action( 'themeblvd_the_post_thumbnail', 'themeblvd_the_post_thumbnail_default', 9, 4 );
	add_action( 'themeblvd_blog_content', 'themeblvd_blog_content_default' );
	
	// Sliders
	add_action( 'themeblvd_standard_slider', 'themeblvd_standard_slider_default', 9, 3 );
	add_action( 'themeblvd_carrousel_slider', 'themeblvd_carrousel_slider_default', 9, 3 );
}

/*-----------------------------------------------------------------------------------*/
/* General Functions for Admin and Frontend
/*-----------------------------------------------------------------------------------*/

/**
 * Setup the config array for which features the 
 * framework supports. This can easily be filtered, so the 
 * theme author has a chance to disable the framework's 
 * various features. The filter is this:
 *
 * themeblvd_config
 *
 * @since 2.0.0
 */
 
function themeblvd_setup() {
	$setup = array(
		'primary' => array(
			'sliders' 			=> true,			// Sliders
			'sidebars'			=> true,			// Custom sidebars
			'builder'			=> true				// Custom layouts
		),
		'admin' => array(
			'options'			=> true,			// Entire Admin presence
			'sliders' 			=> true,			// Sliders page
			'builder'			=> true,			// Layouts page
			'sidebars'			=> true				// Sidebars page
		),
		'meta' => array(
			'hijack_atts'		=> true,			// Hijack and modify "Page Attributes"
			'page_options'		=> true,			// Meta box for basic page options
			'post_options'		=> true				// Meta box for basic post options
		),
		'featured' => array(
			'archive'			=> false,			// Featured area on/off by default
			'blog'				=> false,			// Featured area on/off by default
			'page'				=> false,			// Featured area on/off by default
			'single'			=> false			// Featured area on/off by default
		),
		'comments' => array(
			'pages'				=> false,			// Comments on pages
			'posts'				=> true,			// Commments on posts
		)
	);
	return apply_filters( 'themeblvd_global_config', $setup );
}

/**
 * Test whether an feature is currently supported.
 *
 * @since 2.0.0
 *
 * @param string $group admin or frontend
 * @param string $feature feature key to check
 * @return boolean
 */
 
function themeblvd_supports( $group, $feature ) {
	$setup = themeblvd_setup();
	if( isset( $setup[$group][$feature] ) && $setup[$group][$feature] )
		return true;
	else
		return false;
}

/**
 * Get theme option
 *
 * @since 2.0.0
 * @uses $_themeblvd_theme_options
 */

if( ! function_exists( 'themeblvd_get_option' ) ) {
	function themeblvd_get_option( $primary, $seconday = null, $default = false ) {
		global $_themeblvd_theme_options; // We pull from a global array, so we're not using WordPress's get_option every single time.
		$options = $_themeblvd_theme_options;
		if( isset( $options[$primary] ) ) {
			if( $seconday ) {
				if( is_array( $options[$primary] ) && isset( $options[$primary][$seconday] ) )
					$option = $options[$primary][$seconday];
			} else {
				$option = $options[$primary];
			}
		}
		if( ! isset( $option ) ) {
			if( $default ) {
				$option = $default;
			} else {
				$raw_options = array();
				if( function_exists( 'optionsframework_options' ) )
					$raw_options = optionsframework_options();
				if( ! empty( $raw_options ) ) {
					foreach( $raw_options as $raw_option ) {
						if( isset( $raw_option['id'] ) && $raw_option['id'] == $primary && isset( $raw_option['std'] ) )
							$option = $raw_option['std'];
						
					}
				}
			}
		}
		if( ! isset( $option ) ) $option = null;
		return $option;
	}
}

/**
 * Compress a chunk of code to output.
 *
 * @since 2.0.0
 * 
 * @param string $buffer Text to compress
 * @param string $buffer Buffered text 
 */

function themeblvd_compress( $buffer ) {
	/* remove comments */
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	/* remove tabs, spaces, newlines, etc. */
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
	return $buffer;
}

/**
 * Register any post types used with framework.
 * 
 * @since 2.0.0
 */
 
function themeblvd_register_posts() {
	
	// Sliders
	if( themeblvd_supports( 'primary', 'sliders' ) ) {
		$args = array(
			'labels' 			=> array( 'name' => 'Sliders', 'singular_name' => 'Slider' ),
			'public'			=> false,
			//'show_ui' 		=> true,	// Can uncomment for debugging
			'query_var' 		=> true,
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
			'rewrite' 			=> false,
			'supports' 			=> array( 'title', 'custom-fields', 'editor' ), // needs to support 'editor' for image to be inserted properly
			'can_export'		=> true
		);
		register_post_type( 'tb_slider', $args );
	}
	
	// Custom Sidebars
	if( themeblvd_supports( 'primary', 'sidebars' ) ) {
		$args = array(
			'labels' 			=> array( 'name' => 'Widget Areas', 'singular_name' => 'Widget Area' ),
			'public'			=> false,
			//'show_ui' 		=> true,	// Can uncomment for debugging
			'query_var' 		=> true,
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
			'rewrite' 			=> false,
			'supports' 			=> array( 'title', 'custom-fields' ), 
			'can_export'		=> true
		);
		register_post_type( 'tb_sidebar', $args );
	}
	
	// Custom Layouts
	if( themeblvd_supports( 'primary', 'builder' ) ) {
		$args = array(
			'labels' 			=> array( 'name' => 'Layouts', 'singular_name' => 'Layout' ),
			'public'			=> false,
			//'show_ui' 		=> true,	// Can uncomment for debugging
			'query_var' 		=> true,
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
			'rewrite' 			=> false,
			'supports' 			=> array( 'title', 'custom-fields' ), 
			'can_export'		=> true
		);
		register_post_type( 'tb_layout', $args );
	}

}

/**
 * Retrieves a post id given a post's slug and post type.
 *
 * @since 2.0.0
 * @uses $wpdb
 *
 * @param string $slug slug of post
 * @param string $post_type post type for post.
 * @return string $id ID of post.
 */
function themeblvd_post_id_by_name( $slug, $post_type ) {
	global $wpdb;
	$null = null;
	$slug = sanitize_title( $slug );
	
	// Grab posts from DB (hopefully there's only one!)
	$posts = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND (post_type = %s)", $slug, $post_type ));
	
	// If no results, return null
	if ( empty($posts) )
		return $null;
	
	// Run through our results and return the ID of the first. 
	// Hopefully there was only one result, but if there was 
	// more than one, we'll just return a single ID.
	foreach ( $posts as $post )
		if( $post->ID )
			return $post->ID;
	
	// If for some odd reason, there was no ID in the returned 
	// post ID's, return nothing.
	return $null;
}

/**
 * Register theme's nav menus.
 *
 * @since 2.0.0
 */

function themeblvd_register_navs() {
	$menus = array(
		'primary' => __( 'Primary Navigation', TB_GETTEXT_DOMAIN ),
		'footer' => __( 'Footer Navigation', TB_GETTEXT_DOMAIN )
	);
	$menus = apply_filters( 'themeblvd_nav_menus', $menus );
	register_nav_menus( $menus );
}

/**
 * Any occurances of WordPress's add_theme_support() happen here.
 * Can override function from Child Theme.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_add_theme_support' ) ) {
	function themeblvd_add_theme_support() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
	}
}

/**
 * Add items to admin menu bar. This needs to be here in general 
 * functions because admin bar appears on frontend as well.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_admin_menu_bar' ) ) {
	function themeblvd_admin_menu_bar() {
		global $wp_admin_bar;
		if( ! is_admin() ) {
			if( method_exists( $wp_admin_bar, 'add_menu' ) ) {
				
				// All admin sections of framework
				if( themeblvd_supports( 'admin', 'options' ) ) {
					// Theme Options
					$wp_admin_bar->add_menu( 
						array(
							'id' => 'tb_theme_options',
							'title' => __( 'Theme Options', TB_GETTEXT_DOMAIN ),
							'parent' => 'site-name',
							'href' => admin_url( 'themes.php?page=options-framework')
						)
					);
					// Sidebars
					if( themeblvd_supports( 'admin', 'sidebars' ) ) {
						$wp_admin_bar->add_menu( 
							array(
								'id' => 'tb_sidebars',
								'title' => __( 'Widget Areas', TB_GETTEXT_DOMAIN ),
								'parent' => 'site-name',
								'href' => admin_url( 'admin.php?page=sidebar_blvd')
							)
						);
					}
					// Sliders
					if( themeblvd_supports( 'admin', 'sliders' ) ) {
						$wp_admin_bar->add_menu( 
							array(
								'id' => 'tb_sliders',
								'title' => __( 'Sliders', TB_GETTEXT_DOMAIN ),
								'parent' => 'site-name',
								'href' => admin_url( 'admin.php?page=slider_blvd')
							)
						);
					}
					// Builder
					if( themeblvd_supports( 'admin', 'builder' ) ) {
						$wp_admin_bar->add_menu( 
							array(
								'id' => 'tb_builder',
								'title' => __( 'Builder', TB_GETTEXT_DOMAIN ),
								'parent' => 'site-name',
								'href' => admin_url( 'admin.php?page=builder_blvd')
							)
						);
					}
				}
				
			} // end if method_exists()
		} // end if is_admin()
	}
}

/**
 * Get all sidebar layouts.
 *
 * @since 2.0.0
 *
 * @return array
 */
 
function themeblvd_sidebar_layouts(){
	$layouts = array(
		'full_width' => array(
			'name' 	=> 'Full Width',
			'id'	=> 'full_width'
		),
		'sidebar_right' => array(
			'name' 	=> 'Sidebar Right',
			'id'	=> 'sidebar_right'
		),
		'sidebar_left' => array(
			'name' 	=> 'Sidebar Left',
			'id'	=> 'sidebar_left'
		),
		'double_sidebar' => array(
			'name' 	=> 'Double Sidebar',
			'id'	=> 'double_sidebar'
		),
		'double_sidebar_left' => array(
			'name' 	=> 'Double Left Sidebars',
			'id'	=> 'double_sidebar_left'
		),
		'double_sidebar_right' => array(
			'name' 	=> 'Double Right Sidebars',
			'id'	=> 'double_sidebar_right'
		)
	);
	return apply_filters( 'themeblvd_sidebar_layouts', $layouts );
}

/**
 * Get transparent textures
 *
 * @since 2.0.5
 *
 * @return array
 */
 
function themeblvd_get_textures(){
	$imagepath = get_template_directory_uri().'/framework/frontend/assets/images/textures/';
	$textures = array(
		'boxy' => array( 
			'name' 		=> __( 'Boxy', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'boxy.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'chex' => array( 
			'name' 		=> __( 'Chex', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'chex.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'concrete' => array( 
			'name' 		=> __( 'Concrete', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'concrete.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'cross' => array( 
			'name' 		=> __( 'Crosses', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'cross.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'diagnol_thin' => array( 
			'name' 		=> __( 'Diganol (thin)', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'diagnol_thin.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'diagnol_thick' => array( 
			'name' 		=> __( 'Diagonal (thin)', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'diagnol_thick.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'grid' => array( 
			'name' 		=> __( 'Grid', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'grid.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'grunge' => array( 
			'name' 		=> __( 'Grunge', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'grunge.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'leather' => array( 
			'name' 		=> __( 'Leather', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'leather.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'mosaic' => array( 
			'name' 		=> __( 'Mosaic', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'mosaic.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'noise' => array( 
			'name' 		=> __( 'Noise', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'noise.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'paper' => array( 
			'name' 		=> __( 'Paper', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'paper.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'textile' => array( 
			'name' 		=> __( 'Textile', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'textile.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'vintage' => array( 
			'name' 		=> __( 'Vintage', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'vintage.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		),
		'wood' => array( 
			'name' 		=> __( 'Wood', TB_GETTEXT_DOMAIN ),
			'url' 		=> $imagepath.'wood.png',
			'position' 	=> '0 0',
			'repeat' 	=> 'repeat',
		)

	);
	return apply_filters( 'themeblvd_textures', $textures );
}