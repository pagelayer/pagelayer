<?php

//////////////////////////////////////////////////////////////
//===========================================================
// ajax.php
//===========================================================
// PAGELAYER
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit Gupta
// Date:       23rd Jan 2017
// Time:       23:00 hrs
// Site:       http://pagelayer.com/wordpress (PAGELAYER)
// ----------------------------------------------------------
// Please Read the Terms of use at http://pagelayer.com/tos
// ----------------------------------------------------------
//===========================================================
// (c)Pagelayer Team
//===========================================================
//////////////////////////////////////////////////////////////

// Are we being accessed directly ?
if(!defined('PAGELAYER_VERSION')) {
	exit('Hacking Attempt !');
}

// Is the nonce there ?
if(empty($_REQUEST['pagelayer_nonce'])){
	return;
}

// The ajax handler
add_action('wp_ajax_pagelayer_wp_widget', 'pagelayer_wp_widget_ajax');
function pagelayer_wp_widget_ajax(){

	global $pagelayer;

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	pagelayer_load_shortcodes();
	
	header('Content-Type: application/json');
	
	$ret = [];
	$tag = @$_POST['tag'];
	//pagelayer_print($pagelayer->shortcodes[$tag]);
	
	// No tag ?
	if(empty($pagelayer->shortcodes[$tag])){
		$ret['error'][] =  __pl('no_tag');
		pagelayer_json_output($ret);
	}
	
	// Include the widgets
	include_once(ABSPATH . 'wp-admin/includes/widgets.php');
	
	$class = $pagelayer->shortcodes[$tag]['widget'];
	
	// Check the widget class exists ?
	if(empty($class) || !class_exists($class)){
		$ret['error'][] =  __pl('no_widget_class');
		pagelayer_json_output($ret);
	}
	
	$instance = [];
	$widget = new $class();
	$widget->_set('pagelayer-widget-1234567890');
	
	// Is there any existing data ?
	if(!empty($_POST['widget_data'])){
		$json = json_decode(stripslashes($_POST['widget_data']), true);
		//pagelayer_print($json);die();
		if(!empty($json)){
			$instance = $json;
		}
	}

	// Are there any form values ?
	if(!empty($_POST['values'])){		
		parse_str(stripslashes($_POST['values']), $data);
		//pagelayer_print($data);die();
		
		// Any data ?
		if(!empty($data)){
			
			// First key is useless
			$data = current($data);
			
			// Do we still have valid data ?
			if(!empty($data)){
				
				// 2nd key is useless and just over-ride instance
				$instance = current($data);
				
			}
		}
	}
	
	// Get the form
	ob_start();
	$widget->form($instance);
	$ret['form'] = ob_get_contents();
	ob_end_clean();
	
	// Get the html
	ob_start();
	$widget->widget([], $instance);
	$ret['html'] = ob_get_contents();
	ob_end_clean();
	
	// Widget data to set
	if(!empty($instance)){
		$ret['widget_data'] = $instance;
	}
	
	pagelayer_json_output($ret);
	
}

// Update Post content
add_action('wp_ajax_pagelayer_save_content', 'pagelayer_save_content');
function pagelayer_save_content(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$content = $_POST['pagelayer_update_content'];

	$postID = (int) $_GET['postID'];

	if(empty($postID)){
		$msg['error'] =  __pl('invalid_post_id');
	}
	
	// Check if the post exists
	
	if(!empty($postID) && !empty($content)){
		
		$post = array(
					'ID' => $postID,
					'post_content' => $content,
				);
		
		// Any properties ?
		if(!empty($_REQUEST['page_props'])){
			
			$allowed = ['post_title', 'post_name', 'post_excerpt', 'post_status'];
			
			foreach($allowed as $k){
				if(!empty($_REQUEST['page_props'][$k])){
					$post[$k] = $_REQUEST['page_props'][$k];
				}
			}
			
			$_REQUEST['page_props']['featured_image'] = (int) $_REQUEST['page_props']['featured_image'];
			if(!empty($_REQUEST['page_props']['featured_image'])){
				set_post_thumbnail($postID, $_REQUEST['page_props']['featured_image']);
			}else{
				delete_post_thumbnail($postID);
			}
			
		}
	
		// Apply a filter
		$post = apply_filters('pagelayer_save_content', $post);
		
		// Update the post into the database
		wp_update_post($post);

		if (is_wp_error($postID)) {
			$msg['error'] =  __pl('post_update_err');
		}else{
			$msg['success'] =  __pl('post_update_success');
		}
		
	}else{
		$msg['error'] =  __pl('post_update_err');
	}

	pagelayer_json_output($msg);
	
}

