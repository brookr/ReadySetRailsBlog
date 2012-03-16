<?php
/*-----------------------------------------------------------------------------------*/
/* Admin Display Helpers
/*-----------------------------------------------------------------------------------*/

/**
 * Generates a table for a custom post type.
 *
 * @since 2.0.0
 *
 * @param $post_type string post type id
 * @param $columns array columns for table
 * @return $output string HTML output for table
 */
 
function themeblvd_post_table( $post_type, $columns ) {
	
	// Grab some details for post type
	$post_type_object = get_post_type_object($post_type);
	$name = $post_type_object->labels->name;
	$singular_name = $post_type_object->labels->singular_name;
	
	// Get posts
	$posts = get_posts( array( 'post_type' => $post_type, 'numberposts' => -1 ) );
	
	// Get conflicts if this is a sidebar table
	if( $post_type == 'tb_sidebar' )
		$conflicts = themeblvd_get_assignment_conflicts( $posts );
	
	// Setup header/footer
	$header = '<tr>';
	$header .= '<th scope="col" id="cb" class="manage-column column-cb check-column"><input type="checkbox"></th>';
	foreach( $columns as $column ) {
		$header .= '<th>'.$column['name'].'</th>'; 
	}
	$header .= '</tr>';
	
	// Start main output
	$output = '<table class="widefat">';
	$output .= '<div class="tablenav top">';
	$output .= '<div class="alignleft actions">';
	$output .= '<select name="action">';
	$output .= '<option value="-1" selected="selected">'.__( 'Bulk Actions' ).'</option>';
	$output .= '<option value="trash">'.__( 'Delete' ).' '.$name.'</option>';
	$output .= '</select>';
	$output .= '<input type="submit" id="doaction" class="button-secondary action" value="'.__( 'Apply' ).'">';
	$output .= '</div>';
	$output .= '<div class="alignright tablenav-pages">';
	$output .= '<span class="displaying-num">'.sprintf( _n( '1 '.$singular_name, '%s '.$name, count($posts) ), number_format_i18n( count($posts) ) ).'</span>';
	$output .= '</div>';
	$output .= '<div class="clear"></div>';
	$output .= '</div>';
	
	// Table header
	$output .= '<thead>';
	$output .= $header;
	$output .= '</thead>';
	
	// Table footer
	$output .= '<tfoot>';
	$output .= $header;
	$output .= '</tfoot>';
	
	// Table body
	$output .= '<tbody>';
	if( ! empty( $posts ) ) {
		foreach( $posts as $post ) {
			$output .= '<tr id="row-'.$post->ID.'">';
			$output .= '<th scope="row" class="check-column"><input type="checkbox" name="posts[]" value="'.$post->ID.'"></th>';
			foreach( $columns as $column ){
				switch( $column['type'] ) {
					case 'title' :
						$output .= '<td class="post-title page-title column-title">';
						$output .= '<strong><a href="#'.$post->ID.'" class="title-link edit-'.$post_type.'" title="'.__( 'Edit' ).'">'.$post->post_title.'</strong></a>';
						$output .= '<div class="row-actions">';
						$output .= '<span class="edit">';
						$output .= '<a href="#'.$post->ID.'" class="edit-post edit-'.$post_type.'" title="'.__( 'Edit' ).'">'.__( 'Edit' ).'</a> | ';
						$output .= '</span>';
						$output .= '<span class="trash">';
						$output .= '<a title="'.__( 'Delete' ).'" href="#'.$post->ID.'">'.__( 'Delete' ).'</a>';
						$output .= '</span>';
						$output .= '</div>';
						break;
						
					case 'id' :
						$output .= '<td class="post-id">';
						$output .= $post->ID;
						break;
					
					case 'slug' :
						$output .= '<td class="post-slug">';
						$output .= $post->post_name;
						break;
						
					case 'meta' :
						$output .= '<td class="post-meta-'.$column['config'].'">';
						$meta = get_post_meta( $post->ID, $column['config'], true );
						if( isset( $column['inner'] ) ) {
							if( isset( $meta[$column['inner']] ) )
								$output .= $meta[$column['inner']];
						} else {
							$output .= $meta;
						}
						break;
						
					case 'shortcode' :
						$output .= '<td class="post-shortcode-'.$column['config'].'">';
						$output .= '['.$column['config'].' id="'.$post->post_name.'"]';
						break;
						
					case 'assignments' :
						$output .= '<td class="post-assignments">';
						$location = get_post_meta( $post->ID, 'location', true );
						if( $location && $location != 'floating' ) {
							$assignments = get_post_meta( $post->ID, 'assignments', true );
							if( is_array( $assignments ) && ! empty( $assignments ) ) {
								$output .= '<ul>';
								foreach( $assignments as $key => $assignment ) {
									in_array( $key, $conflicts[$location] ) ? $class = 'conflict' : $class = 'no-conflict';	
									if( $assignment['type'] == 'top' )
										$output .= '<li class="'.$class.'">'.$assignment['name'].'</li>';
									else
										$output .= '<li class="'.$class.'">'.ucfirst( $assignment['type'] ).': '.$assignment['name'].'</li>';
								}
								$output .= '</ul>';
							} else {
								$output .= '<span class="inactive">'.__( 'No Assignments', TB_GETTEXT_DOMAIN ).'</span>';
							}
						} else {
							$output .= '<span class="inactive">[floating]</span>';
						}
						break;
					
					case 'sidebar_location' :
						$output .= '<td class="sidebar-location">';
						$output .= themeblvd_get_sidebar_location_name( get_post_meta( $post->ID, 'location', true ) );
						break;
				}
				$output .= '</td>';
			}
			$output .= '</tr>';
		}
	} else {
		$num = count( $columns ) + 1; // number of columns + the checkbox column
		$output .= '<tr><td colspan="'.$num.'">'.__('No items have been created yet. Click the Add tab above to get started.').'</td></tr>';
	}
	$output .= '</tbody>';
	$output .= '</table>';
	return $output;
}

