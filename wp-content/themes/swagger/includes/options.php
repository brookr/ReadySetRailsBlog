<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

if( ! function_exists( 'optionsframework_option_name' ) ) {
	function optionsframework_option_name() {
	
		// This gets the theme name from the stylesheet (lowercase and without spaces)
		$themename = get_theme_data(STYLESHEETPATH . '/style.css');
		$themename = $themename['Name'];
		$themename = preg_replace('/\W/', '', strtolower($themename) );
		
		$optionsframework_settings = get_option('optionsframework');
		$optionsframework_settings['id'] = $themename;
		
		update_option('optionsframework', $optionsframework_settings);
		
		// echo $themename;
	}
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *  
 */

if( ! function_exists( 'optionsframework_options' ) ) {
	function optionsframework_options() {
	
		$options = array();
	
		// If using image radio buttons, define a directory path
		$imagepath =  get_template_directory_uri() . '/framework/admin/assets/images/';
	
		// Generate sidebar layout options
		$sidebar_layouts = array();
		$layouts = themeblvd_sidebar_layouts();
		foreach( $layouts as $layout )
			$sidebar_layouts[$layout['id']] = $imagepath.'layout-'.$layout['id'].'.png';
		
		// Generate sliders options
		$custom_sliders = array();
		$sliders = get_posts('post_type=tb_slider&numberposts=-1');
		if( ! empty( $sliders ) ) {
			foreach( $sliders as $slider )
				$custom_sliders[$slider->post_name] = $slider->post_title;
		} else {
			$custom_sliders['null'] = __( 'You haven\'t created any custom sliders yet.', TB_GETTEXT_DOMAIN );
		}		
		
		// Pull all the categories into an array
		$options_categories = array();  
		$options_categories_obj = get_categories();
		foreach ($options_categories_obj as $category) {
	    	$options_categories[$category->cat_ID] = $category->cat_name;
		}
		
		// Custom Layouts
		$custom_layouts = array();
		$custom_layout_posts = get_posts('post_type=tb_layout&numberposts=-1');
		if( ! empty( $custom_layout_posts ) ) {
			foreach( $custom_layout_posts as $layout )
				$custom_layouts[$layout->post_name] = $layout->post_title;
		} else {
			$custom_layouts['null'] = __( 'You haven\'t created any custom layouts yet.', TB_GETTEXT_DOMAIN );
		}
		
		/*-------------------------------------------------------*/
		/* Styles
		/*-------------------------------------------------------*/
		
		$options[] = array( 'name' 		=> __( 'Styles', TB_GETTEXT_DOMAIN ),
							'type' 		=> 'heading');
		
		// Main Styles
		
		$options[] = array( 'name' 		=> __( 'Main', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'type' 		=> 'section_start');
		
		$options[] = array(	'name' 		=> __( 'Primary Color', TB_GETTEXT_DOMAIN ),
							'desc'		=> __( 'Select the primary color. This color makes up the background of the site.', TB_GETTEXT_DOMAIN ),
							'id'		=> 'primary_color',
							'std'		=> 'primary_light',
							'type' 		=> 'select',
							'options'	=> array(
								'primary_blue' 			=> __( 'Blue', TB_GETTEXT_DOMAIN ),
								'primary_brown' 		=> __( 'Brown', TB_GETTEXT_DOMAIN ),
								'primary_dark' 			=> __( 'Dark', TB_GETTEXT_DOMAIN ),
								'primary_light' 		=> __( 'Light', TB_GETTEXT_DOMAIN ),
								'primary_light_blue' 	=> __( 'Light Blue', TB_GETTEXT_DOMAIN ),
								'primary_light_orange' 	=> __( 'Light Orange', TB_GETTEXT_DOMAIN ),
								'primary_midnight_blue' => __( 'Midnight Blue', TB_GETTEXT_DOMAIN ),
								'primary_mint' 			=> __( 'Mint', TB_GETTEXT_DOMAIN ),
								'primary_orange' 		=> __( 'Orange', TB_GETTEXT_DOMAIN ),
								'primary_purple' 		=> __( 'Purple', TB_GETTEXT_DOMAIN ),
								'primary_red' 			=> __( 'Red', TB_GETTEXT_DOMAIN ),
								'primary_tan' 			=> __( 'Tan', TB_GETTEXT_DOMAIN )
							) );
		
		$options[] = array(	'name' 		=> __( 'Content Color', TB_GETTEXT_DOMAIN ),
							'desc'		=> __( 'Select the content color. This color will be used as the background in the primary content blocks.', TB_GETTEXT_DOMAIN ),
							'id'		=> 'content_color',
							'std'		=> 'content_light',
							'type' 		=> 'select',
							'options'	=> array(
								'content_blue' 			=> __( 'Blue', TB_GETTEXT_DOMAIN ),
								'content_brown' 		=> __( 'Brown', TB_GETTEXT_DOMAIN ),
								'content_dark' 			=> __( 'Dark', TB_GETTEXT_DOMAIN ),
								'content_light' 		=> __( 'Light', TB_GETTEXT_DOMAIN ),
								'content_light_blue' 	=> __( 'Light Blue', TB_GETTEXT_DOMAIN ),
								'content_light_orange' 	=> __( 'Light Orange', TB_GETTEXT_DOMAIN ),
								'content_midnight_blue' => __( 'Midnight Blue', TB_GETTEXT_DOMAIN ),
								'content_mint' 			=> __( 'Mint', TB_GETTEXT_DOMAIN ),
								'content_orange' 		=> __( 'Orange', TB_GETTEXT_DOMAIN ),
								'content_purple' 		=> __( 'Purple', TB_GETTEXT_DOMAIN ),
								'content_red' 			=> __( 'Red', TB_GETTEXT_DOMAIN ),
								'content_tan' 			=> __( 'Tan', TB_GETTEXT_DOMAIN )
							) );
		
		$options[] = array( 'name' 		=> __( 'Accent Color', TB_GETTEXT_DOMAIN ),
							'desc' 		=> __( 'This color gets applied in a few subtle, but featured areas throughout the theme.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'accent_color',
							'std' 		=> '#f98123', // Also a cool color => #f84444
							'type' 		=> 'color' );
		
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */
		
		// Links
		
		$options[] = array( 'name' 		=> __( 'Links', TB_GETTEXT_DOMAIN ),
							'type' 		=> 'section_start');
		
		$options[] = array( 'name' 		=> __( 'Link Color', TB_GETTEXT_DOMAIN ),
							'desc' 		=> __( 'Choose the color you\'d like applied to links.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'link_color',
							'std' 		=> '#f98123',
							'type' 		=> 'color' );
							
		$options[] = array( 'name' 		=> __( 'Link Hover Color', TB_GETTEXT_DOMAIN ),
							'desc' 		=> __( 'Choose the color you\'d like applied to links when they are hovered over.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'link_hover_color',
							'std' 		=> '#703b12',
							'type' 		=> 'color' );
		
		$options[] = array( 'type'		=> 'section_end');
		
		// Typography
		
		$options[] = array( 'name' 		=> __( 'Typography', TB_GETTEXT_DOMAIN ),
							'type' 		=> 'section_start');
							
		$options[] = array( 'name' 		=> __( 'Primary Font', TB_GETTEXT_DOMAIN ),
							'desc' 		=> __( 'This applies to most of the text on your site.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'typography_body',
							'std' 		=> array('size' => '12px','face' => 'arial','color' => '', 'google' => ''),
							'atts'		=> array('size', 'face'),
							'type' 		=> 'typography');
							
		$options[] = array( 'name' 		=> __( 'Header Font', TB_GETTEXT_DOMAIN ),
							'desc' 		=> __( 'This applies to all of the primary headers throughout your site (h1, h2, h3, h4, h5, h6). This would include header tags used in redundant areas like widgets and the content of posts and pages.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'typography_header',
							'std' 		=> array('size' => '','face' => 'helvetica','color' => '', 'google' => ''),
							'atts'		=> array('face'),
							'type' 		=> 'typography' );
							
		$options[] = array( 'name' 		=> __( 'Special Font', TB_GETTEXT_DOMAIN ),
							'desc' 		=> __( 'It can be kind of overkill to select a super fancy font for the previous option, but here is where you can go crazy. There are a few special areas in this theme where this font will get used. The most noticeable place you will see this font is the main title at the top of your pages and posts.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'typography_special',
							'std' 		=> array('size' => '','face' => 'google','color' => '', 'google' => 'Sansita One'),
							'atts'		=> array('face'),
							'type' 		=> 'typography' );
		
		$options[] = array( 'type'		=> 'section_end');
		
		// Custom
							
		$options[] = array( 'name' 		=> __( 'Custom', TB_GETTEXT_DOMAIN ),
							'type' 		=> 'section_start');
		
		$options[] = array( 'name' 		=> __( 'Custom CSS', TB_GETTEXT_DOMAIN ),
							'desc' 		=> __( 'If you have some minor CSS changes, you can put them here to override the theme\'s default styles. However, if you plan to make a lot of CSS changes, it would be best to create a child theme.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'custom_styles',
							'type'		=> 'textarea');
		
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */	
		
		/*-------------------------------------------------------*/
		/* Layout
		/*-------------------------------------------------------*/
		
		$options[] = array( 'name' 		=> 'Layout', /* Required by Framework */
							'type' 		=> 'heading');
		
		// Header
		
		$options[] = array( 'name' 		=> 'Header', /* Required by Framework */	
							'type' 		=> 'section_start');
		
		$options[] = array( 'name' 		=> 'Logo', /* Required by Framework */
							'desc' 		=> __( 'Configure the primary branding logo for the header of your site.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'logo',
							'std' 		=> array( 'type' => 'image', 'image' => 'http://themeblvd.com/demo/assets/swagger/logo.png' ),
							'type' 		=> 'logo');
							
		$options[] = array( 'name' 		=> 'Social Media Buttons',
							'desc' 		=> __( 'Configure the social media buttons you\'d like to show in the header of your site. Check the buttons you\'d like to use and then input the full URL you\'d like the button to link to in the corresponding text field that appears.<br><br>Example: http://twitter.com/jasonbobich<br><br><em>Note: On the "Email" button, if you want it to link to an actual email address, you would input it like this:<br><br><strong>mailto:you@youremail.com</strong></em><br><br><em>Note: If you\'re using the RSS button, your default RSS feed URL is:<br><br><strong>'.get_feed_link( 'feed' ).'</strong></em>', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'social_media',
							'std' 		=> array( 'twitter' => 'http://twitter.com/jasonbobich', 'facebook' => 'http://facebook.com/jasonbobich', 'google' => 'https://plus.google.com/116531311472104544767/posts', 'rss' => get_bloginfo('rss_url') ),
							'type' 		=> 'social_media');
							
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */
		
		// Main
		
		$options[] = array( 'name' 		=> __( 'Main', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'type' 		=> 'section_start');
		
		$options[] = array(	'name' 		=> __( 'Breadcrumbs', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc'		=> __( 'Select whether you\'d like breadcrumbs to show throughout the site or not.', TB_GETTEXT_DOMAIN ),
							'id'		=> 'breadcrumbs',
							'std'		=> 'show',
							'type' 		=> 'select',
							'options'	=> array(
								'show' => __( 'Yes, show breadcrumbs.', TB_GETTEXT_DOMAIN ),
								'hide' => __( 'No, hide breadcrumbs.', TB_GETTEXT_DOMAIN )
							) );
	
		
		$options[] = array( 'name' 		=> __( 'Default Sidebar Layout', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Choose the default sidebar layout for the main content area of your site.<br><br><em>Note: This will be the default sidebar layout throughout your site, but you can be override this setting for any specific page or custom layout.</em>', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'sidebar_layout',
							'std' 		=> 'sidebar_right',
							'type' 		=> 'images',
							'options' 	=> $sidebar_layouts);	
		
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */
		
		// Footer
					
		$options[] = array( 'name' 		=> __( 'Footer', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'type' 		=> 'section_start');
			
		$options[] = array( 'type'		=> 'subgroup_start', /* Required by Framework */
		    				'class'		=> 'columns');
	
		$options[] = array( 'name'		=> __( 'Setup Columns', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc'		=> __( 'Choose the number of columns along with the corresponding width configurations.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'footer_setup',
							'type'		=> 'columns',
							'options'	=> 'standard');
			
		$options[] = array( 'name'		=> __( 'Footer Column #1', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc'		=> __( 'Configure the content for the first column.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'footer_col_1',
							'type'		=> 'content',
							'class'		=> 'col_1',
							'options'	=> array( 'widget', 'page', 'raw' ) );
		
		$options[] = array( 'name'		=> __( 'Footer Column #2', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc'		=> __( 'Configure the content for the second column.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'footer_col_2',
							'type'		=> 'content',
							'class'		=> 'col_2',
							'options'	=> array( 'widget', 'page', 'raw' ) );
		
		$options[] = array( 'name'		=> __( 'Footer Column #3', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc'		=> __( 'Configure the content for the third column.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'footer_col_3',
							'type'		=> 'content',
							'class'		=> 'col_3',
							'options'	=> array( 'widget', 'page', 'raw' ) );
		
		$options[] = array( 'name'		=> __( 'Footer Column #4', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc'		=> __( 'Configure the content for the fourth column.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'footer_col_4',
							'type'		=> 'content',
							'class'		=> 'col_4',
							'options'	=> array( 'widget', 'page', 'raw' ) );
		
		$options[] = array( 'name'		=> __( 'Footer Column #5', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc'		=> __( 'Configure the content for the fifth column.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'footer_col_5',
							'type'		=> 'content',
							'class'		=> 'col_5',
							'options'	=> array( 'widget', 'page', 'raw' ) );
		
		$options[] = array( 'type'		=> 'subgroup_end'); /* Required by Framework */
	
		$options[] = array( 'name' 		=> __( 'Footer Copyright Text', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Enter the copyright text you\'d like to show in the footer of your site.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'footer_copyright',
							'std' 		=> '(c) '.date('Y').' '.get_bloginfo('site_title').' - Web Design by <a href="http://www.jasonbobich.com" target="_blank">Jason Bobich</a>',
							'type' 		=> 'text');
		
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */
		
		/*-------------------------------------------------------*/
		/* Content
		/*-------------------------------------------------------*/
							
		$options[] = array( 'name' 		=> __( 'Content', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'type' 		=> 'heading');
		
		// Homepage
		
		$options[] = array( 'name' 		=> __( 'Homepage', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'type' 		=> 'section_start');
		
		
		$options[] = array( 'name' 		=> __( 'Homepage Content', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Select the content you\'d like to show on your homepage. Note that for this setting to take effect, you must go to Settings > Reading > Frontpage displays, and select "your latest posts."', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'homepage_content',
							'std' 		=> 'posts',
							'type' 		=> 'radio',
							'options' 	=> array(
								'posts'			=> __( 'Posts', TB_GETTEXT_DOMAIN ),
								'custom_layout' => __( 'Custom Layout', TB_GETTEXT_DOMAIN )
							) );
							
		$options[] = array( 'name' 		=> __( 'Select Custom Layout', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Select from the custom layouts you\'ve built under the <a href="admin.php?page=builder_blvd">Builder</a> section.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'homepage_custom_layout',
							'std' 		=> '',
							'type' 		=> 'select',
							'options' 	=> $custom_layouts);
		
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */
		
		// Single Posts
		
		$options[] = array( 'name' 		=> __( 'Single Posts', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'type' 		=> 'section_start');
		
		$options[] = array( 'name' 		=> __( 'Show meta information at top of posts?', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Select if you\'d like the meta information (date posted, author, etc) to show at the top of the post. If you\'re going for a portfolio-type setup, you may want to hide the meta info.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'single_meta',
							'std' 		=> 'show',
							'type' 		=> 'radio',
							'options' 	=> array(
								'show'		=> __( 'Show meta info.', TB_GETTEXT_DOMAIN ),
								'hide' 		=> __( 'Hide meta info.', TB_GETTEXT_DOMAIN )
							) );
							
		$options[] = array( 'name' 		=> __( 'Show featured images at top of posts?', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Choose how you want your featured images to show at the top of the posts. It can be useful to turn this off if you want to have featured images over on your blogroll or post grid sections, but you don\'t want them to show on the actual posts themeselves.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'single_thumbs',
							'std' 		=> 'small',
							'type' 		=> 'radio',
							'options' 	=> array(
								'small'		=> __( 'Show small thumbnails.', TB_GETTEXT_DOMAIN ),
								'full' 		=> __( 'Show full-width thumbnails.', TB_GETTEXT_DOMAIN ),
								'hide' 		=> __( 'Hide thumbnails.', TB_GETTEXT_DOMAIN )
							) );
							
		$options[] = array( 'name' 		=> __( 'Show comments below posts?', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Select if you\'d like to completely hide comments or not below the post.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'single_comments',
							'std' 		=> 'show',
							'type' 		=> 'radio',
							'options' 	=> array(
								'show'		=> __( 'Show comments.', TB_GETTEXT_DOMAIN ),
								'hide' 		=> __( 'Hide comments.', TB_GETTEXT_DOMAIN )
							) );
		
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */
		
		// Blog
		
		$options[] = array( 'name' 		=> __( 'Primary Posts Display', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc'		=> __( 'These settings apply to your primary posts page that you\'ve selected under Settings > Reading and <strong>all</strong> instances of the "Post List" page template. Note that if you want to use the post list page template for multiple pages with different categories on each, you can accomplish this on each specific page with custom fields - <a href="http://vimeo.com/32754998">Learn More</a>.', TB_GETTEXT_DOMAIN ),
							'type' 		=> 'section_start');
		
		$options[] = array( 'name' 		=> __( 'Featured Images', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Select the size of the blog\'s post thumbnail or whether you\'d like to hide them all together when posts are listed.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'blog_thumbs',
							'std' 		=> 'small',
							'type' 		=> 'radio',
							'options' 	=> array(
								'small'		=> __( 'Show small thumbnails.', TB_GETTEXT_DOMAIN ),
								'full' 		=> __( 'Show full-width thumbnails.', TB_GETTEXT_DOMAIN ),
								'hide' 		=> __( 'Hide thumbnails.', TB_GETTEXT_DOMAIN )
							) );
		
		$options[] = array( 'name' 		=> __( 'Show excerpts or full content?', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'blog_content',
							'std' 		=> 'excerpt',
							'type' 		=> 'radio',
							'options' 	=> array(
								'content'	=> __( 'Show full content.', TB_GETTEXT_DOMAIN ),
								'excerpt' 	=> __( 'Show excerpt only.', TB_GETTEXT_DOMAIN )
							) );
		
		$options[] = array( 'name' 		=> __( 'Exclude Categories', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Select any categories you\'d like to be excluded from your blog.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'blog_categories',
							'type' 		=> 'multicheck',
							'options' 	=> $options_categories);
							
		$options[] = array( 'type'		=> 'subgroup_start', /* Required by Framework */
	    					'class'		=> 'show-hide');
		
		$options[] = array( 'name'		=> __( 'Featured Area', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc'		=> __( 'Show slider above blog?', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'blog_featured',
							'type'		=> 'checkbox',
							'class'		=> 'trigger');
								
		$options[] = array( 'name'		=> __( 'Featured Slider', TB_GETTEXT_DOMAIN ),
							'desc'		=> __( 'Select a slider from you custom-made sliders. Sliders are created <a href="admin.php?page=slider_blvd" target="_blank">here</a>.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'blog_slider',
							'type'		=> 'select',
							'options'	=> $custom_sliders,
							'class'		=> 'hide receiver');	
			
		$options[] = array( 'type'		=> 'subgroup_end'); /* Required by Framework */
		
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */
		
		// Archives
		
		$options[] = array( 'name' 		=> __( 'Archives', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc'		=> __( 'These settings apply any time you\'re viewing search results or posts specific to a category, tag, date, author, etc.', TB_GETTEXT_DOMAIN ),
							'type' 		=> 'section_start');
							
		$options[] = array( 'name' 		=> __( 'Show title on archive pages?', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Choose whether or not you want the title to show on tag archives, category archives, date archives, author archives and search result pages.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'archive_title',
							'std' 		=> 'true',
							'type' 		=> 'radio',
							'options' 	=> array(
								'true'	=> __( 'Yes, show main title at the top of archive pages.', TB_GETTEXT_DOMAIN ), /* Required by Framework */
								'false' => __( 'No, hide the title.', TB_GETTEXT_DOMAIN )
							) );
		
		$options[] = array( 'name' 		=> __( 'Show featured images on archive pages?', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Choose whether or not you want featured images to show on tag archives, category archives, date archives, author archives and search result pages.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'archive_thumbs',
							'std' 		=> 'hide',
							'type' 		=> 'radio',
							'options' 	=> array(
								'small'		=> __( 'Show small thumbnails.', TB_GETTEXT_DOMAIN ),
								'full' 		=> __( 'Show full-width thumbnails.', TB_GETTEXT_DOMAIN ),
								'hide' 		=> __( 'Hide thumbnails.', TB_GETTEXT_DOMAIN )
							) );
	
		$options[] = array( 'name' 		=> __( 'Show excerpts or full content?', TB_GETTEXT_DOMAIN ), /* Required by Framework */
							'desc' 		=> __( 'Choose whether you want to show full content or post excerpts only.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'archive_content',
							'std' 		=> 'excerpt',
							'type' 		=> 'radio',
							'options' 	=> array(
								'content'	=> __( 'Show full content.', TB_GETTEXT_DOMAIN ),
								'excerpt' 	=> __( 'Show excerpt only.', TB_GETTEXT_DOMAIN )
							) );
							
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */	
		
		/*-------------------------------------------------------*/
		/* Configuration
		/*-------------------------------------------------------*/
							
		$options[] = array( 'name' 	=> 'Configuration',
							'type' 	=> 'heading');
		
		$options[] = array( 'name' 		=> __( 'Responsiveness', TB_GETTEXT_DOMAIN ), /* Required by Framework */	
							'type' 		=> 'section_start');
							
		$options[] = array( 'name' 		=> __( 'Tablets and Mobile Devices', TB_GETTEXT_DOMAIN ), /* Required by Framework */	
							'desc' 		=> __( 'This theme comes with a special stylesheet that will target the screen resolution of your website vistors and show them a slightly modified design if their screen resolution matches common sizes for a tablet or a mobile device.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'responsive_css',
							'std' 		=> 'true',
							'type' 		=> 'radio',
							'options' 	=> array(
								'true'		=> __( 'Yes, apply special styles to tablets and mobile devices.', TB_GETTEXT_DOMAIN ),
								'false' 	=> __( 'No, allow website to show normally on tablets and mobile devices.', TB_GETTEXT_DOMAIN )
							) );
		
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */
							
		$options[] = array( 'name' 		=> __( 'Analytics', TB_GETTEXT_DOMAIN ), /* Required by Framework */	
							'type' 		=> 'section_start');
							
		$options[] = array( 'name' 		=> __( 'Analytics Code', TB_GETTEXT_DOMAIN ), /* Required by Framework */	
							'desc' 		=> __( 'Paste in the code provided by your Analytics service.<br><br>If you\'re looking for a free analytics service, definitely check out <a href="http://www.google.com/analytics/">Google Analytics</a>.', TB_GETTEXT_DOMAIN ),
							'id' 		=> 'analytics',
							'type' 		=> 'textarea');
		
		$options[] = array( 'type'		=> 'section_end'); /* Required by Framework */	
		
		return $options;
	}
}