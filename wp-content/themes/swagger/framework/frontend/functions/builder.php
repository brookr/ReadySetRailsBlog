<?php
/*-----------------------------------------------------------------------------------*/
/* Display Layout
/*-----------------------------------------------------------------------------------*/

/**
 * Display custom layout within template_builder.php 
 * page template.
 *
 * When each element is displayed, it is done so with 
 * an external function. This will allow some elements 
 * to be used for other things such as shortcodes. 
 * However, even elements that shouldn't have an external 
 * function do to allow those elements to be indidivually 
 * edited from a child theme.
 *
 * @since 2.0.0
 *
 * @param string $layout Post slug for layout
 * @param string $location Location of elements, featured or primary
 */
 
if( ! function_exists( 'themeblvd_layout' ) ) {
	function themeblvd_elements( $layout, $location ) {
		
		// Setup
		$counter = 0;
		$primary_query = false;
		$layout_id = themeblvd_post_id_by_name( $layout, 'tb_layout' );
		if( ! $layout_id ) {
			// This should rarely happen. A common scenario might 
			// be the user setup a page with a layout, but then 
			// deleted the layout after page was already published.
			echo themeblvd_get_local( 'invalid_layout' );
			return;
		}
		// Gather elements and only move forward if we have elements to show.
		$elements = get_post_meta( $layout_id, 'elements', true );
		if( is_array( $elements ) && isset( $elements[$location] ) && ! empty( $elements[$location] ) ) {
			$elements = $elements[$location];
			$num_elements = count($elements);
		} else {
			return;
		}

		// Loop through elements
		foreach( $elements as $id => $element ) {
			
			// Increase counter
			$counter++;
			
			// CSS classes for element
			$classes = 'element '.$location.'-element-'.$counter.' element-'.$element['type'];
			if( $counter == 1 )
				$classes .= ' first-element';
			if( $num_elements == $counter )
				$classes .= ' last-element';
			if( $element['type'] == 'slider' ) {
				$slider_id = themeblvd_post_id_by_name( $element['options']['slider_id'], 'tb_slider' );
				$type = get_post_meta( $slider_id, 'type', true );
				$classes .= ' element-slider-'.$type;
			}
			if( $element['type'] == 'paginated_post_lst' || $element['type'] == 'paginated_post_grid' )
				$classes .= $element['type'];
			$classes .= themeblvd_get_classes( 'element_'.$element['type'], true );
			
			// Start ouput
			echo '<div class="'.$classes.'">';
			echo '<div class="element-inner">';
			echo '<div class="element-inner-wrap">';
			echo '<div class="grid-protection">';
			
			switch( $element['type'] ) {
				
				/*------------------------------------------------------*/
				/* Columns
				/*------------------------------------------------------*/
				
				case 'columns' :
					$i = 1;
					$columns = array();
					$num = $element['options']['setup']['num'];
					while( $i <= $num ) {
						$columns[] = $element['options']['col_'.$i];
						$i++;
					}
					themeblvd_columns( $num, $element['options']['setup']['width'][$num], $columns );
					break;
				
				/*------------------------------------------------------*/
				/* Content
				/*------------------------------------------------------*/
				
				case 'content' :
					echo themeblvd_content( $element['options'] );
					break;
				
				/*------------------------------------------------------*/
				/* Divider
				/*------------------------------------------------------*/
				
				case 'divider' :
					echo themeblvd_divider( $element['options']['type'] );
					break;
					
				/*------------------------------------------------------*/
				/* Headline
				/*------------------------------------------------------*/
				
				case 'headline' :
					echo themeblvd_headline( $element['options'] );
					break;
					
				/*------------------------------------------------------*/
				/* Post Grid
				/*------------------------------------------------------*/
				
				case 'post_grid' :
					themeblvd_posts( $element['options'], 'grid', $location, 'secondary' );
					break;
					
				/*------------------------------------------------------*/
				/* Post Grid (paginated)
				/*------------------------------------------------------*/
				
				case 'post_grid_paginated' :
					if( ! $primary_query ) {
						themeblvd_posts_paginated( $element['options'], 'grid', $location );
						$primary_query = true;
					}
					break;
					
				/*------------------------------------------------------*/
				/* Post Grid Slider
				/*------------------------------------------------------*/
				
				case 'post_grid_slider' :
					themeblvd_post_slider( $id, $element['options'], 'grid', $location );
					break;
					
				/*------------------------------------------------------*/
				/* Post List
				/*------------------------------------------------------*/
				
				case 'post_list' :
					themeblvd_posts( $element['options'], 'list', $location, 'secondary' );
					break;
					
				/*------------------------------------------------------*/
				/* Post List (paginated)
				/*------------------------------------------------------*/
				
				case 'post_list_paginated' :
					if( ! $primary_query ) {
						themeblvd_posts_paginated( $element['options'], 'list', $location );
						$primary_query = true;
					}
					break;
					
				/*------------------------------------------------------*/
				/* Post List Slider
				/*------------------------------------------------------*/
				
				case 'post_list_slider' :
					themeblvd_post_slider( $id, $element['options'], 'list', $location );
					break;
					
				/*------------------------------------------------------*/
				/* Slider
				/*------------------------------------------------------*/
				
				case 'slider' :
					themeblvd_slider( $element['options']['slider_id'] );
					break;
					
				/*------------------------------------------------------*/
				/* Slogan
				/*------------------------------------------------------*/
				
				case 'slogan' :
					echo themeblvd_slogan( $element['options'] );
					break;
					
				/*------------------------------------------------------*/
				/* Tabs
				/*------------------------------------------------------*/
				
				case 'tabs' :
					echo themeblvd_tabs( $id, $element['options'] );
					break;
					
				/*------------------------------------------------------*/
				/* Recent Tweet
				/*------------------------------------------------------*/
				
				case 'tweet' :
					echo themeblvd_tweet( $id, $element['options'] );
					break;
				
			} // End switch
			
			// Allow to add on custom element that's
			// not in the framework
			do_action( 'themeblvd_'.$element['type'], $id, $element['options'], $location );
			
			// End output
			echo '<div class="clear"></div>';
			echo '</div><!-- .grid-protection (end) -->';
			echo '</div><!-- .element-inner-wrap (end) -->';
			echo '</div><!-- .element-inner (end) -->';
			echo '</div><!-- .element (end) -->';
			
		} // End foreach
				
	}
}