/**
 * All color classes.
 *
 * @since 2.0.0
 *
 * @return $colors array all colors in framework filtered
 */
 
function themeblvd_colors() {
	$colors = array(
		'default'		=> __( 'Default Color', TB_GETTEXT_DOMAIN ),
		'black' 		=> __( 'Black', TB_GETTEXT_DOMAIN ),
		'blue' 			=> __( 'Blue', TB_GETTEXT_DOMAIN ),
		'brown' 		=> __( 'Brown', TB_GETTEXT_DOMAIN ),
		'dark_blue'		=> __( 'Dark Blue', TB_GETTEXT_DOMAIN ),
		'dark_brown' 	=> __( 'Dark Brown', TB_GETTEXT_DOMAIN ),
		'dark_green' 	=> __( 'Dark Green', TB_GETTEXT_DOMAIN ),
		'green' 		=> __( 'Green', TB_GETTEXT_DOMAIN ),
		'mauve' 		=> __( 'Mauve', TB_GETTEXT_DOMAIN ),
		'orange'		=> __( 'Orange', TB_GETTEXT_DOMAIN ),
		'pearl'			=> __( 'Pearl', TB_GETTEXT_DOMAIN ),
		'pink'			=> __( 'Pink', TB_GETTEXT_DOMAIN ),
		'purple'		=> __( 'Purple', TB_GETTEXT_DOMAIN ),
		'red'			=> __( 'Red', TB_GETTEXT_DOMAIN ),
		'slate_grey'	=> __( 'Slate Grey', TB_GETTEXT_DOMAIN ),
		'silver'		=> __( 'Silver', TB_GETTEXT_DOMAIN ),
		'steel_blue'	=> __( 'Steel Blue', TB_GETTEXT_DOMAIN ),
		'teal'			=> __( 'Teal', TB_GETTEXT_DOMAIN ),
		'yellow'		=> __( 'Yellow', TB_GETTEXT_DOMAIN ),
		'wheat'			=> __( 'Wheat', TB_GETTEXT_DOMAIN ),
		'white'			=> __( 'White', TB_GETTEXT_DOMAIN )
	);
	return apply_filters( 'themeblvd_colors', $colors );
}

/**
 * Generates array to be used in a select option 
 * type of the options framework.
 *
 * @since 2.0.0
 *
 * @param $type string type of select to return
 * @return $select array items for select
 */
 
function themeblvd_get_select( $type ) {
	$select = array();
	switch( $type ) {
		
		// Pages
		case 'pages' :
			$pages_select = array();
			$pages = get_pages();
			if( ! empty( $pages ) )
				foreach( $pages as $page )
					$select[$page->post_name] = $page->post_title;
			else
				$select['null'] = __( 'No pages exist.', TB_GETTEXT_DOMAIN );
			break;
		
		// Categories
		case 'categories' :
			$select['all'] = __( '<strong>All Categories</strong>', TB_GETTEXT_DOMAIN );
			$categories = get_categories();
			foreach( $categories as $category )
				$select[$category->slug] = $category->name;
			break;
		
		// Sliders	
		case 'sliders' : 
			$sliders = get_posts( 'post_type=tb_slider&numberposts=-1' );
			if( ! empty( $sliders ) )
				foreach( $sliders as $slider )
					$select[$slider->post_name] = $slider->post_title;
			else
				$select['null'] = __( 'You haven\'t created any custom sliders yet.', TB_GETTEXT_DOMAIN );
			break;
			
		// Floating Sidebars
		case 'sidebars' :
			$sidebars = get_posts('post_type=tb_sidebar&numberposts=-1');
			if( ! empty( $sidebars ) ) {
				foreach( $sidebars as $sidebar ) {
					$location = get_post_meta( $sidebar->ID, 'location', true );
					if( $location == 'floating' )
						$select[$sidebar->post_name] = $sidebar->post_title;
				}
			} // Handle error message for no sidebars outside of this function
			break;
		
	}
	return $select;
}

/**
 * Generates default column widths for column element.
 *
 * @since 2.0.0
 */

