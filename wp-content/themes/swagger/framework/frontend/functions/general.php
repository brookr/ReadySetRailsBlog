<?php
/*-----------------------------------------------------------------------------------*/
/* General Front-end Functions
/*-----------------------------------------------------------------------------------*/

/**
 * Initiate Front-end
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_frontend_init' ) ) {
	function themeblvd_frontend_init() {

		global $_themeblvd_theme_options;
		global $_themeblvd_config;
		global $post;

		// Setup global theme options
		$config = get_option( 'optionsframework' );
		if ( isset( $config['id'] ) )
			$_themeblvd_theme_options = get_option( $config['id'] );
		
		/*------------------------------------------------------*/
		/* Primary Post ID
		/*------------------------------------------------------*/
		
		// Obviously at any time you can access the global $post object, 
		// however we want to store here in the config, so it can accessed 
		// from anywhere including in or after the loop.
		
		global $post;
		$primary = null;
		if( is_object( $post ) )
			$primary = $post->ID;
		
		/*------------------------------------------------------*/
		/* Fake Conditional
		/*------------------------------------------------------*/
		
		// This can be used to replace any conditional statements that 
		// come after any usages of query_posts
		
		$fake_conditional = themeblvd_get_fake_conditional();
		
		/*------------------------------------------------------*/
		/* Builder (ID of custom layout or false)
		/*------------------------------------------------------*/
		
		// If the user has slected the page template for a custom layout, 
		// here we'll set the ID for the chosen custom layout into the 
		// the main config. If the user hasn't selected this page template 
		// the $builder will be set to false. By setting this $builder var, 
		// we can make appropriate edits anywhere to content outside of the 
		// template_builder.php file if we need to.
		
		$builder = false;
		
		// Custom Layout on static page
		if( is_page_template( 'template_builder.php' ) ) {
			$layout_id = get_post_meta( $post->ID, '_tb_custom_layout', true );
			if( $layout_id ) {
				$builder = $layout_id;
				$layout_post_id = themeblvd_post_id_by_name( $layout_id, 'tb_layout' );
				$layout_settings = get_post_meta( $layout_post_id, 'settings', true );
				$sidebar_layout = $layout_settings['sidebar_layout'];
			} else {
				$builder = 'error';
			}
		}
		
		// Custom Layout on homepage
		if( is_home() ) {
			$homepage_content = themeblvd_get_option( 'homepage_content', null, 'post_list' );
			if( $homepage_content == 'custom_layout' ) {
				$layout_id = themeblvd_get_option( 'homepage_custom_layout' );
				if( $layout_id ) {
					$builder = $layout_id;
					$layout_post_id = themeblvd_post_id_by_name( $layout_id, 'tb_layout' );
					$layout_settings = get_post_meta( $layout_post_id, 'settings', true );
					$sidebar_layout = $layout_settings['sidebar_layout'];
				} else {
					$builder = 'error';
				}
			}
		}
		
		/*------------------------------------------------------*/
		/* Featured Area
		/*------------------------------------------------------*/
		
		// In this framework, there will always be a featured area above 
		// the primary content and sidebar layout, whether it's styled or 
		// not. And since generally this featured area will have some sort 
		// of styling, padding, etc, with this set to true or false, we 
		// know whether to completely skip over the featured area's HTML 
		// markup or not when rendering a page from any template file.
		
		$featured = array();
		if( $builder ) {
			$layout_post_id = themeblvd_post_id_by_name( $builder, 'tb_layout' );
			$elements = get_post_meta( $layout_post_id, 'elements', true );
			// Set classes for featured area
			if( is_array( $elements ) && isset( $elements['featured'] ) && ! empty( $elements['featured'] ) ){
				$featured[] = 'has_builder';
				foreach( $elements['featured'] as $element ) {
					switch( $element['type'] ){
						case 'slider' :
							$featured[] = 'has_slider';
							break;
						case 'post_grid_slider' :
							$featured[] = 'has_slider';
							$featured[] = 'has_grid';
							$featured[] = 'has_post_grid_slider';
							break;
						case 'post_list_slider' :
							$featured[] = 'has_slider';
							$featured[] = 'has_post_list_slider';
							break;
						case 'post_grid' :
							$featured[] = 'has_grid';
							break;
					}
				}
				// First element classes
				$first_element = array_shift( array_values( $elements['featured'] ) );
				$first_element = $first_element['type'];
				if( $first_element == 'slider' || $first_element == 'post_grid_slider' || $first_element == 'post_list_slider'  )
					$featured[] = 'slider_is_first';
				if( $first_element == 'post_grid' || $first_element == 'post_grid_slider'  )
					$featured[] = 'grid_is_first';
				if( $first_element == 'slogan'  )
					$featured[] = 'slogan_is_first';
				// Last element classes
				$last_element = end( $elements['featured'] );
				$last_element = $last_element['type'];
				if( $last_element == 'slider' || $last_element == 'post_grid_slider' || $last_element == 'post_list_slider'  )
					$featured[] = 'slider_is_last';
				if( $last_element == 'post_grid' || $last_element == 'post_grid_slider'  )
					$featured[] = 'grid_is_last';
				if( $last_element == 'slogan'  )
					$featured[] = 'slogan_is_last';
			}
		}
		if( is_home() ) {
			$homepage_content = themeblvd_get_option( 'homepage_content', null, 'posts' );
			if( $homepage_content != 'custom_layout' )
				if( themeblvd_get_option( 'blog_featured' ) || themeblvd_supports( 'featured', 'blog' ) )
					$featured[] = 'has_blog_featured';
		}
		if( is_page_template( 'template_list.php' ) ) {
			if( themeblvd_get_option( 'blog_featured' ) || themeblvd_supports( 'featured', 'blog' ) )
				$featured[] = 'has_blog_featured';
		}
		if( is_archive() || is_search() ){
			if( themeblvd_supports( 'featured', 'archive' ) )
				$featured[] = 'has_archive_featured';
		}
		if( is_page() && ! is_page_template( 'template_builder.php' ) && themeblvd_supports( 'featured', 'page' ) )
			$featured[] = 'has_page_featured';
		if( is_single() && themeblvd_supports( 'featured', 'single' ) )
			$featured[] = 'has_single_featured';

		/*------------------------------------------------------*/
		/* Sidebar Layout (ID of sidebar layout)
		/*------------------------------------------------------*/
		
		// The sidebar layout depends on several scenarios the user could 
		// have theoretically setup form the admin panel. So, in all template 
		// files, we need to know the current sidebar layout at the start of 
		// rendering the content in order to know where to display the left 
		// and right sidebars within the overall HTML markup. Unfortunately, 
		// because the framework gives the user so many choices, the sidebars 
		// cannot be placed purely with CSS, and that's why this is necessary.
		
		if( ! isset( $sidebar_layout ) || ! $sidebar_layout ) {
			if( is_page() || is_single() )
				$sidebar_layout = get_post_meta( $post->ID, '_tb_sidebar_layout', true );
		}
		if( ! isset( $sidebar_layout ) || ! $sidebar_layout || 'default' == $sidebar_layout ) {
			if( themeblvd_get_option( 'sidebar_layout' ) )
				$sidebar_layout = themeblvd_get_option( 'sidebar_layout' );
			else
				$sidebar_layout = 'sidebar_right'; // absolute default sidebar layout
		}
		
		/*------------------------------------------------------*/
		/* Sidebar ID's
		/*------------------------------------------------------*/
		
		// Moving past the sidebar layout, there are many potential sidebar 
		// locations the theme could be utilizing. Also, the user could 
		// have theoretically assigned different custom sidebars to different 
		// sidebar locations depending on what page we're on. So, here we 
		// need to determine all of the proper sidebar IDs for our current 
		// template situation.

		$sidebars = array();
		$sidebar_locations = themeblvd_get_sidebar_locations();
		foreach( $sidebar_locations as $location_id => $default_sidebar ) {
    		
    		// Find sidebar depending on location and current page
    		$sidebar_post_slug = themeblvd_get_sidebar_id( $location_id );
    		
    		// Set the sidebar ID based on if we found a custom one or we'll 
    		// be falling back to the default.
    		if( $sidebar_post_slug )
    			$sidebar_id = $sidebar_post_slug;
    		else
    			$sidebar_id = $location_id;
    		
    		// Set current sidebar ID
    		$sidebars[$location_id]['id'] = $sidebar_id;
    		$sidebars[$location_id]['error'] = false;
    		
    		// Determine if there's an error with the sidebar. 
    		// In this case, there can only be a potential 
    		// error if the sidebar has no widgets.
    		if( ! is_active_sidebar( $sidebar_id ) ) {
				if( $default_sidebar['type'] == 'collapsible' ) {
					// If user set a custom sidebar, but left it empty, 
					// we need to tell 'em, but if this is a default 
					// collapsible sidebar, then the error can stay false.
					if( $sidebar_id != $location_id )
						$sidebars[$location_id]['error'] = true;
	    		} else {
	    			// No matter if it's custom or not, we need to tell the 
	    			// user if a fixed sidebar is empty.
	    			$sidebars[$location_id]['error'] = true;
				}
			}
				
    	}	

		/*------------------------------------------------------*/
		/* Finalize Frontend Configuration
		/*------------------------------------------------------*/
		
    	$config = array(
    		'id'				=> $primary,			// global $post->ID that can be accessed anywhere
    		'fake_conditional'	=> $fake_conditional,	// Fake conditional tag
    		'sidebar_layout' 	=> $sidebar_layout, 	// Sidebar layout
    		'builder'			=> $builder,			// ID of current custom layout if not false
    		'featured'			=> $featured,			// Classes for featured areas, if empty featured area won't show
    		'sidebars'			=> $sidebars			// Array of sidbar ID's for all corresponding locations
    	);
    	$_themeblvd_config = apply_filters( 'themeblvd_frontend_config', $config );
	}
}

