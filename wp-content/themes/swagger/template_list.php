<?php
/**
 * Template Name: Post List
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

// Fake conditional
$fake_conditional = themeblvd_get_fake_conditional();

// Setup query string
$query_string = themeblvd_query_string();
$content = themeblvd_get_option( 'blog_content' );

// Header
get_header(); 

	// Featured area
	if( themeblvd_config( 'featured' ) ) {
		themeblvd_featured_start();
		themeblvd_featured( 'page' );
		themeblvd_featured( 'blog' );
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
						<div class="primary-post-list element-post_list_paginated post_list<?php echo themeblvd_get_classes( 'element_post_list_paginated', true ); ?>">
							<?php query_posts( $query_string ); ?>
							<?php if ( have_posts() ) : ?>
								<?php while ( have_posts() ) : the_post(); ?>
									<?php get_template_part( 'content', themeblvd_get_part( 'blog' ) ); ?>
								<?php endwhile; ?>
							<?php else : ?>
								<p><?php echo themeblvd_get_local( 'archive_no_posts' ); ?></p>
							<?php endif; ?>
							<?php themeblvd_pagination(); ?>
						</div><!-- .post_list (end) -->
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