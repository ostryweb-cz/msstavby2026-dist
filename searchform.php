<?php
/**
 * The search form template
 *
 * @package msstavby
 */

$form_action = home_url( '/' );
$search_query = get_search_query();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( $form_action ); ?>">
	<div class="input-group">
		<input
			type="search"
			class="form-control"
			name="s"
			value="<?php echo esc_attr( $search_query ); ?>"
			placeholder="Hledat..."
			aria-label="Hledat"
		>
		<button type="submit" class="btn btn-primary" aria-label="Hledat">
			<i class="bi bi-search"></i>
		</button>
	</div>
</form>