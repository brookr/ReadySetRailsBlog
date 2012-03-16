<?php
/**
 * The template used for displaying posts in a grid.
 */
global $columns;
global $size;
global $counter;
global $location;
?>
<div class="grid-item column <?php echo $size; ?><?php if( $counter % $columns == 0 ) echo ' last'; ?>">
	<div class="article-wrap">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry-content">
				<?php themeblvd_the_post_thumbnail( $location, $size ); ?>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			</div><!-- .entry-content -->
		</article><!-- #post-<?php the_ID(); ?> -->
	</div><!-- .article-wrap (end) -->
</div><!-- .grid-item (end) -->