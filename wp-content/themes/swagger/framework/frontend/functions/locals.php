<?php
/**
 * Return user read text strings. This function allows
 * to have all of the framework's common localized text 
 * strings in once place. Also, the following filters can 
 * be used to add/remove strings.
 *
 * themeblvd_frontend_locals
 *
 * @since 2.0.0
 *
 * @param string $id Key for $locals array
 * @return string $text Localized and filtered text string
 */
 
function themeblvd_get_local( $id ) {
	$text = null;
	$locals = array ( 
		'404'						=> __( 'Apologies, but the page you\'re looking for can\'t be found.', TB_GETTEXT_DOMAIN ),
		'404_title'					=> __( '404 Error', TB_GETTEXT_DOMAIN ),
		'archive_no_posts'			=> __( 'Apologies, but there are no posts to display.', TB_GETTEXT_DOMAIN ),
		'archive'					=> __( 'Archive', TB_GETTEXT_DOMAIN ),
		'cancel_reply_link'			=> __( 'Cancel reply', TB_GETTEXT_DOMAIN ),
		'categories'				=> __( 'Categories', TB_GETTEXT_DOMAIN ),
		'category'					=> __( 'Category', TB_GETTEXT_DOMAIN ),
		'comment_navigation'		=> __( 'Comment navigation', TB_GETTEXT_DOMAIN ),
		'comments'					=> __( 'Comments', TB_GETTEXT_DOMAIN ),
		'comments_closed'			=> __( 'Comments are closed.', TB_GETTEXT_DOMAIN ),
		'comments_newer'			=> __( 'Newer Comments &rarr;', TB_GETTEXT_DOMAIN ),
		'comments_no_password'		=> __( 'This post is password protected. Enter the password to view any comments.', TB_GETTEXT_DOMAIN ),
		'comments_older'			=> __( '&larr; Older Comments', TB_GETTEXT_DOMAIN ),
		'comments_title_single'		=> __( 'One comment on &ldquo;%2$s&rdquo;', TB_GETTEXT_DOMAIN ),
		'comments_title_multiple'	=> __( '%1$s comments on &ldquo;%2$s&rdquo;', TB_GETTEXT_DOMAIN ),
		'contact_us'				=> __( 'Contact Us', TB_GETTEXT_DOMAIN ),
		'crumb_404'					=> __( 'Error 404', TB_GETTEXT_DOMAIN ),
		'crumb_author'				=> __( 'Articles posted by', TB_GETTEXT_DOMAIN ),
		'crumb_search'				=> __( 'Search results for', TB_GETTEXT_DOMAIN ),
		'crumb_tag'					=> __( 'Posts tagged', TB_GETTEXT_DOMAIN ),
		'edit_page'					=> __( 'Edit Page', TB_GETTEXT_DOMAIN ),
		'email'						=> __( 'Email', TB_GETTEXT_DOMAIN ),
		'home'						=> __( 'Home', TB_GETTEXT_DOMAIN ),
		'invalid_layout'			=> __( 'Invalid Layout ID', TB_GETTEXT_DOMAIN ),
		'label_submit'				=> __( 'Post Comment', TB_GETTEXT_DOMAIN ),
		'last_30'					=> __( 'The Last 30 Posts', TB_GETTEXT_DOMAIN ),
		'monthly_archives'			=> __( 'Monthly Archives', TB_GETTEXT_DOMAIN ),
		'name'						=> __( 'Name', TB_GETTEXT_DOMAIN ),
		'page'						=> __( 'Page', TB_GETTEXT_DOMAIN ),
		'pages'						=> __( 'Pages', TB_GETTEXT_DOMAIN ),
		'page_num'					=> __( 'Page %s', TB_GETTEXT_DOMAIN ),
		'posts_per_category'		=> __( 'Posts per category', TB_GETTEXT_DOMAIN ),
		'navigation' 				=> __( 'Navigation', TB_GETTEXT_DOMAIN ),
		'no_slider' 				=> __( 'Slider does not exist.', TB_GETTEXT_DOMAIN ),
		'no_slider_selected' 		=> __( 'Oops! You have not selected a slider in your layout.', TB_GETTEXT_DOMAIN ),
		'no_video'					=> __( 'The video url could not retrieve a video.', TB_GETTEXT_DOMAIN ),
		'read_more'					=> __( 'Read More', TB_GETTEXT_DOMAIN ),
		'search'					=> __( 'Search the site...', TB_GETTEXT_DOMAIN ),
		'search_no_results'			=> __( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', TB_GETTEXT_DOMAIN ),
		'tag'						=> __( 'Tag', TB_GETTEXT_DOMAIN ),
		'title_reply'				=> __( 'Leave a Reply', TB_GETTEXT_DOMAIN ),
		'title_reply_to'			=> __( 'Leave a Reply to %s', TB_GETTEXT_DOMAIN ),
		'website'					=> __( 'Website', TB_GETTEXT_DOMAIN )
	);
	// Apply framework's filter
	$locals = apply_filters( 'themeblvd_frontend_locals', $locals );
	// Set text string
	if( isset( $locals[$id] ) )
		$text = $locals[$id];
	return $text;
}