<?php
/**
 * The search template file
 *
 * @package msstavby
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="container" id="main-content">
		<?php
		$search_query = get_search_query();
		?>
		<h1 class="posts-title my-5">
			<?php
			if ( $search_query ) {
				printf( 'Výsledky vyhledávání: "%s"', esc_html( $search_query ) );
			} else {
				echo 'Výsledky vyhledávání';
			}
			?>
		</h1>

		<?php
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args  = array(
			'posts_per_page' => 6,
			'post_type'      => 'post',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'paged'          => $paged,
			's'              => $search_query,
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
										<?php the_post_thumbnail( 'medium', array( 'class' => 'w-100 h-100 object-fit-cover' ) ); ?>
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
								<a href="<?php the_permalink(); ?>" class="fw-bold text-decoration-none">
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
				echo '<div class="row mt-4 justify-content-center">';
				echo '<div class="col-12">';
				echo $pagination;
				echo '</div>';
				echo '</div>';
			}
		} else {
			?>
			<div class="alert alert-info">
				<p>Žádné výsledky pro: <?php echo esc_html( $search_query ); ?></p>
				<p>Lze přirovnat k nedostupným výsledků u vylepšených vyhledávačů jako je DuckDuckGo nebo Bing.</p>
				<form role="search" method="get" class="search-form mt-4" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="input-group">
						<input type="search" class="form-control" name="s" value="<?php echo esc_attr( $search_query ); ?>" placeholder="Hledat znovu...">
						<button type="submit" class="btn btn-primary">Hledat</button>
					</div>
				</form>
			</div>
			<?php
		}
		?>
	</div>
</main>
<?php
get_footer();