/*-----------------------------------------------------------------------------------*/
/* Elements
/*-----------------------------------------------------------------------------------*/

/**
 * Display set of columns.
 *
 * @since 2.0.0
 *
 * @param array $num Number of columns
 * @param string $widths Width for each column
 * @param array $columns Inidivual columns, number of array items must match $setup's number
 */

if( ! function_exists( 'themeblvd_columns' ) ) {
	function themeblvd_columns( $num, $widths, $columns ) {
		
		// Kill it if number of columns doesn't match the 
		// number of widths exploded from the string.
		$widths = explode( '-', $widths );
		if( $num != count( $widths ) )
			return;
		
		// Kill it if number of columns doesn't match the 
		// number of columns feed into the function.
		if( $num != count( $columns ) )
			return;

		// Last column's key
		$last = $num - 1;
		
		foreach( $columns as $key => $column ) {
			
			// Set CSS classes for column
			$classes = 'column '.$widths[$key];
			if( $last == $key )
				$classes .= ' last';
			
			// Start display
			echo '<div class="'.$classes.'">';
			
			// Column Content
			switch( $column['type'] ) {
				case 'widget' :
					if( isset( $column['sidebar'] ) && $column['sidebar'] ) {
						echo '<div class="widget-area">';
						dynamic_sidebar( $column['sidebar'] );
						echo '</div><!-- .widget-area (end) -->';
					}
					break;
				case 'page' :
					if( isset( $column['page'] ) && $column['page'] ) {
						$page = get_page( themeblvd_post_id_by_name( $column['page'], 'page' ) );
						echo apply_filters( 'the_content', $page->post_content );
					}
					break;
				case 'raw' :
					if( isset( $column['raw'] ) ) {
						echo apply_filters( 'the_content', stripslashes( $column['raw'] ) );
					}
					break;			
			}
			
			// End display
			echo '</div><!-- .column (end) -->';
		}
	}
}

