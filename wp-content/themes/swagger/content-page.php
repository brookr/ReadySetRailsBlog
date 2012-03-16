<?php
/**
 * The template used for displaying page content in page.php
 */
?>
<div class="article-wrap">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', TB_GETTEXT_DOMAIN ), 'after' => '</div>' ) ); ?>
			<?php edit_post_link( __( 'Edit', TB_GETTEXT_DOMAIN ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-content -->
	</article><!-- #post-<?php the_ID(); ?> -->
</div><!-- .article-wrap (end) -->