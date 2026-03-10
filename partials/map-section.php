<?php
/**
 * Map Section Partial
 * Displays the Geo Mashup map with optional category filter
 *
 * @package msstavby
 */
?>

<?php admin_comment('Categories Filter'); ?>
<div class="categories-filter mb-4">
	<?php
	$categories = get_categories(array(
		'hide_empty' => true,
		'orderby' => 'name',
		'order' => 'ASC'
	));
	?>
	<div class="d-flex flex-wrap gap-2 mb-3">
		<button type="button" class="btn btn-outline-primary filter-chip" data-filter="all">Všechny lokality</button>
		<?php foreach ($categories as $category): ?>
			<button type="button" class="btn btn-outline-light filter-chip" data-filter="<?php echo esc_attr($category->term_id); ?>">
				<?php echo esc_html($category->name); ?>
			</button>
		<?php endforeach; ?>
	</div>
</div><?php admin_comment('.categories-filter'); ?>

<?php admin_comment('Geo Mashup Map'); ?>
<div id="geo-map-container" data-lazy-load="true" class="geo-mashup-map-wrapper">
	<?php 
	// Map will be dynamically loaded via JavaScript to improve performance
	// The map will only load when it enters the viewport
	?>
	<div class="loading text-center py-5">
		<div class="spinner-border text-primary" role="status">
			<span class="visually-hidden">Načítání...</span>
		</div>
		<p class="mt-3">Mapa se načítá...</p>
	</div>
</div><?php admin_comment('#geo-map-container'); ?>