/**
 * Display content.
 *
 * @since 2.0.0
 *
 * @param array $options Options for content
 * @return string $output HTML output for content
 */

if( ! function_exists( 'themeblvd_content' ) ) {
	function themeblvd_content( $options ) {
		switch( $options['source'] ) {
			case 'current' :
				$page_id = themeblvd_config('id');
				$page = get_page( $page_id );
				$output = apply_filters( 'the_content', $page->post_content );
				break;
			case 'external' :
				$page_id = themeblvd_post_id_by_name( $options['page_id'], 'page' );
				$page = get_page( $page_id );
				$output = apply_filters( 'the_content', $page->post_content );
				break;
			case 'raw' :
				$output = apply_filters( 'the_content', stripslashes( $options['raw_content'] ) );
				break;
		}
		return $output;
	}
}

/**
 * Display divider.
 *
 * @since 2.0.0
 *
 * @param array $type style of divider
 * @return string $output HTML output for divider
 */

if( ! function_exists( 'themeblvd_divider' ) ) {
	function themeblvd_divider( $type ) {
		$output = '<div class="divider divider-'.$type.'"></div>';
		return $output;
	}
}

/**
 * Display headline.
 *
 * @since 2.0.0
 *
 * @param array $options all options for headline
 * @return string $output HTML output for headline
 */

if( ! function_exists( 'themeblvd_headline' ) ) {
	function themeblvd_headline( $options ) {
		$output = '<'.$options['tag'].' class="text-'.$options['align'].'">';
		$output .= stripslashes( $options['text'] );
		$output .= '</'.$options['tag'].'>';
		if( $options['tagline'] ) {
			$output .= '<p class="text-'.$options['align'].'">';
			$output .= stripslashes( $options['tagline'] );
			$output .= '</p>';
		}
		return $output;
	}
}

/**
 * Display post slider.
 * Desinged to work with FlexSlider jQuery plugin
 *
 * @since 2.0.0
 *
 * @param string $id Unique ID for element
 * @param array $options all options for posts
 * @param string $type Type of posts, grid or list
 */

