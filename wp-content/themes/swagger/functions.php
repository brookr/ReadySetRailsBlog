<?php
/*-----------------------------------------------------------------------------------*/
/* Theme Configuration
/*-----------------------------------------------------------------------------------*/

// Constants
define( 'TB_FRAMEWORK_VERSION', '2.0.5' );
define( 'TB_UPDATE_LOG_URL', 'http://www.themeblvd.com/demo/swagger/wp-content/themes/swagger/changelog.txt' );
define( 'TB_FRAMEWORK_URL', TEMPLATEPATH.'/framework' );
define( 'TB_FRAMEWORK_DIRECTORY', get_template_directory_uri().'/framework' );
define( 'TB_GETTEXT_DOMAIN', 'themeblvd' );

/**
 * Swagger Setup 
 * 
 * You can override this function from a child 
 * theme if any basic setup things need to be changed.
 */

if( ! function_exists( 'swagger_setup' ) ) {
	function swagger_setup() {

		// Content Width
		$content_width = 940; // Default width of primary content area
		
		// Crop sizes
		$sizes = array(
			'large' => array(
				'width' 	=> $content_width,	// 940 => Full width thumb for 1-col page
				'height' 	=> 9999,
				'crop' 		=> false
			),
			'medium' => array(
				'width' 	=> 620, 			// 620 => Full width thumb for 2-col/3-col page
				'height'	=> 9999,
				'crop' 		=> false
			),
			'small' => array(
				'width' 	=> 195,				// Square'ish thumb floated left
				'height' 	=> 195,
				'crop' 		=> false
			),
			'slider-large' => array(
				'width' 	=> 940,				// Slider full-size image
				'height' 	=> 350,
				'crop' 		=> true
			),
			'slider-staged' => array(
				'width' 	=> 564,				// Slider staged image
				'height' 	=> 350,
				'crop' 		=> true
			),
			'grid_fifth_1' => array(
				'width' 	=> 200,				// 1/5 Column
				'height' 	=> 125,
				'crop' 		=> true
			),
			'grid_3' => array(
				'width' 	=> 240,				// 1/4 Column
				'height' 	=> 150,
				'crop' 		=> true
			),
			'grid_4' => array(
				'width' 	=> 320,				// 1/3 Column
				'height' 	=> 200,
				'crop' 		=> true
			),
			'grid_6' => array(
				'width' 	=> 472,				// 1/2 Column
				'height' 	=> 295,
				'crop' 		=> true
			)
		);
		$sizes = apply_filters( 'themeblvd_image_sizes', $sizes );
		
		// Add image sizes
		foreach( $sizes as $size => $atts )
			add_image_size( $size, $atts['width'], $atts['height'], $atts['crop'] );
				
		// Localization
		load_theme_textdomain( TB_GETTEXT_DOMAIN, get_template_directory() . '/lang' );

	}
}
add_action( 'after_setup_theme', 'swagger_setup' );

/**
 * Swagger CSS Files
 *
 * To add styles or remove unwanted styles that you 
 * know you won't need to maybe save some frontend load 
 * time, this function can easily be re-done from a 
 * child theme.
 */

if( ! function_exists( 'swagger_css' ) ) {
	function swagger_css() {
		if( ! is_admin() ) {
			wp_register_style( 'themeblvd_theme', get_template_directory_uri() . '/assets/css/theme.min.css', false, '1.0' );
			wp_register_style( 'themeblvd_responsive', get_template_directory_uri() . '/assets/css/responsive.min.css', false, '1.0' );
			wp_register_style( 'themeblvd_colors', get_template_directory_uri() . '/assets/css/colors.min.css', false, '1.0' );
			wp_register_style( 'themeblvd_ie', get_template_directory_uri() . '/assets/css/ie.css', false, '1.0' );
			wp_enqueue_style( 'themeblvd_theme' );
			if( themeblvd_get_option( 'responsive_css' ) != 'false' ) wp_enqueue_style( 'themeblvd_responsive' );
			wp_enqueue_style( 'themeblvd_colors' );
			$GLOBALS['wp_styles']->add_data( 'themeblvd_ie', 'conditional', 'lt IE 9' ); // Add IE conditional
			wp_enqueue_style( 'themeblvd_ie' );
		}
	}
}
add_action( 'wp_print_styles', 'swagger_css', 11 ); // Needs to come after framework CSS files

/**
 * Swagger Styles 
 * 
 * This is where the theme's configured styles 
 * from the Theme Options page get put into place 
 * by inserting CSS in the <head> of the site. It's 
 * shown here as clearly as possible to be edited, 
 * however it gets compressed when actually inserted 
 * into the front end of the site.
 */
 
