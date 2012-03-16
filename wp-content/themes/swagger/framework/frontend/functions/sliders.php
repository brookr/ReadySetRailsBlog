<?php
/*-----------------------------------------------------------------------------------*/
/* Slider Javascript
/*-----------------------------------------------------------------------------------*/

/**
 * Print out the JS for setting up a standard slider.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_standard_slider_js' ) ) {
	function themeblvd_standard_slider_js( $id, $options ) {
		?>
		<script>
		jQuery(document).ready(function($) {
			$(window).load(function() {
				$('#tb-slider-<?php echo $id; ?> .flexslider').flexslider({
					animation: "<?php echo $options['fx']; ?>",
					<?php if( $options['timeout'] ) : ?>
					slideshowSpeed: <?php echo $options['timeout']; ?>000,
					<?php else : ?>
					slideshow: false,
					<?php endif; ?>
					<?php if( ! $options['nav_arrows'] ) echo 'directionNav: false,'; ?>
					<?php if( ! $options['nav_standard'] ) echo 'controlNav: false,'; ?>
					controlsContainer: ".slides-wrapper-<?php echo $id; ?>",
					start: function(slider) {
        				<?php if( $options['pause_play'] && $options['timeout'] != '0' ) : ?>
	        				$('#tb-slider-<?php echo $id; ?> .flex-direction-nav li:first-child').after('<li><a class="pause" href="#">Pause</a></li><li><a class="play" href="#" style="display:none">Play</a></li>');
	        				$('#tb-slider-<?php echo $id; ?> .pause').click(function(){
								slider.pause();
								$(this).hide();
								$('#tb-slider-<?php echo $id; ?> .play').show();
								return false;
							});
							$('#tb-slider-<?php echo $id; ?> .play').click(function(){
								slider.resume();
								$(this).hide();
								$('#tb-slider-<?php echo $id; ?> .pause').show();
								return false;
							});
							$('#tb-slider-<?php echo $id; ?> .flex-control-nav li, #tb-slider-<?php echo $id; ?> .flex-direction-nav li').click(function(){
								$('#tb-slider-<?php echo $id; ?> .pause').hide();
								$('#tb-slider-<?php echo $id; ?> .play').show();
							});
        				<?php endif; ?>
        				$('#tb-slider-<?php echo $id; ?> .tb-loader').fadeOut();
        				$('#tb-slider-<?php echo $id; ?> .image-link').click(function(){
        					$('#tb-slider-<?php echo $id; ?> .pause').hide();
        					$('#tb-slider-<?php echo $id; ?> .play').show();
        					slider.pause();
        				});
        			}
				});
			});
		});
		</script>
		<?php
	}
}

/**
 * Print out the JS for setting up a carrousel slider.
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_carrousel_slider_js' ) ) {
	function themeblvd_carrousel_slider_js( $id, $options ) {
		?>
		<script>
		jQuery(document).ready(function($) {
			$(window).load(function() {
				$('#tb-slider-<?php echo $id; ?> .tb-loader').fadeOut('fast');
				$('#tb-slider-<?php echo $id; ?> .slider-inner').fadeIn('fast');
				$('#tb-slider-<?php echo $id; ?> .carrousel-slider').roundabout({
					minOpacity: '1',
					<?php if( $options['nav_arrows'] ) : ?>
					btnNext: '#tb-slider-<?php echo $id; ?> .next',
         			btnPrev: '#tb-slider-<?php echo $id; ?> .prev'
         			<?php endif; ?>
				});
			});
			$(window).resize(function() {
				$('#tb-slider-<?php echo $id; ?> .carrousel-slider').roundabout_animateToBearing($.roundabout_getBearing($('#tb-slider-<?php echo $id; ?> .carrousel-slider')));
			});
		});
		</script>
		<?php
	}
}

/*-----------------------------------------------------------------------------------*/
/* Slider Default Actions
/*-----------------------------------------------------------------------------------*/

/**
 * Standard Slider - default action for themeblvd_standard_slider
 *
 * @since 2.0.0
 */