if( ! function_exists( 'themeblvd_post_slider' ) ) {
	function themeblvd_post_slider( $id, $options, $type, $current_location ) {

		global $content; // $options['content']
		global $size; // $options['thumbs']
		global $counter;
		global $columns;
		global $location;
		global $post;
		$location = $current_location;
		$args = themeblvd_get_posts_args( $options, $type, true );
		
		// Configure additional CSS classes
		$classes = '';
		$options['nav_standard'] == '1' ? $classes .= ' show-nav_standard' : $classes .= ' hide-nav_standard';
		$options['nav_arrows'] == '1' ? $classes .= ' show-nav_arrows' : $classes .= ' hide-nav_arrows';
		$options['pause_play'] == '1' ? $classes .= ' show-pause_play' : $classes .= ' hide-pause_play';
		
		// Config before query string
		if( $type == 'grid' ) {
			$columns = $options['columns'];
			$rows = $options['rows'];
			$posts_per_slide = $columns*$rows;
			$size = themeblvd_grid_class( $columns );
		} else {
			$posts_per_slide = $options['posts_per_slide'];
			$options['content'] == 'default' ? $content = themeblvd_get_option( 'blog_content' ) : $content = $options['content'];
			$options['thumbs'] == 'default' ? $size = themeblvd_get_option( 'blog_thumbs' ) : $size = $options['thumbs'];
		}
		
		// Get posts
		$posts = get_posts( $args );
		
		// Adjust offset if neccesary
		if( $args['numberposts'] == -1 && $args['offset'] > 0 ) {
			$i = 0;
			while ( $i < $args['offset'] ) {
				unset( $posts[$i] );
				$i++;
			}
		}

		// Slider JS
		themeblvd_standard_slider_js( $id, $options );
		?>
		<div id="tb-slider-<?php echo $id; ?>" class="slider-wrapper standard-slider-wrapper">
			<div class="slider-inner<?php echo $classes; ?>">
				<div class="slides-wrapper slides-wrapper-<?php echo $id; ?>">
					<div class="slides-inner">
						<div class="slider standard-slider flexslider">
							<div class="loader"></div>
							<ul class="slides">
								<?php
								if ( ! empty( $posts ) ) {
									if( $type == 'grid' ) {
										
										/*-------------------------------------------*/
										/* Post Grid Loop
										/*-------------------------------------------*/
										
										$counter = 1;
										$per_slide_counter = 1;
										$number_of_posts = count( $posts );
										
										foreach ( $posts as $post ) {
											setup_postdata( $post );
											// Start first slide and first row.
											if( $counter == 1 ) {
												echo '<li class="slide">';
												echo '<div class="post_'.$type.'">';
												themeblvd_open_row();
											}
											// Include the post
											get_template_part( 'content', 'grid' );
											// Add in the complicated stuff
											if( $per_slide_counter == $posts_per_slide ) {
												// End of a slide and thus end the row
												themeblvd_close_row();
												echo '</div><!-- .post_'.$type.' (end) -->';
												echo '</li>';
												// And if posts aren't done yet, open a 
												// new slide and new row.
												if( $number_of_posts != $counter ) {
													echo '<li class="slide">';
													echo '<div class="post_'.$type.'">';
													themeblvd_open_row();
												}
												// Set the posts per slide counter back to 
												// 0 for the next slide.
												$per_slide_counter = 1;
											} else if( $per_slide_counter % $columns == 0  ) {
												// End row only
												themeblvd_close_row();
												// Open a new row if there are still post left 
												// in this slide.
												if( $posts_per_slide != $per_slide_counter ) {
													themeblvd_open_row();
												}
												// Increase number of posts per slide cause 
												// we're not done yet.
												$per_slide_counter++;
											} else {
												// Increment posts per slide number 
												// if nothing else is happenning.
												$per_slide_counter++;
											}									
											$counter++;
											
										}
									} else {
										
										/*-------------------------------------------*/
										/* Post List Loop
										/*-------------------------------------------*/
										
										$counter = 1;
										$per_slide_counter = 1;
										$number_of_posts = count( $posts );
										
										foreach ( $posts as $post ) { 
											setup_postdata( $post );
											if( $counter == 1 ) {
												echo '<li class="slide">';
												echo '<div class="post_'.$type.'">';
											}
											get_template_part( 'content', get_post_format() );
											// Add in the complicated stuff
											if( $per_slide_counter == $posts_per_slide ) {
												// End of a slide and thus end the row
												echo '</div><!-- .post_'.$type.' (end) -->';
												echo '</li>';
												// And if posts aren't done yet, open a 
												// new slide
												if( $number_of_posts != $counter ) {
													echo '<li class="slide">';
													echo '<div class="post_'.$type.'">';
												}
												// Set the posts per slide counter back to 
												// 0 for the next slide.
												$per_slide_counter = 1;
											} else {
												// Increment posts per slide number 
												// if nothing else is happenning.
												$per_slide_counter++;
											}
											$counter++;
										}
									}
								} else {
									echo '<p>'.themeblvd_get_local( 'archive_no_posts' ).'</p>';
								}
								?>
							</ul>
						</div><!-- .slider (end) -->
					
					</div><!-- .slides-inner (end) -->
				</div><!-- .slides-wrapper (end) -->
			</div><!-- .slider-inner (end) -->
			<div class="design-1"></div>
			<div class="design-2"></div>
			<div class="design-3"></div>
			<div class="design-4"></div>
		</div><!-- #<?php echo $id; ?> (end) -->
		<?php
	}
}

