<?php
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );

// ============================================================
// THEME SETUP
// ============================================================

if ( !function_exists( 'msstavby_setup' ) ) {
	function msstavby_setup() {
		add_editor_style( 'style.css' );
	}
	add_action( 'after_setup_theme', 'msstavby_setup' );
}

// ============================================================
// ASSET VERSION MANAGEMENT
// ============================================================

function msstavby_get_asset_version($asset_filename) {
	// For theme version, use WordPress standard: style.css Version header
	if ($asset_filename === 'style.css') {
		return wp_get_theme()->get('Version');
	}
	
	// For individual assets, use version.json
	$versions_file = get_template_directory() . '/version.json';
	if (!file_exists($versions_file)) {
		return '1.0.0'; // fallback version
	}
	
	$versions_data = json_decode(file_get_contents($versions_file), true);
	if (!$versions_data || !isset($versions_data['assets'][$asset_filename])) {
		return '1.0.0'; // fallback version
	}
	
	return $versions_data['assets'][$asset_filename];
}

// ============================================================
// ENQUEUE STYLES AND SCRIPTS
// ============================================================

add_action( 'wp_enqueue_scripts', function() {
// Google Fonts: Plus Jakarta Sans
	wp_enqueue_style( 'google-fonts-plus-jakarta-sans', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap', array(), null );
	
	// Bootstrap 5 CSS
	wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2' );
	
	// Bootstrap Icons
	wp_enqueue_style( 'bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css', array('bootstrap-css'), '1.11.1' );
	
// Theme styles
	$style_version = msstavby_get_asset_version('style.css');
	$style_semantic_version = msstavby_get_asset_version('style-semantic.css');
	$script_version = msstavby_get_asset_version('script.min.js');
	
	wp_enqueue_style( 'style', get_theme_file_uri( 'style.css' ), array( 'bootstrap-css', 'google-fonts-plus-jakarta-sans' ), $style_version );
wp_enqueue_style( 'style-semantic', get_theme_file_uri( 'style-semantic.css' ), array( 'style', 'google-fonts-plus-jakarta-sans' ), $style_semantic_version );
	
	// Bootstrap 5 JS (depends on jQuery)
	wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.3.2', true );
	
	// Custom scripts
	wp_enqueue_script( 'custom-script', get_theme_file_uri() . '/script.min.js', array( 'jquery' ), $script_version, true );
	
	// JS Alert
	wp_enqueue_script( 'js-alert', 'https://unpkg.com/js-alert/dist/jsalert.min.js', array(), null, true );
});

// ============================================================
// BLOCK PATTERNS
// ============================================================

function msstavby_register_block_patterns() {
	$msstavby_block_pattern_categories = apply_filters( 'msstavby_block_pattern_categories', array(
		'msstavby-general' => array(
			'label' => esc_html__( 'MS Stavby General', 'msstavby' ),
		),
		'msstavby-footer' => array(
			'label' => esc_html__( 'MS Stavby Footer', 'msstavby' ),
		),
		'msstavby-header' => array(
			'label' => esc_html__( 'MS Stavby Header', 'msstavby' ),
		),
		'msstavby-menu' => array(
			'label' => esc_html__( 'MS Stavby Menu', 'msstavby' ),
		),
		'msstavby-post_list' => array(
			'label' => esc_html__( 'MS Stavby Post_List', 'msstavby' ),
		),
	) );

	uasort( $msstavby_block_pattern_categories, function( $a, $b ) { 
		return strcmp( $a["label"], $b["label"] ); 
	});

	foreach ( $msstavby_block_pattern_categories as $slug => $settings ) {
		register_block_pattern_category( $slug, $settings );
	}
}

// ============================================================
// PERMALINK MANAGEMENT
// ============================================================

function reset_permalinks() {
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure('/%postname%/');
	$wp_rewrite->flush_rules(true);
}

// ============================================================
// PAGE AND MENU SETUP
// ============================================================

function add_pages(){
	// Page creation disabled - pages should be created manually in WordPress admin
}

function add_menus(){
	// Menu creation disabled - menus should be created manually in WordPress admin
}

// ============================================================
// THEME SUPPORT
// ============================================================

if ( !function_exists( 'msstavby_theme_support' ) ) {
	function msstavby_theme_support() {
		add_theme_support('menus');
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'post-formats', array( 'image', 'gallery', 'video', 'audio' ) );
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'msstavby' ),
			'footer' => esc_html__( 'Footer Menu', 'msstavby' ),
			'social' => esc_html__( 'Social Networks', 'msstavby' ),
			'learn-more' => esc_html__( 'Learn More', 'msstavby' )
		) );
		set_post_thumbnail_size( 235, 150, true );
	}
}