// Shortcodes Widget Handler
add_action('wp_ajax_pagelayer_do_shortcodes', 'pagelayer_do_shortcodes');
function pagelayer_do_shortcodes(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$data = '';
	if(isset($_REQUEST['shortcode_data'])){
		$data = stripslashes($_REQUEST['shortcode_data']);
	}

	// Load shortcodes
	pagelayer_load_shortcodes();

	$data = do_shortcode($data);
	
	// Create the HTML object
	$node = pQuery::parseStr($data);
	$node->query('.pagelayer-ele')->removeClass('pagelayer-ele');
	echo $node->html();
	
	wp_die();
	
}

// Give the JS
add_action('wp_ajax_pagelayer_givejs', 'pagelayer_givejs');
function pagelayer_givejs(){
	
	global $pagelayer;
	
	// WordPress adds the Expires header in all AJAX calls. We need to remove it for cache to work
	header_remove("Expires");
	header_remove("Cache-Control");
	
	// Load shortcodes
	pagelayer_load_shortcodes();
	
	// Pagelayer Template Loading Mechanism
	include_once(PAGELAYER_DIR.'/js/givejs.php');
	
	exit();
	
}

// Shortcodes Widget Handler
add_action('wp_ajax_pagelayer_get_section_shortcodes', 'pagelayer_get_section_shortcodes');
function pagelayer_get_section_shortcodes(){
	
	global $pagelayer;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$data = '';
	if(isset($_REQUEST['pagelayer_section_id'])){
		$get_url = PAGELAYER_API.'/library.php?give_id='.$_REQUEST['pagelayer_section_id'].(!empty($pagelayer->license['license']) ? '&license='.$pagelayer->license['license'] : '');
		$fetch = file_get_contents($get_url);
		$data = json_decode($fetch, true);
	}
	
	if(isset($_REQUEST['postID'])){
		$post_id = (int) $_REQUEST['postID'];
		
		if(!empty($post_id)){
			$post = get_post( $post_id );
			// Need to make the reviews post global 
			if ( !empty( $post ) ) {
				$GLOBALS['post'] = $post;
			}
		}
	}
	
	// Upload the images if any in the shortcode
	preg_match_all('/"'.preg_quote('{{pl_lib_images}}', '/').'([^"]*)"/is', $data['code'], $matches);
	
	foreach($matches[0] as $k => $v){
		$image_url = trim($v, '"\'');
		$urls[$image_url] = $image_url;
	}
	
	foreach($urls as $k => $image_url){
		
		$file = basename($image_url);
		$id = 0;
		
		// Upload this
		if(!empty($data[$file])){
			
			$id = pagelayer_upload_media($file, base64_decode($data[$file]));
			
			if(!empty($id)){
				$data['code'] = str_replace('"'.$image_url.'"', '"'.$id.'"', $data['code']);
			}
		}
		
	}

	// Load shortcodes
	pagelayer_load_shortcodes();
	
	if(!empty($data['code'])){
		$data['code'] = do_shortcode($data['code']);
	}
	
	pagelayer_json_output($data);

}

// Get the Site Title
add_action('wp_ajax_pagelayer_fetch_site_title', 'pagelayer_fetch_site_title');
function pagelayer_fetch_site_title(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	echo get_bloginfo('name');
	wp_die();
}

