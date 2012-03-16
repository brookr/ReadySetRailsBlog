<?php
/**
 * The template used for displaying content in attachment.php
 */
?>
<div class="article-wrap single-post">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<?php $attachment = wp_get_attachment_image_src( $post->ID, 'full' ); ?>
			<img src="<?php echo $attachment[0]; ?>" alt="<?php the_title(); ?>" />
			<?php the_content(); ?>
			<?php edit_post_link( __( 'Edit', TB_GETTEXT_DOMAIN ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-content -->
	</article><!-- #post-<?php the_ID(); ?> -->
</div><!-- .article-wrap (end) -->