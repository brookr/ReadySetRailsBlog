<?php
/**
 * Setup information for all framework's built-in sidebars.
 *
 * Within the framework, there are sidebars and sidebar "locations." A
 * sidebar location is a location supported by the current theme 
 * where a widgetized area will be displayed. This original concept 
 * was designed to be similar to Joomla "module positions" but has 
 * since evolved a bit.
 *
 * Users can create their own custom sidebars for different locations 
 * from the admin panel, and then assign them to different areas of the 
 * website. However for every location there must always be a default 
 * sidebar in place if the user hasn't created an overriding one. So 
 * for each of the framework's sidebar locations you will also find the 
 * setup for a default sidebar, which are all setup in this file. 
 *
 * When a user creates a sidebar, it can either be set to a particular 
 * "location" or it can be set as a "floating sidebar." A floating 
 * sidebar can be inserted into areas that aren't default locations, 
 * like the setup of a set of columns in the layotu builder, for example.
 *
 * Here are the plugin's current sidebar locations: 
 *
 * (1) sidebar_left
 * (2) sidebar_right
 * (3) ad_above_header
 * (4) ad_header
 * (5) ad_above_content
 * (6) ad_below_content
 * (7) ad_below_footer
 *
 * @since 2.0.0
 */

function themeblvd_get_sidebar_locations() {

	$sidebars = array(
	
		// Default Left Sidebar
		'sidebar_left' => array(
			'type' => 'fixed',
			'location'	=> array(
				'name' 	=> __( 'Left Sidebar', TB_GETTEXT_DOMAIN ),
				'id' 	=> 'sidebar_left'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'sidebar_left'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Left Sidebar', TB_GETTEXT_DOMAIN ),
			    'description' 	=> __( 'This is default placeholder for the "Left Sidebar" location.', TB_GETTEXT_DOMAIN ),
			    'id' 			=> 'sidebar_left',
			    'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' 	=> '</div></aside>',
				'before_title' 	=> '<h3 class="widget-title">',
				'after_title' 	=> '</h3>'
			)
		),
		
		// Default Right Sidebar
		'sidebar_right' => array(
			'type' => 'fixed',
			'location' => array(
				'name' 	=> __( 'Right Sidebar', TB_GETTEXT_DOMAIN ),
				'id'	=> 'sidebar_right'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'sidebar_right'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Right Sidebar', TB_GETTEXT_DOMAIN ),
			    'description' 	=> __( 'This is default placeholder for the "Right Sidebar" location.', TB_GETTEXT_DOMAIN ),
			    'id' 			=> 'sidebar_right',
			    'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' 	=> '</div></aside>',
				'before_title' 	=> '<h3 class="widget-title">',
				'after_title' 	=> '</h3>'
			)
		),
		
		// Default Ad Space - Above Header
		'ad_above_header' => array(
			'type' => 'collapsible',
			'location' => array(
				'name' 	=> __( 'Ads Above Header', TB_GETTEXT_DOMAIN ),
				'id'	=> 'ad_above_header'
			),		
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'ad_above_header'
				)
			),
			'args' => array(		
			    'name' 			=> __( 'Location: Ads Above Header', TB_GETTEXT_DOMAIN ),
			    'description' 	=> __( 'This is default placeholder for the "Ads Above Header" location, which is designed for banner ads, and so not all widgets will appear as expected.', TB_GETTEXT_DOMAIN ),
			    'id' 			=> 'ad_above_header',
			    'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' 	=> '</div></aside>',
				'before_title' 	=> '<h3 class="widget-title">',
				'after_title' 	=> '</h3>'
			)
		),
		
		// Default Ad Space - Header
		'ad_header' => array(
			'type' => 'collapsible',
			'location' => array(		
				'name' 	=> __( 'Ads in Header', TB_GETTEXT_DOMAIN ),
				'id'	=> 'ad_header'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'ad_header'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Ads in Header', TB_GETTEXT_DOMAIN ),
			    'description' 	=> __( 'This is default placeholder for the "Ads Header" location, which is designed for banner ads, and so not all widgets will appear as expected.', TB_GETTEXT_DOMAIN ),
			    'id' 			=> 'ad_header',
			    'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' 	=> '</div></aside>',
				'before_title' 	=> '<h3 class="widget-title">',
				'after_title' 	=> '</h3>'		
			)
		),
		
		// Default Ad Space - Above Content
		'ad_above_content' => array(
			'type' => 'collapsible',
			'location' => array(
				'name' 	=> __( 'Ads Above Content', TB_GETTEXT_DOMAIN ),
				'id'	=> 'ad_above_content'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'ad_above_content'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Ads Above Content', TB_GETTEXT_DOMAIN ),
			    'description' 	=> __( 'This is default placeholder for the "Ads Above Content" location, which is designed for banner ads, and so not all widgets will appear as expected.', TB_GETTEXT_DOMAIN ),
			    'id' 			=> 'ad_above_content',
			    'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' 	=> '</div></aside>',
				'before_title' 	=> '<h3 class="widget-title">',
				'after_title' 	=> '</h3>'
			)
		),
	
		// Default Ad Space - Below Content
		'ad_below_content' => array(
			'type' => 'collapsible',
			'location' => array(
				'name' 	=> __( 'Ads Below Content', TB_GETTEXT_DOMAIN ),
				'id'	=> 'ad_below_content'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'ad_below_content'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Ads Below Content', TB_GETTEXT_DOMAIN ),
			    'description' 	=> __( 'This is default placeholder for the "Ads Below Content" location, which is designed for banner ads, and so not all widgets will appear as expected.', TB_GETTEXT_DOMAIN ),
			    'id' 			=> 'ad_below_content',
			    'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' 	=> '</div></aside>',
				'before_title' 	=> '<h3 class="widget-title">',
				'after_title' 	=> '</h3>'	
			)
		),
	
		// Default Ad Space - Below Footer
		'ad_below_footer' => array(
			'type' => 'collapsible',
			'location' => array(
				'name' 	=> __( 'Ads Below Footer', TB_GETTEXT_DOMAIN ),
				'id'	=> 'ad_below_footer'
			),
			'assignments' => array(
				'default' => array(
					'type' 			=> 'default',
					'id' 			=> null,
					'name' 			=> 'Everything',
					'sidebar_id' 	=> 'ad_below_footer'
				)
			),
			'args' => array(
			    'name' 			=> __( 'Location: Ads Below Footer', TB_GETTEXT_DOMAIN ),
			    'description' 	=> __( 'This is default placeholder for the "Ads Below Footer" location, which is designed for banner ads, and so not all widgets will appear as expected.', TB_GETTEXT_DOMAIN ),
			    'id' 			=> 'ad_below_footer',
			    'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' 	=> '</div></aside>',
				'before_title' 	=> '<h3 class="widget-title">',
				'after_title' 	=> '</h3>'
			)
		)
	);
	return apply_filters( 'themeblvd_sidebar_locations', $sidebars );
}