// Update the Site Title
add_action('wp_ajax_pagelayer_update_site_title', 'pagelayer_update_site_title');
function pagelayer_update_site_title(){
	global $wpdb;

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$site_title = $_POST['site_title'];

	update_option('blogname', $site_title);

	$wpdb->query("UPDATE `sm_sitemeta` 
				SET meta_value = '".$site_title."'
				WHERE meta_key = 'site_name'");
	wp_die();
}

// Show the SideBars
add_action('wp_ajax_pagelayer_fetch_sidebar', 'pagelayer_fetch_sidebar');
function pagelayer_fetch_sidebar(){
	
	global $wp_registered_sidebars;

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	// Create a list
	$pagelayer_wp_widgets = array();
	
	foreach($wp_registered_sidebars as $v){
		$pagelayer_wp_widgets[$v['id']] = $v['name'];
	}
	
	$id = @$_REQUEST['sidebar'];
		
	if(function_exists('dynamic_sidebar') && !empty($pagelayer_wp_widgets[$id])) {
		ob_start();
		dynamic_sidebar($id);
		$result = ob_get_clean();
	}else{
		$result =  __pl('no_widget_area');
	}
	
	echo $result;
	wp_die();
	
}

// Show the primary menu !
add_action('wp_ajax_pagelayer_fetch_primary_menu', 'pagelayer_fetch_primary_menu');
function pagelayer_fetch_primary_menu(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if(isset($_POST['nav_list'])){
		echo wp_nav_menu([
			'menu'   => wp_get_nav_menu_object($_POST['nav_list']),
			'menu_id' => $_POST["nav_list"],
			'menu_class' => 'pagelayer-wp_menu-ul',
			//'theme_location' => 'primary',
		]);
	}
	
	wp_die();
}

// Save post revision 
add_action('wp_ajax_pagelayer_create_post_autosave', 'pagelayer_create_post_autosave');
function pagelayer_create_post_autosave(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$postID = (int) $_GET['postID'];
	$post_revisions = array();
	
	$content = $_REQUEST['pagelayer_post_content'];
	
	if(empty($postID)){
		$post_revisions['error'] =  __pl('invalid_post_id');
	}else{
		
		$post = array(
			'post_ID' => $postID,
			'post_content' => $content,
		);
		
		$post_revisions['id'] = wp_create_post_autosave($post);
	}
	
	$post_revisions['url'] = get_preview_post_link($postID);
	
	pagelayer_json_output($post_revisions);
	
}

// Get post revision 
add_action('wp_ajax_pagelayer_get_revision', 'pagelayer_get_revision');
function pagelayer_get_revision(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$postID = (int) $_GET['postID'];
	$post_revisions = array();
	
	if(empty($postID)){
		$post_revisions['error'] =  __pl('invalid_post_id');
	}else{
		$post_revisions = pagelayer_get_post_revision_by_id($postID);
	}
	
	pagelayer_json_output($post_revisions);
	
}

// Get post revision 
add_action('wp_ajax_pagelayer_apply_revision', 'pagelayer_apply_revision');
function pagelayer_apply_revision(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$revisionID = (int) $_REQUEST['revisionID'];
	$post_data = array();
	
	if(empty($revisionID)){
		$post_data['error'] =  __pl('invalid_post_id');
	}else{
		
		$post = get_post( $revisionID );
		
		if ( empty( $post ) ) {
			$post_data['error'] =  __pl('invalid_revision');
			pagelayer_json_output($post_data);
		}
		
		// Need to make the reviews post global 
		$GLOBALS['post'] = $post;
		
		// Need to reload the shortcodes
		pagelayer_load_shortcodes();
		
		$post_data['content'] = do_shortcode($post->post_content);
		
		if (is_wp_error($postID)) {
			$post_data['error'] =  __pl('rev_load_error');
		}else{
			$post_data['success'] = __pl('rev_load_success');
		}
		
		wp_reset_postdata();
	}
	
	pagelayer_json_output($post_data);
	
}

// Get post revision 
add_action('wp_ajax_pagelayer_delete_revision', 'pagelayer_delete_revision');
function pagelayer_delete_revision() {
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	$revisionID = (int) $_REQUEST['revisionID'];
	
	if(empty($revisionID)){
		$post_data['error'] =  __pl('invalid_post_id');
	}else{

		$revision = get_post( $revisionID );

		if ( empty( $revision ) ) {
			$post_data['error'] =  __pl('invalid_revision');
		}else{

			if ( ! current_user_can( 'delete_post', $revision->ID ) ) {
					$post_data['error'] =  __pl('access_denied');
					pagelayer_json_output($post_data);
					return false;
			}

			$deleted = wp_delete_post_revision( $revision->ID );

			if ( ! $deleted || is_wp_error( $deleted ) ) {
				$post_data['error'] =  __pl('delete_rev_error');
			}else{
				$post_data['success'] =  __pl('delete_rev_success');
			}
		}
	}
	
	pagelayer_json_output($post_data);
	
}

// Get post revision 
add_action('wp_ajax_pagelayer_post_nav', 'pagelayer_post_nav');
function pagelayer_post_nav() {
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if(!isset($_REQUEST['data']) || !isset($_REQUEST['postID'])){
		return;
	}
	
	$el['atts'] = $_REQUEST['data'];
	
	$post = get_post($_REQUEST['postID']);
	
	// Need to make this post global
	$GLOBALS['post'] = $post;
	
	$in_same_term = false;
	$taxonomies = 'category';
	$title = '';
	$arrows_list = $el['atts']['arrows_list'];
	
	if($el['atts']['in_same_term']){
		$in_same_term = true;
		$taxonomies = $el['atts']['taxonomies'];
	}
	
	if($el['atts']['post_title']){
		$title = '<span class="pagelayer-post-nav-title">%title</span>';
	}
	
	$next_label = '<span class="pagelayer-next-holder">
		<span class="pagelayer-post-nav-link"> '.$el["atts"]["next_label"].'</span>'.$title.'
	</span>
	<span class="pagelayer-post-nav-icon fa fa-'.$arrows_list.'-right"></span>';
		
	$prev_label = '<span class="pagelayer-post-nav-icon fa fa-'.$arrows_list.'-left"></span>
	<span class="pagelayer-next-holder">
		<span class="pagelayer-post-nav-link"> '.$el["atts"]["prev_label"].'</span>'.$title.'
	</span>';

	$el['atts']['next_link'] = get_next_post_link('%link', $next_label, $in_same_term, '', $taxonomies); 

	$el['atts']['prev_link'] = get_previous_post_link('%link', $prev_label, $in_same_term, '', $taxonomies ); 
	
	pagelayer_json_output($el);
	
}

// Get post comment template 
add_action('wp_ajax_pagelayer_post_comment', 'pagelayer_post_comment');
function pagelayer_post_comment() {
	global $post;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if(!isset($_REQUEST['postID'])){
		return true;
	}
	
	$GLOBALS['post'] = get_post($_REQUEST['postID']);
	$GLOBALS['withcomments'] = true;
	
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="pagelayer-comments-template">'.comments_template().'</div>';
	}else{
		echo '<div class="pagelayer-comments-close">
			<h2>Comments are closed!</h2>
		</div>';
	}
	wp_die();
		
}

