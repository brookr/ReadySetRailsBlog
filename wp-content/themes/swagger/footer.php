<?php
/**
 * The template for displaying the footer.
 *  
 * WARNING: This template file is a core part of the 
 * Theme Blvd WordPress Framework. This framework is 
 * designed around this file NEVER being altered. It 
 * is advised that any edits to the way this file 
 * displays its content be done with via hooks and filters.
 * 
 * @author		Jason Bobich
 * @copyright	Copyright (c) Jason Bobich
 * @link		http://jasonbobich.com
 * @link		http://themeblvd.com
 * @package 	Theme Blvd WordPress Framework
 */
?>
		<!-- FOOTER (start) -->
		
		<?php themeblvd_footer_before(); ?>
		<div id="bottom">
			<footer id="colophon" role="contentinfo">
				<div class="content">
					<?php
					/**
					 * Display footer elements.
					 */
					themeblvd_footer_content();
					themeblvd_footer_sub_content();
					themeblvd_footer_below();
					?>
				</div><!-- .content (end) -->
			</footer><!-- #colophon (end) -->
		</div><!-- #bottom (end) -->
		<?php themeblvd_footer_after(); ?>
		
		<!-- FOOTER (end) -->
	
	</div><!-- #container (end) -->
</div><!-- #wrapper (end) -->
<?php themeblvd_after(); ?>
<?php wp_footer(); ?>
</body>
</html>