function themeblvd_column_widths() {
	$widths = array (
		'1-col' => array (					// User selects 1 columns
			array(
				'name' 	=> '100%',
				'value' => 'grid_12',
			)
		),
		'2-col' => array (					// User selects 2 columns
			array(
				'name' 	=> '20% | 80%',
				'value' => 'grid_fifth_1-grid_fifth_4',
			),
			array(
				'name' 	=> '25% | 75%',
				'value' => 'grid_3-grid_9',
			),
			array(
				'name' 	=> '30% | 70%',
				'value' => 'grid_tenth_3-grid_tenth_7',
			),
			array(
				'name' 	=> '33% | 66%',
				'value' => 'grid_4-grid_8',
			),
			array(
				'name' 	=> '50% | 50%',
				'value' => 'grid_6-grid_6',
			),
			array(
				'name' 	=> '66% | 33%',
				'value' => 'grid_8-grid_4',
			),
			array(
				'name' 	=> '70% | 30%',
				'value' => 'grid_tenth_7-grid_tenth_3',
			),
			array(
				'name' 	=> '75% | 25%',
				'value' => 'grid_9-grid_3',
			),
			array(
				'name' 	=> '80% | 20%',
				'value' => 'grid_fifth_4-grid_fifth_1',
			)
		),
		'3-col' => array (					// User selects 3 columns
			array(
				'name' 	=> '33% | 33% | 33%',
				'value' => 'grid_4-grid_4-grid_4',
			),
			array(
				'name' 	=> '25% | 25% | 50%',
				'value' => 'grid_3-grid_3-grid_6',
			),
			array(
				'name' 	=> '25% | 50% | 25%',
				'value' => 'grid_3-grid_6-grid_3',
			),
			array(
				'name' 	=> '50% | 25% | 25% ',
				'value' => 'grid_6-grid_3-grid_3',
			),
			array(
				'name' 	=> '20% | 20% | 60%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_3',
			),
			array(
				'name' 	=> '20% | 60% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_3-grid_fifth_1',
			),
			array(
				'name' 	=> '60% | 20% | 20%',
				'value' => 'grid_fifth_3-grid_fifth_1-grid_fifth_1',
			),
			array(
				'name' 	=> '40% | 40% | 20%',
				'value' => 'grid_fifth_2-grid_fifth_2-grid_fifth_1',
			),
			array(
				'name' 	=> '40% | 20% | 40%',
				'value' => 'grid_fifth_2-grid_fifth_1-grid_fifth_2',
			),
			array(
				'name' 	=> '20% | 40% | 40%',
				'value' => 'grid_fifth_1-grid_fifth_2-grid_fifth_2',
			),
			array(
				'name' 	=> '30% | 30% | 40%',
				'value' => 'grid_tenth_3-grid_tenth_3-grid_fifth_2',
			),
			array(
				'name' 	=> '30% | 40% | 30%',
				'value' => 'grid_tenth_3-grid_fifth_2-grid_tenth_3',
			),
			array(
				'name' 	=> '40% | 30% | 30%',
				'value' => 'grid_fifth_2-grid_tenth_3-grid_tenth_3',
			)
		),
		'4-col' => array (					// User selects 4 columns
			array(
				'name' 	=> '25% | 25% | 25% | 25%',
				'value' => 'grid_3-grid_3-grid_3-grid_3',
			),
			array(
				'name' 	=> '20% | 20% | 20% | 40%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_2',
			),
			array(
				'name' 	=> '20% | 20% | 40% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_2-grid_fifth_1',
			),
			array(
				'name' 	=> '20% | 40% | 20% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_2-grid_fifth_1-grid_fifth_1',
			),
			array(
				'name' 	=> '40% | 20% | 20% | 20%',
				'value' => 'grid_fifth_2-grid_fifth_1-grid_fifth_1-grid_fifth_1',
			)
		),
		'5-col' => array (						// User selects 5 columns
			array(
				'name' 	=> '20% | 20% | 20% | 20% | 20%',
				'value' => 'grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1-grid_fifth_1',
			)
		)
	);
	return apply_filters( 'themeblvd_column_widths', $widths );
}

/**
 * Generates option for configuring columns.
 * 
 * This has been moved to a separate function 
 * because it's a custom addition to the optionframework 
 * module and it's pretty lengthy.
 *
 * @since 2.0.0
 *
 * @param $type string type of use, standard or element
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */
 
