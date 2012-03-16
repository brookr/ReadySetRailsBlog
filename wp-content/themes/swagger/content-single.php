<?php
/**
 * The template used for displaying single post content in single.php
 */
global $location;
?>
<div class="article-wrap single-post">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<?php themeblvd_the_post_thumbnail( $location ); ?>
			<?php the_content(); ?>
			<?php themeblvd_blog_tags(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', TB_GETTEXT_DOMAIN ), 'after' => '</div>' ) ); ?>
			<?php edit_post_link( __( 'Edit', TB_GETTEXT_DOMAIN ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-content -->
	</article><!-- #post-<?php the_ID(); ?> -->
</div><!-- .article-wrap (end) -->