if( ! function_exists( 'themeblvd_standard_slider_default' ) ) {
	function themeblvd_standard_slider_default( $slider, $settings, $slides ) {
		
		// Configure additional CSS classes
		$classes = themeblvd_get_classes( 'slider_standard', true );
		$settings['nav_standard'] == '1' ? $classes .= ' show-nav_standard' : $classes .= ' hide-nav_standard';
		$settings['nav_arrows'] == '1' ? $classes .= ' show-nav_arrows' : $classes .= ' hide-nav_arrows';
		$settings['pause_play'] == '1' ? $classes .= ' show-pause_play' : $classes .= ' hide-pause_play';
		
		// Start output
		themeblvd_standard_slider_js( $slider, $settings );
		?>
		<div id="tb-slider-<?php echo $slider; ?>" class="slider-wrapper standard-slider-wrapper">
			<div class="slider-inner<?php echo $classes; ?>">	
				<div class="slides-wrapper slides-wrapper-<?php echo $slider; ?>">
					<div class="slides-inner">
						<div class="slider standard-slider flexslider">
							<div class="tb-loader"></div>
							<ul class="slides">
								<?php if( ! empty( $slides ) ) : ?>
									<?php foreach( $slides as $slide ) : ?>
										<?php
										if( ! isset( $slide['custom'] ) ) {
											// Setup CSS classes									
											$classes = 'media-'.$slide['position'].' '.$slide['slide_type'].'-slide';									
											if( $slide['position'] == 'full' && $slide['slide_type'] == 'image' )
												$classes .= ' full-image';
											// Image setup
											if( $slide['slide_type'] == 'image' ) {
												// Image Size
												if( $slide['position'] == 'full' )
													$image_size = 'slider-large';
												else
													$image_size = 'slider-staged';
												// Image URL
												$attachment = wp_get_attachment_image_src( $slide['image']['id'], $image_size );												
												$image_url = null;
												if( $attachment )
													$image_url = $attachment[0];
												if( ! $image_url ) {
													// Attachment ID didn't exist
													if( $slide['image']['url'] )
														$image_url = $slide['image']['url'];
													else
														$image_url = themeblvd_placeholder_image( $image_size );
												}
											}
											// Video Setup
											if( $slide['slide_type'] == 'video' && $slide['position'] == 'full' ) {
												$slide['elements']['headline'] = null; // Backup in case user did soemthing funky
												$slide['elements']['description'] = null; // Backup in case user did soemthing funky
												$slide['elements']['button']['url'] = null; // Backup in case user did soemthing funky
											}
											if( $slide['slide_type'] == 'video' ) {	
												// Attributes
												if( $slide['position'] == 'full' )
													$atts = array( 'height' => '350' );
												else
													$atts = array( 'width' => '564' );
												// Get HTML
												$video = wp_oembed_get( $slide['video'], $atts );
												// Set error message
												if( ! $video )
													$video = '<p>'.themeblvd_get_local( 'no_video' ).'</p>';
											}
											// Elements
											$elements = array();
											if( isset( $slide['elements']['include'] ) && is_array( $slide['elements']['include'] ) )
												$elements = $slide['elements']['include'];
											if( $slide['slide_type'] == 'video' && $slide['position'] == 'full' )
												$elements = array(); // Full width video slide can't have elements.
										}
										?>
										<li class="slide tight <?php echo $classes; ?>">
											<div class="slide-body">
												<div class="grid-protection">
													<?php // Custom Slides ?>
													<?php if( isset( $slide['custom'] ) ) : ?>
														<?php echo $slide['custom']; ?>
													<?php // Video and Image Slides ?>
													<?php else : ?>
														<?php if( in_array( 'headline', $elements ) || in_array( 'description', $elements ) || in_array( 'button', $elements ) ) : ?>
															<div class="content<?php if($slide['position'] != 'full') echo ' grid_fifth_2'; ?>">
																<div class="content-inner">	
																	<?php if( in_array( 'headline', $elements ) && $slide['elements']['headline'] ) : ?>
																		<div class="slide-title"><span><?php echo stripslashes( $slide['elements']['headline'] ); ?></span></div>
																	<?php endif; ?>
																	<?php if( in_array( 'description', $elements ) || in_array( 'button', $elements ) ) : ?>
																		<div class="slide-description">
																			<span>
																				<?php if( in_array( 'description', $elements ) ) : ?>
																					<p class="slide-description-text"><?php echo stripslashes( $slide['elements']['description'] ); ?></p>
																				<?php endif; ?>
																				<?php if( in_array( 'button', $elements ) && $slide['elements']['button']['text'] ) : ?>
																					<p class="slide-description-button"><?php echo themeblvd_button( stripslashes( $slide['elements']['button']['text'] ), $slide['elements']['button']['url'], 'default', $slide['elements']['button']['target'], 'medium' ); ?></p>
																				<?php endif; ?>
																			</span>
																		</div><!-- .description (end) -->
																	<?php endif; ?>
																</div><!-- .content-inner (end) -->
															</div><!-- .content (end) -->
														<?php endif; ?>
														<div class="media <?php echo $slide['slide_type']; if($slide['position'] != 'full') echo ' grid_fifth_3'; ?>">
															<div class="media-inner">
																<?php if( $slide['slide_type'] == 'image' ) : ?>
																	<?php if( in_array( 'image_link', $elements ) && $slide['elements']['image_link']['url'] ) : ?>
																		<?php if( $slide['elements']['image_link']['target'] == 'lightbox' ) : ?>
																			<a href="<?php echo $slide['elements']['image_link']['url']; ?>" class="image-link enlarge" rel="themeblvd_lightbox" title=""><span>Image Link</span></a>
																		<?php else : ?>
																			<a href="<?php echo $slide['elements']['image_link']['url']; ?>" target="<?php echo $slide['elements']['image_link']['target']; ?>" class="image-link external"><span>Image Link</span></a>
																		<?php endif; ?>
																	<?php endif; ?>
																	<img src="<?php echo $image_url; ?>" />
																<?php else : ?>
																	<?php echo $video; ?>
																<?php endif; ?>
															</div><!-- .media-inner (end) -->
														</div><!-- .media (end) -->
													<?php endif; ?>
												</div><!-- .grid-protection (end) -->
											</div><!-- .slide-body (end) -->
										</li>
									<?php endforeach; ?>
								<?php endif; ?>								
							</ul>
						</div><!-- .slider (end) -->
					</div><!-- .slides-inner (end) -->					
				</div><!-- .slides-wrapper (end) -->
			</div><!-- .slider-inner (end) -->
			<div class="design-1"></div>
			<div class="design-2"></div>
			<div class="design-3"></div>
			<div class="design-4"></div>					
		</div><!-- .slider-wrapper (end) -->
		<?php
	}
}

