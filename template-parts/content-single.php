<?php
/**
 * @package yabwt
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php echo yabwt_get_post_icon(); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'yabwt' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php if ( 'post' == get_post_type() ) : ?>
				<?php yabwt_posted_on(); ?>
		<?php endif; ?>
		<?php yabwt_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
