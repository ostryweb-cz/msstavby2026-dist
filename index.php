<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package Ostryweb
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container" id="main-content">
		<?php
		if ( have_posts() ) {
			// Start the Loop
			while ( have_posts() ) {
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card mb-4' ); ?>>
					<?php if ( has_post_thumbnail() ) { ?>
						<div class="card-img-top position-relative mb-3">
							<a href="<?php the_permalink(); ?>">
								<?php echo get_the_post_thumbnail( get_the_ID(), 'medium', array( 'class' => 'w-100 h-100 object-fit-cover' ) ); ?>
							</a>
						</div>
					<?php } ?>
					<h2 class="card-title">
						<a href="<?php the_permalink(); ?>" class="text-decoration-none">
							<?php the_title(); ?>
						</a>
					</h2>
					<div class="entry-header">
						<small class="text-muted">
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
								<?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?>
							</time>
						</small>
					</div>
					<?php if ( get_the_excerpt() ) { ?>
						<p class="card-text"><?php echo get_the_excerpt(); ?></p>
					<?php } ?>
					<a href="<?php the_permalink(); ?>" class="fw-bold text-decoration-none">&rarr; Číst dále</a>
				</article>
			<?php
		}

		// Pagination
		the_posts_pagination(
				array(
					'prev_text' => '&lsaquo;',
					'next_text' => '&rsaquo;',
				)
			);

		} else {
			?>
			<p><?php _e( 'Sorry, no posts found.', 'ostryweb' ); ?></p>
		<?php
	}
	?>
	</div><?php admin_comment('.container'); ?>
</main><?php admin_comment('#primary'); ?>
<?php
get_footer();