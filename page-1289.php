<?php
/**
 * Template for Page ID 1289 (Mapa)
 * Dedicated template for the map page with full-width map functionality
 *
 * @package msstavby
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container" id="main-content">
		<?php
		// Query for page 1289
		$page_query = new WP_Query(array(
			'post_type' => 'page',
			'page_id' => 1289,
		));

		if ($page_query->have_posts()) {
			while ($page_query->have_posts()) {
				$page_query->the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php admin_comment('Title'); ?>
					<header class="entry-header mb-0">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header><?php admin_comment('.entry-header'); ?>

					<?php admin_comment('60px space'); ?>
					<div class="spacer-60"></div>

					<?php admin_comment('Map Section'); ?>
					<?php get_template_part('partials/map-section'); ?>

				</article><?php admin_comment('#post-'.get_the_ID()); ?>
				<?php
			}
			wp_reset_postdata();
	}
	?>
	</div><?php admin_comment('.container'); ?>
</main><?php admin_comment('#primary'); ?>
<?php
get_footer();