/**
 * Add framework css classes to body_class() 
 *
 * @since 2.0.2
 *
 * @param array $classes Current WordPress body classes
 * @return array $classes Modified body classes
 */

if( ! function_exists( 'themeblvd_body_class' ) ) {
	function themeblvd_body_class( $classes ) {
		
		// Featured Area
		if( themeblvd_config( 'featured' ) )
			$classes[] = 'show-featured-area';
		else
			$classes[] = 'hide-featured-area';

		// Custom Layout
		$custom_layout = themeblvd_config( 'builder' );
		if( $custom_layout )
			$classes[] = 'custom-layout-'.$custom_layout;
			
		// Sidebar Layout
		$classes[] = 'sidebar-layout-'.themeblvd_config( 'sidebar_layout' );
		
		return $classes;
	}
}

/**
 * Set fake conditional.
 *
 * Because query_posts alters the current global $wp_query 
 * conditional, this function is called before query_posts 
 * and assigns a variable to act as a fake conditional if 
 * needed after query_posts.
 *
 * @since 2.0.0
 *
 * @return string $fake_condtional HTML to output thumbnail
 */

if( ! function_exists( 'themeblvd_get_fake_conditional' ) ) {
	function themeblvd_get_fake_conditional() {
		$fake_conditional = '';
		if( is_home() )
			$fake_conditional = 'home';
		else if( is_page_template( 'template_builder.php' ) )
			$fake_conditional = 'template_builder.php';
		else if( is_page_template( 'template_list.php' ) )
			$fake_conditional = 'template_list.php';
		else if( is_page_template( 'template_grid.php' ) )
			$fake_conditional = 'template_grid.php';
		else if( is_single() )
			$fake_conditional = 'single';
		else if( is_search() )
			$fake_conditional = 'search';
		else if ( is_archive() )
			$fake_conditional = 'archive';
		return $fake_conditional;
	}	
}

