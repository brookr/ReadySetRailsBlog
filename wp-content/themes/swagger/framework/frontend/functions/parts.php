<?php
/*-----------------------------------------------------------------------------------*/
/* Moveable Parts
/*-----------------------------------------------------------------------------------*/

/** 
 * Contact button bar 
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_contact_bar' ) ) {
	function themeblvd_contact_bar() {
		// Theme may or may not give user an option to 
		// change style. If the option doesn't exist, 
		// this style class will just be null.
		$style = themeblvd_get_option( 'social_media_style' );
		// Set it up
		$buttons = themeblvd_get_option( 'social_media' );
		$output = null;
		if( is_array( $buttons ) && ! empty ( $buttons ) ) {
			$output = '<div class="themeblvd-contact-bar">';
			$output .= '<ul class="'.$style.'">';
			foreach( $buttons as $id => $url ) {
				$output .= '<li><a href="'.$url.'" title="'.ucfirst( $id ).'" class="'.$id.'" target="_blank">'.ucfirst( $id ).'</a></li>';
			}
			$output .= '</ul>';
			$output .= '<div class="clear"></div>';
			$output .= '</div><!-- .themeblvd-contact-bar (end) -->';
		}
		return $output;
	}
}

/** 
 * Button
 *
 * @since 2.0.0
 *
 * @param string $text Text to show in button
 * @param string $color Color class of button
 * @param string $url URL where the button points to
 * @param string $target Anchor tag's target, _self, _blank, or lightbox
 * @param string $size Size of button - small, medium, or large
 * @param string $classes CSS classes to attach onto button
 * @return $output string HTML to output for button
 */

if( ! function_exists( 'themeblvd_button' ) ) {
	function themeblvd_button( $text, $url, $color = 'default', $target = '_self', $size = 'small', $classes = null, $title = null ) {
		if( $target == 'lightbox' ) {
			$lightbox = ' rel="themeblvd_lightbox"';
			$target = null;
		} else {
			$lightbox = null;
			$target = ' target="'.$target.'"';
		}
		if( ! $title )
			$title = $text;
		$output = '<a href="'.$url.'" title="'.$title.'" class="tb-button tb-button-'.$size.' '.$color.' '.$classes.'"'.$target.$lightbox.'>';
		$output .= '<span>'.$text.'</span>';
		$output .= '</a>';
		return $output;
	}
}