// ============================================================
// PLUGIN MANAGEMENT
// ============================================================

function plugin_installation(){
	// Plugin installation disabled - plugins must be installed manually
}

// ============================================================
// ADMIN BAR AND LOGIN CUSTOMIZATION
// ============================================================

function remove_admin_bar() {
	if (current_user_can('administrator')) {
		add_filter( 'show_admin_bar', '__return_true' );
	} else {
		add_filter( 'show_admin_bar', '__return_false' );
	}
}

function get_global_script() {
	// Global script loading disabled
}

function remove_demo_menu() {
	remove_submenu_page( 'tools.php', 'export.php' );
	remove_submenu_page( 'tools.php', 'export-personal-data.php');
}

function remove_default_endpoints_smarter( $endpoints ) {
	$prefix = '/wp-block-editor/v1/export';
	foreach ( $endpoints as $endpoint => $details ) {
		if ( fnmatch($prefix, $endpoint, FNM_CASEFOLD ) ) {
			unset( $endpoints[$endpoint] );
		}
	}
	return $endpoints;
}

function custom_admin_login() { 
	echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('stylesheet_directory').'/login.css" />';
}
add_action('login_head', 'custom_admin_login');

function put_my_url(){
	return site_url();
}

function put_my_title($message) {
	return get_bloginfo('description');
}

add_filter("login_headertitle","put_my_title");
add_filter('login_headerurl', 'put_my_url');

// ============================================================
// BROWSER AND OS DETECTION
// ============================================================

function browser_class_names($classes) {
	$browser = $_SERVER['HTTP_USER_AGENT'] ?? '';

	// OS Detection
	if (preg_match("/Mac/i", $browser)) {
		$classes[] = 'mac';
	} elseif (preg_match("/Windows/i", $browser)) {
		$classes[] = 'windows';
	} elseif (preg_match("/Linux/i", $browser)) {
		$classes[] = 'linux';
	} else {
		$classes[] = 'unknown-os';
	}

	// Browser Detection
	if (preg_match("/Chrome/i", $browser)) {
		$classes[] = 'chrome';
		if (preg_match("/Chrome\/([\\d\\.]+)/i", $browser, $matches) && isset($matches[1])) {
			$ch_version = 'ch' . str_replace('.', '-', $matches[1]);
			$classes[] = $ch_version;
		}
	} elseif (preg_match("/Safari/i", $browser) && !preg_match("/Chrome/i", $browser)) {
		$classes[] = 'safari';
		if (preg_match("/Version\/([\\d\\.]+)/i", $browser, $matches) && isset($matches[1])) {
			$sf_version = 'sf' . str_replace('.', '-', $matches[1]);
			$classes[] = $sf_version;
		}
	} elseif (preg_match("/Opera|OPR/i", $browser)) {
		$classes[] = 'opera';
		if (preg_match("/(Opera|OPR)\/([\\d\\.]+)/i", $browser, $matches) && isset($matches[2])) {
			$op_version = 'op' . str_replace('.', '-', $matches[2]);
			$classes[] = $op_version;
		}
	} elseif (preg_match("/MSIE/i", $browser) || preg_match("/Trident/i", $browser)) {
		$classes[] = 'msie';
		if (preg_match("/MSIE 6\\.0/i", $browser)) {
			$classes[] = 'ie6';
		} elseif (preg_match("/MSIE 7\\.0/i", $browser)) {
			$classes[] = 'ie7';
		} elseif (preg_match("/MSIE 8\\.0/i", $browser)) {
			$classes[] = 'ie8';
		} elseif (preg_match("/rv:11\\.0/i", $browser)) {
			$classes[] = 'ie11';
		}
	} elseif (preg_match("/Firefox/i", $browser) && preg_match("/Gecko/i", $browser)) {
		$classes[] = 'firefox';
		if (preg_match("/Firefox\/([\\d\\.]+)/i", $browser, $matches) && isset($matches[1])) {
			$ff_version = 'ff' . str_replace('.', '-', $matches[1]);
			$classes[] = $ff_version;
		}
	} else {
		$classes[] = 'unknown-browser';
	}

	return $classes;
}