/**
 * This function is used from within the theme's template 
 * files to return the values setup in the previous init function.
 *
 * @since 2.0.0
 * 
 * @param $key string $key to retrieve from $_themeblvd_config
 * @return $value mixed value from $_themeblvd_config
 */

function themeblvd_config( $key, $seconday = null ) {
	global $_themeblvd_config;
	$value = null;
	if( $seconday ) {
		if( isset( $_themeblvd_config[$key][$seconday] ) )
			$value = $_themeblvd_config[$key][$seconday];	
	} else {	
		if( isset( $_themeblvd_config[$key] ) )
			$value = $_themeblvd_config[$key];
	}
	return $value;
}

/**
 * Locate the id for sidebar or override to be used based 
 * on the current WP query compared with assignments.
 *
 * These conditionals are split into three tiers. This 
 * means that we loop through conditionals each tier and 
 * only move onto the next tier if we didn't find an 
 * assignment to set.
 *
 * @since 2.0.0
 * 
 * @param $assignments array all of elements assignments to check through
 * @return $id string id of element to return
 */

function themeblvd_get_assigned_id( $assignments ) {
	
	// Initialize $id
	$id = null;
	
	// Reset the query
	wp_reset_query();
	
	// Tier I conditionals
	if( ! empty( $assignments ) ) {
		foreach($assignments as $assignment) {
			if( $assignment['type'] != 'top') {
				// Page
				if( $assignment['type'] == 'page' ) {
					if( is_page( $assignment['id'] ) )			
						$id = $assignment['post_slug'];
				}
				// Post
				if( $assignment['type'] == 'post' ) {
					if( is_single( $assignment['id'] ) )		
						$id = $assignment['post_slug'];
				}
				// Category
				if( $assignment['type'] == 'category' ) {
					if( is_category($assignment['id'] ) )			
						$id = $assignment['post_slug'];
				}
				// Tag
				if( $assignment['type'] == 'tag') {
					if( is_tag($assignment['id'] ) )		
						$id = $assignment['post_slug'];
				}
			}
		}
	}
	
	// Tier II conditionals
	if( ! isset( $id ) && ! empty( $assignments ) ) {
		foreach( $assignments as $assignment ) {
			if( $assignment['type'] != 'top' ) {				
				// Posts in Category
				if( $assignment['type'] == 'posts_in_category' ) {
					if( in_category( $assignment['id'] ) )		
						$id = $assignment['post_slug'];
				}			
			}
		}
	}
	// Tier III conditionals
	if( ! isset( $id ) && ! empty( $assignments ) ) {
		foreach( $assignments as $assignment ) {
			if( $assignment['type'] == 'top' ) {				
				switch( $assignment['id'] ) {

					// Homepage
					case 'home' :
						if( is_home() )		
							$id = $assignment['post_slug'];
						break;
					
					// All Posts	
					case 'posts' :
						if( is_single() )
							$id = $assignment['post_slug'];
						break;
						
					// All Pages	
					case 'pages' :
						if( is_page() )
							$id = $assignment['post_slug'];
						
						break;
						
					// Archives	
					case 'archives' :
						if( is_archive() )
							$id = $assignment['post_slug'];
						break;
						
					// Categories	
					case 'categories' :
						if( is_category() )
							$id = $assignment['post_slug'];
						break;
						
					// Tags	
					case 'tags' :
						if( is_tag() )
							$id = $assignment['post_slug'];
						break;
						
					// Authors	
					case 'authors' :
						if( is_author() )
							$id = $assignment['post_slug'];
						break;
						
					// Search Results
					case 'search' :
						if( is_search() )
							$id = $assignment['post_slug'];
						break;
						
					// 404	
					case '404' :
						if( is_404() )
							$id = $assignment['post_slug'];
						break;
				} // End switch $assignment['id']					
			}
		}
	}
	return $id;	
}