// Get post comment template 
add_action('wp_ajax_pagelayer_post_info', 'pagelayer_post_info');
function pagelayer_post_info() {
	global $post;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');

	if(!isset($_REQUEST['postID']) || !isset($_REQUEST['el'])){
		return true;
	}
	
	$el['atts'] = $_REQUEST['el'];
	
	$GLOBALS['post'] = get_post($_REQUEST['postID']);
	
	$post_info_content ='';
	$link ='';
	$info_content ='';
	$avatar_url ='';
	
	switch($el['atts']['type']){
		case 'author':
		
			$link = get_author_posts_url( get_the_author_meta( 'ID' ) );
			$avatar_url = get_avatar_url( get_the_author_meta( 'ID' ), 96 );
			$post_info_content = get_the_author_meta( 'display_name', $post->post_author );
			break;

		case 'date':
		
			$format = [
				'default' => 'F j, Y',
				'0' => 'F j, Y',
				'1' => 'Y-m-d',
				'2' => 'm/d/Y',
				'3' => 'd/m/Y',
				'custom' => empty( $el['atts']['date_format_custom'] ) ? 'F j, Y' : $el['atts']['date_format_custom'],
			];

			$post_info_content = get_the_time( $format[ $el['atts']['date_format'] ] );
			$link = get_day_link( get_post_time( 'Y' ), get_post_time( 'm' ), get_post_time( 'j' ) );
				
			break;

		case 'time':
		
			$format = [
				'default' => 'g:i a',
				'0' => 'g:i a',
				'1' => 'g:i A',
				'2' => 'H:i',
				'custom' =>  empty( $el['atts']['time_format_custom'] ) ? 'F j, Y' : $el['atts']['time_format_custom'],
			];
			$post_info_content = get_the_time( $format[ $el['atts']['time_format'] ] );
			
			break;

		case 'comments':
		
			if (comments_open()) {
				$post_info_content = (int) get_comments_number();
				$link = get_comments_link();
			}
			
			break;

		case 'terms':
		
			$taxonomy = $el['atts']['taxonomy'];
			$terms = wp_get_post_terms( get_the_ID(), $taxonomy );
			foreach ( $terms as $term ) {
				$post_info_content .= ' <a if-ext="{{info_link}}" href="'. get_term_link( $term ) .'" class="pagelayer-post-info-list-link"> '. $term->name .' </a>';
			}
			
			break;

		case 'custom':
		
			$post_info_content = $el['atts']['type_custom'];
			$link = $el['atts']['info_custom_link'];

			break;
	}
				
	$el['atts']['post_info_content'] = $post_info_content;
	$el['atts']['avatar_url'] = $avatar_url;
	$el['atts']['link'] = $link;
	
	pagelayer_json_output($el['atts']);
		
}

// Get the Featured Image
add_action('wp_ajax_pagelayer_fetch_featured_img', 'pagelayer_fetch_featured_img');
function pagelayer_fetch_featured_img(){

	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if($_POST['size']){
		echo get_the_post_thumbnail_url($_POST['post_id'], $_POST['size']);
	}else{
		echo get_the_post_thumbnail_url($_POST['post_id']);
	}
	wp_die();
}

// Get the postfolio posts
add_action('wp_ajax_pagelayer_fetch_posts', 'pagelayer_fetch_posts');
function pagelayer_fetch_posts(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	echo pagelayer_widget_posts($_POST);
	
	wp_die();
}

// Get the Posts
add_action('wp_ajax_pagelayer_posts_data', 'pagelayer_posts_data');
function pagelayer_posts_data(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	// Load shortcodes
	pagelayer_load_shortcodes();
	
	echo pagelayer_posts($_POST);
	wp_die();
}

// Get the Posts
add_action('wp_ajax_pagelayer_archive_posts_data', 'pagelayer_archive_posts_data');
function pagelayer_archive_posts_data(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	// Set excerpt length
	if($_POST['exc_length']){
		$exc_length = (int) $params['exc_length'];
		add_filter( 'excerpt_length', function($length) use($exc_length){
			return $exc_length;
		}, 999 );
	}
	
	// Load shortcodes
	pagelayer_load_shortcodes();
	
	echo pagelayer_posts($_POST, $_POST['pagelayer_wp_query']);
	wp_die();
}