/** 
 * Display title for archive pages
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_archive_title' ) ) {
	function themeblvd_archive_title() {
		global $post;
		global $posts;
	    if( $posts ) $post = $posts[0]; // Hack. Set $post so that the_date() works.
	    if ( is_search() ) {
			// Search Results
			echo themeblvd_get_local('crumb_search').' "'.get_search_query().'"';
	    } else if ( is_category() ) {
	    	// If this is a category archive 
	    	// echo themeblvd_get_local( 'category' ).': ';
	    	single_cat_title();
	    } else if( is_tag() ) {
	    	// If this is a tag archive 
	    	echo themeblvd_get_local('crumb_tag').' "'.single_tag_title('', false).'"';
	    } else if ( is_day() ) {
	    	// If this is a daily archive 
	    	echo themeblvd_get_local( 'archive' ).': ';
	    	the_time('F jS, Y');
	    } else if ( is_month()) {
	    	// If this is a monthly archive 
	    	echo themeblvd_get_local( 'archive' ).': ';
	    	the_time('F, Y');
	    } else if ( is_year()) {
	    	// If this is a yearly archive 
	    	echo themeblvd_get_local( 'archive' ).': ';
	    	the_time('Y');
	    } else if ( is_author()) {
	    	// If this is an author archive 
	    	global $author;
			$userdata = get_userdata($author);
			echo themeblvd_get_local('crumb_author').' '.$userdata->display_name;
	    }
	}
}

/**
 * Pagination
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_pagination' ) ) {
	function themeblvd_pagination( $pages = '', $range = 2 ) {  
		global $paged;
		global $_themeblvd_paged;
		$showitems = ($range * 2)+1;  	     
		if( $_themeblvd_paged ) $paged = $_themeblvd_paged; // Static frontpage compatibility
		if( empty( $paged ) ) $paged = 1;
		if( $pages == '' ) {
			global $wp_query;
			$pages = $wp_query->max_num_pages;
			if( !$pages ) {
				$pages = 1;
			}
		}
		if( 1 != $pages ) {
			echo "<div class='pagination-wrap'><div class='pagination'><ul>";
			if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'>&laquo;</a></li>";
			if($paged > 1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a></li>";
			for ( $i = 1; $i <= $pages; $i++ ) {
				if (1 != $pages &&( ! ( $i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
					echo ( $paged == $i ) ? '<li><span class="current">'.$i.'</span></li>' : '<li><a href="'.get_pagenum_link($i).'" class="inactive">'.$i.'</a></li>';
				}
			}
			if ($paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a></li>";  
			if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'>&raquo;</a></li>";
			echo "</ul><div class=\"clear\"></div></div></div>\n";
		}
	}
}

/** 
 * Breadcrumbs
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_get_breadcrumbs' ) ) {
	function themeblvd_get_breadcrumbs() {
		global $post;
		// Filterable attributes
		$atts = array(
			'delimiter' => '&raquo;',
			'home' => themeblvd_get_local('home'),
			'home_link' => home_url(),
			'before' => '<span class="current">',
			'after' => '</span>'
		);
		$atts = apply_filters( 'themeblvd_breadcrumb_atts', $atts );
		// Start output
		$output = '<div id="breadcrumbs">';
		$output .= '<div class="breadcrumbs-inner">';
		$output .= '<div class="breadcrumbs-content">';
		$output .= '<a href="'.$atts['home_link'].'" class="home-link" title="'.$atts['home'].'">'.$atts['home'].'</a>'.$atts['delimiter'].' ';
		if ( is_category() ) {
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0) $output .= (get_category_parents($parentCat, TRUE, ' '.$atts['delimiter'].' '));
			$output .= $atts['before'].single_cat_title('', false).$atts['after'];
		} else if ( is_day() ) {
			$output .= '<a href="'.get_year_link(get_the_time('Y')).'">'.get_the_time('Y').'</a> '.$atts['delimiter'].' ';
			$output .= '<a href="'.get_month_link(get_the_time('Y'),get_the_time('m')).'">'.get_the_time('F').'</a> '.$atts['delimiter'].' ';
			$output .= $atts['before'].get_the_time('d').$atts['after'];
		} else if ( is_month() ) {
			$output .= '<a href="'.get_year_link(get_the_time('Y')).'">'.get_the_time('Y').'</a> '.$atts['delimiter'].' ';
			$output .= $atts['before'].get_the_time('F').$atts['after'];
		} else if ( is_year() ) {
			$output .= $atts['before'].get_the_time('Y').$atts['after'];
		} else if ( is_single() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				$output .= '<a href="'.$atts['home_link'].'/'.$slug['slug'].'/">'.$post_type->labels->singular_name.'</a> '.$atts['delimiter'].' ';
				$output .= $atts['before'].get_the_title().$atts['after'];
			} else {
				$cat = get_the_category(); $cat = $cat[0];
				$output .= get_category_parents($cat, TRUE, ' '.$atts['delimiter'].' ');
				$output .= $atts['before'].get_the_title().$atts['after'];
			}
		} else if ( is_search() ) {
			$output .= $atts['before'].themeblvd_get_local('crumb_search').' "'.get_search_query().'"'.$atts['after'];
		} else if ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
			$post_type = get_post_type_object(get_post_type());
			$output .= $atts['before'].$post_type->labels->singular_name.$atts['after'];
		} else if ( is_attachment() ) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID);
			if( ! empty( $cat ) ) {
				$cat = $cat[0];
				$output .= get_category_parents($cat, TRUE, ' '.$atts['delimiter'].' ');
			}
			$output .= '<a href="'.get_permalink($parent).'">'.$parent->post_title.'</a> '.$atts['delimiter'].' ';
			$output .= $atts['before'].get_the_title().$atts['after'];
		} else if ( is_page() && !$post->post_parent ) {
			$output .= $atts['before'].get_the_title().$atts['after'];
		} else if ( is_page() && $post->post_parent ) {
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="'.get_permalink($page->ID).'">'.get_the_title($page->ID).'</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) $output .= $crumb.' '.$atts['delimiter'].' ';
			$output .= $atts['before'].get_the_title().$atts['after'];
		} else if ( is_tag() ) {
			$output .= $atts['before'].themeblvd_get_local('crumb_tag').' "'.single_tag_title('', false).'"'.$atts['after'];
		} else if ( is_author() ) {
			global $author;
			$userdata = get_userdata($author);
			$output .= $atts['before'].themeblvd_get_local('crumb_author').' '.$userdata->display_name.$atts['after'];
		} else if ( is_404() ) {
		  $output .= $atts['before'].themeblvd_get_local('crumb_404').$atts['after'];
		}
		if ( get_query_var('paged') ) {
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ' (';
			$output .= themeblvd_get_local('page').' '.get_query_var('paged');
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) $output .= ')';
		}
		$output .= '</div><!-- .breadcrumbs-content (end) -->';
		$output .= '</div><!-- .breadcrumbs-inner (end) -->';
		$output .= '</div><!-- #breadcrumbs (end) -->';
		return $output;
	}
}

/**
 * Recent Tweets
 *
 * @since 2.0.0
 *
 * @param string $count Number of tweets to display
 * @param string $username Twitter username to pull tweets from
 * @param string $widget_id Unique ID for widget
 * @param string $time Display time of tweets, yes or no
 * @param string $exclude_replies Exclude replies, yes or no
 * @return string $filtered_tweet Final list of tweets
 */