/**
 * Register default and custom sidebars.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_register_sidebars' ) ) {
	function themeblvd_register_sidebars() {
		// Register default sidebars
		$default_sidebars = themeblvd_get_sidebar_locations();
		foreach( $default_sidebars as $sidebar ) {
			register_sidebar( $sidebar['args'] );
		}
		// Register custom sidebars
		$custom_sidebars = get_posts( 'post_type=tb_sidebar&numberposts=-1&order=ASC' );
		foreach( $custom_sidebars as $sidebar ) {
			$args = array(
				'name' 			=> __( 'Custom: ', TB_GETTEXT_DOMAIN ).$sidebar->post_title,
			    'id' 			=> $sidebar->post_name,
			    'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
				'after_widget' 	=> '</div></aside>',
				'before_title' 	=> '<h3 class="widget-title">',
				'after_title' 	=> '</h3>'
			);
			$location = get_post_meta( $sidebar->ID, 'location', true );
			if( $location && $location != 'floating' )
				$args['description'] = sprintf( esc_attr__( 'This is a custom widget area to replace the %s on its assigned pages.', TB_GETTEXT_DOMAIN ), themeblvd_get_sidebar_location_name( $location ) );
			else
				$args['description'] = __( 'This is a custom floating widget area. ', TB_GETTEXT_DOMAIN );
			register_sidebar( $args );
		}
	}
}

/**
 * Get the user friendly name for a sidebar location.
 *
 * @since 2.0.0
 * 
 * @param string $location ID of sidebar location
 * @return string $name name of sidebar location
 */
 
function themeblvd_get_sidebar_location_name( $location ) {
	$sidebars = themeblvd_get_sidebar_locations();
	if( isset( $sidebars[$location]['location']['name']) )
		return $sidebars[$location]['location']['name'];
	return __( 'Floating Widget Area', TB_GETTEXT_DOMAIN );
}

/**
 * Retrieve current sidebar ID for a location.
 *
 * @since 2.0.0
 * 
 * @param string $location the location for the sidebar
 * @return string $id the id of the sidebar that should be shown
 */

function themeblvd_get_sidebar_id( $location ) {
	
	// Innitiate assignments
	$assignments = array();
	
	// Get all the sidebars for this location and create
	// a single array of just their assignments
	$custom_sidebars = get_posts( 'post_type=tb_sidebar&numberposts=-1' );
	if( $custom_sidebars ) {
    	foreach( $custom_sidebars as $sidebar ){
    		if( $location == get_post_meta( $sidebar->ID, 'location', true ) ) {
    			$current_assignments = get_post_meta( $sidebar->ID, 'assignments', true );
    			if( is_array( $current_assignments ) && ! empty ( $current_assignments ) ) {
	    			foreach( $current_assignments as $key => $value ) {
	    				$assignments[$key] = $value;
	    			}
	    		}
    		}
    	}
    }
	
	// Find the current custom sidebar with from assignments
	return themeblvd_get_assigned_id( $assignments );
}

