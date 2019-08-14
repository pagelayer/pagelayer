<?php

// We need the ABSPATH
if (!defined('ABSPATH')) exit;

define('PAGELAYER_BASE', plugin_basename(__FILE__));
define('PAGELAYER_FILE', __FILE__);
define('PAGELAYER_VERSION', '0.9.7');
define('PAGELAYER_DIR', WP_PLUGIN_DIR.'/'.basename(dirname(PAGELAYER_FILE)));
define('PAGELAYER_SLUG', 'pagelayer');
define('PAGELAYER_URL', plugins_url('', PAGELAYER_FILE));
define('PAGELAYER_CSS', PAGELAYER_URL.'/css');
define('PAGELAYER_JS', PAGELAYER_URL.'/js');
define('PAGELAYER_PRO_URL', 'https://pagelayer.com/features#compare');
define('PAGELAYER_DOCS', 'https://pagelayer.com/docs/');
define('PAGELAYER_API', 'https://api.pagelayer.com/');
define('PAGELAYER_SC_PREFIX', 'pl');
define('PAGELAYER_YOUTUBE_BG', 'https://www.youtube.com/watch?v=Csa6rvCWmLU');

include_once(PAGELAYER_DIR.'/main/functions.php');
include_once(PAGELAYER_DIR.'/main/class.php');

// Ok so we are now ready to go
register_activation_hook(PAGELAYER_FILE, 'pagelayer_activation');

// Is called when the ADMIN enables the plugin
function pagelayer_activation(){

	global $wpdb;

	$sql = array();

	/*$sql[] = "DROP TABLE IF EXISTS `".$wpdb->prefix."pagelayer_logs`";

	foreach($sql as $sk => $sv){
		$wpdb->query($sv);
	}*/

	add_option('pagelayer_version', PAGELAYER_VERSION);
	add_option('pagelayer_options', array());

}

// Checks if we are to update ?
function pagelayer_update_check(){

global $wpdb;

	$sql = array();
	$current_version = get_option('pagelayer_version');
	$version = (int) str_replace('.', '', $current_version);

	// No update required
	if($current_version == PAGELAYER_VERSION){
		return true;
	}

	// Is it first run ?
	if(empty($current_version)){

		// Reinstall
		pagelayer_activation();

		// Trick the following if conditions to not run
		$version = (int) str_replace('.', '', PAGELAYER_VERSION);

	}

	// Save the new Version
	update_option('pagelayer_version', PAGELAYER_VERSION);

}

// Add the action to load the plugin 
add_action('plugins_loaded', 'pagelayer_load_plugin');

// The function that will be called when the plugin is loaded
function pagelayer_load_plugin(){

	global $pagelayer;

	// Check if the installed version is outdated
	pagelayer_update_check();

	// Set the array
	$pagelayer = new PageLayer();

	// Is there any ACTION set ?
	$pagelayer->action = pagelayer_optreq('pagelayer-action');

	// Load settings
	$options = get_option('pagelayer_options');
	$pagelayer->settings['post_types'] = empty($options['post_types']) ? array('post', 'page') : $options['post_types'];
	$pagelayer->settings['css_code'] = empty($options['css_code']) ? '' : $options['css_code'];
	$pagelayer->settings['animate'] = empty($options['animate']) ? '' : $options['animate'];
	$pagelayer->settings['max_width'] = empty($options['max_width']) ? 1170 : $options['max_width'];

	// Load the language
	load_plugin_textdomain('pagelayer', false, PAGELAYER_SLUG.'/languages/');
	
	// Show the promo
	pagelayer_maybe_promo([
		'after' => 1,// In days
		'interval' => 30,// In days
		//'pro_url' => 'https://pagelayer.com/themes/wordpress/corporate/Bizworx_Pro',
		'rating' => 'https://wordpress.org/plugins/pagelayer/#reviews',
		'twitter' => 'https://twitter.com/pagelayer?status='.rawurlencode('I love #PageLayer Site Builder by @pagelayer team  for my #WordPress site - '.home_url()),
		'facebook' => 'https://www.facebook.com/pagelayer',
		'website' => '//pagelayer.com',
		'image' => PAGELAYER_URL.'/images/pagelayer-logo-256.png'
	]);

	// Its premium
	if(defined('PAGELAYER_PREMIUM')){
		include_once(PAGELAYER_DIR.'/main/template-builder.php');
	}

}

// This adds the left menu in WordPress Admin page
add_action('admin_menu', 'pagelayer_admin_menu', 5);