add_filter('body_class', 'browser_class_names', 20);

// ============================================================
// SOCIAL MEDIA OPTIMIZATION
// ============================================================

function get_social_img() { 
	global $post;
	$image = 'https://www.msstavby.cz/msstavby.jpg';
	$ogtype = 'blog';
	$ogurl = get_option('home');
	$ogdescription = 'content="Blog monitorující dění na poli výstavby a rekonstrukcí staveb v Moravskoslezském regionu."/>';
	$tagname = '';
	
	if ( is_single ()){
		$ogtype = 'article';
		$ogurl = post_permalink();
		if ( function_exists('has_post_thumbnail') && has_post_thumbnail($id) ) {
			$thumbnail = get_the_post_thumbnail($post->ID, 'full');
			$image = (preg_match('~\bsrc="([^\"]++)\"~', $thumbnail, $matches)) ? $matches[1] : '';
		}
		$excerpt = htmlspecialchars(strip_tags(get_the_excerpt()));
		$ogdescription = 'content="'. ( (strpos ( $excerpt , 'Související články')===FALSE)?$excerpt:'Blog monitorující dění na poli výstavby a rekonstrukcí staveb v Moravskoslezském regionu.') .'"/>'; 
	} else if (is_tag ()){
		$ogtype = 'place';
		$tagname = single_tag_title("", false);
		$ogurl = get_tag_link(get_term_by('name', $tagname , 'post_tag')->term_id);
		$excerpt = htmlspecialchars(trim(strip_tags(preg_replace('/<strong>(.*)<\/strong>/', '', tag_description( )))));
		$getlength = strlen($excerpt);
		$excerpt = substr($excerpt, 0, 160);
		if ($getlength > 160) $excerpt .= "...";
		$ogdescription = 'content="'. ( (strpos ( $excerpt , '//')===FALSE)?$excerpt:'Blog monitorující dění na poli výstavby a rekonstrukcí staveb v Moravskoslezském regionu.') .'"/>'; 	
	}
	$ogdescription = '<meta property="og:description" '.$ogdescription."\n\t".'<meta name="description" '.$ogdescription;
	$lati = get_post_custom_values('geo_latitude', $post->ID);
	$longi = get_post_custom_values('geo_longitude', $post->ID);
	?>
	<link rel="previewimage" href="<?php echo esc_attr($image); ?>" />
	<link rel="image_src" href="<?php echo esc_attr($image); ?>" />
	<meta property="og:image" content="<?php echo esc_attr($image); ?>" />
	<meta property="og:title" content="<?php if (is_home () ) { echo (trim(get_bloginfo('name'))); } 
		elseif ( is_category() ) { single_cat_title(); echo " - "; bloginfo('name'); }
		elseif (is_single() || is_page() ) { single_post_title(); } 
		elseif (is_tag() ) { 
			preg_match('/<strong>(.*)<\/strong>/', tag_description(), $match);
			if (empty($match[1])){
				echo ucfirst(single_tag_title("", false)); 
			} else {
				echo ucfirst($match[1]); 
			}
		}
		elseif (is_search() ) { echo "Vyhledávání na ". get_bloginfo('name') .": "; echo wp_specialchars($s); } 
		else { echo trim(wp_title('',false)); } ?>" />
	<meta property="og:type" content="<?php echo $ogtype; ?>" />
	<meta property="og:url" content="<?php echo $ogurl; ?>" />
	<?php
	if (is_single() && !empty($lati) ){
		echo '<meta property="og:latitude" content="'.$lati[0].'\" /><meta property="og:longitude" content="'.$longi[0].'" />';
	}
	if (is_tag() && !empty($lati)){
		echo '<meta property="og:latitude" content="'.$lati[0].'" /><meta property="og:longitude" content="'.$longi[0].'" />';
		echo '<meta property="place:location:latitude" content="'.$lati[0].'" /><meta property="place:location:longitude" content="'.$longi[0].'" />';
	}
	?>
	<meta property="fb:app_id" content="475898622461736" />
	<?php echo $ogdescription."\n";
}
add_action('wp_head', 'get_social_img');

// ============================================================
// PERFORMANCE OPTIMIZATIONS
// ============================================================