// Handle Contact Form Data
add_action('wp_ajax_pagelayer_contact_submit', 'pagelayer_contact_submit');
add_action('wp_ajax_nopriv_pagelayer_contact_submit', 'pagelayer_contact_submit' );
function pagelayer_contact_submit(){

	$to_mail = get_option('pagelayer_cf_to_email');
	$subject = get_option('pagelayer_cf_subject');
	
	$fdata = $_POST['form_data'];
	parse_str($fdata, $formdata);
	
	// Make the email content
	foreach($formdata as $k => $i){
		$data .= ''.$k.'\t : \t'.$i.'\n';
	}
	
	// Send the email
	$r = wp_mail( $to_mail, $subject, $data );
	
	if($r == TRUE){
		$wp['success'] = get_option( 'pagelayer_cf_success' );
	}else{
		$wp['failed'] = get_option( 'pagelayer_cf_failed' );
	}
	
	pagelayer_json_output($wp);
	
}

// Fetch Google reCaptcha Key
add_action('wp_ajax_pagelayer_fetch_grecaptcha_key', 'pagelayer_fetch_grecaptcha_key');
function pagelayer_fetch_grecaptcha_key(){
	
	$data['key'] = get_option('pagelayer_google_captcha');
	
	pagelayer_json_output($data);
	
}

// Handle Login Submit
add_action('wp_ajax_pagelayer_login_submit', 'pagelayer_login_submit');
add_action('wp_ajax_nopriv_pagelayer_login_submit', 'pagelayer_login_submit');
function pagelayer_login_submit(){

	$fdata = $_POST['form_data'];
	parse_str($fdata, $formdata);

	$creds = array();
	$creds['user_login'] = $formdata['username'];
	$creds['user_password'] = $formdata['password'];
	$creds['remember'] = $formdata['remember_me'];
	
	// If After logout URL, then save
	if(!empty($formdata['logout_url'])){
		update_user_option('pagelayer_logout_url', $formdata['logout_url']);
	}
	
	// Login the user
	$user = wp_signon( $creds, false );	
	
	if ( is_wp_error($user) ){
		$data['error'] = $user->get_error_message();
	}else{
		$data['redirect'] = (empty($formdata['login_url']) ? '' : $formdata['login_url']);
		$data['error'] = '';
	}

	pagelayer_json_output($data);
	
}

// Handle Logout Redirect here
add_action('wp_logout', 'pagelayer_after_logout');
function pagelayer_after_logout(){
	
	$url = get_user_option('pagelayer_logout_url');
	
	// We will redirect if we have the given item set.
	if(!empty($url)){
		wp_redirect( $url );
		exit();
	}
	
}

// Get Page List for SiteMap
add_action('wp_ajax_pagelayer_get_pages_list', 'pagelayer_get_pages_list');
add_action('wp_ajax_nopriv_pagelayer_get_pages_list', 'pagelayer_get_pages_list');
function pagelayer_get_pages_list(){
	
	$args = array(
		'post_type' => $_POST['type'],
		'orderby' => $_POST['post_order'],
		'order' => $_POST['order'],
		'hierarchical' => (empty($_POST['hier']) || $_POST['hier'] == null ? '' : $_POST['hier']),
		'number' => (empty($_POST['depth']) || $_POST['depth'] == null ? '' : $_POST['depth']),
	);
	
	$option = '<ul>';
	$pages = new WP_Query($args);
	$posts = $pages->posts;
	foreach ( $posts as $page ) {
		$option .= '<li class="pagelayer-sitemap-list-item" data-postID="'.$page->ID.'"><a class="pagelayer-ele-link" href="'.$page->guid.'">'.$page->post_name.'</a></li>';
	}
	$option .= '</ul>';
	
	
	echo $option;

	wp_die();	
}