// Shows the admin menu of Pagelayer
function pagelayer_admin_menu() {

	global $wp_version, $pagelayer;

	$capability = 'activate_plugins';// TODO : Capability for accessing this page

	// Add the menu page
	add_menu_page(__('PageLayer Editor'), __('Pagelayer'), $capability, 'pagelayer', 'pagelayer_page_handler', PAGELAYER_URL.'/images/pagelayer-logo-19.png');

	// Settings Page
	add_submenu_page('pagelayer', __('PageLayer Editor'), __('Settings'), $capability, 'pagelayer', 'pagelayer_page_handler');

	// Its premium
	if(defined('PAGELAYER_PREMIUM')){

		// Fonts link
		add_submenu_page('pagelayer', __('Font Settings'), __('Font Settings'), $capability, 'pagelayer_fonts', 'pagelayer_page_fonts');

		// Add new template
		add_submenu_page('pagelayer', __('Theme Builder'), __('Theme Builder'), $capability, 'edit.php?post_type=pagelayer-template');

		// Add new template Link
		//add_submenu_page('pagelayer', __('Add New Template'), __('Add New Template'), $capability, 'edit.php?post_type=pagelayer-template#new');

		// Add new template
		add_submenu_page('pagelayer', __('Add New Template'), __('Add New Template'), $capability, 'pagelayer_template_wizard', 'pagelayer_builder_template_wizard');

	// Its free
	}else{

		// Go Pro link
		add_submenu_page('pagelayer', __('PageLayer Go Pro'), __('Go Pro'), $capability, PAGELAYER_PRO_URL);

	}

}

// This function will handle the pages in Pages in PageLayer
function pagelayer_page_handler(){

	global $wp_version, $pagelayer;
	
	wp_enqueue_script( 'pagelayer-admin', PAGELAYER_JS.'/pagelayer-admin.js', array('jquery'), PAGELAYER_VERSION);
	wp_enqueue_style( 'pagelayer-admin', PAGELAYER_CSS.'/pagelayer-admin.css', array(), PAGELAYER_VERSION);

	include_once(PAGELAYER_DIR.'/main/settings.php');
	
	// Handle fonts
	if($pagelayer->action == 'fonts-manager'){
		include_once(PAGELAYER_DIR.'/main/fonts.php');
	}

}

// Load the Live Body
add_action('template_redirect', 'pagelayer_load_live_body', 4);

function pagelayer_load_live_body(){

	global $post;

	// If its not live editing then stop
	if(!pagelayer_is_live()){
		return;
	}

	// If its the iFRAME then return
	if(pagelayer_is_live_iframe()){
		return;
	}

	// Are you allowed to edit ?
	if(!pagelayer_user_can_edit()){
		return;
	}

	// Load the editor live body
	include_once(PAGELAYER_DIR.'/main/live-body.php');

	pagelayer_live_body();

}

// Add the JS and CSS for Posts and Pages when being viewed ONLY if there is our content called
add_action('template_redirect', 'pagelayer_enqueue_frontend', 5);

function pagelayer_enqueue_frontend($force = false){

	global $post, $pagelayer;

	if(!empty($pagelayer->cache['enqueue_frontend'])){
		return;
	}

	if(empty($post->ID)){
		return;
	}

	// Enqueue the FRONTEND CSS
	if(get_post_meta($post->ID , 'pagelayer-data') || $force){

		// We dont need the auto <p> and <br> as they interfere with us
		remove_filter('the_content', 'wpautop');
		
		// No need to add curly codes to the content
		remove_filter('the_content', 'wptexturize');

		pagelayer_load_shortcodes();
		$pagelayer->cache['enqueue_frontend'] = true;
		
		// Load the global styles
		add_action('wp_head', 'pagelayer_global_js', 2);
		
		$premium_js = '';
		if(defined('PAGELAYER_PREMIUM')){
			$premium_js = ',chart.min.js,slick.min.js,premium-frontend.js';
			$premium_css = ',slick.css,slick-theme.css,premium-frontend.css';
			// Load this For audio widget
			wp_enqueue_script('wp-mediaelement');
			wp_enqueue_style( 'wp-mediaelement' );
		}
				
		// Enqueue our Editor's Frontend JS
		wp_register_script('pagelayer-frontend', PAGELAYER_JS.'/givejs.php?give=pagelayer-frontend.js,nivo-lightbox.min.js,wow.min.js,jquery-numerator.js,simpleParallax.min.js,owl.carousel.min.js'.$premium_js, array('jquery'), PAGELAYER_VERSION);
		wp_enqueue_script('pagelayer-frontend');

		wp_register_style('pagelayer-frontend', PAGELAYER_CSS.'/givecss.php?give=pagelayer-frontend.css,nivo-lightbox.css,animate.min.css,owl.carousel.min.css,owl.theme.default.min.css'.$premium_css, array(), PAGELAYER_VERSION);
		wp_enqueue_style('pagelayer-frontend');

		wp_register_style('font-awesome', PAGELAYER_CSS.'/font-awesome.min.css', array(), PAGELAYER_VERSION);
		wp_enqueue_style('font-awesome');
		
		// Load the global styles
		add_action('wp_head', 'pagelayer_global_styles', 5);
		
		// Load custom widgets
		do_action('pagelayer_custom_frontend_enqueue');

	}

}

// Load the google fonts
add_action('wp_footer', 'pagelayer_enqueue_fonts', 5);