// CLS Prevention: Add loading='lazy' to images by default
function add_lazy_loading_to_images($attr, $attachment, $size) {
	$attr['loading'] = 'lazy';
	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'add_lazy_loading_to_images', 10, 3);

// CLS Prevention: Ensure images have width and height attributes
function add_image_dimensions($content) {
	preg_match_all('/<img[^>]+>/i', $content, $images);
	if (!empty($images)) {
		foreach ($images[0] as $image) {
			if (strpos($image, 'width=') === false || strpos($image, 'height=') === false) {
				$image_data = wp_get_attachment_image_src(attachment_url_to_postid($image), 'full');
				if ($image_data) {
					$content = str_replace($image, preg_replace('/(<img[^>]+)>/i', '$1 width="' . $image_data[1] . '" height="' . $image_data[2] . '">', $image), $content);
				}
			}
		}
	}
	return $content;
}
add_filter('the_content', 'add_image_dimensions');

// CLS Prevention: Reserve space for embeds
function reserve_space_for_embeds($content) {
	$content = preg_replace('/(<iframe[^>]*youtube[^>]*><\/iframe>)/', '<div class="videoWrapper">$1</div>', $content);
	return $content;
}
add_filter('the_content', 'reserve_space_for_embeds');

// ============================================================
// GEO MASHUP MAP AJAX LOADER
// ============================================================

// Register REST API endpoint for lazy-loading the map
function register_geo_map_endpoint() {
	register_rest_route('msstavby/v1', '/geo-map', array(
		'methods' => 'GET',
		'callback' => 'get_geo_map_shortcode',
		'permission_callback' => '__return_true',
	));
}
add_action('rest_api_init', 'register_geo_map_endpoint');

// Register REST API endpoint for filtering posts by category
function register_filter_posts_endpoint() {
	register_rest_route('msstavby/v1', '/filter-posts', array(
		'methods' => 'POST',
		'callback' => 'filter_posts_by_category',
		'permission_callback' => '__return_true',
	));
}
add_action('rest_api_init', 'register_filter_posts_endpoint');

// Callback to filter posts
function filter_posts_by_category($request) {
	$params = $request->get_json_params();
	$categories = isset($params['categories']) ? $params['categories'] : array();
	$posts_per_page = isset($params['posts_per_page']) ?intval($params['posts_per_page']) : 6;
	$paged = isset($params['paged']) ? intval($params['paged']) : 1;

	$args = array(
		'post_type' => 'post',
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => $paged,
		'posts_per_page' => $posts_per_page,
	);

	// Filter by category if specified
	if (!empty($categories) && !in_array(0, $categories)) {
		$args['category__in'] = $categories;
	}

	$query = new WP_Query($args);
	
	$html = '';
	if ($query->have_posts()) {
		$html = '<div class="row">';
		while ($query->have_posts()) {
			$query->the_post();
			$html .= '<div class="col-12 col-md-4 mb-4">';
			$html .= '<div class="card h-100">';
			if (has_post_thumbnail()) {
				$html .= '<div class="card-img-top position-relative">';
				$html .= '<a href="' . esc_url(get_the_permalink()) . '">';
				$html .= get_the_post_thumbnail(get_the_ID(), 'medium', array('class' => 'w-100 h-100 object-fit-cover'));
				$html .= '</a>';
				$html .= '</div>';
			}
			$html .= '<div class="card-body d-flex flex-column">';
			$html .= '<small class="text-muted mb-2">';
			$html .= '<time datetime="' . esc_attr(get_the_date('c')) . '">';
			$html .= esc_html(get_the_date('d.m.Y'));
			$html .= '</time></small>';
			$html .= '<h5 class="card-title">';
			$html .= '<a href="' . esc_url(get_the_permalink()) . '" class="text-decoration-none">';
			$html .= get_the_title();
			$html .= '</a></h5>';
			$html .= '<p class="card-text flex-grow-1">' . get_the_excerpt() . '</p>';
			$html .= '<div class="d-flex justify-content-between align-items-center mt-auto">';
			$html .= '<a href="' . esc_url(get_the_permalink()) . '" class="fw-bold text-decoration-none">&rarr; Číst dále</a>';
			if (comments_open()) {
				$html .= '<span class="badge bg-secondary"><i class="bi bi-chat-dots"></i> ' . get_comments_number() . '</span>';
			}
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
			$html .= '</div>';
		}
		$html .= '</div>';
		wp_reset_postdata();
	}

	// Generate AJAX-friendly pagination (avoid REST-context links like /wp-json/...)
	$pagination = '';
	if ($query->max_num_pages > 1) {
		// Use paginate_links for proper pagination (similar to home.php)
		$big = 999999999;
		$wp_pagination = paginate_links(
			array(
				'prev_text' => '&lsaquo;', // Remove href to make it clickable via JS
				'next_text' => '&rsaquo;', // Remove href to make it clickable via JS
				'current' => $paged,
				'total' => $query->max_num_pages,
				'type' => 'array',
			)
		);

		$prev = '<li class="page-item">';
		$prev .= '<a class="page-link filter-page-link" data-page="' . max(1, $paged - 1) . '" href="javascript:void(0)">&lsaquo;</a>';
		$prev .= '</li>';

		$next = '<li class="page-item">';
		$next .= '<a class="page-link filter-page-link" data-page="' . min($query->max_num_pages, $paged + 1) . '" href="javascript:void(0)">&rsaquo;</a>';
		$next .= '</li>';

		$pages = '';
		if (!empty($wp_pagination)) {
			foreach ($wp_pagination as $page_link) {
				// Check if this is the current page
				if (strpos($page_link, 'current') !== false) {
					$pages .= '<li class="page-item active"><span class="page-link">' . strip_tags($page_link) . '</span></li>';
				} elseif (strpos($page_link, 'dots') !== false) {
					$pages .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
				} else {
					// Extract page number from the link
					preg_match('/>([0-9]+)</', $page_link, $matches);
					$page_num = isset($matches[1]) ? $matches[1] : '';
					if ($page_num) {
						$pages .= '<li class="page-item"><a class="page-link filter-page-link" data-page="' . $page_num . '" href="javascript:void(0)">' . $page_num . '</a></li>';
					}
				}
			}
		}

		$pagination = '<nav aria-label="Page navigation"><ul class="pagination">' . $prev . $pages . $next . '</ul></nav>';
	}

	return array(
		'success' => true,
		'html' => $html,
		'pagination' => $pagination,
	);
}