if( ! function_exists( 'themeblvd_twitter' ) ) {
	function themeblvd_twitter( $count, $username, $widget_id, $time = 'yes', $exclude_replies = 'yes' ) {		

			$filtered_message = null;
			$output = null;
			$iterations = 0;

			// Check for cached tweets
			$tweets = get_transient( $widget_id.'-'.$username );

			// If cache is set, use it, but if not, grab from Twitter.
			if( ! $tweets ) {
				// Grab response from Twitter if no cache
				$raw_response = wp_remote_get( 'http://api.twitter.com/1/statuses/user_timeline.xml?screen_name='.$username );
				if ( ! is_wp_error( $raw_response ) ) {
					// Parse it.
					$twitter_response = simplexml_load_string( $raw_response['body'] );
					// Setup the tweets.
					if( empty( $twitter_response->error ) ) {
				    	if ( isset( $twitter_response->status[0] ) ) {
				    	    $tweets = array();
				    	    foreach ( $twitter_response->status as $tweet ) {
				    	    	if( $iterations == $count ) break;
				    	    	$text = (string) $tweet->text;
				    	    	if( $exclude_replies == 'no' || ( $exclude_replies == 'yes' && $text[0] != "@") ) {
				    	    		$iterations++;
				    	    		$tweets[] = array(
				    	    			'id' => (string)$tweet->id,
				    	    			'text' => themeblvd_twitter_filter( $text ),
				    	    			'created' =>  strtotime( $tweet->created_at ),
				    	    			'user' => array(
				    	    				'name' 			=> (string)$tweet->user->name,
				    	    				'screen_name' 	=> (string)$tweet->user->screen_name,
				    	    				'image' 		=> (string)$tweet->user->profile_image_url,
				    	    				'utc_offset' 	=> (int) $tweet->user->utc_offset[0],
				    	    				'follower' 		=> (int) $tweet->user->followers_count
			    	    				)
				    	    		);
				    			}
				    		}
							// Cache it for next time.
							set_transient( $widget_id.'-'.$username, $tweets, 60*60*3 ); // 3 hour cache
				    	}
				    }
				}
			}
			
			// Start output of tweets
		    if( isset( $tweets[0] ) ) {	
		    	foreach( $tweets as $tweet) {	
		    		$output .= '<li class="tweet">';
		    		$output .= '<div class="tweet-wrap">';
		    		$output .= '<div class="tweet-text">'.$tweet['text'].'</div>';
		    		if( $time == 'yes' ) $output .= '<div class="tweet-time"><a href="http://twitter.com/'.$tweet['user']['screen_name'].'/status/'.$tweet['id'].'" target="_blank">'.date_i18n( get_option('date_format')." - ".get_option('time_format'), $tweet['created'] + $tweet['user']['utc_offset']).'</a></div>';
		    		$output .= '</div><!-- .tweet-wrap (end) -->';
		    		$output .= '</li>';
				}
		    }
		    
			// Filter output
			if( $output )
				$filtered_tweet = '<ul class="tweets">'.$output.'</ul>';
			else
				$filtered_tweet = '<ul class="tweets"><li>'.__( 'No public Tweets found', TB_GETTEXT_DOMAIN ).'</li></ul>';
			
			// Return the output!
			return $filtered_tweet;
	}
}