/**
 * Display post list or post grid
 *
 * @since 2.0.0
 *
 * @param array $options all options for posts
 * @param string $type Type of posts, grid or list
 * @param string $current_location Current location of element, featured or primary
 */

if( ! function_exists( 'themeblvd_posts' ) ) {
	function themeblvd_posts( $options, $type, $current_location ) {
		global $content; // $options['content']
		global $size; // $options['thumbs']
		global $counter;
		global $columns;
		global $location;
		global $post;
		global $more;

		$location = $current_location;
		$args = themeblvd_get_posts_args( $options, $type );
		
		// Config before query string
		if( $type == 'grid' ) {
			$columns = $options['columns'];
			$rows = $options['rows'];
			$size = themeblvd_grid_class( $columns );
		} else {
			$options['content'] == 'default' ? $content = themeblvd_get_option( 'blog_content' ) : $content = $options['content'];
			$options['thumbs'] == 'default' ? $size = themeblvd_get_option( 'blog_thumbs' ) : $size = $options['thumbs'];
		}
		
		// Get posts
		$posts = get_posts( $args );
		
		// Adjust offset if neccesary
		if( $args['numberposts'] == -1 && $args['offset'] > 0 ) {
			$i = 0;
			while ( $i < $args['offset'] ) {
				unset( $posts[$i] );
				$i++;
			}
		}

		// Start the loop
		echo '<div class="post_'.$type.'">';
		if ( ! empty( $posts ) ) {
			if( $type == 'grid' ) {
				// Loop for post grid (i.e. Portfolio)
				$counter = 1;
				$number_of_posts = count( $posts );
				foreach ( $posts as $post ) {
					setup_postdata( $post );
					if( $counter == 1 ) themeblvd_open_row();
					get_template_part( 'content', 'grid' );
					if( $counter % $columns == 0 ) themeblvd_close_row();
					if( $counter % $columns == 0 && $number_of_posts != $counter ) themeblvd_open_row();
					$counter++;
				}
				if( $number_of_posts % $columns != 0 ) themeblvd_close_row();
			} else {
				// Loop for post list (i.e. Blog)
				foreach ( $posts as $post ) { 
					setup_postdata( $post );
					$more = 0;
					get_template_part( 'content', get_post_format() );
				}
			}
		} else {
			echo '<p>'.themeblvd_get_local( 'archive_no_posts' ).'</p>';
		}
		echo '</div><!-- .post_'.$type.' (end) -->';
		// Show link
		if( $options['link'] )
			echo '<a href="'.$options['link_url'].'" target="'.$options['link_target'].'" title="'.$options['link_text'].'" class="lead-link">'.$options['link_text'].'</a>';
			
	}
}

/**
 * Display paginated post list or post grid
 *
 * @since 2.0.0
 *
 * @param array $options all options for posts
 * @param string $type Type of posts, grid or list
 */