// Callback to render the map shortcode
function get_geo_map_shortcode($request) {
	// Check if Geo Mashup plugin is active
	if (!shortcode_exists('geo_mashup_map')) {
		return new WP_Error('plugin_missing', 'Geo Mashup plugin is not installed or active.', array('status' => 500));
	}
	
	// Get category filter from request
	$category_id = $request->get_param('category');
	$shortcode_args = '';
	
	if ($category_id && $category_id !== '0') {
		$category = get_category($category_id);
		if ($category) {
			$shortcode_args = ' category_name="' . $category->slug . '"';
		}
	}
	
	// Render Geo Mashup shortcode with category filter
	$map_html = do_shortcode('[geo_mashup_map' . $shortcode_args . ']');
	
	return array(
		'success' => true,
		'html' => $map_html
	);
}

// Replace uploaded image with large version
function replace_uploaded_image($image_data) {
	if (!isset($image_data['sizes']['large'])) return $image_data;
	$upload_dir = wp_upload_dir();
	$uploaded_image_location = $upload_dir['basedir'] . '/' .$image_data['file'];
	$large_image_location = $upload_dir['path'] . '/'.$image_data['sizes']['large']['file'];
	unlink($uploaded_image_location);
	rename($large_image_location,$uploaded_image_location);
	$image_data['width'] = $image_data['sizes']['large']['width'];
	$image_data['height'] = $image_data['sizes']['large']['height'];
	unset($image_data['sizes']['large']);
	return $image_data;
}
add_filter('wp_generate_attachment_metadata','replace_uploaded_image');

// ============================================================
// CUSTOM GALLERY SHORTCODE
// ============================================================

remove_shortcode('gallery', 'gallery_shortcode');
add_shortcode('gallery', 'msstavby_gallery_shortcode');