// Get the data for template
add_action('wp_ajax_pagelayer_search_ids', 'pagelayer_search_ids');
function pagelayer_search_ids() {
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if ( empty( $_POST['filter_type'] ) || empty( $_POST['search'] ) ) {
		wp_die();
	}

	$sel_opt = '';

	switch ( $_POST['filter_type'] ) {
		case 'taxonomy':
			$query_params = [
				'taxonomy' => $_POST['object_type'],
				'search' => $_POST['search'],
				'hide_empty' => false,
			];

			$terms = get_terms( $query_params );

			global $wp_taxonomies;

			foreach ( $terms as $term ) {
				$sel_opt .= '<span class="pagelayer-temp-search-sel-span" value="'. $term->term_taxonomy_id .'">'. $term->name .'</span>';
			}

			break;

		case 'post':
			$query_params = [
				'post_type' => $_POST['object_type'], //$this->extract_post_type( $data ),
				's' => $_POST['search'],
				'posts_per_page' => -1,
			];

			if ( 'attachment' === $query_params['post_type'] ) {
				$query_params['post_status'] = 'inherit';
			}

			$query = new \WP_Query( $query_params );

			foreach ( $query->posts as $post ) {
				$sel_opt .= '<span class="pagelayer-temp-search-sel-span" value="'. $post->ID .'">'. $post->post_title .'</span>';
			}
			break;

		case 'author':
			$query_params = [
				'who' => 'authors',
				'fields' => [
					'ID',
					'display_name',
				],
				'search' => '*' . $_POST["search"] . '*',
				'search_columns' => [
					'user_login',
					'user_nicename',
				],
			];

			$user_query = new \WP_User_Query( $query_params );

			foreach ( $user_query->get_results() as $author ) {
				$sel_opt .= '<span class="pagelayer-temp-search-sel-span" value="'. $author->ID .'">'. $author->display_name .'</span>';
			}
			break;
		default:
			$sel_opt = 'Result Not Found';
	}
	
	if(!empty($sel_opt)){
		echo $sel_opt;
	}else{
		echo 'Result Not Found';
	}
	
	wp_die();
}


// Save the post data from pagelayer setting page
add_action('wp_ajax_pagelayer_save_template', 'pagelayer_save_template');
function pagelayer_save_template() {
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$done = [];
	
	$post_id = (int) $_GET['postID'];
	
	// We need to create the post
	if(empty($post_id)){
	
		// Get the template type
		if(empty($_POST['pagelayer_template_type'])){
			$done['error'] = __pl('temp_error_type');
			pagelayer_json_output($done);
		}
		
		$ret = wp_insert_post([
			'post_title' => $_POST['pagelayer_lib_title'],
			'post_type' => 'pagelayer-template',
			'post_status' => 'publish',
			'comment_status' => 'closed',
			'ping_status' => 'closed'
		]);
		
		// An error occured
		if(is_wp_error($ret)){
			$done['error'] = __pl('temp_error').' : '.$ret->get_error_message();
			pagelayer_json_output($done);
		}
		
		$post_id = $ret;
		$done['id'] = $post_id;
		
		// Save our template type
		$ret = update_post_meta($post_id, 'pagelayer_template_type', $_POST['pagelayer_template_type']);
		
	}
	
	// The ID in consideration
	$done['id'] = $post_id;
	
	// Check if the post title in not empty
	if(!empty($_POST['pagelayer_lib_title'])){
		
		$post = array(
					'ID' => $post_id,
					'post_title' => $_POST['pagelayer_lib_title'],
				);

		// Update the post into the database
		$ret = wp_update_post($post);
		
	}
	
	// Save template library display conditions
	$condi_array = array();
	$condi_len = count($_POST['pagelayer_condition_type']);
	if($_POST['pagelayer_template_type'] != 'section'){
		for( $i =0; $i < $condi_len; $i++ ){
			$condi_array[$i] = array(
				'type' => $_POST['pagelayer_condition_type'][$i],
				'template' => $_POST['pagelayer_condition_name'][$i],
				'sub_template' => $_POST['pagelayer_condition_sub_template'][$i],
				'id' => $_POST['pagelayer_condition_id'][$i],
			);
		}
	}
	//print_r($condi_array);
	
	$ret = update_post_meta($post_id, 'pagelayer_template_conditions', $condi_array);
	
	if(is_wp_error($post_id)){
		$done['error'] = __pl('temp_error').' : '.$ret->get_error_message();
	}else{
		$done['success'] =  __pl('temp_update_success');
	}

	pagelayer_json_output($done);
	
}

// Product Images Handler
add_action('wp_ajax_pagelayer_product_images', 'pagelayer_product_images');
function pagelayer_product_images(){
	global $product;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if ( !isset($_REQUEST['postID']) ) {
		return;
	}

	$product = wc_get_product($_REQUEST['postID']);

	if ( empty( $product ) ) {
		return ;
	}

	if ( isset($_POST['sale_flash']) ) {
		wc_get_template( 'loop/sale-flash.php' );
	}
	wc_get_template( 'single-product/product-image.php' );
	
	// On render widget from Editor - trigger the init manually.
	echo '	
	<script>
		jQuery(".woocommerce-product-gallery").each( function() {
			jQuery(this).wc_product_gallery();
		} );
	</script>
	';
	
	wp_die();
}

// Related Products Handler
add_action('wp_ajax_pagelayer_product_related', 'pagelayer_product_related');
function pagelayer_product_related(){
	global $product;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if ( !isset($_REQUEST['postID']) ) {
		return;
	}

	$product = wc_get_product($_REQUEST['postID']);

	if ( empty( $product ) ) {
		return ;
	}
	
	$args = $_REQUEST['pagelayer_args'];
	
	if(function_exists( 'woocommerce_related_products' )){
		woocommerce_related_products($args);	
	}
		
	wp_die();
}