function themeblvd_columns_option( $type, $id, $name, $val ) {

	/*------------------------------------------------------*/
	/* Setup Internal Options
	/*------------------------------------------------------*/
	
	// Dropdown for number of columns selection
	$data_num = array (
		array(
			'name' 	=> __( 'Hide Columns', TB_GETTEXT_DOMAIN ),
			'value' => 0,
		),
		array(
			'name' 	=> '1 '.__( 'Column', TB_GETTEXT_DOMAIN ),
			'value' => 1,
		),
		array(
			'name' 	=> '2 '.__( 'Columns', TB_GETTEXT_DOMAIN ),
			'value' => 2,
		),
		array(
			'name' 	=> '3 '.__( 'Columns', TB_GETTEXT_DOMAIN ),
			'value' => 3,
		),
		array(
			'name' 	=> '4 '.__( 'Columns', TB_GETTEXT_DOMAIN ),
			'value' => 4,
		),
		array(
			'name' 	=> '5 '.__( 'Columns', TB_GETTEXT_DOMAIN ),
			'value' => 5,
		)
	);
	
	// Dropdowns for column width configuration
	$data_widths = themeblvd_column_widths();
	
	/*------------------------------------------------------*/
	/* Construct <select> Menus
	/*------------------------------------------------------*/
	
	// Number of columns
	if( $type == 'element' ) {
		unset( $data_num[0] );
		unset( $data_num[1] );
	}
	
	$select_number = '<select class="column-num" name="'.esc_attr( $name.'['.$id.'][num]' ).'">';
	if( is_array($val) && isset( $val['num'] ) )
		$current_value = $val['num'];
	else
		$current_value = null;
	
	foreach( $data_num as $num )
		$select_number .= '<option value="'.$num['value'].'" '.selected( $current_value, $num['value'], false ).'>'.$num['name'].'</option>';

	$select_number .= '</select>';
	
	// Columns widths
	if( $type == 'element' ) {
		unset( $data_widths['1-col'] );
	}
	
	if( $type == 'element' )
		$i = 2;
	else
		$i = 1;
	
	$select_widths = '<div class="column-width column-width-0"><p class="inactive">'.__( 'Columns will be hidden.', TB_GETTEXT_DOMAIN ).'</p></div>';
	foreach( $data_widths as $widths ) {
		$select_widths .= '<select class="column-width column-width-'.$i.'" name= "'.esc_attr( $name.'['.$id.'][width]['.$i.']' ).'">';
		
		if( is_array( $val ) && isset( $val['width'][$i] ) )
			$current_value = $val['width'][$i];
		else
			$current_value = null;
		
		foreach( $widths as $width ) {
			$select_widths .= '<option value="'.$width['value'].'" '.selected( $current_value, $width['value'], false ).'>'.$width['name'].'</option>';
		}
		
		$select_widths .= '</select>';
		$i++;
	}
	
	/*------------------------------------------------------*/
	/* Primary Output
	/*------------------------------------------------------*/
	
	$output = '<div class="select-wrap alignleft">';
	$output .= $select_number;
	$output .= '</div>';
	$output .= '<div class="select-wrap alignleft">';
	$output .= $select_widths;
	$output .= '</div>';
	$output .= '<div class="clear"></div>';
	
	return $output;
}

/**
 * Generates option for configuring tabs.
 * 
 * This has been moved to a separate function 
 * because it's a custom addition to the optionframework 
 * module and it's pretty lengthy.
 *
 * @since 2.0.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */

function themeblvd_tabs_option( $id, $name, $val ) {
	
	/*------------------------------------------------------*/
	/* Build <select> for number of tabs
	/*------------------------------------------------------*/
	
	$numbers = array (
		array(
			'name' 	=> '2 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 2,
		),
		array(
			'name' 	=> '3 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 3,
		),
		array(
			'name' 	=> '4 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 4,
		),
		array(
			'name' 	=> '5 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 5,
		),
		array(
			'name' 	=> '6 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 6,
		),
		array(
			'name' 	=> '7 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 7,
		),
		array(
			'name' 	=> '8 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 8,
		),
		array(
			'name' 	=> '9 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 9,
		),
		array(
			'name' 	=> '10 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 10,
		),
		array(
			'name' 	=> '11 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 11,
		),
		array(
			'name' 	=> '12 '.__( 'Tabs', TB_GETTEXT_DOMAIN ),
			'value' => 12,
		)
	);
	
	$select_number = '<select class="tabs-num" name="'.esc_attr( $name.'['.$id.'][num]' ).'">';
	if( is_array($val) && isset( $val['num'] ) )
		$current_value = $val['num'];
	else
		$current_value = null;
	
	foreach( $numbers as $num )
		$select_number .= '<option value="'.$num['value'].'" '.selected( $current_value, $num['value'], false ).'>'.$num['name'].'</option>';
		
	$select_number .= '</select>';
	
	/*------------------------------------------------------*/
	/* Build <select> for style of tabs
	/*------------------------------------------------------*/
	
	$select_style = '<select class="tabs-style" name="'.esc_attr( $name.'['.$id.'][style]' ).'">';
	if( is_array($val) && isset( $val['style'] ) )
		$current_value = $val['style'];
	else
		$current_value = null;
	
	$select_style .= '<option value="open" '.selected( $current_value, 'open', false ).'>'.__( 'Open Style', TB_GETTEXT_DOMAIN ).'</option>';
	$select_style .= '<option value="framed" '.selected( $current_value, 'framed', false ).'>'.__( 'Framed Style', TB_GETTEXT_DOMAIN ).'</option>';
	$select_style .= '</select>';
	
	/*------------------------------------------------------*/
	/* Add in text fields for names of tabs
	/*------------------------------------------------------*/
	
	// Add 1st tab
	array_unshift( $numbers, array( 'value' => 1 ) );
	$input_names = null;
	foreach( $numbers as $number ) {
		// Default value
		if( is_array( $val ) && isset( $val['names']['tab_'.$number['value']] ) )
			$current_value = stripslashes( $val['names']['tab_'.$number['value']] );
		else
			$current_value = null;
		// Output
		$input_names .= '<div class="tab-name tab-name-'.$number['value'].'">';
		$input_names .= '<label for="tab-name-input='.$number['value'].'">'.sprintf( __('Tab #%d Name', TB_GETTEXT_DOMAIN), $number['value'] ).'</label>';
		$input_names .= '<input id="tab-name-input='.$number['value'].'" type="text" name="'.esc_attr( $name.'['.$id.'][names][tab_'.$number['value'].']' ).'" value ="'.esc_attr($current_value).'" />';
		$input_names .= '<div class="clear"></div>';
		$input_names .= '</div>';
	}
	
	/*------------------------------------------------------*/
	/* Primary Output
	/*------------------------------------------------------*/
	
	$output = '<div class="select-tab-num">';
	$output .= $select_number;
	$output .= '</div>';
	$output .= '<div class="select-tab-style">';
	$output .= $select_style;
	$output .= '</div>';
	$output .= '<div class="tab-names">';
	$output .= $input_names;
	$output .= '</div>';
	
	return $output;
	
}