function pagelayer_enqueue_fonts(){
	global $pagelayer;

	if(empty($pagelayer->cache['enqueue_frontend'])){
		return;
	}
	
	$url = 'Open Sans:300italic,400italic,600italic,300,400,600&subset=latin,latin-ext';
	//pagelayer_print($pagelayer->runtime_fonts);die('alpesh');
		
	foreach($pagelayer->runtime_fonts as $font){
		$url .= '|'.$font.':100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i';
	}
	
	//echo '<link href="https://fonts.googleapis.com/css?family='.$url.'" rel="stylesheet">';
	
	wp_register_style('pagelayer-google-font', 'https://fonts.googleapis.com/css?family='.rawurlencode($url), array(), PAGELAYER_VERSION);
	wp_enqueue_style('pagelayer-google-font');
	
}

// Load any header we have
function pagelayer_global_js(){
	
	echo '<script>
var pagelayer_ajaxurl = "'.admin_url( 'admin-ajax.php' ).'?";
var pagelayer_ajax_nonce = "'.wp_create_nonce('pagelayer_ajax').'";
var pagelayer_server_time = '.time().';
var pagelayer_facebook_id = "'.get_option('pagelayer-fbapp-id').'";
</script>';

}

// We need to handle global styles
function pagelayer_global_styles(){

	$styles = '<style id="pagelayer-global-styles" type="text/css">';
	
	$width  = get_option('pagelayer_content_width', '1170');
	
	$styles .= '.pagelayer-row-stretch-auto .pagelayer-row-holder{ max-width: '.$width.'px; margin-left: auto; margin-right: auto;}';
	
	$styles .= '</style>';
	
	echo $styles;
}

// Load the live editor if needed
add_action('wp_enqueue_scripts', 'pagelayer_load_live', 9999);

function pagelayer_load_live(){

	global $post;

	// If its not live editing then stop
	if(!pagelayer_is_live_iframe()){
		return;
	}

	// Are you allowed to edit ?
	if(!pagelayer_user_can_edit()){
		return;
	}

	// Load the editor class
	include_once(PAGELAYER_DIR.'/main/live.php');

	// Call the constructor
	$pl_editor = new PageLayer_LiveEditor();

}

// If we are doing ajax and its a pagelayer ajax
if(wp_doing_ajax()){	
	include_once(PAGELAYER_DIR.'/main/ajax.php');
}

// Show the backend editor options
add_action('edit_form_after_title', 'pagelayer_after_title', 10);
function pagelayer_after_title(){

	global $post;
	
	// Get the current screen
	$current_screen = get_current_screen();
	
	// For gutenberg
	if(method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor()){

		// Add the code in the footer
		add_action('admin_footer', 'pagelayer_gutenberg_after_title');
		
		return;
	}
	
	$link = pagelayer_shortlink($post->ID).'&pagelayer-live=1';

	echo '
<div id="pagelayer-editor-button-row" style="margin-top:15px; display:inline-block;">
	<a id="pagelayer-editor-button" href="'.$link.'" class="button button-primary button-large" style="height:auto; padding:6px; font-size:18px;">
		<img src="'.PAGELAYER_URL.'/images/pagelayer-logo-40.png" align="top" width="24" /> <span>'.__('Edit with PageLayer').'</span>
	</a>
</div>';

}

function pagelayer_gutenberg_after_title(){

	global $post;
	
	$link = pagelayer_shortlink($post->ID).'&pagelayer-live=1';

	echo '
<div id="pagelayer-editor-button-row" style="margin-left:15px; display:none">
	<a id="pagelayer-editor-button" href="'.$link.'" class="button button-primary button-large" style="height:auto; padding:6px; font-size:18px;">
		<img src="'.PAGELAYER_URL.'/images/pagelayer-logo-40.png" align="top" width="24" /> <span>'.__('Edit with PageLayer').'</span>
	</a>
</div>

<script type="text/javascript">
jQuery(document).ready(function(){
	
	var pagelayer_timer;
	var pagelayer_button = function(){
		var button = jQuery("#pagelayer-editor-button-row");
		var g = jQuery(".edit-post-header-toolbar");
		if(g.length < 1){
			return;
		}
		button.detach();
		//console.log(button);
		g.append(button);
		button.show();
		clearInterval(pagelayer_timer);
	}
	pagelayer_timer = setInterval(pagelayer_button, 100);
});
</script>';
	
}

add_filter( 'post_row_actions', 'pagelayer_quick_link', 10, 2 );
add_filter( 'page_row_actions', 'pagelayer_quick_link', 10, 2 );
function pagelayer_quick_link($actions, $post){
	$link = pagelayer_shortlink($post->ID).'&pagelayer-live=1';

	$actions['pagelayer'] = '<a href="'.esc_url( $link ).'">'.__( 'Edit using PageLayer', 'pagelayer') .'</a>';

	return $actions;
}

// Pagelayer Template Loading Mechanism
include_once(PAGELAYER_DIR.'/main/template.php');