/**
 * Carrousel Slider - default action for themeblvd_carrousel_slider
 *
 * @since 2.0.0
 */
 
if( ! function_exists( 'themeblvd_carrousel_slider_default' ) ) {
	function themeblvd_carrousel_slider_default( $slider, $settings, $slides ) {
		themeblvd_carrousel_slider_js( $slider, $settings );
		$classes = themeblvd_get_classes( 'slider_carrousel', true );
		?>
		<div id="tb-slider-<?php echo $slider; ?>" class="slider-wrapper carrousel-slider-wrapper<?php echo $classes; ?>">
			<div class="tb-loader"></div>
			<div class="slider-inner">
				<?php if( $settings['nav_arrows'] ) : ?>
				<div class="roundabout-nav">
					<a href="#" title="Previous" class="prev">Previous</a>
					<a href="#" title="Next" class="next">Next</a>
				</div><!-- .roundabout-nav (end) -->
				<?php endif; ?>
				<ul class="carrousel-slider">
					<?php foreach( $slides as $slide ) : ?>
						<li class="slide">
							<div class="slide-body">
								<div class="grid-protection">
									<?php
									// Image
									$attachment = wp_get_attachment_image_src( $slide['image']['id'], 'grid_4' );
									$image_url = null;
									if( $attachment ) {
										if( $slide['image']['url'] ) {
											$url = explode( '/', $slide['image']['url'] );
											$url = explode( '.', end( $url ) );
											$match = strpos( $attachment[0], $url[0] );
											if( $match )
												$image_url = $attachment[0];
										}
									}
									if( ! $image_url ) {
										// Attachment ID didn't exist or ID didn't match URL.
										if( $slide['image']['url'] )
											$image_url = $slide['image']['url'];
										else
											$image_url = themeblvd_placeholder_image( 'slider-staged' );
									}
									// Elements
									$elements = array();
									if( isset( $slide['elements']['include'] ) && is_array( $slide['elements']['include'] ) )
										$elements = $slide['elements']['include'];
									?>
									<?php if( in_array( 'image_link', $elements ) ) : ?>
										<?php if( $slide['elements']['image_link']['target'] == 'lightbox' ) : ?>
											<a href="<?php echo $slide['elements']['image_link']['url']; ?>" class="image-link enlarge" rel="themeblvd_lightbox" title=""><span>Image Link</span></a>
										<?php else : ?>
											<a href="<?php echo $slide['elements']['image_link']['url']; ?>" target="<?php echo $slide['elements']['image_link']['target']; ?>" class="image-link external"><span>Image Link</span></a>
										<?php endif; ?>
									<?php endif; ?>
									<img src="<?php echo $image_url; ?>" />
								</div><!-- .grid-protection (end) -->
							</div><!-- .slide-body (end) -->
						</li>
					<?php endforeach; ?>
				</ul>
			</div><!-- .slider-inner (end) -->
		</div><!-- .slider-wrapper (end) -->
		<?php
	}
}