/** 
 * Responsive wp nav menu 
 *
 * @since 2.0.0
 *
 * @param string $location Location of wp nav menu to grab
 * @return string $select_menu Select menu version of wp nav menu
 */

if( ! function_exists( 'themeblvd_nav_menu_select' ) ) {
	function themeblvd_nav_menu_select( $location ) {
		$select_menu = '';
		$locations = get_nav_menu_locations();
		if( isset( $locations[$location] ) ) {
			$menu = wp_get_nav_menu_object( $locations[$location] );
			if( is_object( $menu ) ) {
				$menu_items = wp_get_nav_menu_items( $menu->term_id );
				if( ! empty( $menu_items ) ) {
					$select_menu .= '<form class="responsive-nav" action="" method="post">';
					$select_menu .= '<select class="tb-jump-menu">';
					$select_menu .= '<option value="">'.themeblvd_get_local('navigation').'</option>';
					foreach( $menu_items as $key => $item )
						$select_menu .= '<option value="'.$item->url.'">'.$item->title.'</option>';
					$select_menu .= '</select>';
					$select_menu .= '</form>';
				}
			}
		}
		return $select_menu;
	}
}

/** 
 * Simple Contact module (primary meant for simple contact widget)
 *
 * @since 2.0.3
 *
 * @param array $args Arguments to be used for the elements
 */

if( ! function_exists( 'themeblvd_get_simple_contact' ) ) {
	function themeblvd_get_simple_contact( $args ) {
		// Setup icon links
		$icons = array();
		$i = 1; // 6 possible icons
		while ( $i <= 6 ) {
			if( isset( $args['link_'.$i.'_url'] ) && $args['link_'.$i.'_url'] )
				$icons[$args['link_'.$i.'_icon']] = $args['link_'.$i.'_url'];
			$i++;
		}
		// Start Output
		$module = '<ul class="simple-contact">';
		// Phone #1
		if( isset( $args['phone_1'] ) && $args['phone_1'] )
			$module .= '<li class="phone">'.$args['phone_1'].'</li>';
		// Phone #2
		if( isset( $args['phone_2'] ) && $args['phone_2'] )
			$module .= '<li class="phone">'.$args['phone_2'].'</li>';
		// Email #1
		if( isset( $args['email_1'] ) && $args['email_1'] )
			$module .= '<li class="email"><a href="mailto:'.$args['email_1'].'">'.$args['email_1'].'</a></li>';
		// Email #2
		if( isset( $args['email_2'] ) && $args['email_2'] )
			$module .= '<li class="email"><a href="mailto:'.$args['email_2'].'">'.$args['email_2'].'</a></li>';
		// Contact Page
		if( isset( $args['contact'] ) && $args['contact'] )
			$module .= '<li class="contact"><a href="'.$args['contact'].'">'.themeblvd_get_local( 'contact_us' ).'</a></li>';
		// Skype
		if( isset( $args['skype'] ) && $args['skype'] )
			$module .= '<li class="skype">'.$args['skype'].'</li>';
		// Social Icons
		if( ! empty( $icons ) ) {
			$module .= '<li class="link"><ul class="icons">';
			foreach( $icons as $icon => $url ) {
				$module .= '<li class="'.$icon.'"><a href="'.$url.'" target="_blank" title="'.ucfirst($icon).'">'.ucfirst($icon).'</a></li>';
			}
			$module .= '</ul></li>';
		}
		$module .= '</ul>';
		return $module;
	}
}