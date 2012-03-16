
= THEME BLVD WORDPRESS FRAMEWORK =

-----------------------------------------------
= Framework File Structure
-----------------------------------------------

* This framework is setup in a way designed for certain files to never be edited. Anything that is a core file should never be edited.

* NOTE: If you're working with this framework in a theme already created, it's best to make ALL edits from a child theme, and never edit any files within the current theme.

- 404.php 				- CORE FILE
- archive.php 			- CORE FILE
- /assets/				- THEME DEPENDENT
- comments.php			- THEME DEPENDENT
- content.php			- THEME DEPENDENT
- content-{type}.php	- THEME DEPENDENT
- footer.php			- CORE FILE
- /framework/			- CORE FILES
- functions.php			- THEME DEPENDENT
- header.php			- CORE FILE
- /includes/			- THEME DEPENDENT
- index.php				- CORE FILE
- page.php				- CORE FILE
- rtl.css				- THEME DEPENDENT
- screenshot.png		- THEME DEPENDENT
- search.php			- CORE FILE
- searchform.php		- THEME DEPENDENT
- single.php			- CORE FILE
- style.css				- THEME DEPENDENT
- template_archives.php	- CORE FILE
- template_builder.php	- CORE FILE
- template_grid.php		- CORE FILE
- template_list.php		- CORE FILE
- template_redirect.php	- CORE FILE
- template_sitemap.php	- CORE FILE

-----------------------------------------------
= Frontend Theme Hooks
-----------------------------------------------

* This framework is designed around everything being edited via hooks and filters. 

* In this section outlines all of the primary frontend actions and the default function that the framework applies. Make sure to check your current theme's functions.php files for any possible edits to the following actions.

* If you dive deeper into the framework's core files, you can find many more actions and filters.

1) HTML Head

	A) themeblvd_title
		- Description: The title tag.
		- Default framework hooked function: themeblvd_title_default

2) Before and after site

	A) themeblvd_before
		- Description: Before the site just after the opening body tag.
		- No default hooked function by framework
	
	B) themeblvd_after
		- Description: After the site just before the closing body tag.
		- No default hooked function by framework

3) Header

	A) themeblvd_header_top
		- Description: The highest location in the header.
		- No default hooked function by framework
		
	B) themeblvd_header_above
		- Description: Location above logo, but below themeblvd_header_top.
		- Default framework hooked function: themeblvd_header_above_default
	
	C) themeblvd_header_content
		- Description: Main content area of the header, default function calls themeblvd_header_logo and themeblvd_header_addon here.
		- Default framework hooked function: themeblvd_header_content_default
	
	D) themeblvd_header_logo
		- Description: Website logo, called in themeblvd_header_content by default.
		- Default framework hooked function: themeblvd_header_logo_default
		
	E) themeblvd_header_addon
		- Description: Additional area within default themeblvd_header_content.
		- No default hooked function by framework
		
	F) themeblvd_header_menu
		- Description: Main menu below content of header.
		- Default framework hooked function: themeblvd_header_content_default
		
	G) themeblvd_header_menu_addon
		- Description: Adds to default themeblvd_header_menu.
		- No default hooked function by framework

4) Featured area
	
	A) themeblvd_featured_start
		- Description: Start HTML markup for featured area.
		- Default framework hooked function: themeblvd_featured_start_default
	
	B) themeblvd_featured_{type}
		- Description: Start content area of featured area
		- Types: archive, blog, page, single
		- No default hooked function by framework
	
	C) themeblvd_featured_end
		- Description: End HTML markup for featured area.
		- Default framework hooked function: themeblvd_featured_end_default 

5) Main content area

	A) themeblvd_main_start
		- Description: Start HTML markup for main area.
		- Default framework hooked function: themeblvd_main_start_default
		
	B) themeblvd_main_top
		- Description: Top of main area, default displays "ad_above_content" widget area.
		- Default framework hooked function: themeblvd_main_top_default
		
	C) themeblvd_main_bottom
		- Description: Bottom of main area, default displays "ad_below_content" widget area.
		- Default framework hooked function: themeblvd_main_bottom_default
		
	D) themeblvd_main_end
		- Description: End HTML markup for main area.
		- Default framework hooked function: themeblvd_main_end_default
		
	E) themeblvd_breadcrumbs
		- Description: Breadcrumbs, called after themeblvd_main_top.
		- Default framework hooked function: themeblvd_breadcrumbs_default
		
	F) themeblvd_before_layout
		- Description: Your last chance to add any content before content and sidebars.
		- No default hooked function by framework