if( ! function_exists( 'themeblvd_posts_paginated' ) ) {
	function themeblvd_posts_paginated( $options, $type, $current_location ) {
		global $wp_query;
		global $_themeblvd_paged;
		global $content;
		global $size;
		global $counter;
		global $columns;
		global $location;
		global $more;
    	$more = 0;
		$location = $current_location;
		$query_string = '';
		
		// Config before query string
		if( $type == 'grid' ) {
			$columns = $options['columns'];
			$rows = $options['rows'];
			$size = themeblvd_grid_class( $columns );
			if( $options['rows'] )
				$posts_per_page = $columns * $rows;
			else
				$posts_per_page = '-1';
		} else {
			$options['content'] == 'default' ? $content = themeblvd_get_option( 'blog_content' ) : $content = $options['content'];
			$options['thumbs'] == 'default' ? $size = themeblvd_get_option( 'blog_thumbs' ) : $size = $options['thumbs'];
		}
		
		/*------------------------------------------------------*/
		/* Query String (very similar to themeblvd_get_posts_args() 
		/* in helpers.php - May combine functions later.
		/*------------------------------------------------------*/
		
		if( ! $options['categories']['all'] ) {
			unset( $options['categories']['all'] );
			$categories = '';
			foreach( $options['categories'] as $category => $include ) {
				if( $include ) {
					$categories .= $category.',';
				}
			}
			if( $categories ) {
				$categories = themeblvd_remove_trailing_char( $categories, $char = ',' );
				$query_string .= 'category_name='.$categories.'&';
			}
		}
		if( $type == 'grid' ) {
			$query_string .= 'posts_per_page='.$posts_per_page.'&';
		} else {
			if( $options['posts_per_page'] ) 
				$query_string .= 'posts_per_page='.$options['posts_per_page'].'&';
		}
		if( $options['orderby'] ) $query_string .= 'orderby='.$options['orderby'].'&';
		if( $options['order'] ) $query_string .= 'order='.$options['order'].'&';
		// Pagination
		if ( get_query_var('paged') )
	        $paged = get_query_var('paged');
	    else if ( get_query_var('page') )
	        $paged = get_query_var('page'); // This provides compatiblity with static frontpage
		else
	        $paged = 1;
		$_themeblvd_paged = $paged; // Set global variable for pagination compatiblity on static frontpage
		$query_string .= 'paged='.$paged;
		
		/*------------------------------------------------------*/
		/* The Loop
		/*------------------------------------------------------*/
		
		// Query posts
		query_posts( $query_string );
		
		// Start the loop
		echo '<div class="post_'.$type.'">';
		if ( have_posts() ) {
			if( $type == 'grid' ) {
				// Loop for post grid (i.e. Portfolio)
				$counter = 1;
				while ( have_posts() ) { 
					the_post();
					if( $counter == 1 ) themeblvd_open_row();
					get_template_part( 'content', 'grid' );
					if( $counter % $columns == 0 ) themeblvd_close_row();
					if( $counter % $columns == 0 && $posts_per_page != $counter ) themeblvd_open_row();
					$counter++;
				}
				if( ($counter-1) != $posts_per_page ) themeblvd_close_row();
			} else {
				// Loop for post list (i.e. Blog)
				while ( have_posts() ) { 
					the_post();
					get_template_part( 'content', get_post_format() );
				}
			}
		} else {
			echo '<p>'.themeblvd_get_local( 'archive_no_posts' ).'</p>';
		}
		themeblvd_pagination();
		echo '</div><!-- .post_'.$type.' (end) -->';		
	}
}

/**
 * Display slider.
 *
 * @since 2.0.0
 *
 * @param string $slider Slug of custom-built slider to use
 */

if( ! function_exists( 'themeblvd_slider' ) ) {
	function themeblvd_slider( $slider ) {
		// Kill it if there's no slider
		if( ! $slider ) {
			echo '<div class="tb-warning">'.themeblvd_get_local( 'no_slider_selected.' ).'</div>';
			return;
		}
		// Get Slider ID
		$slider_id = themeblvd_post_id_by_name( $slider, 'tb_slider' );
		if( ! $slider_id ) {
			echo themeblvd_get_local( 'no_slider' );
			return;
		}
		// Gather info
		$type = get_post_meta( $slider_id, 'type', true );
		$settings = get_post_meta( $slider_id, 'settings', true );
		$slides = get_post_meta( $slider_id, 'slides', true );
		// Display slider based on its slider type
		do_action( 'themeblvd_'.$type.'_slider', $slider, $settings, $slides );
	}
}

/**
 * Display slogan.
 *
 * @since 2.0.0
 *
 * @param array $options all options for slogan
 * @return string $output HTML output for slogan
 */

