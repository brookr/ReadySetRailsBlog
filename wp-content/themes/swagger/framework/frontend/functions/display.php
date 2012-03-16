<?php
/*-----------------------------------------------------------------------------------*/
/* <head>
/*-----------------------------------------------------------------------------------*/

/**
 * Display <title>
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_title_default' ) ) {
	function themeblvd_title_default() {
		global $page, $paged;
		wp_title( '|', true, 'right' );
		// Add the blog name.
		bloginfo( 'name' );
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( themeblvd_get_local( 'page_num' ), max( $paged, $page ) );
	}
}

/*-----------------------------------------------------------------------------------*/
/* Header
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_header_above
 *
 * @since 2.0.0
 */
 
if( ! function_exists( 'themeblvd_header_above_default' ) ) {
	function themeblvd_header_above_default() {		
		echo '<div class="header-above">';
		themeblvd_display_sidebar( 'ad_above_header', 'collapsible' );
		echo '</div><!-- .header-above (end) -->';
	}
}

/**
 * Default display for action: themeblvd_header_content
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_header_content_default' ) ) {
	function themeblvd_header_content_default() {
		?>
		<div id="header_content">
			<div class="container">
				<div class="inner">
					<?php 
					themeblvd_header_logo();
					themeblvd_header_addon();
					?>
					<div class="clear"></div>
				</div><!-- .inner (end) -->
			</div><!-- .container (end) -->
		</div><!-- #header_content (end) -->
		<?php
	}
}

/**
 * Default display for action: themeblvd_header_logo
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_header_logo_default' ) ) {
	function themeblvd_header_logo_default() {
		$option = themeblvd_get_option( 'logo' );
		?>
		<div class="header_logo">
			<?php
			if( is_array( $option ) && isset( $option['type'] ) ) {
				switch( $option['type'] ) {
					case 'title' :
						echo '<a href="'.home_url().'" title="'.get_bloginfo('name').'" class="tb-text-logo">'.get_bloginfo('name').'</a>';
						break;
					case 'custom' :
						echo '<a href="'.home_url().'" title="'.$option['custom'].'" class="tb-text-logo">'.$option['custom'].'</a>';
						break;
					case 'image' :
						echo '<a href="'.home_url().'" title="'.get_bloginfo('name').'" class="tb-image-logo"><img src="'.$option['image'].'" alt="'.get_bloginfo('name').'" /></a>';
						break;
				}
			}
			?>
		</div><!-- .tbc_header_logo (end) -->
		<?php
	}
}

/**
 * Default display for action: themeblvd_header_main_menu
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_header_menu_default' ) ) {
	function themeblvd_header_menu_default() {
		?>
		<?php echo themeblvd_nav_menu_select( 'primary' ); ?>
		<nav id="access" role="navigation">
			<div class="container">
				<div class="content">
					<?php wp_nav_menu( array( 'menu_id' => 'primary-menu', 'menu_class' => 'sf-menu','container' => '', 'theme_location' => 'primary', 'fallback_cb' => 'themeblvd_primary_menu_fallback' ) ); ?>
					<?php themeblvd_header_menu_addon(); ?>
					<div class="clear"></div>
				</div><!-- .content (end) -->
			</div><!-- .container (end) -->
		</nav><!-- #access (end) -->
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Footer
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_footer_content
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_footer_content_default' ) ) {
	function themeblvd_footer_content_default() {
		
		// Grab the setup
		$footer_setup = themeblvd_get_option( 'footer_setup' );

		// Only move forward if user has selected for columns to show
		if( is_array( $footer_setup ) && $footer_setup['num'] > 0 ) {
			
			// Build array of columns
			$i = 1;
			$columns = array();
			$num = $footer_setup['num'];
			while( $i <= $num ) {
				$columns[] = themeblvd_get_option( 'footer_col_'.$i );
				$i++;
			}
			?>
			<div class="footer_content">
				<div class="container">
					<div class="content">
						<div class="grid-protection">
							<?php themeblvd_columns( $num, $footer_setup['width'][$num], $columns ); ?>
							<div class="clear"></div>
						</div><!-- .grid-protection (end) -->
					</div><!-- .content (end) -->
				</div><!-- .container (end) -->
			</div><!-- .footer_content (end) -->
			<?php
		}
	}
}

/**
 * Default display for action: themeblvd_footer_sub_content
 *
 * @since 2.0.0
 */
 