6) Footer

	A) themeblvd_footer_above
		- Description: Before footer starts.
		- No default hooked function by framework
		
	B) themeblvd_footer_content
		- Description: Primary content area of footer, default holds dymanically setup columns from Theme Options.
		- Default framework hooked function: themeblvd_footer_content_default
		
	C) themeblvd_footer_sub_content
		- Description: Area for copyright info.
		- Default framework hooked function: themeblvd_footer_sub_content_default
		
	D) themeblvd_footer_below
		- Description: Bottom of footer area, default displays "ad_below_footer" widget area.
		- Default framework hooked function: themeblvd_footer_below_default

7) Content

	A) themeblvd_content_top
		- Description: Top of content area.
		- No default hooked function by framework
			
	B) themeblvd_blog_meta
		- Description: Meta info for blog post.
		- Default framework hooked function: themeblvd_blog_meta_default
			
	C) themeblvd_blog_tags
		- Description: Tags for blog post.
		- Default framework hooked function: themeblvd_blog_tags_default
			
	D) themeblvd_the_post_thumbnail
		- Description: The post thumbnail for posts. We'd really suggest not messing with this one. :)
		- Default framework hooked function: themeblvd_the_post_thumbnail_default
		
	E) themeblvd_blog_content
		- Description: Shows content area of post in blogroll.
		- Default framework hooked function: themeblvd_blog_content_default

-----------------------------------------------
= Page Templates
-----------------------------------------------

1) Archives - template_archives.php

	* This template file lists the last 30 posts, the categories, and the date archives.

2) Custom Layout - template_builder.php

	* This template file shows custom layouts built with the Layout Builder.

3) Post Grid - template_grid.php

	* This template file shows a grid of posts, which many might refer to as a "Portfolio". 
 	* You can use the following custom fields:
		- categories: Categories to include, category slugs separated by commas (no spaces!), or blank for all categories
		- category_name: Slug of category to include
		- columns: Number of posts per row
		- rows: Number of rows - 2, 3, 4, or 5
		- orderby: post_date, title, comment_count, rand
		- order: DESC, ASC
		- offset: Number of posts to offset off the start, defaults to 0

4) Post List - template_list.php
	
	* This template file shows a grid of posts, which many might refer to as a "Blog". 
 	* You can use the following custom fields:		
		- categories: Categories to include, category slugs separated by commas (no spaces!), or blank for all categories
		- category_name: Slug of category to include
		- orderby: post_date, title, comment_count, rand
		- order: DESC, ASC
		- offset: Number of posts to offset off the start, defaults to 0

5) Redirect - template_redirect.php

	* This template file will redirect the current page to a URL.
	* To use, put the URL of the webpage you want to forward to by itself in the content of the page.

6) Sitemap - template_sitemap.php

	* This template file lists all pages, categories, and posts.

-----------------------------------------------
= Shortcodes
-----------------------------------------------

1) Columns
	
	* This group of shortcodes allow you to setup columns. The last shortcode of a row always needs to have the word "last" and you should end each row with [clear].
	
	* Example Row: 
		[one_third]content...[one_third]
		[one_third]content...[one_third]
		[one_third last]content...[one_third]
		[clear]
	
	* Available column shortcodes:	
		- [one_sixth]
		- [one_fourth]
		- [one_third]
		- [one_half]
		- [two_third]
		- [three_fourth]
		- [one_fifth]
		- [two_fifth]
		- [three_fifth]
		- [four_fifth]
		- [three_tenth]
		- [seven_tenth]
		- [clear]
		