if( ! function_exists( 'themeblvd_slogan' ) ) {
	function themeblvd_slogan( $options ) {
		// Wrapping class
		if( $options['button'] )
			$class = 'has_button';
		else
			$class = 'text_only';
		// Output
		$output = '<div class="slogan '.$class.'">';
		if( $options['button'] ) {
			$output .= themeblvd_button( $options['button_text'], $options['button_url'], $options['button_color'], $options['button_target'], 'large' );	
		}
		$output .= '<span class="slogan-text">'.stripslashes( $options['slogan'] ).'</span>';
		$output .= '</div><!-- .slogan (end) -->';
		return $output;
	}	
}

/**
 * Display set of tabs.
 *
 * @since 2.0.0
 *
 * @param array $id unique ID for tab set
 * @param array $options all options for tabs
 * @return string $output HTML output for tabs
 */

if( ! function_exists( 'themeblvd_tabs' ) ) {
	function themeblvd_tabs( $id, $options ) {
		$output = '<div class="tb-tabs tb-tabs-'.$options['setup']['style'].'">';
		// Fixed Height
		$height = '';
		if( $options['height'] )
			$height = ' style="height:'.$options['height'].'px"';
		// Navigation
		$i = 0;
		$class = null;
		$output .= '<div class="tab-nav">';
		$output .= '<ul>';
		foreach( $options['setup']['names'] as $key => $name ) {
			if( $i == 0 ) 
				$class = 'active';
			$output .= '<li class="'.$class.'"><a href="#'.$id.'-'.$key.'" title="'.$name.'">'.$name.'</a></li>';
			$class = null;
			$i++;
		}
		$output .= '</ul>';
		$output .= '<div class="clear"></div>';
		$output .= '</div><!-- .tab-nav (end) -->';
		// Tab content
		$i = 0;
		$class = null;
		foreach( $options['setup']['names'] as $key => $name ) {
			$output .= '<div id="'.$id.'-'.$key.'" class="tab-content">';
			$output .= '<div class="grid-protection"'.$height.'>';
			switch( $options[$key]['type'] ) {
				case 'page' :
					$page_id = themeblvd_post_id_by_name( $options[$key]['page'], 'page' );
					$page = get_page( $page_id );
					$output .= apply_filters( 'the_content', $page->post_content );
					break;
				case 'raw' :
					$output .= apply_filters( 'the_content', stripslashes( $options[$key]['raw'] ) );
					break;
			}
			$output .= '<div class="clear"></div>';
			$output .= '</div><!-- .grid-production (end) -->';
			$output .= '</div><!-- #'.$id.'-'.$key.' (end) -->';
			$i++;
		}
		$output .= '</div><!-- .tb-tabs (end) -->';
		return $output;
	}
}

/**
 * Display recent tweet.
 *
 * @since 2.0.0
 *
 * @param array $options all options for tweet
 * @return string $output HTML output for tweet
 */

if( ! function_exists( 'themeblvd_tweet' ) ) {
	function themeblvd_tweet( $id, $options ) {
		// Create the stream context
		$context = stream_context_create(array(
		    'http' => array(
		        'timeout' => 5      // Timeout in seconds
		    )
		));
		
		// Username
		$username = $options['account'];
		
		// Check for cached tweet
		$tweet = get_transient( $id.'-'.$username );
		
		// Fetch the data from Twitter
		if( ! $tweet ) {
			echo 'new tweet!';
			$format = 'json';
			$contents = file_get_contents( "http://api.twitter.com/1/statuses/user_timeline/{$username}.{$format}", 0, $context );
			if ( ! empty( $contents ) ) {
				// Decode it.
				$tweet = json_decode( $contents );
				// Cache it for next time.
				set_transient( $id.'-'.$username, $tweet, 60*60*3 ); // 3 hour cache
			}
		}
		
		// Check to make sure we have a tweet and display it.
		if ( $tweet ) {
		    $output = '<span class="tweet-icon '.$options['icon'].'"></span>';
		    $output .= '<a href="http://twitter.com/'.$username.'/status/'.$tweet[0]->id_str.'" target="_blank">';
		    $output .= $tweet[0]->text;
		    $output .= '</a>';
		} else {
		    $output = 'Twitter timed out.';
		}
		return $output;
	}	
}