if( ! function_exists( 'themeblvd_footer_sub_content_default' ) ) {
	function themeblvd_footer_sub_content_default() {
		?>
		<div id="footer_sub_content">
			<div class="container">
				<div class="content">
					<div class="copyright">
						<p>
							<span><?php echo apply_filters( 'themeblvd_footer_copyright', themeblvd_get_option( 'footer_copyright' ) ); ?></span>
						</p>
					</div><!-- .copyright (end) -->
					<div class="clear"></div>
				</div><!-- .content (end) -->
			</div><!-- .container (end) -->
		</div><!-- .footer_sub_content (end) -->
		<?php
	}
}

/**
 * Default display for action: themeblvd_footer_below
 *
 * @since 2.0.0
 */

if( ! function_exists( 'tthemeblvd_footer_below_default' ) ) {
	function themeblvd_footer_below_default() {		
		echo '<div class="footer-below">';
		themeblvd_display_sidebar( 'ad_below_footer', 'collapsible' );
		echo '</div><!-- .footer-below (end) -->';
	}
}


/*-----------------------------------------------------------------------------------*/
/* Sidebars/Widget Areas
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_fixed_sidebar_before
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_fixed_sidebar_before_default' ) ) {
	function themeblvd_fixed_sidebar_before_default( $side ) {
		echo '<div class="fixed-sidebar '.$side.'-sidebar">';
		echo '<div class="fixed-sidebar-inner">';
	}
}

/**
 * Default display for action: themeblvd_fixed_sidebar_after
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_fixed_sidebar_after_default' ) ) {
	function themeblvd_fixed_sidebar_after_default() {
		echo '</div><!-- .fixed-sidebar-inner (end) -->';
		echo '</div><!-- .fixed-sidebar (end) -->';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Featured Area
/*-----------------------------------------------------------------------------------*/
	
/**
 * Default display for action: themeblvd_featured_start
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_featured_start_default' ) ) {
	function themeblvd_featured_start_default() {
		$classes = '';
		$featured = themeblvd_config( 'featured' );
		if( $featured ) {
			foreach( $featured as $class )
				$classes .= " $class";

		}
		?>
		<!-- FEATURED (start) -->
		
		<div id="featured">
			<div class="featured-inner<?php echo $classes; ?>">
				<div class="featured-content">
		<?php
	}
}

/**
 * Default display for action: themeblvd_featured_end
 *
 * @since 2.0.0
 */
 