/**
 * Generates option for an individual block of content. 
 * This was mainly designed to be used within setting 
 * up columns or tabs.
 * 
 * This has been moved to a separate function 
 * because it's a custom addition to the optionframework 
 * module and it's pretty lengthy.
 *
 * @since 2.0.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @param $options array content sources to choose from
 * @return $output string HTML for option
 */

function themeblvd_content_option( $id, $name, $val, $options ) {
	
	/*------------------------------------------------------*/
	/* Build <select> for type of content
	/*------------------------------------------------------*/
	
	// Setup content types to choose from
	$sources = array(
		'null' 		=> __( '- Select Content Type -', TB_GETTEXT_DOMAIN ),
	);
	
	if( in_array ( 'widget', $options ) )
		$sources['widget'] = __( 'Floating Widget Area', TB_GETTEXT_DOMAIN );
		
	if( in_array ( 'page', $options ) )
		$sources['page'] = __( 'External Page', TB_GETTEXT_DOMAIN );
	
	if( in_array ( 'raw', $options ) )
		$sources['raw'] = __( 'Raw Content', TB_GETTEXT_DOMAIN );
	
	// Set default value
	if( is_array( $val ) && isset( $val['type'] ) )
		$current_value = $val['type'];
	else
		$current_value = null;
	
	// Build <select>
	$select_type = '<select class="select-type" name= "'.esc_attr( $name.'['.$id.'][type]' ).'">';
	foreach( $sources as $key => $value ) {
		$select_type .= '<option value="'.$key.'" '.selected( $current_value, $key, false ).'>'.$value.'</option>';
	}
	$select_type .= '</select>';
	
	/*------------------------------------------------------*/
	/* Build <select> for widget area
	/*------------------------------------------------------*/
	
	if( in_array ( 'widget', $options ) ) {
	
		$sidebars = array();
		
		// Set default value
		if( is_array( $val ) && isset( $val['sidebar'] ) )
			$current_value = $val['sidebar'];
		else
			$current_value = null;
		
		// Get all custom sidebars from custom post type
		$sidebars = themeblvd_get_select( 'sidebars' );
		
		// Build <select>
		if( ! empty( $sidebars ) ) {
			$select_sidebar = '<select class="select-sidebar" name= "'.esc_attr( $name.'['.$id.'][sidebar]' ).'">';
			foreach( $sidebars as $key => $value ) {
				$select_sidebar .= '<option value="'.$key.'" '.selected( $current_value, $key, false ).'>'.$value.'</option>';
			}
			$select_sidebar .= '</select>';
		} else {
			$select_sidebar = '<p class="warning">'.__( 'You haven\'t created any floating widget areas.', TB_GETTEXT_DOMAIN ).'</p>';
		}
		
	}
	
	
	/*------------------------------------------------------*/
	/* Build <select> for external page
	/*------------------------------------------------------*/
	
	if( in_array ( 'page', $options ) ) {
	
		// Set default value
		if( is_array( $val ) && isset( $val['page'] ) )
			$current_value = $val['page'];
		else
			$current_value = null;
		
		// Get all pages from WP database
		$pages = themeblvd_get_select( 'pages' );
		
		// Build <select>
		if( ! empty( $pages ) ) {
			$select_page = '<select name= "'.esc_attr( $name.'['.$id.'][page]' ).'">';
			foreach( $pages as $key => $value ) {
				$select_page .= '<option value="'.$key.'" '.selected( $current_value, $key, false ).'>'.$value.'</option>';
			}
			$select_page .= '</select>';
		} else {
			$select_page = '<p class="warning">'.__( 'You haven\'t created any pages.', TB_GETTEXT_DOMAIN ).'</p>';
		}
		
	}
	
	/*------------------------------------------------------*/
	/* Build raw content input
	/*------------------------------------------------------*/
	
	if( in_array ( 'raw', $options ) ) {
	
		// Set default value
		if( is_array( $val ) && isset( $val['raw'] ) )
			$current_value = stripslashes( $val['raw'] );
		else
			$current_value = null;
		
		$raw_content = '<textarea name="'.esc_attr( $name.'['.$id.'][raw]' ).'" class="of-input" cols="8" rows="8">'.$current_value.'</textarea>';
	
	}
		
	/*------------------------------------------------------*/
	/* Primary Output
	/*------------------------------------------------------*/
	
	$output = '<div class="column-content-types">';
	$output .= $select_type;
	
	if( in_array ( 'widget', $options ) ) {
		$output .= '<div class="column-content-type column-content-type-widget">';
		$output .= $select_sidebar;
		$output .= '<p class="note">'.__( 'Select from your floating custom widget areas. In order for a custom widget area to be "floating" you must have it configured this way in the Widget Area manager.', TB_GETTEXT_DOMAIN ).'</p>';
		$output .= '</div>';
	}
	
	if( in_array ( 'page', $options ) ) {
		$output .= '<div class="column-content-type column-content-type-page">';
		$output .= $select_page;
		$output .= '<p class="note">'.__( 'Select an external page to pull content from.', TB_GETTEXT_DOMAIN ).'</p>';
		$output .= '</div>';
	}
	
	if( in_array ( 'raw', $options ) ) {
		$output .= '<div class="column-content-type column-content-type-raw">';
		$output .= $raw_content;
		$output .= '<p class="note">'.__( 'You can use basic HTML here, and most shortcodes.', TB_GETTEXT_DOMAIN ).'</p>';
		$output .= '</div>';
	}
	
	$output .= '</div><!-- .column-content-types (end) -->';
	
	return $output;
}