function msstavby_gallery_shortcode($attr) {
	global $post, $wp_locale;
	static $instance = 0;
	$instance++;

	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => '',
		'icontag'    => '',
		'captiontag' => '',
		'columns'    => 1,
		'size'       => 'medium',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$output = "\n";
	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		$link = wp_get_attachment_link($id, $size, false, false);
		$output .= '<p>';	
		$output .= preg_replace_callback(
			'/(<a(.*?)href=("\'|\'?)([^("\"|\'\'?)]*.)(bmp|gif|jpeg|jpg|png)("\'|\'?)(.*?)>(.*?)<img)/i',
			function($matches) {
				return( (strstr($matches[2].$matches[5],"rel=\"") ? $matches[1] : '<a'.$matches[2].'href="'.$matches[4].$matches[5].'" rel="thumbnail" id="img'.$id.'"><img class="aligncenter"') );
			},
			str_replace("\n","",$link)
		);
		$output .= '</p>';	
	}
	$output .= "\n";
	return $output;
}

// ============================================================
// PLUGIN REMOVAL ON HOMEPAGE
// ============================================================

function remove_plugs_onhome() {
	if (is_home() || is_single(36356)){
		/* share this */
		remove_action('wp_head', 'st_widget_head');
		/* pro player */
		remove_action('wp_head', array(&$proPlayer, "addHeaderCode"));
		/* erp show related posts */
		remove_action('wp_head', 'erp-show-related-posts');
		/* easy fancy box */
		remove_action('init', array('easyFancyBox', 'init'), 999);
		remove_action('wp_print_scripts', array('easyFancyBox','register_scripts'), 999);
		remove_action('wp_enqueue_scripts', array('easyFancyBox','enqueue_styles'), 999);
		remove_action('wp_head', array('easyFancyBox','main_script'), 999);
		remove_action('wp_footer', array('easyFancyBox','enqueue_footer_scripts'), 999);
		remove_action('wp_footer', array('easyFancyBox', 'on_ready'), 999);
	}
}
add_action('wp_head', 'remove_plugs_onhome', 1);

// ============================================================
// COMMENT CUSTOMIZATIONS
// ============================================================

function mytheme_comments_form_defaults($default) {
	unset($default['comment_notes_after']);
	unset($default['title_reply']);
	return $default;
}
add_filter('comment_form_defaults','mytheme_comments_form_defaults');

function msstavby_comment_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	$comment_link = get_comment_link( $comment->comment_ID );
	$comment_author_name = get_comment_author();
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	<div class="comment-wrapper">
		<div class="comment-meta">
			<cite class="comment-author-reply" data-comment-id="<?php echo esc_attr( $comment->comment_ID ); ?>" data-comment-link="<?php echo esc_attr( $comment_link ); ?>" data-comment-author="<?php echo esc_attr( $comment_author_name ); ?>" title="Reagovat na <?php echo esc_attr( $comment_author_name ); ?>"><?php comment_author(); ?></cite>
			<span class="comment-time">
					<?php comment_date( 'd.m.Y' ); ?>
					<span class="separator">&nbsp;&#9679;&nbsp;</span>
					<?php echo esc_html( get_comment_time( 'H:i' ) ); ?>
				</span>
		</div>
		<div class="comment-text"><?php comment_text(); ?></div>
		<div class="comment-actions">
			<div class="comment-rating">
				<?php if ( function_exists('ckrating_display_karma') && !is_single(36356) ) { ckrating_display_karma(); } ?>
			</div>
			<?php comment_reply_link( array( 'before' => '', 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ); ?>
		</div>
	</div>
</li>
<?php
}

function trollcheck($comment_id){
	$comment = get_comment($comment_id);
	if (!$comment) {
		return false;
	}
	
	$email = $comment->comment_author_email;
	$author = $comment->comment_author;
	$istroll = true;
	
	if ( strpos($author, "[msstavby.cz]") !== false){
		switch($author){
			case 'acdlcia [msstavby.cz]':
				if ($email == 'acdlcia@seznam.cz')	$istroll = false;
			break;
			case 'Martin [msstavby.cz]':
				if ($email == 'redakce@msstavby.cz')	$istroll = false;
			break;
			case 'Jakub Ivánek':
				if ($email == 'jakub.i@seznam.cz')	$istroll = false;
			break;
			case 'Hynek [msstavby.cz]':
				if ($email == 'hynek@ostryweb.cz')	$istroll = false;
			break;
		}
	} else {
		$istroll = false;
	}
	return !($istroll);
}

// Filter out troll comments from the comments list
function filter_troll_comments($comments) {
	$filtered_comments = array();
	foreach ($comments as $comment) {
		if (trollcheck($comment->comment_ID)) {
			$filtered_comments[] = $comment;
		}
	}
	return $filtered_comments;
}
add_filter('comments_array', 'filter_troll_comments');