// Upsell Products Handler
add_action('wp_ajax_pagelayer_product_upsell', 'pagelayer_product_upsell');
function pagelayer_product_upsell(){
	global $product;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if ( !isset($_REQUEST['postID']) ) {
		return;
	}

	$product = wc_get_product($_REQUEST['postID']);

	if ( empty( $product ) ) {
		return ;
	}
		
	if(function_exists( 'woocommerce_related_products' )){
		woocommerce_upsell_display( $_REQUEST['limit'], $_REQUEST['columns'], $_REQUEST['orderby'], $_REQUEST['order'] );	
	}
		
	wp_die();
}

// Products Categories Handler
add_action('wp_ajax_pagelayer_product_categories', 'pagelayer_product_categories');
function pagelayer_product_categories(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$attributes = '';
	$attributes .= ' number="'. $_POST['atts']['number'] .'" ';
	$attributes .= ' columns="'. $_POST['atts']['columns'] .'" ';
	$attributes .= ' hide_empty="'. (!empty($_POST['atts']['hide_empty']) ? 1 : 0) .'" ';
	$attributes .= ' orderby="'. $_POST['atts']['nuorderbymber'] .'" ';
	$attributes .= ' order="'. $_POST['atts']['order'] .'" ';	
	
	if ( 'by_id' === $_POST['atts']['source'] ) {
		$attributes .= ' ids="'. $_POST['atts']['by_id'] .'" ';
	} elseif ( 'by_parent' === $_POST['atts']['source'] ) {
		$attributes .= ' parent="'. $_POST['atts']['parent'] .'" ';
	} elseif ( 'current_subcategories' === $_POST['atts']['source'] ) {
		$attributes .= ' parent="'. get_queried_object_id() .'" ';
	}

	$shortcode = '[product_categories '. $attributes .']';
	
	// do_shortcode the shortcode
	echo do_shortcode($shortcode);
		
	wp_die();
}

// Products Categories Handler
add_action('wp_ajax_pagelayer_product_archives', 'pagelayer_product_archives');
function pagelayer_product_archives(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if ( WC()->session ) {
		wc_print_notices();
	}
	
	$atts['paginate'] = true;
	$atts['cache'] = false;
	$no_found = $_POST['atts']['no_found'];
		
	if( empty($_POST['atts']['allow_order']) ){
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	}
	if( empty($_POST['atts']['show_result']) ){
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	}
			
	$type = 'pagelayer_current_query';
	
	// Set the current query 
	add_action( 'woocommerce_shortcode_products_query', 'pagelayer_shortcode_current_query', 10, 10);
	
	// If product not found
	add_action( "woocommerce_shortcode_{$type}_loop_no_results", function ($attributes) use ($no_found){
		echo '<div class="pagelayer-product-no-found">'.$no_found.'</div>';
	} );
	
	// Get the products list
	$shortcode = new WC_Shortcode_Products( $atts, $type );

	echo $shortcode->get_content();
		
	wp_die();
}

// Products Categories Handler
add_action('wp_ajax_pagelayer_products_ajax', 'pagelayer_products_ajax');
function pagelayer_products_ajax(){
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	if ( WC()->session ) {
		wc_print_notices();
	}
	
	$no_found = $_POST['atts']['no_found'];
	
	$attributes = '';
	$type = $_POST['atts']['source'];
	$attributes .= ' columns="'. $_POST['atts']['columns'] .'" ';
	$attributes .= ' rows="'. $_POST['atts']['rows'] .'" ';
	$attributes .= ' paginate="'. (!empty($_POST['atts']['paginate']) ? true : false) .'" ';
	$attributes .= ' orderby="'. $_POST['atts']['orderby'] .'" ';
	$attributes .= ' order="'. $_POST['atts']['order'] .'" ';	
	$attributes .= ' cache="false" ';	
	
	// Hide the catalog order
	if( empty($_POST['atts']['allow_order']) ){
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	}
	
	// Hide the result count
	if( empty($_POST['atts']['show_result']) ){
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	}
	
	if( $type == 'by_id' ){
		$type = 'products';
		$attributes .= ' ids="'. (!empty($_POST['atts']['ids']) ? $_POST['atts']['ids'] : '') .'" ';	
	}elseif( $type == 'pagelayer_current_query' ){
		
		$atts['paginate'] = (!empty($_POST['atts']['paginate']) ? true : false);
		$atts['cache'] = false;
				
		$type = 'pagelayer_current_query';
		
		// Set the current query
		add_action( 'woocommerce_shortcode_products_query', 'pagelayer_shortcode_current_query', 10, 10);
		
		// If product not found
		add_action( "woocommerce_shortcode_{$type}_loop_no_results", function ($attributes) use ($no_found){
			echo '<div class="pagelayer-product-no-found">'.$no_found.'</div>';
		} );
		
		// Get the products list
		$shortcode = new WC_Shortcode_Products( $atts, $type );
			
		echo $shortcode->get_content();
		return true;
	}
		
	$shortcode = '['.$type.' '. $attributes .']';
	
	$content = do_shortcode($shortcode);
	
	// If product not found
	if('<div class="woocommerce columns-'.$_POST['atts']['columns'] .' "></div>' == $content){
		$content = '<div class="pagelayer-product-no-found">'. $no_found .'</div>';
	}
	
	echo $content;
		
	wp_die();
}