/**
 * Create accordion panel for selecting conditional
 * assignments.
 *
 * @since 2.0.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */
 
function themeblvd_conditionals_option( $id, $name, $val = null ) {
	
	// Create array of all assignments seperated 
	// by type to check against when displaying them
	// back to the user.
	$assignments = array(
		'pages' 			=> array(),
		'posts' 			=> array(),
		'posts_in_category' => array(),
		'categories' 		=> array(),
		'tags' 				=> array(),
		'top' 				=> array()
	);
	if( is_array( $val ) && ! empty( $val ) ) {
		foreach( $val as $key => $group ) {
			$item_id = $group['id'];
			switch( $group['type'] ) {
				case 'page' :
					$assignments['pages'][] = $item_id;
					break;
				case 'post' :
					$assignments['posts'][] = $item_id;
					break;
				case 'posts_in_category' :
					$assignments['posts_in_category'][] = $item_id;
					break;
				case 'category' :
					$assignments['categories'][] = $item_id;
					break;
				case 'tag' :
					$assignments['tags'][] = $item_id;
					break;
				case 'top' :
					$assignments['top'][] = $item_id;
					break;
			}	
		}
	}
	
	// Grab all conditionals to choose from
	$conditionals = themeblvd_conditionals_config();
	
	// Start output
	$output = '<div class="accordion">';
	
	// Display each accordion element
	foreach( $conditionals as $conditional ) {
		$output .= '<div class="element">';
		$output .= '<a href="#" class="element-trigger">'.$conditional['name'].'</a>';
		$output .= '<div class="element-content">';
		switch( $conditional['id'] ) {
			
			// Pages
			case 'pages' :
				$pages = get_pages();
				if( ! empty( $pages ) ) {
					$output .= '<ul>';
					foreach( $pages as $page ) {
						in_array( $page->post_name, $assignments['pages'] ) ? $checked = true : $checked = false;
						$output .= '<li><input type="checkbox" '.checked( $checked, true, false ).' name="'.esc_attr( $name.'['.$id.'][page][]' ).'" value="'.$page->post_name.'" /> <span>'.$page->post_title.'</span></li>';
						$checked = false;
					}
					$output .= '</ul>';
				} else {
					$output .= '<p class="warning">'.$conditional['empty'].'</p>';
				}
				break;
			
			// Posts	
			case 'posts' :
				$posts = get_posts('numberposts=-1');
				if( ! empty( $posts ) ) {
					$output .= '<ul>';
					foreach( $posts as $post ) {
						in_array( $post->post_name, $assignments['posts'] ) ? $checked = true : $checked = false;
						$output .= '<li><input type="checkbox" '.checked( $checked, true, false ).' name="'.esc_attr( $name.'['.$id.'][post][]' ).'" value="'.$post->post_name.'" /> <span>'.$post->post_title.'</span></li>';
						$checked = false;
					}
					$output .= '</ul>';
				} else {
					$output .= '<p class="warning">'.$conditional['empty'].'</p>';
				}
				break;
			
			// Posts in Category	
			case 'posts_in_category' :
				$categories = get_categories();
		        if( ! empty( $categories ) ) {
		        	$output .= '<ul>';
		        	foreach ( $categories as $category ) {
						in_array( $category->slug, $assignments['posts_in_category'] ) ? $checked = true : $checked = false;
						$output .= '<li><input type="checkbox" '.checked( $checked, true, false ).' name="'.esc_attr( $name.'['.$id.'][posts_in_category][]' ).'" value="'.$category->slug.'" /> <span>'.$category->name.'</span></li>';
						$checked = false;
					}
					$output .= '</ul>';
				} else {
					$output .= '<p class="warning">'.$conditional['empty'].'</p>';
				}
				break;
			
			// Category Archives	
			case 'categories' :
				$categories = get_categories();
		        if( ! empty( $categories ) ) {
		        	$output .= '<ul>';
		        	foreach ( $categories as $category ) {
						in_array( $category->slug, $assignments['categories'] ) ? $checked = true : $checked = false;
						$output .= '<li><input type="checkbox" '.checked( $checked, true, false ).' name="'.esc_attr( $name.'['.$id.'][category][]' ).'" value="'.$category->slug.'" /> <span>'.$category->name.'</span></li>';
						$checked = false;
					}
					$output .= '</ul>';
				} else {
					$output .= '<p class="warning">'.$conditional['empty'].'</p>';
				}
				break;
			
			// Tag Archives
			case 'tags' :
				$tags = get_tags();
		        if( ! empty( $tags ) ) {
		        	$output .= '<ul>';
			        foreach ( $tags as $tag ) {
						in_array( $tag->slug, $assignments['tags'] ) ? $checked = true : $checked = false;
						$output .= '<li><input type="checkbox" '.checked( $checked, true, false ).' name="'.esc_attr( $name.'['.$id.'][tag][]' ).'" value="'.$tag->slug.'" /> <span>'.$tag->name.'</span></li>';
						$checked = false;
					}
					$output .= '</ul>';
				} else {
					$output .= '<p class="warning">'.$conditional['empty'].'</p>';
				}
				break;
			
			// Hierarchy
			case 'top' :
				$items = $conditional['items'];
				foreach( $items as $item_id => $item_name ) {
					in_array( $item_id, $assignments['top'] ) ? $checked = true : $checked = false;
					$output .= '<li><input type="checkbox" '.checked( $checked, true, false ).' name="'.esc_attr( $name.'['.$id.'][top][]' ).'" value="'.$item_id.'" /> <span>'.$item_name.'</span></li>';
					$checked = false;
				}
				break;

		}
		$output .= '<ul>';
		$output .= '</div><!-- .element-content (end) -->';
		$output .= '</div><!-- .element (end) -->';
	}
	$output .= '</div><!-- .accordion (end) -->';
	
	return $output;
}