// ============================================================
// WORDPRESS CUSTOMIZATIONS
// ============================================================

// Timestamp column in admin
function timestamp_column($defaults) {
	$defaults['timestamp'] = 'Datum+';
	return $defaults;
}

function timestamp_custom_column($column_name, $post_id) {
	if ( $column_name == 'timestamp' ) {
		$post = get_post($post_id);
		echo get_post_status($post_id).'<br>';
		echo get_the_time('d.m.Y H:i', $post_id);
	}
}
add_action('manage_posts_custom_column', 'timestamp_custom_column', 10, 2);
add_filter('manage_posts_columns', 'timestamp_column', 10, 2);

// Thumbnail column in admin
if ( !function_exists('fb_AddThumbColumn') ) { 
	function fb_AddThumbColumn($cols) {
		$cols['thumbnail'] = __('Thumbnail');
		return $cols;
	}
	
	function fb_AddThumbValue($column_name, $post_id) {
		if ( 'thumbnail' == $column_name ) {
			$thumb = get_the_post_thumbnail($post_id, 'thumbnail');
			if ( $thumb ) {
				echo $thumb;
			}
		}
	}
	add_filter( 'manage_posts_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_posts_custom_column', 'fb_AddThumbValue', 10, 2 );
}

remove_action('wp_head', 'wp_generator');
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );

// Contributor upload permissions
function allow_contributor_uploads() {
	$contributor = get_role('contributor');
	$contributor->add_cap('upload_files');
}
if ( current_user_can('contributor') && !current_user_can('upload_files') )
	add_action('admin_init', 'allow_contributor_uploads');

// Default post content with geo mashup map
function my_editor_content( $content ) {
	$content = "[geo_mashup_map]";
	return $content;
}
if (is_admin()) {
	add_filter( 'default_content', 'my_editor_content' );
}

// ============================================================
// RSS FEED MODIFICATIONS
// ============================================================

function msstavby_content($content) {
	return  '<i>Článek z webu <a href="https://www.msstavby.cz" rel="author">msstavby.cz</a></i>:<br/>'.$content;
}
add_filter('the_content_feed', 'msstavby_content');

// Publishing delay for RSS (2 hours)
function publish_later_on_feed($where) {
	global $wpdb;
	if ( is_feed() ) {
		$now = gmdate('Y-m-d H:i:s');
		$wait = '2';
		$device = 'HOUR';
		$where .= " AND TIMESTAMPDIFF($device, $wpdb->posts.post_date_gmt, '$now') > $wait ";
	}
	return $where;
}
add_filter('posts_where', 'publish_later_on_feed');

// ============================================================
// EMOJI REMOVAL
// ============================================================

function theme_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'theme_disable_emojis' );

function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

// ============================================================
// OFFTOPIC PAGE CUSTOMIZATION
// ============================================================