/**
 * Load framework's JS scripts 
 *
 * To add scripts or remove unwanted scripts that you 
 * know you won't need to maybe save some frontend load 
 * time, this function can easily be re-done from a 
 * child theme.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_include_scripts' ) ) {
	function themeblvd_include_scripts() {
		// Register scripts
		wp_register_script( 'prettyPhoto', TB_FRAMEWORK_DIRECTORY . '/frontend/assets/plugins/prettyphoto/js/jquery.prettyPhoto.js', array('jquery'), '3.1.3' );
		wp_register_script( 'superfish', TB_FRAMEWORK_DIRECTORY . '/frontend/assets/js/superfish.js', array('jquery'), '1.4.8' );
		wp_register_script( 'flexslider', TB_FRAMEWORK_DIRECTORY . '/frontend/assets/js/flexslider.js', array('jquery'), '1.8' );
		wp_register_script( 'roundabout', TB_FRAMEWORK_DIRECTORY . '/frontend/assets/js/roundabout.js', array('jquery'), '1.1' );
		wp_register_script( 'themeblvd', TB_FRAMEWORK_DIRECTORY . '/frontend/assets/js/themeblvd.js', array('jquery'), '1.0' );
		// Enqueue 'em
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'prettyPhoto' );
		wp_enqueue_script( 'superfish' );
		wp_enqueue_script( 'flexslider' );
		wp_enqueue_script( 'roundabout' );
		wp_enqueue_script( 'themeblvd' );
	}
}

/**
 * Load framework's CSS files 
 *
 * To add styles or remove unwanted styles that you 
 * know you won't need to maybe save some frontend load 
 * time, this function can easily be re-done from a 
 * child theme.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_include_styles' ) ) {
	function themeblvd_include_styles() {
		// Register framework styles
		wp_register_style( 'prettyPhoto', 			TB_FRAMEWORK_DIRECTORY . '/frontend/assets/plugins/prettyphoto/css/prettyPhoto.css' );
		wp_register_style( 'themeblvd_plugins', 	TB_FRAMEWORK_DIRECTORY . '/frontend/assets/css/plugins.css' );
		wp_register_style( 'themeblvd', 			TB_FRAMEWORK_DIRECTORY . '/frontend/assets/css/themeblvd.css' );
		// Enqueue framework styles
		wp_enqueue_style( 'prettyPhoto' );
		wp_enqueue_style( 'themeblvd_plugins' );
		wp_enqueue_style( 'themeblvd' );
	}
}

/**
 * Include font from google. Accepts unlimited 
 * amount of font arguments.
 *
 * @since 2.0.0
 *
 * @return string $stacks All current font stacks
 */

