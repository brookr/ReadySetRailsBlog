<?php
/**
 * Template Name: Sitemap
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

// Header
get_header(); 
	
	the_post();
	
	// Featured area
	if( themeblvd_config( 'featured' ) ) {
		themeblvd_featured_start();
		themeblvd_featured( 'page' );
		themeblvd_featured_end();
	}
	
	// Start main area
	themeblvd_main_start();
	themeblvd_main_top();
	
	// Breadcrumbs
	themeblvd_breadcrumbs();
	
	// Before sidebar+content layout
	themeblvd_before_layout();
	?>
	
	<div id="sidebar_layout">
		<div class="sidebar_layout-inner">
			<div class="grid-protection">

				<?php themeblvd_fixed_sidebars( 'left' ); ?>
				
				<!-- CONTENT (start) -->
	
				<div id="content" role="main">
					<div class="inner">
						<?php themeblvd_content_top(); ?>
						<?php get_template_part( 'content', 'template_sitemap' ); ?>
						<?php if( themeblvd_supports( 'comments', 'pages' ) ) comments_template( '', true ); ?>
					</div><!-- .inner (end) -->
				</div><!-- #content (end) -->
					
				<!-- CONTENT (end) -->	
				
				<?php themeblvd_fixed_sidebars( 'right' ); ?>
			
			</div><!-- .grid-protection (end) -->
		</div><!-- .sidebar_layout-inner (end) -->
	</div><!-- .sidebar-layout-wrapper (end) -->
		
	<?php	
	// End main area
	themeblvd_main_bottom();
	themeblvd_main_end();
	
// Footer
get_footer();