if( ! function_exists( 'themeblvd_featured_end_default' ) ) {
	function themeblvd_featured_end_default() {
		?>
					<div class="clear"></div>
				</div><!-- .featured-content (end) -->
			</div><!-- .featured-inner (end) -->
		</div><!-- #featured (end) -->
		
		<!-- FEATURED (end) -->
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Primary Content Area
/*-----------------------------------------------------------------------------------*/

/**
 * Default display for action: themeblvd_main_start
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_main_start_default' ) ) {
	function themeblvd_main_start_default() {
		?>
		<!-- MAIN (start) -->
		
		<div id="main" class="<?php themeblvd_sidebar_layout_class(); ?>">
			<div class="main-inner">
				<div class="main-content">
					<div class="grid-protection">
		<?php
	}
}

/**
 * Default display for action: themeblvd_main_end
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_main_end_default' ) ) {
	function themeblvd_main_end_default() {
		?>
						<div class="clear"></div>
					</div><!-- .grid-protection (end) -->
				</div><!-- .main-content (end) -->
			</div><!-- .main-inner (end) -->
		</div><!-- #main (end) -->
		
		<!-- MAIN (end) -->
		<?php
	}
}

/**
 * Default display for action: themeblvd_main_top
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_main_top_default' ) ) {
	function themeblvd_main_top_default() {		
		echo '<div class="main-top">';
		themeblvd_display_sidebar( 'ad_above_content', 'collapsible' );
		echo '</div><!-- .main-top (end) -->';
	}
}

/**
 * Default display for action: themeblvd_main_top
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_main_bottom_default' ) ) {
	function themeblvd_main_bottom_default() {		
		echo '<div class="main-bottom">';
		themeblvd_display_sidebar( 'ad_below_content', 'collapsible' );
		echo '</div><!-- .main-bottom (end) -->';
	}
}

/**
 * Default display for action: themeblvd_breadcrumbs
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_breadcrumbs_default' ) ) {
	function themeblvd_breadcrumbs_default() {
		wp_reset_query();
		global $post;
		$display = '';
		// Pages and Posts
		if( is_page() || is_single() )
			$display = get_post_meta( $post->ID, '_tb_breadcrumbs', true );
		// Standard site-wide option
		if( ! $display || $display == 'default' )
			$display = themeblvd_get_option( 'breadcrumbs' );
		// Disable on posts homepage
		if( is_home() )
			$display = 'hide';
		// Show breadcrumbs if not hidden
		if( $display == 'show' )
			echo themeblvd_get_breadcrumbs();
	}
}

/*-----------------------------------------------------------------------------------*/
/* Content
/*-----------------------------------------------------------------------------------*/

// The following must happen within the loop!

/**
 * Default display for action: themeblvd_meta
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_blog_meta_default' ) ) {
	function themeblvd_blog_meta_default() {
		?>
		<div class="entry-meta">
			<span class="sep"><?php _e( 'Posted on', TB_GETTEXT_DOMAIN ); ?></span>
			<time class="entry-date" datetime="<?php the_time('c'); ?>" pubdate><?php the_time( get_option('date_format') ); ?></time>
			<span class="sep"> <?php _e( 'by', TB_GETTEXT_DOMAIN ); ?> </span>
			<span class="author vcard"><a class="url fn n" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo sprintf( esc_attr__( 'View all posts by %s', TB_GETTEXT_DOMAIN ), get_the_author() ); ?>" rel="author"><?php the_author(); ?></a></span>
			<span class="sep"> <?php _e( 'in', TB_GETTEXT_DOMAIN ); ?> </span>
			<span class="category"><?php the_category(', '); ?></span>
			<?php if ( comments_open() ) : ?>
			<span class="comments-link">
				<?php comments_popup_link( __( '<span class="leave-reply">No Comments</span>', TB_GETTEXT_DOMAIN ), __( '1 Comment', TB_GETTEXT_DOMAIN ), __( '% Comments', TB_GETTEXT_DOMAIN ) ); ?>
			</span>
			<?php endif; ?>
		</div><!-- .entry-meta -->		
		<?php
	}
}

/**
 * Default display for action: themeblvd_tags
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_blog_tags_default' ) ) {
	function themeblvd_blog_tags_default() {
		the_tags( '<span class="tags">', ', ', '</span>' );
	}
}

/**
 * Default display for action: themeblvd_the_post_thumbnail
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_the_post_thumbnail_default' ) ) {
	function themeblvd_the_post_thumbnail_default( $location, $size, $link ) {
		echo themeblvd_get_post_thumbnail( $location, $size, $link );
	}
}

/**
 * Default display for action: themeblvd_content
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_blog_content_default' ) ) {
	function themeblvd_blog_content_default( $type ) {
		if( $type == 'content' ) {
			// Show full content
			the_content();
		} else {
			// Show excerpt and read more button
			the_excerpt();
			echo '<div class="clear"></div>';
			echo themeblvd_button( themeblvd_get_local( 'read_more' ), get_permalink( get_the_ID() ), 'default', '_self', 'small', 'read-more', get_the_title( get_the_ID() )  );
		}
	}
}