if( ! function_exists( 'swagger_styles' ) ) {
	function swagger_styles() {
		$custom_styles = themeblvd_get_option( 'custom_styles' );
		$body_font = themeblvd_get_option( 'typography_body' );
		$header_font = themeblvd_get_option( 'typography_header' );
		$special_font = themeblvd_get_option( 'typography_special' );
		themeblvd_include_google_fonts( $body_font, $header_font, $special_font );
		echo '<style>'."\n";
		ob_start();
		?>
		/* Fonts */
		body {
			font-family: <?php echo themeblvd_get_font_face($body_font); ?>;
			font-size: <?php echo $body_font['size'];?>;
		}
		h1, h2, h3, h4, h5, h6, .slide-title {
			font-family: <?php echo themeblvd_get_font_face($header_font); ?>;
		}
		#branding .header_logo .tb-text-logo,
		#featured .media-full .slide-title,
		#content .media-full .slide-title,
		#featured .featured-entry-title .entry-title,
		#content .featured-entry-title .entry-title,
		.element-slogan .slogan .slogan-text,
		.element-tweet {
			font-family: <?php echo themeblvd_get_font_face($special_font); ?>;
		}
		/* Link Colors */
		a {
			color: <?php echo themeblvd_get_option('link_color'); ?>;
		}
		a:hover {
			color: <?php echo themeblvd_get_option('link_hover_color'); ?>;
		}
		/* Accent Color */
		.default,
		#branding {
			border-color: <?php echo themeblvd_get_option('accent_color'); ?>;
		}
		.article-wrap article {
			border-bottom-color: <?php echo themeblvd_get_option('accent_color'); ?>;
		}
		.default,
		#featured .media-full .slide-title span,
		#content .media-full .slide-title span,
		.standard-slider .image-link,
		.carrousel-slider .image-link {
			background-color: <?php echo themeblvd_get_option('accent_color'); ?>;
		}
		#branding .header_logo .tb-text-logo:hover,
		#featured .media-full .tb-button,
		#content .media-full .tb-button,
		.entry-title a:hover,
		.widget ul li a:hover,
		#main #breadcrumbs a:hover,
		.tags a:hover,
		.entry-meta a:hover {
			color: <?php echo themeblvd_get_option('accent_color'); ?> !important;
		}
		<?php if( themeblvd_get_option( 'responsive_css' ) == 'false' ) : ?>
		#branding,
		#main .main-inner,
		#featured .featured-inner,
		#colophon {
			width: 960px;
		}
		<?php endif; ?>
		<?php
		// Ouptput compressed CSS
		echo themeblvd_compress( ob_get_clean() );
		// Add in user's custom CSS
		if( $custom_styles ) echo $custom_styles;
		echo "\n</style>\n";
	}
}
add_action( 'wp_head', 'swagger_styles' ); // Must come after framework loads styles, which are hooked with wp_print_styles

/**
 * Swagger Body Classes 
 * 
 * Here we filter WordPress's default body_class()
 * function to include necessary classes for Main 
 * Styles selected in Theme Options panel.
 */

if( ! function_exists( 'swagger_body_class' ) ) {
	function swagger_body_class( $classes ) {
		$classes[] = themeblvd_get_option( 'primary_color' );
		$classes[] = themeblvd_get_option( 'content_color' );
		return $classes;
	}
}
add_filter( 'body_class', 'swagger_body_class' );

/*-----------------------------------------------------------------------------------*/
/* Run ThemeBlvd Framework
/*-----------------------------------------------------------------------------------*/

require_once ( TB_FRAMEWORK_URL . '/themeblvd.php' );

/*-----------------------------------------------------------------------------------*/
/* Theme Filters
/*
/* Here we can take advantage of modifying anything in the framework that is 
/* filterable. 
/*-----------------------------------------------------------------------------------*/

/* Text String Overwrites */

if( ! function_exists( 'swagger_frontend_locals' ) ) {
	function swagger_frontend_locals( $locals ) {
		$locals['read_more'] = __( 'View Post', TB_GETTEXT_DOMAIN );
		return $locals;
	}
}

/* Adjust frontend global config */

if( ! function_exists( 'swagger_frontend_config' ) ) {
	function swagger_frontend_config( $config ) {
		global $post;
		if( is_page() ) {
			if( 'hide' != get_post_meta( $post->ID, '_tb_title', true ) )
				$config['featured'][] = 'has_page_featured';
		}
		if( is_single() ) {
			$config['featured'] = array( 'has_single_featured' );
		}
		if( is_archive() || is_search() ) {
			if( themeblvd_get_option( 'archive_title' ) != 'false' )
				$config['featured'] = array( 'has_archive_featured' );
		}
		return $config;
	}
}

/* De-register footer navigation for this theme */

if( ! function_exists( 'swagger_nav_menus' ) ) {
	function swagger_nav_menus( $menus ) {
		unset( $menus['footer'] );
		return $menus;
	}
}