if( ! function_exists( 'themeblvd_include_google_fonts' ) ) {
	function themeblvd_include_google_fonts() {
		$fonts = func_get_args();
		if( ! empty( $fonts ) ) {
			// Before including files, determine if SSL is being 
			// used because if we include an external file without https 
			// on a secure server, they'll get an error.
			if( isset( $_SERVER['HTTPS'] ) )
				$protocol = 'https://'; // Google does support https
			else
				$protocol = 'http://';
			// Include each font file from google.
			foreach( $fonts as $font ) {
				if( $font['face'] == 'google' && $font['google'] ) {
					$name = themeblvd_remove_trailing_char( $font['google'] ); 
					$name = str_replace( ' ', '+', $name );
					echo '<link href="'.$protocol.'fonts.googleapis.com/css?family='.$name.'" rel="stylesheet" type="text/css">'."\n";
				}
			}
		}
	}
}

/**
 * Get all current font stacks
 *
 * @since 2.0.0
 *
 * @return string $stacks All current font stacks
 */

if( ! function_exists( 'themeblvd_font_stacks' ) ) {
	function themeblvd_font_stacks() {
		$stacks = array(
			'default'		=> 'Arial, sans-serif', // Used to chain onto end of google font
			'arial'     	=> 'Arial, "Helvetica Neue", Helvetica, sans-serif',
			'baskerville'	=> 'Baskerville, "Times New Roman", Times, serif',
			'georgia'   	=> 'Georgia, Times, "Times New Roman", serif',
			'helvetica' 	=> '"Helvetica Neue", Helvetica, Arial,sans-serif',
			'lucida'  		=> '"Lucida Sans", "Lucida Grande", "Lucida Sans Unicode", sans-serif;',
			'palatino'  	=> 'Palatino, "Palatino Linotype", Georgia, Times, "Times New Roman", serif',
			'tahoma'    	=> 'Tahoma, Geneva, Verdana, sans-serif',
			'times'     	=> 'Times New Roman',
			'trebuchet' 	=> '"Trebuchet MS", "Lucida Sans Unicode", "Lucida Grande", "Lucida Sans", Arial, sans-serif',
			'verdana'   	=> 'Verdana, Geneva, Tahoma, sans-serif',
			'google'		=> 'Google Font'
		);
		$stacks = apply_filters( 'themeblvd_font_stacks', $stacks );
		return $stacks;
	}
}

/**
 * Display CSS class for current sidebar layout. 
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_sidebar_layout_class' ) ) {
	function themeblvd_sidebar_layout_class() {
		global $_themeblvd_config;
		echo $_themeblvd_config['sidebar_layout'];
	}
}

/**
 * Get content extension for uses of get_template_part
 * End Usage: get_template_part( 'content', {$part} )
 * File name structure: content-{$part}.php
 *
 * @since 2.0.0
 * 
 * @param string $type Type of template part to get
 * @return string $part Extension to use for get_template_part
 */

if( ! function_exists( 'themeblvd_get_part' ) ) {
	function themeblvd_get_part( $type ) {
		$part = null;
		$post_format = null;
		// Post format
		if( ! is_404() && ! is_search() )
			$post_format = get_post_format();
		// Parts
		$parts = array(
			'404'				=> '404',
			'archive'			=> 'archive',		// Note: If working with magazine theme, can change to 'archive_grid' to be compatible with archive.php
			'blog' 				=> $post_format,	// Note: This could be changed to a new part of magazine theme to be separate from default.
			'grid' 				=> 'grid',
			'index' 			=> $post_format,	// Note: If working with magazine theme, can change to 'index_grid' to be compatible with archive.php
			'page' 				=> 'page',
			'portfolio' 		=> 'grid',			
			'search'			=> 'search',		// Note: This is for displaying content when no search results were found.
			'search_results'	=> 'archive',
			'single'			=> 'single'			// Note: For blog style theme, this could be changed to $post_format to match blogroll
		);
		$parts = apply_filters( 'themeblvd_template_parts', $parts );
		// Set and part if exists
		if( isset( $parts[$type] ) )
			$part = $parts[$type];
		// Return part	
		return $part;
	}
}