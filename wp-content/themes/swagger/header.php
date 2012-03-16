<?php
/**
 * The Header for our theme.
 * 
 * WARNING: This template file is a core part of the 
 * Theme Blvd WordPress Framework. This framework is 
 * designed around this file NEVER being altered. It 
 * is advised that any edits to the way this file 
 * displays its content be done with via hooks and filters.
 * 
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<?php if( themeblvd_get_option( 'responsive_css' ) != 'false' ) : ?>
	<meta name="viewport" content="width=device-width, maximum-scale=1" />
	<?php endif; ?>
	<title><?php themeblvd_title(); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/framework/frontend/assets/js/html5.js" type="text/javascript"></script>
	<![endif]-->
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php themeblvd_before(); ?>
<div id="wrapper">
	<div id="container">
		
		<!-- HEADER (start) -->
		
		<?php themeblvd_header_before(); ?>
		<div id="top">
			<header id="branding" role="banner">
				<div class="content">
					<?php
					/**
					 * Display header elements.
					 */
					themeblvd_header_top();
					themeblvd_header_above();
					themeblvd_header_content();
					themeblvd_header_menu();
					?>
				</div><!-- .content (end) -->
			</header><!-- #branding (end) -->
		</div><!-- #top (end) -->
		<?php themeblvd_header_after(); ?>
		
		<!-- HEADER (end) -->