/* Add page tagline to meta box on when editing pages */

if( ! function_exists( 'swagger_page_meta' ) ) {
	function swagger_page_meta( $setup ) {
		$setup['options'][] = array(
			'id'		=> '_tb_tagline',
			'name' 		=> __( 'Page Tagline', TB_GETTEXT_DOMAIN ),
			'desc'		=> __( 'This tagline will appear underneath the title of the page.', TB_GETTEXT_DOMAIN ),
			'type' 		=> 'text'
		);
		return $setup;
	}
}

/* Remove header area widget area (not supported in theme) */

if( ! function_exists( 'swagger_sidebar_locations' ) ) {
	function swagger_sidebar_locations( $locations ) {
		unset( $locations['ad_header'] );
		return $locations;
	}
}

/* Add Swagger's Homepage to Layout Builder samples */

if( ! function_exists( 'swagger_sample_layouts' ) ) {
	function swagger_sample_layouts( $samples ) {
		$swagger = array(
			'swagger' => array(
				'name' => 'Swagger Homepage',
				'id' => 'swagger',
				'preview' => get_template_directory_uri().'/assets/images/sample-swagger.png',
				'credit' => 'This is the layout used in the <a href="http://themeblvd.com/demo/swagger" target="_blank">Swagger demo website\'s homepage</a>.',
				'sidebar_layout' => 'full_width',
				'featured' => array(
					'element_1' => array(
						'type' => 'slider',
						'query_type' => 'secondary',
						'options' => array(
							'slider_id' => null
						)
					)
				),
				'primary' => array(
					'element_2' => array(
						'type' => 'tabs',
						'query_type' => 'none',
						'options' => array(
							'setup' => array (
								'num' => 2,
								'style' => 'open',
								'names' => array(
									'tab_1' => 'Why Swagger?',
									'tab_2' => 'From the Portfolio'
								)
							),
							'height' => '515',                 
							'tab_1' => array (
								'type' => 'raw',
								'raw' => '[raw]

[one_third]
<h3>A WordPress Experience</h3>
<img src="http://themeblvd.com/demo/assets/swagger/swagger_layout_1.png" class="pretty" />
<p>Utilizing the all-new version 2 of the Theme Blvd Framework, Swagger provides a WordPress experience like you\'ve never experienced with things like the Layout Builder.</p>
[/one_third]
[one_third]
<h3>Responsive Design</h3>
<img src="http://themeblvd.com/demo/assets/swagger/swagger_layout_2.png" class="pretty" />
<p>The entire Theme Blvd framework was built from the ground up with the intention of making sure all of its themes display gracefully no matter where you view them.</p>
[/one_third]
[one_third last]
<h3>HTML5 and CSS3</h3>
<img src="http://themeblvd.com/demo/assets/swagger/swagger_layout_3.png" class="pretty" />
<p>Many themes around the community are marketing themselves with the HTML5 emblem, but Swagger is truly built to give you the most modern web experience possible.</p>
[/one_third]

[one_third]
<h3>Just the Right Options</h3>
<img src="http://themeblvd.com/demo/assets/swagger/swagger_layout_4.png" class="pretty" />
<p>Every single option has been crafted and plotted out to give you the most flexible methods of configuring your site with out making you read through a million options.</p>
[/one_third]
[one_third]
<h3>Top-Notch Design</h3>
<img src="http://themeblvd.com/demo/assets/swagger/swagger_layout_5.png" class="pretty" />
<p>Swagger has been detailed down to the pixel to give you the most elegant design for your next website. You\'ll be hard-pressed to find a better WordPress theme.</p>
[/one_third]
[one_third last]
<h3>Rock-Solid Support</h3>
<img src="http://themeblvd.com/demo/assets/swagger/swagger_layout_6.png" class="pretty" />
<p>With the most detailed documentation and tutorial videos on ThemeForest matched with some of the fastest support responses, you can be sure to not fall behind.</p>
[/one_third]

[/raw]'
							),
							'tab_2' => array (
								'type' => 'raw',
								'raw' => '[raw]
[two_third]
[post_grid categories="portfolio" columns="3" rows="3"]
[/two_third]
[one_third last]
<h4>We\'re doing some awesome things.</h4>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
<a href="#" class="lead-link">View Portfolio →</a>
[/one_third]
[/raw]'
							),
							'tab_3' => array (
								'type' => null
							),
							'tab_4' => array (
								'type' => null
							),
							'tab_5' => array (
								'type' => null
							),
							'tab_6' => array (
								'type' => null
							),
							'tab_7' => array (
								'type' => null
							),
							'tab_8' => array (
								'type' => null
							),
							'tab_9' => array (
								'type' => null
							),
							'tab_10' => array (
								'type' => null
							),
							'tab_11' => array (
								'type' => null
							),
							'tab_12' => array (
								'type' => null
							)
						)
					)
				)
			)
		);
		$samples = array_merge( $swagger, $samples );
		return $samples;
	}
}