/**
 * Display sidebar of widgets.
 *
 * Whether we're in a traditional fixed sidebar or a 
 * collapsible widget area like ad space, for example, 
 * this function will output the widgets for that 
 * widget area using WordPress's dynamic_sidebar function.
 *
 * @since 2.0.0
 * 
 * @param string $location the location for the sidebar
 * @param string $type type of sidebar, fixed or collapsible
 */

function themeblvd_display_sidebar( $location, $type ) {

	// Current configuration for sidebar
	$sidebar = themeblvd_config( 'sidebars', $location );
	
	// If sidebar is set to false or sidebar doesn't 
	// exist, kill it.
	if( ! $sidebar ) 
		return;
	
	// If this is a collapsible default sidebar with 
	// no errors, we'll want to just kill it if it 
	// has no widgets.
	if( $type == 'collapsible' && ! $sidebar['error'] && ! is_active_sidebar( $sidebar['id'] ) ) 
		return; 
		
	// Start display.
	do_action( 'themeblvd_sidebar_'.$type.'_before' ); // Framework does not hook anything here by default
	do_action( 'themeblvd_sidebar_'.$location.'_before' ); // Framework does not hook anything here by default
	echo '<div class="widget-area widget-area-'.$type.'">';
	
	// Proceed, but check for error
	if( $sidebar['error'] ) {
		// Only show error message if user is logged in.
		if( is_user_logged_in() ) {
			// Set message
			switch( $type ) {
				case 'collapsible' :
					$message = sprintf( __( 'This is a collapsible widget area with ID, <strong>%s</strong>, but you haven\'t put any widgets in it yet. Normally this wouldn\'t show at all when empty, but since you have assigned a custom widget area here and didn\'t put any widgets in it, you are seeing this message.', TB_GETTEXT_DOMAIN ), $sidebar['id'] );
					break;
					
				case 'fixed' :
					$message = sprintf( __( 'This is a fixed sidebar with ID, <strong>%s</strong>, but you haven\'t put any widgets in it yet.', TB_GETTEXT_DOMAIN ), $sidebar['id'] );
					break;
			}
			// Ouput message
			echo '<div class="tb-warning">';
			echo '	<p>'.$message.'</p>';
			echo '</div><!-- .tb-warning (end) -->';
		}
	} else {
		// Sidebar ID exists and there are no errors.
		// So, let's display the darn thing.
		dynamic_sidebar( $sidebar['id'] );
	}
	
	// End display
	echo '</div><!-- .widget_area (end) -->';
	do_action( 'themeblvd_sidebar_'.$location.'_after' ); // Framework does not hook anything here by default
	do_action( 'themeblvd_sidebar_'.$type.'_after' ); // Framework does not hook anything here by default
}

/**
 * Display fixed sidebar(s).
 *
 * Don't confuse this with the above function "themeblvd_display_sidebar". 
 * This function is called from within template files and displays 
 * the appropriate sidebars depending on the current sidebar layout 
 * and depending on if this is the right or left side. The 
 * "themeblvd_display_sidebar" function above gets used WITHIN this function.
 *
 * @since 2.0.0
 * 
 * @param string $position position of sidebar on page, left or right
 */

function themeblvd_fixed_sidebars( $position ) {
	$layout = themeblvd_config( 'sidebar_layout' );
	// Sidebar Left, Sidebar Right, Double Sidebars
	if( $layout == 'sidebar_'.$position || $layout == 'double_sidebar' ) {
		do_action( 'themeblvd_fixed_sidebar_before', $position  );
		themeblvd_display_sidebar( 'sidebar_'.$position, 'fixed' );
		do_action( 'themeblvd_fixed_sidebar_after' );
	}
	// Double Left Sidebars
	if( $layout == 'double_sidebar_left' && $position == 'left' ) {
		// Left Sidebar
		do_action( 'themeblvd_fixed_sidebar_before', 'left'  );
		themeblvd_display_sidebar( 'sidebar_left', 'fixed' );
		do_action( 'themeblvd_fixed_sidebar_after' );
		// Right Sidebar
		do_action( 'themeblvd_fixed_sidebar_before', 'right'  );
		themeblvd_display_sidebar( 'sidebar_right', 'fixed' );
		do_action( 'themeblvd_fixed_sidebar_after' );
	}
	// Double Right Sidebars
	if( $layout == 'double_sidebar_right' && $position == 'right' ) {
		// Left Sidebar
		do_action( 'themeblvd_fixed_sidebar_before', 'left'  );
		themeblvd_display_sidebar( 'sidebar_left', 'fixed' );
		do_action( 'themeblvd_fixed_sidebar_after' );
		// Right Sidebar
		do_action( 'themeblvd_fixed_sidebar_before', 'right'  );
		themeblvd_display_sidebar( 'sidebar_right', 'fixed' );
		do_action( 'themeblvd_fixed_sidebar_after' );
	}
}