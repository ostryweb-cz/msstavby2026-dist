<?php
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
$generation_serial = '63.14296990208514';

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
// ENQUEUE STYLES AND SCRIPTS
// ============================================================

add_action( 'wp_enqueue_scripts', function() {
	// Bootstrap 5 CSS
	wp_enqueue_style( 'bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2' );
		
	// Bootstrap Icons
	wp_enqueue_style( 'bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css', array('bootstrap-css'), '1.11.1' );
	
	// Theme styles
	wp_enqueue_style( 'style', get_theme_file_uri( 'style.css' ), array( 'bootstrap-css' ), wp_get_theme( 'msstavby' )->get( 'Version' ) );
	wp_enqueue_style( 'style-semantic', get_theme_file_uri( 'style-semantic.css' ), array( 'style' ), wp_get_theme( 'msstavby' )->get( 'Version' ) );
	
	// Bootstrap 5 JS (depends on jQuery)
	wp_enqueue_script( 'bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.3.2', true );
	
	// Custom scripts
	wp_enqueue_script( 'custom-script', get_theme_file_uri() . '/script.js', array( 'jquery' ), wp_get_theme( 'msstavby' )->get( 'Version' ), true );
	
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
	global $generation_serial;
	$pages = array(
		// Templates removed - use standard WordPress page templates instead
	);
	// Template page generation disabled - creates pages from standard PHP templates
	// Pages should be created manually in WordPress admin
}

function add_menus(){
	function add_links_to_menu($menu_id, $links) {
		foreach ($links as $link) {
			wp_update_nav_menu_item($menu_id, 0, array(
				'menu-item-title' => $link['title'],
				'menu-item-url' => $link['url'],
				'menu-item-status' => 'publish',
				'menu-item-type' => 'custom'
			));
		}
	}
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
			'footer' => esc_html__( 'Footer Menu', 'msstavby' )
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

function activate_msstavby_plugins($plugins){
	foreach ( $plugins as $plugin ) {
		$result = activate_plugin(WP_CONTENT_DIR."/plugins/".$plugin."/".$plugin.".php" );
		if ( is_wp_error( $result ) ) {
			$errors[ $plugin ] = $result;
		}
	}
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
	$ogdescription = '<meta property="og:description" '.$ogdescription."\n".'\t<meta name="description" '.$ogdescription;
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
// COMMENT CUSTOMIZATIONS
// ============================================================

function msstavby_comment($comment, $args, $depth) {
	if (trollcheck($comment->ID)){
		$GLOBALS['comment'] = $comment;
		$title = "Komentuje ".strip_tags($comment->comment_author). " u článku '". get_the_title($comment->comment_post_ID)."'";
		$date = (get_comment_time('d.m')==date('d.m')?get_comment_time('H:i'):get_comment_time('d.m'));
		date_default_timezone_set('Europe/Prague');
		$comment_time = strtotime($comment->comment_date);
		$output .= "\n<li title=\"".$title."\" id=\"".$comment->comment_post_ID."-".$comment_time."\">";
		$output .= $date . ' ';
		$output .= " <a href=\"" . preg_replace('%comment-page-(\d+)?/%', '', get_comment_link( $comment->comment_ID ));
		$output .= "\" title=\"" .$title;
		$output .= "\">";
		$commentx = iconv("UTF-8","ISO-8859-2", $comment->comment_content );
		$snippet = trim( preg_replace('#<a.*?>.*?</a>:#i', '', $commentx) );
		if (empty($snippet)){
			$output .= substr(strip_tags($comment->comment_content), 0, 200); 
		} else {
			$output .= iconv("ISO-8859-2","UTF-8//IGNORE", substr(strip_tags($snippet), 0, 200) );
		}
		$output .= "</a></li>";
	}
	echo $output;
}

function mytheme_comments_form_defaults($default) {
	unset($default['comment_notes_after']);
	unset($default['title_reply']);
	return $default;
}
add_filter('comment_form_defaults','mytheme_comments_form_defaults');

function trollcheck($comment){
	$istroll = true;
	$email = get_comment_author_email($comment);
	$author = get_comment_author($comment);
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

function remove_plugs_onhome() {
	if (is_home() || is_single(36356)){
		remove_action('wp_head', 'st_widget_head');
		remove_action('wp_head', array(&$proPlayer, "addHeaderCode"));
		remove_action('wp_head', 'erp-show-related-posts');
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
// ADMIN INTERFACE ENHANCEMENTS
// ============================================================

// Timestamp column
function timestamp_column($defaults) {
	$defaults['timestamp'] = 'Datum+';
	return $defaults;
}

function timestamp_custom_column($column_name) {
	if( $column_name == 'timestamp' ) {
		echo get_post_status($post_id).'<br>';
		echo the_time('d.m.Y H:i');
	}
}
add_action('manage_posts_custom_column', 'timestamp_custom_column', 10, 2);
add_filter('manage_posts_columns', 'timestamp_column', 10, 2);

// Thumbnail column
if ( !function_exists('fb_AddThumbColumn') && function_exists('add_theme_support') ) { 
	function fb_AddThumbColumn($cols) {
		$cols['thumbnail'] = __('Thumbnail');
		return $cols;
	}
	
	function fb_AddThumbValue($column_name, $post_id) {
		if ( 'thumbnail' == $column_name ) {
			if ( function_exists('has_post_thumbnail') && has_post_thumbnail($id) ) {
				$thumb = get_the_post_thumbnail($post->ID);
			}
			if ( isset($thumb) && $thumb ) {
				echo $thumb;
			}
		}
	}
	add_filter( 'manage_posts_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_posts_custom_column', 'fb_AddThumbValue', 10, 2 );
}

// ============================================================
// WORDPRESS CUSTOMIZATIONS
// ============================================================

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
remove_filter( 'the_content', 'wpautop' );
?>
