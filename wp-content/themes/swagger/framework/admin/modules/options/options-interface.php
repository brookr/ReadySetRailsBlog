<?php
/**
 * Generates the options fields that are used in the form.
 * 
 * @param string $option_name prefix for all field name attributes
 * @param array $options all options to show in form
 * @param array $settings any current settings for all form fields
 * @param boolean $close whether to add closing </div>
 * @return array $output final options form
 */

function optionsframework_fields( $option_name, $options, $settings, $close = true ) {

    $counter = 0;
	$menu = '';
	$output = '';
	
	foreach ($options as $value) {
	   	
		$counter++;
		$val = '';
		$select_value = '';
		$checked = '';
		$class = '';
		
		// Sub Groups (added by ThemeBlvd)
		// This allows for a wrapping div around groups of elements. 
		// The primary reason for this is to help link certain options 
		// together in order to apply custom javascript for certain 
		// common groups.
	   	if( $value['type'] == 'subgroup_start' ) {
	   		if( isset( $value['class'] ) ) $class = ' '.$value['class'];
	   		$output .= '<div class="subgroup'.$class.'">';
	   		continue;
	   	}
	   	if( $value['type'] == 'subgroup_end' ) {
	   		$output .= '</div><!-- .subgroup (end) -->';
	   		continue;
	   	}
	   	
	   	// Name Grouping (added by ThemeBlvd)
	   	// This allows certain options to be grouped together in the 
	   	// final saved options array by adding a common prefix to their
	   	// name form attributes.
	   	if( isset( $value['group'] ) )
	   		$option_name .= '['.$value['group'].']';
	   		
	   	// Sections (added by ThemeBlvd)
		// This allows for a wrapping div around certain sections. This 
		// is meant to create visual dividing styles between sections, 
		// opposed to sub groups, which are used to section off the code 
		// for hidden purposes.
	   	if( $value['type'] == 'section_start' ) {
	   		if( isset( $value['class'] ) ) $class = ' '.$value['class'];
	   		$output .= '<div class="postbox inner-section'.$class.'">';
	   		$output .= '<h3>' . esc_html( $value['name'] ) . '</h3>';
	   		if( isset($value['desc']) ) $output .= '<div class="section-description">'.$value['desc'].'</div>';
	   		continue;
	   	}
	   	if( $value['type'] == 'section_end' ) {
	   		$output .= '</div><!-- .inner-section (end) -->';
	   		continue;
	   	}
		
		// Wrap all options
		if ( ($value['type'] != "heading") && ($value['type'] != "info") ) {

			// Keep all ids lowercase with no spaces
			$value['id'] = preg_replace('/\W/', '', strtolower($value['id']) );

			$id = 'section-' . $value['id'];

			$class = 'section ';
			if ( isset( $value['type'] ) ) {
				$class .= ' section-' . $value['type'];
				if( $value['type'] == 'logo' ) {
					$class .= ' section-upload';
				}
			}
			if ( isset( $value['class'] ) ) {
				$class .= ' ' . $value['class'];
			}

			$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
			if( isset( $value['name'] ) ) // Name not required any more (edit by ThemeBlvd)
				$output .= '<h4 class="heading">' . esc_html( $value['name'] ) . '</h4>' . "\n";
			$output .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";
		 }
		
		// Set default value to $val
		if ( isset( $value['std']) ) {
			$val = $value['std'];
		}
		
		// If the option is already saved, ovveride $val (Modified by ThemeBlvd to check for grouping)
		if ( ($value['type'] != 'heading') && ($value['type'] != 'info')) {
			if( isset( $value['group'] ) ) {
				// Set grouped value
				if ( isset($settings[($value['group'])][($value['id'])]) ) {
					$val = $settings[($value['group'])][($value['id'])];
					// Striping slashes of non-array options
					if (!is_array($val)) {
						$val = stripslashes($val);
					}
				}
			} else {
				// Set non-grouped value
				if ( isset($settings[($value['id'])]) ) {
					$val = $settings[($value['id'])];
					// Striping slashes of non-array options
					if (!is_array($val)) {
						$val = stripslashes($val);
					}
				}
			}
		}
                          
		switch ( $value['type'] ) {
		
		// Basic text input
		case 'text':
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
		break;
		
		// Textarea
		case 'textarea':
			$cols = '8';
			$ta_value = '';
			
			if(isset($value['options'])){
				$ta_options = $value['options'];
				if(isset($ta_options['cols'])){
					$cols = $ta_options['cols'];
				} else { $cols = '8'; }
			}
			
			$val = stripslashes( $val );
			
			$output .= '<textarea id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" cols="'. esc_attr( $cols ) . '" rows="8">' . esc_textarea( $val ) . '</textarea>';
		break;
		
		// Select Box
		case ($value['type'] == 'select'):
			$output .= '<select class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '">';
			
			foreach ($value['options'] as $key => $option ) {
				$selected = '';
				 if( $val != '' ) {
					 if ( $val == $key) { $selected = ' selected="selected"';} 
			     }
				 $output .= '<option'. $selected .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
			 }
			 $output .= '</select>';
			 
			 // If this is a builder sample select, show preview images
			 if( isset( $value['class'] ) && $value['class'] == 'builder_samples' ) {
			 	if( function_exists( 'builder_blvd_sample_previews' ) ) {
			 		$output .= builder_blvd_sample_previews();	
			 	}
			 }
			 
		break;

		
		// Radio Box
		case "radio":
			$name = $option_name .'['. $value['id'] .']';
			foreach ($value['options'] as $key => $option) {
				$id = $option_name . '-' . $value['id'] .'-'. $key;
				$output .= '<input class="of-input of-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label>';
			}
		break;
		
		// Image Selectors
		case "images":
			$name = $option_name .'['. $value['id'] .']';
			foreach ( $value['options'] as $key => $option ) {
				$selected = '';
				$checked = '';
				if ( $val != '' ) {
					if ( $val == $key ) {
						$selected = ' of-radio-img-selected';
						$checked = ' checked="checked"';
					}
				}
				$output .= '<input type="radio" id="' . esc_attr( $value['id'] .'_'. $key) . '" class="of-radio-img-radio" value="' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" '. $checked .' />';
				$output .= '<div class="of-radio-img-label">' . esc_html( $key ) . '</div>';
				$output .= '<img src="' . esc_url( $option ) . '" alt="' . $option .'" class="of-radio-img-img' . $selected .'" onclick="document.getElementById(\''. esc_attr($value['id'] .'_'. $key) .'\').checked=true;" />';
			}
		break;
		
		// Checkbox
		case "checkbox":
			$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" '. checked( $val, 1, false) .' />';
		break;
		
		// Multicheck
		case "multicheck":
			foreach ($value['options'] as $key => $option) {
				$checked = '';
				$label = $option;
				$option = preg_replace('/\W/', '', strtolower($key));
				$id = $option_name . '-' . $value['id'] . '-'. $option;
				$name = $option_name . '[' . $value['id'] . '][' . $key .']';
			    if ( isset($val[$key]) ) {
					$checked = checked($val[$key], 1, false);
				}
				$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . $label . '</label>';
			}
		break;
		
		// Color picker
		case "color":
			$output .= '<div id="' . esc_attr( $value['id'] . '_picker' ) . '" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $val ) . '"></div></div>';
			$output .= '<input class="of-color" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '" type="text" value="' . esc_attr( $val ) . '" />';
		break; 
		
		// Uploader
		case "upload":
			$val = array( 'url' => $val, 'id' => '' ); // Added by ThemeBlvd
			$output .= optionsframework_medialibrary_uploader( $option_name, 'standard', $value['id'], $val ); // New AJAX Uploader using Media Library // Mod'd by ThemeBlvd	
		break;
		
		// Typography
		case 'typography':	
		
			$typography_stored = $val;
			
			// Font Size
			if( in_array( 'size', $value['atts'] ) ) {
				$output .= '<select class="of-typography of-typography-size" name="' . esc_attr( $option_name . '[' . $value['id'] . '][size]' ) . '" id="' . esc_attr( $value['id'] . '_size' ) . '">';
				for ($i = 9; $i < 71; $i++) { 
					$size = $i . 'px';
					$output .= '<option value="' . esc_attr( $size ) . '" ' . selected( $typography_stored['size'], $size, false ) . '>' . esc_html( $size ) . '</option>';
				}
				$output .= '</select>';
			}
		
			// Font Face
			if( in_array( 'face', $value['atts'] ) ) {
				$output .= '<select class="of-typography of-typography-face" name="' . esc_attr( $option_name . '[' . $value['id'] . '][face]' ) . '" id="' . esc_attr( $value['id'] . '_face' ) . '">';
				$faces = of_recognized_font_faces();
				foreach ( $faces as $key => $face ) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['face'], $key, false ) . '>' . esc_html( $face ) . '</option>';
				}			
				$output .= '</select>';	
			}
			
			/*
			// Font Weight
			$output .= '<select class="of-typography of-typography-style" name="'.$option_name.'['.$value['id'].'][style]" id="'. $value['id'].'_style">';
			$styles = of_recognized_font_styles();
			foreach ( $styles as $key => $style ) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['style'], $key, false ) . '>'. $style .'</option>';
			}
			$output .= '</select>';
			*/
				
			// Font Color
			if( in_array( 'color', $value['atts'] ) ) {		
				$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $typography_stored['color'] ) . '"></div></div>';
				$output .= '<input class="of-color of-typography of-typography-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $typography_stored['color'] ) . '" />';
			}
			
			$output .= '<div class="clear"></div>';
			
			// Google Font support (added by ThemeBlvd)
			if( in_array( 'face', $value['atts'] ) ) {
				$output .= '<div class="google-font hide">';
				$output .= '<h5>'.__( 'Enter the name of a font from the <a href="http://www.google.com/webfonts" target="_blank">Google Font Directory</a>.', TB_GETTEXT_DOMAIN ).'</h5>';
				$output .= '<input type="text" name="' . esc_attr( $option_name . '[' . $value['id'] . '][google]' ) . '" value="' . esc_attr( $typography_stored['google'] ) . '" />';
				$output .= '<p class="note">Example Font Name: "Hammersmith One"</p>';
				$output .= '</div>';
			}
			
		break;
		
		// Background
		case 'background':
			
			$background = $val;
			
			// Background Color		
			$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $background['color'] ) . '"></div></div>';
			$output .= '<input class="of-color of-background of-background-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $background['color'] ) . '" />';
			
			// Background Image - New AJAX Uploader using Media Library
			if (!isset($background['image'])) {
				$background['image'] = '';
			}
			
			$current_bg_image = array( 'url' => $background['image'], 'id' => '' ); // Added by ThemeBlvd
			$output .= optionsframework_medialibrary_uploader( $option_name, 'standard', $value['id'], $current_bg_image, null, '',0,'image'); // Mod'd by ThemeBlvd
			$class = 'of-background-properties';
			if ( '' == $background['image'] ) {
				$class .= ' hide';
			}
			$output .= '<div class="' . esc_attr( $class ) . '">';
			
			// Background Repeat
			$output .= '<select class="of-background of-background-repeat" name="' . esc_attr( $option_name . '[' . $value['id'] . '][repeat]'  ) . '" id="' . esc_attr( $value['id'] . '_repeat' ) . '">';
			$repeats = of_recognized_background_repeat();
			
			foreach ($repeats as $key => $repeat) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
			}
			$output .= '</select>';
			
			// Background Position
			$output .= '<select class="of-background of-background-position" name="' . esc_attr( $option_name . '[' . $value['id'] . '][position]' ) . '" id="' . esc_attr( $value['id'] . '_position' ) . '">';
			$positions = of_recognized_background_position();
			
			foreach ($positions as $key=>$position) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
			}
			$output .= '</select>';
			
			// Background Attachment
			$output .= '<select class="of-background of-background-attachment" name="' . esc_attr( $option_name . '[' . $value['id'] . '][attachment]' ) . '" id="' . esc_attr( $value['id'] . '_attachment' ) . '">';
			$attachments = of_recognized_background_attachment();
			
			foreach ($attachments as $key => $attachment) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
			}
			$output .= '</select>';
			$output .= '</div>';
		
		break;  
		
		// Info
		case "info":
			$class = 'section';
			if ( isset( $value['type'] ) ) {
				$class .= ' section-' . $value['type'];
			}
			if ( isset( $value['class'] ) ) {
				$class .= ' ' . $value['class'];
			}

			$output .= '<div class="' . esc_attr( $class ) . '">' . "\n";
			if ( isset($value['name']) ) {
				$output .= '<h4 class="heading">' . esc_html( $value['name'] ) . '</h4>' . "\n";
			}
			if ( isset( $value['desc'] ) ) {
				$output .= apply_filters('of_sanitize_info', $value['desc'] ) . "\n";
			}
			$output .= '<div class="clear"></div></div>' . "\n";
		break;
		
		// Columns Setup (added by ThemeBlvd)
		case "columns":
			$output .= themeblvd_columns_option( $value['options'], $value['id'], $option_name, $val );
		break;
		
		// Tabs Setup (added by ThemeBlvd)
		case "tabs":
			$output .= themeblvd_tabs_option( $value['id'], $option_name, $val );
		break;
		
		// Content (added by ThemeBlvd)
		// Originally designed to work in conjunction with setting up columns and tabs
		case "content":
			$output .= themeblvd_content_option( $value['id'], $option_name, $val, $value['options'] );
		break;
		
		// Conditionals (added by ThemeBlvd)
		// Originally designed to allow users to assign custom sidebars to certain pages.
		case "conditionals":
			$output .= themeblvd_conditionals_option( $value['id'], $option_name, $val );
		break; 
		
		// Logo (added by ThemeBlvd)
		case "logo":
			$output .= themeblvd_logo_option( $value['id'], $option_name, $val );
		break; 
		
		// Social Media (added by ThemeBlvd)
		case "social_media":
			$output .= themeblvd_social_media_option( $value['id'], $option_name, $val );
		break;                       
		
		// Heading for Navigation
		case "heading":
			if($counter >= 2){
			   $output .= '</div>'."\n";
			}
			$jquery_click_hook = preg_replace('/\W/', '', strtolower($value['name']) );
			$jquery_click_hook = "of-option-" . $jquery_click_hook;
			$menu .= '<a id="'.  esc_attr( $jquery_click_hook ) . '-tab" class="nav-tab" title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#'.  $jquery_click_hook ) . '">' . esc_html( $value['name'] ) . '</a>';
			$output .= '<div class="group" id="' . esc_attr( $jquery_click_hook ) . '">';
			//$output .= '<h3>' . esc_html( $value['name'] ) . '</h3>' . "\n";
			break;
		}

		if ( ( $value['type'] != "heading" ) && ( $value['type'] != "info" ) ) {
			if ( $value['type'] != "checkbox" ) {
				$output .= '<br/>';
			}
			$explain_value = '';
			if ( isset( $value['desc'] ) ) {
				$explain_value = $value['desc'];
			}
			
			$allowedtags = themeblvd_allowed_tags( true );
			$output .= '</div><div class="explain">' . wp_kses( $explain_value, $allowedtags) . '</div>'."\n";
			$output .= '<div class="clear"></div></div></div>'."\n";
		}
	}
    if( $close )
    	$output .= '</div>';
    return array($output,$menu);
}