2) HTML

	A) [button]
	
		Required arguments
		- link: The full URL of where you want the button to link
	
		Optional arguments
		- color: default, black, blue, brown, dark_blue, dark_brown, dark_green, green, mauve, orange, pearl, pink, purple, red, slate_grey, silver, steel_blue, teal, yellow, wheat, white
		- target: _self, _blank, or lightbox
		- size: small, medium, large
		- class: Any CSS classes you want to add
		- title: Title of link, will default to button's text
	
	B) [icon]

		Required arguments
		- image: Name of icon's image {image_name}.png located in /framework/frontend/assets/images/icons/
		
		Optional arguments:
		- align: left, right, center, none
		
	C) [box]
		
		Required arguments
		- style: alert, approved, camera, cart, doc, download, media, note, notice, quote, warning
			
	D) [icon_list]
	
		Required arguments
		- style: check, crank, delete, doc, plus, star, star2, warning, write
	
	E) [icon_link]
	
		Required arguments
		- icon: alert, approved, attention, camera, cart, doc, download, media, note, notice, quote
		- link: The full URL of where you want the link to go
		
		Optional arguments
		- target: _self or _blank
		- class: Any CSS classes you want to add
		- title: Title of link, will default to button's text
	
	F) [highlight]
	
		- No arguments -
	
	G) [dropcap]
	
		- No arguments -
	
	H) [divider]
	
		Optional arguments
		-style: dashed, shadow, solid
	
3) Tabs
	
	* Usage Example:
		
	[tabs style="open" tab_1="Tab #1" tab_2="Tab #2" tab_3="Tab #3"]
	[tab_1]First tab content here.[/tab_1]
	[tab_2]Second content here.[/tab_2]
	[tab_3]Third tab content here.[/tab_3]
	[/tabs]
	
4) Toggles

	* Usage Example:
	
	[raw]
	[toggle title="Toggle #1"]This is the content of the toggle.[/toggle]
	[toggle title="Toggle #2"]This is the content of the toggle.[/toggle]
	[toggle title="Toggle #3"]This is the content of the toggle.[/toggle]
	[toggle title="Toggle #4"]This is the content of the toggle.[/toggle]
	[toggle title="Toggle #5"]This is the content of the toggle.[/toggle]
	[/raw]

5) Sliders
	
	A) [slider]

		Required arguments
		- id: ID of your custom-built slider
	
	B) [post_grid_slider]
	
		Optional arguments
		- fx: Transition of slider - fade, slide
		- timeout: Seconds in between transitions, 0 for no auto-advancing
		- nav_standard: Show standard nav dots to control slider - true or false
		- nav_arrows: Show directional arrows to control slider - true or false
		- pause_play: Show pause/play button - true or false
		- categories: Categories to include, category slugs separated by commas (no spaces!), or blank for all categories
		- columns: Number of posts per row - 2, 3, 4, or 5
		- rows: Number of rows per slide
		- numberposts: Total number of posts, -1 for all posts
		- orderby: post_date, title, comment_count, rand
		- order: DESC, ASC
		- offset: Number of posts to offset off the start, defaults to 0
	
	C) [post_list_slider]

		Optional arguments
		 - fx: Transition of slider - fade, slide
		 - timeout: Seconds in between transitions, 0 for no auto-advancing
		 - nav_standard: Show standard nav dots to control slider - true or false
		 - nav_arrows: Show directional arrows to control slider - true or false
		 - pause_play: Show pause/play button - true or false
		 - categories: Categories to include, category slugs separated by commas, or blank for all categories
		 - thumbs: Size of post thumbnails - default, small, full, hide
		 - post_content: Show excerpts or full content - default, content, excerpt
		 - posts_per_slide: Number of posts per slide.
		 - numberposts: Total number of posts, -1 for all posts
		 - orderby: post_date, title, comment_count, rand
		 - order: DESC, ASC
		 - offset: number of posts to offset off the start, defaults to 0

6) Display Posts
	
	A) [post_grid]
	
		Optional arguments
		- categories: Categories to include, category slugs separated by commas (no spaces!), or blank for all categories
		- columns: Number of posts per row - 2, 3, 4, or 5
		- rows: Number of rows
		- orderby: post_date, title, comment_count, rand
		- order: DESC, ASC
		- offset: Number of posts to offset off the start, defaults to 0
		- link: Show link after posts, true or false
		- link_text: Text for the link
		- link_url: URL where link should go
		- link_target: Where link opens - _self, _blank
	
	B) [post_list]
	
		Optional arguments
		- categories: Categories to include, category slugs separated by commas (no spaces!), or blank for all categories
		- thumbs: Size of post thumbnails - default, small, full, hide
		- post_content: Show excerpts or full content - default, content, excerpt
		- numberposts: Total number of posts, -1 for all posts
		- orderby: post_date, title, comment_count, rand
		- order: DESC, ASC
		- offset: Number of posts to offset off the start, defaults to 0
		- link: Show link after posts, true or false
		- link_text: Text for the link
		- link_url: URL where link should go
		- link_target: Where link opens - _self, _blank