/* Function of all filters to hook down under "Hook Adjustments" */

if( ! function_exists( 'swagger_filters' ) ) {
	function swagger_filters() {
		add_filter( 'themeblvd_frontend_locals', 'swagger_frontend_locals' );
		add_filter( 'themeblvd_frontend_config', 'swagger_frontend_config' );
		add_filter( 'themeblvd_nav_menus', 'swagger_nav_menus' );
		add_filter( 'themeblvd_page_meta', 'swagger_page_meta' );
		add_filter( 'themeblvd_sidebar_locations', 'swagger_sidebar_locations' );
		add_filter( 'themeblvd_sample_layouts', 'swagger_sample_layouts' );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Theme Functions
/*
/* The following functions either add elements to unsed hooks in the framework, 
/* or replace default functions. These functions can be overridden from a child 
/* theme.
/*-----------------------------------------------------------------------------------*/

/* Header Addon */

if( ! function_exists( 'swagger_social_media' ) ) {
	function swagger_social_media() {
		?>
		<div class="social-media">
			<?php echo themeblvd_contact_bar(); ?>
		</div><!-- .social-media (end) -->
		<?php
	}
}

/* Featured Titles on posts */

if( ! function_exists( 'swagger_featured_single' ) ) {
	function swagger_featured_single() {
		global $post;
		
		// Determine if meta info should show
		$show_meta = true;
		if( themeblvd_get_option( 'single_meta' ) == 'hide' )
			$show_meta = false;
		if( get_post_meta( $post->ID, '_tb_meta', true ) == 'hide' )
			$show_meta = false;
		else if( get_post_meta( $post->ID, '_tb_meta', true ) == 'show' )
			$show_meta = true;
		if( is_attachment() )
			$show_meta = false;
		
		?>
		<div class="element element-headline featured-entry-title">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php if( $show_meta ) themeblvd_blog_meta();	?>
		</div><!-- .element (end) -->
		<?php
	}
}

/* Featured Titles on pages */

if( ! function_exists( 'swagger_featured_page' ) ) {
	function swagger_featured_page() {
		global $post;
		if( is_page() ) { // Need conditional because template_builder.php gets called for custom homepage.
			if( 'hide' != get_post_meta( $post->ID, '_tb_title', true ) ) {
				$tagline = get_post_meta( $post->ID, '_tb_tagline', true );
				echo '<div class="element element-headline featured-entry-title">';
				echo '<h1 class="entry-title">'.get_the_title( $post->ID ).'</h1>';
				if( $tagline ) echo '<p class="tagline">'.stripslashes( $tagline ).'</p>';
				echo '</div><!-- .element (end) -->';
			}
		}
	}
}

/* Featured Titles on archives */

if( ! function_exists( 'swagger_featured_archive' ) ) {
	function swagger_featured_archive() {
		?>
		<div class="element element-headline featured-entry-title">
			<h1 class="entry-title"><?php themeblvd_archive_title(); ?></h1>
		</div><!-- .element (end) -->
		<?php
	}
}

/* Featured slider on blog */

if( ! function_exists( 'swagger_featured_blog' ) ) {
	function swagger_featured_blog() {
		if( themeblvd_get_option( 'blog_featured' ) ){
			echo '<div class="element">';
			themeblvd_slider( themeblvd_get_option( 'blog_slider' ) );
			echo '</div>';
		}
	}
}

/* End of Featured section (adds the secondary bg panel) */

if( ! function_exists( 'swagger_featured_end' ) ) {
	function swagger_featured_end() {
		?>
					<div class="clear"></div>
				</div><!-- .featured-content (end) -->
				<div class="secondary-bg"></div>
			</div><!-- .featured-inner (end) -->
		</div><!-- #featured (end) -->
		
		<!-- FEATURED (end) -->
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Hook Adjustments on framework
/*-----------------------------------------------------------------------------------*/

// Remove hooks
remove_action( 'themeblvd_featured_end', 'themeblvd_featured_end_default' );

// Add hooks
add_action( 'after_setup_theme', 'swagger_filters' );
add_action( 'themeblvd_header_addon', 'swagger_social_media' );
add_action( 'themeblvd_featured_single', 'swagger_featured_single' );
add_action( 'themeblvd_featured_page', 'swagger_featured_page' );
add_action( 'themeblvd_featured_archive', 'swagger_featured_archive' );
add_action( 'themeblvd_featured_end', 'swagger_featured_end' );
add_action( 'themeblvd_featured_blog', 'swagger_featured_blog' );