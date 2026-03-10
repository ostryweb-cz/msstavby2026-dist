<?php
/**
 * The home/blog template file
 *
 * @package msstavby
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container" id="main-content">
	<h1 class="posts-title my-5"><?php bloginfo( 'name' ); ?></h1>

	<?php admin_comment('Categories Filter (Lokality)'); ?>
		<section class="mb-5">
			<h2 class="h4 mb-3">Filtrujte podle lokality</h2>
			<div class="category-filter-scroll">
				<button class="filter-chip filter-chip-active" data-filter="all" data-checked="true">
					<i class="bi bi-check-circle"></i>
					Všechny
				</button>
				<?php
				$categories = get_categories(
					array(
						'orderby'    => 'name',
						'order'      => 'ASC',
						'exclude'    => '1',
						'hide_empty' => false,
					)
				);
				foreach ( $categories as $category ) {
					?>
					<button class="filter-chip" data-filter="<?php echo esc_attr( $category->term_id ); ?>" data-type="checkbox">
						<?php echo esc_html( $category->name ); ?>
						<span class="badge bg-secondary"><?php echo $category->count; ?></span>
					</button>
				<?php } ?>
			</div>
		</section>

		<?php
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args  = array(
			'posts_per_page' => 6,
			'post_type'      => 'post',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'paged'          => $paged,
		);
		$posts = new WP_Query( $args );

		if ( $posts->have_posts() ) {
			?>
			<div class="row" id="projects">
				<?php
				while ( $posts->have_posts() ) {
					$posts->the_post();
					?>
					<div class="col-12 col-md-4 mb-4">
						<div class="card h-100">
					<?php if ( has_post_thumbnail() ) { ?>
						<div class="card-img-top position-relative">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-100 h-100 object-fit-cover' ) ); ?>
								</a>
							</div>
						<?php } else { ?>
						<div class="card-img-top position-relative">
							<a href="<?php the_permalink(); ?>">
								<img src="<?php echo get_template_directory_uri(); ?>/images/blank.png" width="235" height="150" alt="" class="w-100 object-fit-cover" style="background-color: #222;" loading="lazy">
							</a>
						</div>
					<?php } ?>
							<div class="card-body d-flex flex-column">
								<small class="text-muted mb-2">
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
										<?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?>
									</time>
								</small>
								<h5 class="card-title">
									<a href="<?php the_permalink(); ?>" class="text-decoration-none">
										<?php the_title(); ?>
									</a>
								</h5>
								<p class="card-text flex-grow-1">
									<?php echo get_the_excerpt(); ?>
								</p>
							<div class="d-flex justify-content-between align-items-center mt-auto">
								<a href="<?php the_permalink(); ?>" class="read-more-link">
									&rarr; Číst dále
								</a>
									<?php if ( comments_open() ) { ?>
										<span class="badge bg-secondary">
											<i class="bi bi-chat-dots"></i> <?php echo get_comments_number(); ?>
										</span>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>

			<?php
			// Pagination
			$big       = 999999999;
			$pagination = paginate_links(
				array(
					'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format'    => '?paged=%#%',
					'current'   => max( 1, $paged ),
					'total'     => $posts->max_num_pages,
					'type'      => 'list',
					'prev_text' => '&laquo;',
					'next_text' => '&raquo;',
				)
			);
			wp_reset_postdata();
			if ( $pagination ) {
				// Convert WordPress pagination to Bootstrap 5 pagination
				$pagination = str_replace( "<ul class='page-numbers'>", "<nav aria-label='Page navigation'><ul class='pagination'>", $pagination );
				$pagination = str_replace( "</ul>", "</ul></nav>", $pagination );
				$pagination = str_replace( "<li><a class='page-numbers'", "<li class='page-item'><a class='page-link'", $pagination );
				$pagination = str_replace( "</a></li>", "</a></li>", $pagination );
				$pagination = str_replace( "<li><span aria-current='page' class='page-numbers current'", "<li class='page-item active'><span aria-current='page' class='page-link'", $pagination );
				$pagination = str_replace( "<li><span class='page-numbers dots'", "<li class='page-item disabled'><span class='page-link'", $pagination );
				$pagination = str_replace( "</span></li>", "</span></li>", $pagination );
			echo '<div class="row mt-4">';
			echo '<div class="col-12 position-relative" style="min-height: 60px;">';
			echo '<div class="position-absolute top-50 start-50 translate-middle">';
			echo '<button type="button" class="btn-filter-chip" id="load-more-posts">Další projekty</button>';
			echo '</div>';
			echo '<div class="position-absolute top-50 translate-middle-y end-0" id="posts-pagination">';
			echo $pagination;
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		}
		?>

		<?php admin_comment('AD Block Position 1'); ?>
		<div class="ad-block"></div>
		<?php admin_comment('AD Block Position 2'); ?>
		<div class="ad-block"></div>
	
	</div><?php admin_comment('.container'); ?>

	<?php
	// Latest Comments Section
	$latest_comments = get_comments(
		array(
			'number' => 6,
			'status' => 'approve',
			'type'   => 'comment',
		)
	);

	if ( $latest_comments ) {
		?>
		<section class="latest-comments-section">
			<div class="container py-4">
				<h2 class="comments-title mb-4">Poslední komentáře</h2>
				<div class="comments-grid row">
				  <?php
				  foreach ( $latest_comments as $comment ) {
				    $post              = get_post( $comment->comment_post_ID );
				    $comment_content   = wp_kses_post( $comment->comment_content );
				  ?>
				  <div class="col-12 col-md-4 mb-3">
				    <div class="comment-card h-100">
				      <div class="comment-card-header">
				        <div class="comment-author-name">
						  <strong>
						    <?php echo esc_html( $comment->comment_author ); ?>
						  </strong>
						  <span class="text-muted">
						    <?php echo esc_html( get_comment_time( 'H:i', $comment->comment_ID ) . ' &#9679; ' . get_comment_date( 'd.m.', $comment->comment_ID ) ); ?>
						  </span>
						</div>
				      </div>
				      <div class="comment-card-body">
						<p class="mb-2 comment-text">
						  <?php echo wp_trim_words( $comment_content, 50, '...' ); ?>
						</p>
						<small class="text-muted">
						  <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" class="text-decoration-none text-muted">
						    <?php echo esc_html( get_the_title( $post ) ); ?>
						  </a>
						</small>
				      </div>
				    </div>
				  </div>
				<?php } ?>
                </div>
            </div>
		</section>
		<?php } ?>

		<div class="container">
		<?php admin_comment('AD Block Position 3'); ?>
			<div class="ad-block"></div>
		</div>

		<?php get_template_part('partials/map-section'); ?>

		<?php admin_comment('AD Block Position 4'); ?>
		<div class="ad-block"></div>
</main><?php admin_comment('#primary'); ?>
<?php
get_footer();