/**
 * Generates option to edit a logo.
 * 
 * This has been moved to a separate function 
 * because it's a custom addition to the optionframework 
 * module and it's pretty lengthy.
 *
 * @since 2.0.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */

function themeblvd_logo_option( $id, $name, $val ) {
	
	/*------------------------------------------------------*/
	/* Type of logo
	/*------------------------------------------------------*/
	
	$types = array(
		'title' 	=> __( 'Site Title', TB_GETTEXT_DOMAIN ),
		'custom' 	=> __( 'Custom Text', TB_GETTEXT_DOMAIN ),
		'image' 	=> __( 'Image', TB_GETTEXT_DOMAIN )
	);
	
	$select_type = '<select name="'.esc_attr( $name.'['.$id.'][type]' ).'">';
	if( is_array($val) && isset( $val['type'] ) )
		$current_value = $val['type'];
	else
		$current_value = null;
	
	foreach( $types as $key => $type )
		$select_type .= '<option value="'.$key.'" '.selected( $current_value, $key, false ).'>'.$type.'</option>';
		
	$select_type .= '</select>';
	
	/*------------------------------------------------------*/
	/* Site Title
	/*------------------------------------------------------*/
	
	$site_title = '<p class="note">';
	$site_title .= __( 'Current Site Title', TB_GETTEXT_DOMAIN ).': <strong>';
	$site_title .= get_bloginfo( 'name' ).'</strong><br>';
	$site_title .= __( 'You can change your site title by going <a href="options-general.php" target="_blank">here</a>.', TB_GETTEXT_DOMAIN );
	$site_title .= '</p>';
	
	/*------------------------------------------------------*/
	/* Custom Text
	/*------------------------------------------------------*/
	
	if( is_array( $val ) && isset( $val['custom'] ) )
		$current_value = $val['custom'];
	else
		$current_value = null;

	$custom_text = '<input type="text" name="'.esc_attr( $name.'['.$id.'][custom]' ).'" value="'.esc_attr($current_value).'" />';
	$custom_text .= '<p class="note">'.__( 'Insert your custom text.', TB_GETTEXT_DOMAIN ).'</p>';
	
	/*------------------------------------------------------*/
	/* Image
	/*------------------------------------------------------*/
	
	if( is_array( $val ) && isset( $val['image'] ) )
		$current_value = array( 'url' => $val['image'], 'id' => '' );
	else
		$current_value = array( 'url' => '', 'id' => '' );
	
	$image_upload = optionsframework_medialibrary_uploader( $name, 'logo', $id, $current_value, null, null, 0, 'image' );
	$image_upload .= '<p class="note">'.__( 'Use the "Upload" button to either upload an image or select an image from your media library. You can also type in the URL to an image in the text field.', TB_GETTEXT_DOMAIN ).'</p>';

	/*------------------------------------------------------*/
	/* Primary Output
	/*------------------------------------------------------*/
	
	$output = '<div class="select-type">';
	$output .= $select_type;
	$output .= '</div>';
	$output .= '<div class="logo-item title">';
	$output .= $site_title;
	$output .= '</div>';
	$output .= '<div class="logo-item custom">';
	$output .= $custom_text;
	$output .= '</div>';
	$output .= '<div class="logo-item image">';
	$output .= $image_upload;
	$output .= '</div>';
	
	return $output;
}