// Get Taxamony List for SiteMap
add_action('wp_ajax_pagelayer_get_taxonomy_list', 'pagelayer_get_taxonomy_list');
add_action('wp_ajax_nopriv_pagelayer_get_taxonomy_list', 'pagelayer_get_taxonomy_list');
function pagelayer_get_taxonomy_list(){
	
	$args = array(
		'title_li' => 0,
		'orderby' => $_POST['post_order'],
		'order' => $_POST['order'],
		'style' => '',
		'hide_empty' => $_POST['empty'],
		'echo' => false,
		'hierarchical' => (empty($_POST['hier']) || $_POST['hier'] == null ? '' : $_POST['hier']),
		'taxonomy' => $_POST['type'],
		'depth' => (empty($_POST['depth']) || $_POST['depth'] == null ? '' : $_POST['depth']),		
	);

	$taxonomies = get_categories( $args );
	
	$option = '<ul>';	
	foreach ( $taxonomies as $taxonomy ) {
		$option .= '<li class="pagelayer-sitemap-list-item" data-postID="'.$taxonomy->term_id.'"><a class="pagelayer-ele-link" href="'.get_term_link($taxonomy->term_id).'">'.$taxonomy->name.'</a></li>';
	}
	$option .= '</ul>'; 
	
	echo $option;
	wp_die();	
}

// Export the template
add_action('wp_ajax_pagelayer_export_template', 'pagelayer_export_template');
function pagelayer_export_template(){
	
	global $pagelayer;
	
	// Some AJAX security
	check_ajax_referer('pagelayer_ajax', 'pagelayer_nonce');
	
	$done = [];
	
	// Load the templates
	pagelayer_builder_load_templates();
	
	if(empty($pagelayer->templates)){
		$done['error'] = __pl('temp_export_empty');
		pagelayer_json_output($done);
	}
	
	// Get the active theme
	$theme_dir = get_stylesheet_directory();
	$conf = [];
	
	$pagelayer->export_mode = 1;
	
	// Write the files
	foreach($pagelayer->templates as $k => $v){
		
		// Are there specific templates to export
		if(!empty($_POST['templates'])){
			if(!isset($_POST['templates'][$v->ID])){
				continue;
			}
		}
		
		// Write the content
		file_put_contents($theme_dir.'/'.$v->post_name.'.pgl', pagelayer_export_content($v->post_content));		
		$conf[$v->post_name] = [
			'type' => get_post_meta($v->ID, 'pagelayer_template_type', true),
			'title' => $v->post_title,
			'conditions' => get_post_meta($v->ID, 'pagelayer_template_conditions', true),
		];
	}
	
	// Write the config
	file_put_contents($theme_dir.'/pagelayer.conf', json_encode($conf, JSON_PRETTY_PRINT));
	
	// Any pages to export for users ?
	if(!empty($_POST['pages'])){
		
		mkdir($theme_dir.'/data/');
		mkdir($theme_dir.'/data/page');
		
		$conf = [];
		
		// Load the pages
		$pages_query = new WP_Query(['post_type' => 'page', 'status' => 'publish']);
		$pages = $pages_query->posts;
	
		// Write the files
		foreach($pages as $k => $v){
			
			if(!isset($_POST['pages'][$v->ID])){
				continue;
			}
		
			file_put_contents($theme_dir.'/data/page/'.$v->post_name, pagelayer_export_content($v->post_content));
			unset($v->post_content);
			$conf['page'][$v->post_name] = $v;
			
			do_action('pagelayer_page_exported', $v, $theme_dir);
			
		}
		
		if(get_option('pagelayer_body_font')){
			$conf['conf']['pagelayer_body_font'] = get_option('pagelayer_body_font');
		}
	
		// Write the config
		file_put_contents($theme_dir.'/pagelayer-data.conf', json_encode($conf, JSON_PRETTY_PRINT));
		
	}
	
	// Are we to export any media ?
	if(!empty($pagelayer->media_to_export)){		
		// TODO
		//$done['media'] = $pagelayer->media_to_export;
	}
	
	$done['success'] = __pl('temp_export_success');
	
	// Output and die
	pagelayer_json_output($done);
	
}