// Map page template (page 1289)
function my_map_page_template($template) {
    if (is_page('1289')) {
        $custom_template = locate_template('page-1289.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('template_include', 'my_map_page_template');

function my_single_template_by_post_id( $located_template ) {
	if( is_single(36356) ){
		return locate_template( array( "single-offtopic.php", $located_template ) );
	} else {
		return $located_template;
	}
}
add_filter( 'single_template', 'my_single_template_by_post_id' );

function wpb_reverse_comments($comments) {
	if( is_single(36356) ){
		return array_reverse($comments);
	} else {
		return $comments;
	}
}
add_filter ('comments_array', 'wpb_reverse_comments');

// ============================================================
// NEW USER NOTIFICATION
// ============================================================

if ( !function_exists('wp_new_user_notification') ) {
	function wp_new_user_notification($user_id, $plaintext_pass = '') {
		$user = get_userdata( $user_id );
		$user_login = stripslashes($user->user_login);
		$user_email = stripslashes($user->user_email);
		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		$message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
		$message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

		if ( !empty($plaintext_pass) ) {
			$message  = sprintf(__('Username: %s'), $user_login) . "\r\n";
			$message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n";
			$message .= wp_login_url() . "\r\n";
			wp_mail($user_email, sprintf(__('[%s] Your username and password'), $blogname), $message);
		}
	}
}

// ============================================================
// THEME VERSION UPDATE CHECKER FROM GITHUB
// ============================================================

function msstavby_theme_update_checker($update_transient) {
	$theme_data = wp_get_theme();
	$theme_version = $theme_data->get('Version');
	$theme_slug = $theme_data->get_stylesheet();
	
// Debug: Log that function was called
	$log_data = array(
		'timestamp' => current_time('mysql'),
		'status' => 'checker_called',
		'theme_version' => $theme_version,
		'theme_slug' => $theme_slug
	);
	update_option('msstavby_update_debug', $log_data);
	
	
	// Check for updates from GitHub
	$github_api_url = 'https://api.github.com/repos/ostryweb-cz/msstavby2026-dist/releases/latest';
	$response = wp_remote_get($github_api_url, array('timeout' => 10));
	
	if (is_wp_error($response)) {
		update_option('msstavby_update_debug_error', 'API Error: ' . $response->get_error_message());
		return $update_transient;
	}
	
	$body = wp_remote_retrieve_body($response);
	$release = json_decode($body);
	
	if (isset($release->tag_name)) {
		$latest_version = ltrim($release->tag_name, 'v');
		
		// Get download URL from release assets
		$download_url = $release->zipball_url;
		if (!empty($release->assets) && !empty($release->assets[0]->browser_download_url)) {
			$download_url = $release->assets[0]->browser_download_url;
		}
		
		if (version_compare($latest_version, $theme_version, '>')) {
			// Update the existing transient with our update info
			$update_transient->response[$theme_slug] = array(
				'slug' => $theme_slug,
				'new_version' => $latest_version,
				'url' => 'https://github.com/ostryweb-cz/msstavby2026-dist',
				'package' => $download_url,
				'tested' => '6.5',
				'requires_php' => '7.4'
			);
			
			// Debug: Update found
			update_option('msstavby_update_debug', array(
				'theme_version' => $theme_version,
				'latest_version' => $latest_version,
				'update_needed' => 'YES',
				'download_url' => $download_url,
				'timestamp' => current_time('mysql')
			));
		} else {
			// No update needed
			update_option('msstavby_update_debug', array(
				'theme_version' => $theme_version,
				'latest_version' => $latest_version,
				'update_needed' => 'NO - Current version',
				'timestamp' => current_time('mysql')
			));
		}
	}
	
	return $update_transient;
}
add_filter('pre_set_site_transient_update_themes', 'msstavby_theme_update_checker', 10, 1);

function msstavby_delete_theme_cache() {
	$theme_data = wp_get_theme();
	$theme_slug = $theme_data->get_stylesheet();
	$update_transient = 'update_themes_' . $theme_slug;
	delete_site_transient($update_transient);
}
add_action('after_switch_theme', 'msstavby_delete_theme_cache');

// Clear transient on forced update check
function msstavby_force_update_check($transient) {
	if (isset($_GET['force-check']) && $transient === false) {
		msstavby_delete_theme_cache();
	}
	return $transient;
}
add_filter('site_transient_update_themes', 'msstavby_force_update_check', 10, 1);
add_action('after_switch_theme', 'msstavby_delete_theme_cache');

// ============================================================
// ADMIN-ONLY COMMENTS
// ============================================================

/**
 * Output HTML comments only for administrators
 * @param string $comment The comment text (without <!-- --> wrapper)
 */
function admin_comment($comment_text) {
	if (current_user_can('administrator')) {
		echo '<!-- ' . esc_html($comment_text) . ' -->';
	}
}

// ============================================================
// INITIALIZATION
// ============================================================

$site_url = get_site_url();
if (str_contains($site_url, 'https://playground.wordpress.net') || str_contains($site_url, 'https://wordpress.org/playground')) {
	add_action( 'admin_menu', 'remove_demo_menu' );
	add_action('after_setup_theme', 'get_global_script');
	add_filter( 'rest_endpoints', 'remove_default_endpoints_smarter' );
}

function initial_theme(){
	add_pages();
	reset_permalinks();
	msstavby_theme_support();
	plugin_installation();
	msstavby_register_block_patterns();
}

remove_all_filters("content_save_pre");
remove_all_filters("pre_content");
remove_all_filters("pre_post_content");
remove_all_filters("content_pre ");

add_action('after_switch_theme', 'initial_theme');
add_action('after_setup_theme', 'remove_admin_bar');
add_action('after_setup_theme', 'add_menus');
add_action('after_setup_theme', 'msstavby_theme_support');
remove_filter( 'the_content', 'wpautop' );
?>