/**
 * Generates option to edit social media buttons.
 * 
 * This has been moved to a separate function 
 * because it's a custom addition to the optionframework 
 * module and it's pretty lengthy.
 *
 * @since 2.0.0
 *
 * @param $id string unique ID for option
 * @param $name string prefix for form name value
 * @param $val array currently saved data if exists
 * @return $output string HTML for option
 */

function themeblvd_social_media_option( $id, $name, $val ) {
	
	$sources = array(
		'amazon' 		=> 'Amazon',
		'delicious' 	=> 'del.icio.us',
		'deviantart' 	=> 'Deviant Art',
		'digg' 			=> 'Digg',
		'dribbble' 		=> 'Dribbble',
		'ebay' 			=> 'Ebay',
		'email' 		=> 'Email',
		'facebook' 		=> 'Facebook',
		'feedburner' 	=> 'Feedburner',
		'flickr' 		=> 'Flickr',
		'forrst' 		=> 'Forrst',
		'foursquare' 	=> 'Foursquare',
		'github' 		=> 'Github',
		'google' 		=> 'Google+',
		'instagram' 	=> 'Instagram',
		'linkedin' 		=> 'Linkedin',
		'myspace' 		=> 'MySpace',
		'paypal' 		=> 'PayPal',
		'picasa' 		=> 'Picasa',
		'reddit' 		=> 'Reddit',
		'scribd' 		=> 'Sribd',
		'squidoo' 		=> 'Squidoo',
		'technorati' 	=> 'Technorati',
		'tumblr' 		=> 'Tumblr',
		'twitter' 		=> 'Twitter',
		'vimeo' 		=> 'Vimeo',
		'xbox' 			=> 'Xbox',
		'yahoo' 		=> 'Yahoo',
		'youtube' 		=> 'YouTube',
		'rss' 			=> 'RSS'
	);
	$sources = apply_filters( 'themeblvd_social_media_buttons', $sources );
	
	$counter = 1;
	$divider = round( count($sources)/2 );
	$output = '<div class="column-1">';
	foreach( $sources as $key => $source ) {
		$output .= '<div class="item">';
		$output .= '<span>';
		is_array( $val ) && array_key_exists( $key, $val ) ? $checked = true : $checked = false;
		$output .= '<input class="checkbox of-input" value="'.$key.'" type="checkbox" '.checked( $checked, true, false ).' name="'.esc_attr( $name.'['.$id.'][includes][]' ).'" />';
		$output .= $source;
		$output .= '</span>';
		if( is_array( $val ) && isset( $val[$key] ) ) { 
			$value = $val[$key];
		} else {
			$key == 'email' ? $value = 'mailto:' : $value = 'http://';
		}
		$output .= '<input class="of-input social_media-input" value="'.esc_attr( $value ).'" type="text" name="'.esc_attr( $name.'['.$id.']['.$key.']' ).'" />';
		$output .= '</div><!-- .item (end) -->';
		if( $counter == $divider ) {
			// Separate options into two columns
			$output .= '</div><!-- .column-1 (end) -->';
			$output .= '<div class="column-2">';
		}
		$counter++;
	}
	$output .= '</div><!-- .column-2 (end) -->';
	
	return $output;
}

/**
 * Option for selecting a sidebar layout that gets 
 * inserted into out Hi-jacked "Page Attributes" 
 * meta box.
 *
 * @since 2.0.0
 *
 * @param $layout string current sidebar layout
 * @param $output string HTML to output
 */

function themeblvd_sidebar_layout_dropdown( $layout = null ) {
	$sidebar_layouts = themeblvd_sidebar_layouts();
	$output = '<p><strong>'.__( 'Sidebar Layout', TB_GETTEXT_DOMAIN ).'</strong></p>';
	$output .= '<select name="_tb_sidebar_layout">';
	$output .= '<option value="default">'.__( 'Default Sidebar Layout', TB_GETTEXT_DOMAIN ).'</option>';
	foreach( $sidebar_layouts as $sidebar_layout )
		$output .= '<option value="'.$sidebar_layout['id'].'" '.selected( $sidebar_layout['id'], $layout, false ).'>'.$sidebar_layout['name'].'</option>';
	$output .= '</select>';
	return $output;
}

/**
 * Option for selecting a custom layout that gets 
 * inserted into out Hi-jacked "Page Attributes" 
 * meta box.
 *
 * @since 2.0.0
 *
 * @param $layout string current custom layout
 * @param $output string HTML to output
 */
 
function themeblvd_custom_layout_dropdown( $layout = null ) {
	$custom_layouts = get_posts('post_type=tb_layout&numberposts=-1');
	$output = '<p><strong>'.__( 'Custom Layout', TB_GETTEXT_DOMAIN ).'</strong></p>';
	if( ! empty( $custom_layouts ) ) {
		$output .= '<select name="_tb_custom_layout">';
		foreach( $custom_layouts as $custom_layout )
			$output .= '<option value="'.$custom_layout->post_name.'" '.selected( $custom_layout->post_name, $layout, false ).'>'.$custom_layout->post_title.'</option>';
		$output .= '</select>';
	} else {
		$output .='<em>'.__( 'You haven\'t created any custom layouts in the Layout builder yet.', TB_GETTEXT_DOMAIN ).'</em>';
	}
	return $output;
}