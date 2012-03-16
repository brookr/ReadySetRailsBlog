<?php
/*-----------------------------------------------------------------------------------*/
/* Front-end Action Functions
/*-----------------------------------------------------------------------------------*/

// <head>
function themeblvd_title() { do_action( 'themeblvd_title' ); }

// Before and after site
function themeblvd_before() { do_action( 'themeblvd_before' ); } // No default hooked function
function themeblvd_after() { do_action( 'themeblvd_after' ); } // No default hooked function

// Header
function themeblvd_header_top() { do_action( 'themeblvd_header_top' ); } // No default hooked function
function themeblvd_header_above() { do_action( 'themeblvd_header_above' ); }
function themeblvd_header_content() { do_action( 'themeblvd_header_content' ); }
function themeblvd_header_logo() { do_action( 'themeblvd_header_logo' ); }
function themeblvd_header_addon() { do_action( 'themeblvd_header_addon' ); } // No default hooked function
function themeblvd_header_menu() { do_action( 'themeblvd_header_menu' ); }
function themeblvd_header_menu_addon() { do_action( 'themeblvd_header_menu_addon' ); } // No default hooked function
function themeblvd_header_before() { do_action( 'themeblvd_header_before' ); } // No default hooked function
function themeblvd_header_after() { do_action( 'themeblvd_header_after' ); } // No default hooked function

// Featured area
function themeblvd_featured_start() { do_action( 'themeblvd_featured_start' ); }
function themeblvd_featured( $type ) { do_action( 'themeblvd_featured_'.$type ); } // No default hooked function
function themeblvd_featured_end() { do_action( 'themeblvd_featured_end' ); }

// Main content area
function themeblvd_main_start() { do_action( 'themeblvd_main_start' ); }
function themeblvd_main_top() { do_action( 'themeblvd_main_top' ); }
function themeblvd_main_bottom() { do_action( 'themeblvd_main_bottom' ); }
function themeblvd_main_end() { do_action( 'themeblvd_main_end' ); }
function themeblvd_breadcrumbs() { do_action( 'themeblvd_breadcrumbs' ); }
function themeblvd_before_layout() { do_action( 'themeblvd_before_layout' ); } // No default hooked function

// Footer
function themeblvd_footer_above() { do_action( 'themeblvd_footer_above' ); }  // No default hooked function
function themeblvd_footer_content() { do_action( 'themeblvd_footer_content' ); }
function themeblvd_footer_sub_content() { do_action( 'themeblvd_footer_sub_content' ); }
function themeblvd_footer_below() { do_action( 'themeblvd_footer_below' ); }
function themeblvd_footer_before() { do_action( 'themeblvd_footer_before' ); } // No default hooked function
function themeblvd_footer_after() { do_action( 'themeblvd_footer_after' ); } // No default hooked function

// Content
function themeblvd_content_top() { do_action( 'themeblvd_content_top' ); } // No default hooked function
function themeblvd_blog_meta() { do_action( 'themeblvd_blog_meta' ); }
function themeblvd_blog_tags() { do_action( 'themeblvd_blog_tags' ); }
function themeblvd_the_post_thumbnail( $location = 'primary', $size = '', $link = true ) { do_action( 'themeblvd_the_post_thumbnail', $location, $size, $link ); }
function themeblvd_blog_content( $type ) { do_action( 'themeblvd_blog_content', $type ); }
function themeblvd_single_footer() { do_action( 'themeblvd_single_footer' ); }
function themeblvd_page_footer() { do_action( 'themeblvd_page_footer' );}