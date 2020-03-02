<?php

//////////////////////////////////////////////////////////////
//===========================================================
// class.php
//===========================================================
// PAGELAYER
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit Gupta
// Date:	   23rd Jan 2017
// Time:	   23:00 hrs
// Site:	   http://pagelayer.com/wordpress (PAGELAYER)
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

// Get the client IP
function _pagelayer_getip(){
	if(isset($_SERVER["REMOTE_ADDR"])){
		return $_SERVER["REMOTE_ADDR"];
	}elseif(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	}elseif(isset($_SERVER["HTTP_CLIENT_IP"])){
		return $_SERVER["HTTP_CLIENT_IP"];
	}
}

// Get the client IP
function pagelayer_getip(){

	global $pagelayer;

	// Just so that we have something
	$ip = _pagelayer_getip();

	$pagelayer['ip_method'] = (int) @$pagelayer['ip_method'];

	if(isset($_SERVER["REMOTE_ADDR"])){
		$ip = $_SERVER["REMOTE_ADDR"];
	}

	if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && @$pagelayer['ip_method'] == 1){
		$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}

	if(isset($_SERVER["HTTP_CLIENT_IP"]) && @$pagelayer['ip_method'] == 2){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
	}

	// Hacking fix for X-Forwarded-For
	if(!pagelayer_valid_ip($ip)){
		return '';
	}

	return $ip;

}

// Execute a select query and return an array
function pagelayer_selectquery($query, $array = 0){
	global $wpdb;

	$result = $wpdb->get_results($query, 'ARRAY_A');

	if(empty($array)){
		return current($result);
	}else{
		return $result;
	}
}

// Check if an IP is valid
function pagelayer_valid_ip($ip){

	// IPv6
	if(pagelayer_valid_ipv6($ip)){
		return true;
	}

	// IPv4
	if(!ip2long($ip)){
		return false;
	}

	return true;
}

function pagelayer_valid_ipv6($ip){

	$pattern = '/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/';

	if(!preg_match($pattern, $ip)){
		return false;
	}

	return true;

}

// Check if a field is posted via POST else return default value
function pagelayer_optpost($name, $default = ''){

	if(!empty($_POST[$name])){
		return pagelayer_inputsec(pagelayer_htmlizer(trim($_POST[$name])));
	}

	return $default;
}

// Check if a field is posted via GET else return default value
function pagelayer_optget($name, $default = ''){

	if(!empty($_GET[$name])){
		return pagelayer_inputsec(pagelayer_htmlizer(trim($_GET[$name])));
	}

	return $default;
}

// Check if a field is posted via GET or POST else return default value
function pagelayer_optreq($name, $default = ''){

	if(!empty($_REQUEST[$name])){
		return pagelayer_inputsec(pagelayer_htmlizer(trim($_REQUEST[$name])));
	}

	return $default;
}

// For filling in posted values
function pagelayer_POSTval($name, $default = ''){

	return (!empty($_POST) ? (!isset($_POST[$name]) ? '' : $_POST[$name]) : $default);

}

function pagelayer_POSTchecked($name, $default = false){

	return (!empty($_POST) ? (isset($_POST[$name]) ? 'checked="checked"' : '') : (!empty($default) ? 'checked="checked"' : ''));

}

function pagelayer_POSTselect($name, $value, $default = false){

	if(empty($_POST)){
		if(!empty($default)){
			return 'selected="selected"';
		}
	}else{
		if(isset($_POST[$name])){
			if(trim($_POST[$name]) == $value){
				return 'selected="selected"';
			}
		}
	}

}

function pagelayer_inputsec($string){

	if(!get_magic_quotes_gpc()){

		$string = addslashes($string);

	}else{

		$string = stripslashes($string);
		$string = addslashes($string);

	}

	// This is to replace ` which can cause the command to be executed in exec()
	$string = str_replace('`', '\`', $string);

	return $string;

}

function pagelayer_htmlizer($string){

	$string = htmlentities($string, ENT_QUOTES, 'UTF-8');

	preg_match_all('/(&amp;#(\d{1,7}|x[0-9a-fA-F]{1,6});)/', $string, $matches);//r_print($matches);

	foreach($matches[1] as $mk => $mv){
		$tmp_m = pagelayer_entity_check($matches[2][$mk]);
		$string = str_replace($matches[1][$mk], $tmp_m, $string);
	}

	return $string;

}

function pagelayer_entity_check($string){

	//Convert Hexadecimal to Decimal
	$num = ((substr($string, 0, 1) === 'x') ? hexdec(substr($string, 1)) : (int) $string);

	//Squares and Spaces - return nothing 
	$string = (($num > 0x10FFFF || ($num >= 0xD800 && $num <= 0xDFFF) || $num < 0x20) ? '' : '&#'.$num.';');

	return $string;

}

// Check if a checkbox is selected
function pagelayer_is_checked($post){

	if(!empty($_POST[$post])){
		return true;
	}
	return false;
}

// Reoort an error
function pagelayer_report_error($error = array()){

	if(empty($error)){
		return true;
	}

	$error_string = '<b>Please fix the below error(s) :</b> <br />';

	foreach($error as $ek => $ev){
		$error_string .= '* '.$ev.'<br />';
	}

	echo '<div id="message" class="error"><p>'
					. __pl($error_string)
					. '</p></div>';
}

// Report a notice
function pagelayer_report_notice($notice = array()){

	global $wp_version;

	if(empty($notice)){
		return true;
	}

	// Which class do we have to use ?
	if(version_compare($wp_version, '3.8', '<')){
		$notice_class = 'updated';
	}else{
		$notice_class = 'updated';
	}

	$notice_string = '<b>Please check the below notice(s) :</b> <br />';

	foreach($notice as $ek => $ev){
		$notice_string .= '* '.$ev.'<br />';
	}

	echo '<div id="message" class="'.$notice_class.'"><p>'
					. __pl($notice_string)
					. '</p></div>';
}

// Convert an objext to array
function pagelayer_objectToArray($d){

	if(is_object($d)){
		$d = get_object_vars($d);
	}

	if(is_array($d)){
		return array_map(__FUNCTION__, $d); // recursive
	}elseif(is_object($d)){
		return pagelayer_objectToArray($d);
	}else{
		return $d;
	}
}

// Sanitize variables
function pagelayer_sanitize_variables($variables = array()){

	if(is_array($variables)){
		foreach($variables as $k => $v){
			$variables[$k] = trim($v);
			$variables[$k] = escapeshellcmd($v);
		}
	}else{
		$variables = escapeshellcmd(trim($variables));
	}

	return $variables;
}

// Is multisite ?
function pagelayer_is_multisite() {

	if(function_exists('get_site_option') && function_exists('is_multisite') && is_multisite()){
		return true;
	}

	return false;
}

// Generate a random string
function pagelayer_RandomString($length = 10){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$charactersLength = strlen($characters);
	$randomString = '';
	for($i = 0; $i < $length; $i++){
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function pagelayer_print($array){

	echo '<pre>';
	print_r($array);
	echo '</pre>';

}

function pagelayer_cleanpath($path){
	$path = str_replace('\\\\', '/', $path);
	$path = str_replace('\\', '/', $path);
	$path = str_replace('//', '/', $path);
	return rtrim($path, '/');
}

// Returns the Numeric Value of results Per Page
function pagelayer_get_page($get = 'page', $resperpage = 50){

	$resperpage = (!empty($_REQUEST['reslen']) && is_numeric($_REQUEST['reslen']) ? (int) pagelayer_optreq('reslen') : $resperpage);

	if(pagelayer_optget($get)){
		$pg = (int) pagelayer_optget($get);
		$pg = $pg - 1;
		$page = ($pg * $resperpage);
		$page = ($page <= 0 ? 0 : $page);
	}else{
		$page = 0;
	}
	return $page;
}

// Are we editing from the Admin panel ?
function pagelayer_is_editing($force = false){

	global $post, $pagelayer;

	if(!empty($force)){
		return true;
	}

	if(!is_admin()){
		return false;
	}

	$current_file = basename($_SERVER['PHP_SELF']);

	$type = get_post_type();
	//echo $type;return false;
	//$page = pagelayer_optreq('page');

	// Are we in the live editor mode OR is this a post which is supported
	if((pagelayer_supported_type($type) && in_array($current_file, array('post.php', 'post-new.php'))) || pagelayer_is_live()){
		return true;
	}else{
		return false;
	}

}

// Is the given post type editable by us ?
function pagelayer_supported_type($type){

	global $pagelayer;

	$type = trim($type);

	if(in_array($type, $pagelayer->settings['post_types'])){
		return true;
	}

	return false;

}

function pagelayer_shortlink($id){
	$link = wp_get_shortlink($id);
	$link .= substr_count($link, '?') > 0 ? '' : '?';
	return $link;
}

// Pagelayer live link
function pagelayer_livelink($id){
	return pagelayer_shortlink($id).'&pagelayer-live=1';
}

// Are we in live mode ?
function pagelayer_is_live(){

	global $post;

	// Are we seeing the post ?
	if(!isset($post) || !isset($post->ID) || empty($post->ID)){
		return false;
	}

	// Is it the live mode ?
	if(pagelayer_optreq('pagelayer-live')){
		return true;
	}

	return false;

}

// Are we in live IFRAME mode ?
function pagelayer_is_live_iframe(){

	// Are we seeing the post ?
	if(!pagelayer_is_live()){
		return false;
	}

	// Is it the live mode ?
	if(pagelayer_optreq('pagelayer-iframe')){
		return true;
	}

	return false;

}

// Can the current user edit the post ?
function pagelayer_user_can_edit($post = NULL){

	global $wp_the_query, $current_user;

	if(!isset($post) || empty($post) || $post === NULL){
		global $post;
	}

	wp_get_current_user();

	if(isset($post) && is_object($post) && isset($post->ID) && isset($post->post_author) && isset($current_user) && is_object($current_user) && isset($current_user->ID) && (current_user_can('edit_others_posts', $post->ID) || ($post->post_author == $current_user->ID))){
		return true;
	}

	return false;

}

// Language sting function
function __pl($key){

	global $pagelayer;

	if(!empty($pagelayer->l[$key])){
		return $pagelayer->l[$key];
	}

	return $key;

}

// Give the list of icon sources
function pagelayer_icon_sources(){
	return array();
}

// Loads the shortcodes
function pagelayer_load_shortcodes(){

	include_once(PAGELAYER_DIR.'/main/shortcode_functions.php');
	if(defined('PAGELAYER_PREMIUM')){
		include_once(PAGELAYER_DIR.'/main/premium_functions.php');
	}
	include_once(PAGELAYER_DIR.'/main/shortcodes.php');

	// pQuery
	include_once(PAGELAYER_DIR.'/lib/pquery/IQuery.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_formatter.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_node_html.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_tokenizer.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_parser_html.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_selector_html.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/gan_xml2array.php');
	include_once(PAGELAYER_DIR.'/lib/pquery/pQuery.php');
	
	// Apply filter to load custom widgets
	do_action('pagelayer_load_custom_widgets');

}

// Add the shortcodes to the pagelayer list
function pagelayer_add_shortcode($tag, $params = array()){

	global $pagelayer;

	// Is there a handler function ?
	if(!empty($params['func'])){
		
		if($tag == 'pl_row'){
			$inner_tag = 'pl_inner_row';
			add_shortcode($inner_tag, 'pagelayer_render_shortcode');
		}
		
		if($tag == 'pl_col'){
			$inner_tag = 'pl_inner_col';
			add_shortcode($inner_tag, 'pagelayer_render_shortcode');
		}
		
		add_shortcode($tag, 'pagelayer_render_shortcode');//$params['func']);
		//unset($params['func']);
	}

	// Is there a group ?
	if(empty($params['group'])){
		$params['group'] = 'misc';
	}

	// Add the advanced styling group
	$params['options'] = [
		'ele_bg_styles' => __pl('ele_bg_styles'),
		'ele_styles' => __pl('ele_styles'),
		'border_styles' => __pl('border_styles'),
		'position_styles' => __pl('position_styles'),
		'animation_styles' => __pl('animation_styles'),
		'responsive_styles' => __pl('responsive_styles'),
		'custom_styles' => __pl('custom_styles'),
	];
	
	if(!empty($params['skip_props_cat'])){
		foreach($params['skip_props_cat'] as $k => $v){
			unset($params['options'][$v]);
		}
	}

	// Are the settings there which hold the params ?
	if(empty($params['settings'])){
		$params['settings'] = [
			'params' => $params['name'],
		];
	}

	// Disable the style options
	if(!empty($params['styles'])){
		$params['settings'] = array_merge($params['settings'], $params['styles']);
		unset($params['styles']);
	}
	
	/*// The following is for testing only
	$r = [];
	foreach($pagelayer->styles as $k => $v){
		foreach($v as $kk => $vv){
			$r[$kk] = $kk;
		}
	}
	//print_r($r);die();
	
	foreach($params['settings'] as $k => $v){
		if(empty($params[$k])) continue;
		foreach($params[$k] as $kk => $vv){
			if(!empty($r[$kk])){
				echo 'Duplicate KEY '.$kk.' in Shortcode '.$tag."<br>";
			}
		}
	}
	//die();*/

	// Insert the shortcode
	$pagelayer->shortcodes[$tag] = $params;
	$pagelayer->groups[$params['group']][] = $tag;

}

// Returns the Image values
function pagelayer_image($id){
	
	global $pagelayer;
	
	$ret = [];

	// External image ?
	if(pagelayer_is_external_img($id)){

		$ret['url'] = $id;

	// Attachment
	}elseif(!empty($id)){

		$id = (int) @$id;

		$image = get_post($id);

		// Is there an attachment which is an image ?
		if(!empty($image) && $image->post_type == 'attachment' && wp_attachment_is_image($id)){
		
			// Need to export necessary media
			if(!empty($pagelayer->export_mode)){
				$pagelayer->media_to_export[] = $id;
			}

			$sizes = get_intermediate_image_sizes();
			array_unshift($sizes, 'full');

			foreach($sizes as $size){
				$src = wp_get_attachment_image_src($id, $size);
				$ret[$size.'-url'] = $src[0];
			}

			// Title and Alt
			$title = esc_attr($image->post_title);
			$alt = get_post_meta($id, '_wp_attachment_image_alt', true);
			$alt = empty($alt) ? $image->post_excerpt : $alt;
			$alt = empty($alt) ? $image->post_title : $alt;
			$alt = empty($alt) ? '' : trim(strip_tags($alt));
			$link = get_attachment_link($id);
			$caption = wp_get_attachment_caption($id);
			$caption = !empty($caption) ? $caption : '';

			$ret['alt'] = $alt;
			$ret['title'] = $title;
			$ret['link'] = $link;
			$ret['caption'] = $caption;

		}

	}

	// First preference to full url
	if(!empty($ret['full-url'])){
		$ret['url'] = $ret['full-url'];
	}

	// No image
	if(empty($ret['url'])){
		$ret['url'] = PAGELAYER_URL.'/images/default-image.png';
	}
	
	$ret = apply_filters('pagelayer_image', $ret);

	return $ret;

}

// Checks if the given parameter is an external link or a wp attachment id
function pagelayer_is_external_img($img = ''){

	if(empty($img)){
		return false;
	}

	if(preg_match('#http://#is', $img) || preg_match('#https://#is', $img) || preg_match('#^{{#is', $img)){
		return true;
	}

	return false;

}

// Returns the attachment url
function pagelayer_attachment($id){

	$ret = [];

	// External url ?
	if(pagelayer_is_external_img($id)){

		$ret['url'] = $id;

	// Attachment
	}elseif(!empty($id)){
		
		// Need to export necessary media
		if(!empty($pagelayer->export_mode)){
			$pagelayer->media_to_export[] = $id;
		}

		$ret['url'] = wp_get_attachment_url($id);

	}
	
	$ret = apply_filters('pagelayer_attachment', $ret);

	return $ret;

}

// Convert the regular URL of a Video to a Embed URL
// Todo : Check
function pagelayer_video_url($source){

	if (!empty($source)) {
		
		$source = esc_url( $source );
		$source = str_replace('&amp;', '&', $source);
		$url = parse_url($source);
		$videoSite ='';

		$youtubeRegExp = '/youtube\.com|youtu\.be/is';
		$vimeoRegExp = '/vimeo\.com/is';

		if (preg_match($youtubeRegExp, $source)) {
			$videoSite = 'youtube';
		} else if (preg_match($vimeoRegExp, $source)) {
			$videoSite = 'vimeo';
		}

		switch ($videoSite) {
			case 'youtube':

				if (preg_match('/youtube\.com/is', $source)) {

					if (preg_match('/watch/is', $source)) {
						parse_str($url['query'], $parameters);

						if (isset($parameters['v']) && !empty($parameters['v'])) {
						   $videoId = $parameters['v'];
						}

					} else if (preg_match('/embed/is', $url['path'])) {
						$path = explode('/', $url['path']);
						if (isset($path[2]) && !empty($path[2])) {
							$videoId = $path[2];
						}
					}

				} else if (preg_match('/youtu\.be/is', $url['host'])) {
					$path = explode('/', $url['path']);

					if (isset($path[1]) && !empty($path[1])) {
						$videoId = $path[1];
					}

				}

				return '//youtube.com/embed/'.$videoId;

				break;
			case 'vimeo':

				if (preg_match('/player\.vimeo\.com/is', $url['host']) && preg_match('/video/is', $url['path'])) {
					$path = explode('/', $url['path']);

					if (isset($path[2]) && !empty($path[2])) {
						$videoId = $path[2];
					}

				} else if (preg_match('/vimeo\.com/is', $url['host'])) {
					$path = explode('/', $url['path']);

					if (isset($path[1]) && !empty($path[1])) {
						$videoId = $path[1];
					}

				}

				return '//player.vimeo.com/video/'.$videoId;

				break;
			default:

				return $source;

		}

	}
}


// As per the JS specification
function pagelayer_escapeHTML($str){
	
	$replace = [
		']' => '&#93;',
		'[' => '&#91;',
		//'=' => '&#61;',
		'<' => '&lt;',
		'>' => '&gt;',
		'"' => '&quot;',
		//'&' => '&amp;',
		'\'' => '&#39;'
	];
	
	$str = str_replace(array_keys($replace), array_values($replace), $str);
	
	return $str;
}

// As per the JS specification
function pagelayer_unescapeHTML($str){
	$replace = [
		'#93' => ']',
		'#91' => '[',
		//'#61' => '=',
		'lt' => '<',
		'gt' => '>',
		'quot' => '"',
		//'amp' => '&',
		'#39' => '\''
	];
	
	foreach($replace as $k => $v){
		$str = str_replace('&'.$k.';', $v, $str);
	}
	return $str;
}

// Show promo notice on dashboard
function pagelayer_show_promo(){
	
	global $pagelayer_promo_opts;
	$opts = $pagelayer_promo_opts;
	
	echo '<style>
.pagelayer_promo_button {
background-color: #4CAF50; /* Green */
border: none;
color: white;
padding: 6px 10px;
text-align: center;
text-decoration: none;
display: inline-block;
font-size: 13px;
margin: 4px 2px;
-webkit-transition-duration: 0.4s; /* Safari */
transition-duration: 0.4s;
cursor: pointer;
}
.pagelayer_promo_button:focus,
.pagelayer_promo_button:hover{
border: none;
color: white;
box-shadow: 0 6px 8px 0 rgba(0,0,0,0.24), 0 9px 25px 0 rgba(0,0,0,0.19);
color: white;
}
.pagelayer_promo_buy {
color: white;
padding: 8px 12px;
font-size: 14px;
}
.pagelayer_promo_button1 {
color: white;
background-color: #4CAF50;
border:3px solid #4CAF50;
}
.pagelayer_promo_button1:hover {
border:3px solid #4CAF50;
}
.pagelayer_promo_button2 {
color: white;
background-color: #0085ba;
}
.pagelayer_promo_button3 {
color: white;
background-color: #365899;
}
.pagelayer_promo_button4 {
color: white;
background-color: rgb(66, 184, 221);
}
.pagelayer_promo-close{
float:right;
text-decoration:none;
margin: 5px 10px 0px 0px;
}
.pagelayer_promo-close:hover{
color: red;
}
</style>
<script type="application/javascript">
	jQuery(document).ready(function(){
		jQuery("#pagelayer_promo .pagelayer_promo-close").click(function(){
			var data;
			jQuery("#pagelayer_promo").hide();
			// Save this preference
			jQuery.post("'.admin_url('?pagelayer_promo=0').'", data, function(response) {
				//alert(response);
			});
		});
	});
</script>

	<div class="notice notice-success" id="pagelayer_promo" style="min-height:90px">
	<a class="pagelayer_promo-close" href="javascript:" aria-label="Dismiss this Notice">
		<span class="dashicons dashicons-dismiss"></span> Dismiss
	</a>';
	
	if(!empty($opts['image'])){
		echo '<a href="'.$opts['website'].'"><img src="'.$opts['image'].'" style="float:left; margin:10px 20px 10px 10px" width="67" /></a>';
	}
	
	echo '
	<p style="font-size:13px">We are glad you like <a href="'.$opts['website'].'"><b>Pagelayer</b></a> and have been using it since the past few days. It is time to take the next step !</p>
	<p>
		'.(empty($opts['pro_url']) ? '' : '<a class="pagelayer_promo_button pagelayer_promo_buy" target="_blank" href="'.$opts['pro_url'].'">Buy Pagelayer Pro</a>').'
		'.(empty($opts['rating']) ? '' : '<a class="pagelayer_promo_button pagelayer_promo_button2" target="_blank" href="'.$opts['rating'].'">Rate it 5★\'s</a>').'
		'.(empty($opts['facebook']) ? '' : '<a class="pagelayer_promo_button pagelayer_promo_button3" target="_blank" href="'.$opts['facebook'].'"><span class="dashicons dashicons-thumbs-up"></span> Facebook</a>').'
		'.(empty($opts['twitter']) ? '' : '<a class="pagelayer_promo_button pagelayer_promo_button4" target="_blank" href="'.$opts['twitter'].'"><span class="dashicons dashicons-twitter"></span> Tweet</a>').'
		'.(empty($opts['website']) ? '' : '<a class="pagelayer_promo_button pagelayer_promo_button4" target="_blank" href="'.$opts['website'].'">Visit our website</a>').'
	</p>
	<p style="font-size:13px"><a href="'.$opts['pro_url'].'"><b>Pagelayer Pro</b></a> has many more features like 60+ widgets, 400+ sections, Theme Builder, WooCommerce Builder, Theme Creator and Exporter, Form Builder, Popup Builder, etc.';
	
	if(date('Ymd') <= 20200331){
		echo '<br><span style="font-size: 14px"><b>Promotional Offer</b></span> : If you buy <a href="'.$opts['pro_url'].'"><b>Pagelayer Pro</b></a> before <b>31st March, 2020</b> then you will get an additional year free and your license will expire on <b>31st March, 2022</b>.';
	}
	
echo '</p></div>';

}

// Are we to show a promo ?
function pagelayer_maybe_promo($opts){
	
	global $pagelayer_promo_opts;
	
	// There must be an interval
	if(empty($opts['interval'])){
		return false;
	}
	
	// Are we to show a promo	
	$opt_name = 'pagelayer_promo_time';
	$promo_time = get_option($opt_name);
	
	// First time access
	if(empty($promo_time)){
		update_option($opt_name, time() + (!empty($opts['after']) ? $opts['after'] * 86400 : 0));
		$promo_time = get_option($opt_name);
	}
	
	// Is there interval elapsed
	if(time() > $promo_time){
		$pagelayer_promo_opts = $opts;
		add_action('admin_notices', 'pagelayer_show_promo');
	}
	
	// Are we to disable the promo
	if(isset($_GET['pagelayer_promo']) && (int)$_GET['pagelayer_promo'] == 0){
		update_option($opt_name, time() + ($opts['interval'] * 86400));
		die('DONE');
	}
	
}

// Bread Crumbs with links
function pagelayer_get_breadcrumb(){
	
	// Home page
	$ret = '<a href="'.home_url().'" rel="nofollow">{{home}}</a>';
	
	// Is Front page
	if(is_front_page()){
		return $ret;
	
	// Is search query
	}elseif(is_search()){
		$ret .= '<b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b><b class="pagelayer-breadcrumb-prefix" if="{{search_prefix}}">{{search_prefix}}</b>';
		$ret .= '<em>';
		$ret .= get_search_query();
		$ret .= '</em>';
		
	//Is category or single post
	}elseif(is_category() || is_single()){
		
		$ret .= '<b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b>';
		$categories = get_the_category();
		$separator = ' ';
		$output = '';
		if(!empty($categories)){
			$ret .= get_category_parents($categories[0], true, ' <b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b> ');
		}
		if(is_single()) {
			/* if (empty($categories)){
				$ret .= ' <b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b> ';
			} */
			$ret .= get_the_title();
		}
	
	// Is page
	}elseif(is_page()){
		$ret .= '<b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b>';
		$ret .= get_the_title();
	}else{
		$ret .= '<b class="pagelayer-breadcrumb-sep" if="{{separator}}">{{separator}}</b>';
		$ret .= wp_title('', false );
	}
	
	// wooCommerce Integration left
	
	return $ret;
}

// Portfolio Posts
function pagelayer_widget_posts($args){
	
	$r = new \WP_Query($args);
	
	$ret = '';
	if ( $r->have_posts() ){
		
		if($args['filter_by'] != 'none' && $args['post_type'] == 'post'){
			$ret .= '<div class="pagelayer-postfolio-filter">';
		
			$data_filter = ( $args['filter_by'] == 'category' ? get_categories() : get_tags() );
			
			if(!empty($data_filter)){
				
				$ret .= '<div class="pagelayer-postfolio-btn" data-filter="all">'.__pl('All').'</div>';
			
				foreach($data_filter as $filter) {
				   $ret .= '<div class="pagelayer-postfolio-btn" data-filter="'.$filter->name.'">' . $filter->name . '</div>';
				}
			
			}

			$ret .= '</div>';
		}
		
		$ret .= '<div class="pagelayer-postfolio-container">';
		
		while ( $r->have_posts() ) : $r->the_post();
			$post_meta = ( $args['filter_by'] == 'category' ? get_the_category() : get_the_tags() );
			$meta_attr = '';
			if($post_meta){
				
				$meta_array = array();
				foreach( $post_meta as $meta ){
					$meta_array[] = $meta->name;
				}
				$meta_attr .= ' data-category="'.implode(' ', $meta_array).'"';
			}
			
			$ret .= '<div class="pagelayer-postfolio-thumb"'.(has_post_thumbnail() ? ' style="background:url('.get_the_post_thumbnail_url().')"' : ''). $meta_attr .'>';
			$ret .= '<a href="'.esc_url( get_permalink() ).'" rel="bookmark">';
			$ret .= '<div class="pagelayer-postfolio-content">';
			$ret .= '<h4 class="pagelayer-entry-title">'.get_the_title().'</h4>';
			$ret .= '</div></a></div>';
		
		endwhile;
		
		$ret .= '</div>';
		
	}
	
	return $ret;
}

// List all available sizes of images registered on WordPress
function pagelayer_image_sizes(){
	
	$sizes = array();
	$sizes = get_intermediate_image_sizes();
	$ret = array();
	foreach($sizes as $size){
		$ret[$size] = __pl($size);
	}
	
	return $ret;
}

function pagelayer_remove_excerpt_more($more){
	return '';
}

function pagelayer_posts($params, $args = []){
	
	if($params['exc_length']){
		$exc_length = (int) $params['exc_length'];
		add_filter( 'excerpt_length', function($length) use($exc_length){
			return $exc_length;
		}, 999 );
	}
		
	add_filter('excerpt_more', 'pagelayer_remove_excerpt_more', 999);
	
	// If args is empty
	if(empty($args)){
		$args = array(
			'post_type' => $params['post_type'],
			'posts_per_page' => $params['posts_per_page'],
			'order' => $params['order']
		);
		
		if($params['term']){
			
			$term = explode(':', $params['term']);
			//pagelayer_print($term);
			if($term[0] == 'category'){
				$args['category_name'] = $term[1];
			}
			if($term[0] == 'post_tag'){
				$args['tag'] = $term[1];
			}
		}
		
		if($params['exc_term']){
			
			$term = explode(':', $params['exc_term']);
			//pagelayer_print($term);
			if($term[0] == 'category'){
				$args['category__not_in'] = $term[2];
			}
			if($term[0] == 'post_tag'){
				$args['tag__not_in'] = $term[2];
			}
		}
		
		if($params['author_name']){
			$author_name = explode(':', $params['author_name']);
			$args['author_name'] = $author_name[0];
		}
		
		if($params['exc_author']){
			$exc_author = explode(':', $params['exc_author']);
			$args['author'] = '-'.$exc_author[1];
		}
		
		if(!empty($params['offset'])){
			$args['offset'] = $params['offset'];
		}
		
		if(!empty($params['ignore_sticky'])){
			$args['ignore_sticky_posts'] = $params['ignore_sticky'];
		}
		
		if(!empty($params['orderby'])){
			$args['orderby'] = $params['orderby'];
		}
		
		if($params['by_period']){
			$date_arg = array();
			switch($params['by_period']){
				case 'last_day':
					$date_arg['day'] = date('j')-1;
					break;
				case 'last_week':
					$date_arg['week'] = date('W')-1;
					break;
				case 'last_month':
					$date_arg['month'] = date('n')-1;
					break;
				case 'last_year':
					$date_arg['year'] = date('Y')-1;
					break;
				case 'custom':
					$date_arg['before'] = $params['before_date'];
					$date_arg['after'] = $params['after_date'];
					break;
			}
			$args['date_query'] = array(
				$date_arg
			);
		}
	}
	//pagelayer_print($args);
	
	$postsquery = new WP_Query($args);
	$data = '';
	
	if(!$postsquery->have_posts()){
		return '<h3>Something went wrong, Please give proper inputs!</h3>';
	}
	
	while($postsquery->have_posts()) : $postsquery->the_post();
		$data .= '<div class="pagelayer-wposts-col">
			<div class="pagelayer-wposts-post">
				<div class="pagelayer-wposts-featured">';
		$data .= '<a href="'. get_the_permalink() .'">';
		
		if(isset($params['show_thumb']) && has_post_thumbnail( $postsquery->ID )){
			$data .= '<div class="pagelayer-wposts-thumb"'.(has_post_thumbnail() ? ' style="background:url('.get_the_post_thumbnail_url($postsquery->ID,$params['thumb_size']).')"' : '').'></div>';
		}
		/* if($params['show_thumb'] && has_post_thumbnail( $postsquery->ID )){
			$data .= get_the_post_thumbnail_url($postsquery->ID,$params['thumb_size']);
		} */
		$data .= '</a></div>
			<div class="pagelayer-wposts-content">';
		
		if(isset($params['show_title'])){
			$data .= '<a href="'.esc_url( get_permalink() ).'" rel="bookmark"><div class="pagelayer-wposts-title">'. get_the_title().'</div></a>';
		}
		
		$data .= '<div class="pagelayer-wposts-meta">';
		$sep = '';
		if(isset($params['meta_sep'])){
			$sep = '<b class="pagelayer-wposts-sep">'.$params['meta_sep'].'</b>';
		}
		if(isset($params['author'])){
			$data .= '<span class="pagelayer-wposts-author">By <a class="pagelayer-wposts-author-url" href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'">'.esc_html(get_the_author()).'</a></span>'.$sep;
		}
		if(isset($params['date'])){
			$data .= '<span class="pagelayer-wposts-date"><time class="pagelayer-wposts-entry-date published updated" datetime="'.get_the_date('c').'"><span class="date-d">'.get_the_date('j').'</span><span class="date-my">'.get_the_date('M, y').'</span></time></span>'.$sep;
		}
		if(isset($params['category'])){
			$category = get_the_category();
			$singlecategory = '';
			foreach( $category as $cat ){
				$singlecategory .= '<a href="' . get_tag_link( $cat->term_id ) . '">'. $cat->name .'</a>';
			}
			$data .= '<span class="pagelayer-wposts-category">' . $singlecategory . '</span>'.$sep;
		}
		if(isset($params['tags'])){
			$tags = get_the_tags();
			$singletag = '';
			if(!empty($tags)){
				foreach( $tags as $tag ){
					$singletag .= '<a href="' . get_tag_link( $tag->term_id ) . '">'. $tag->name .'</a>';
				}
				if(!empty($singletag)){
					$data .= '<span class="pagelayer-wposts-tags">'.$singletag.'</span>'.$sep;
				}
			}
			
		}
		if(isset($params['comments'])){
			$data .= '<span class="pagelayer-wposts-comments"><i class="far fa-comment"></i><a href="' . esc_url( get_permalink() ) . '">' . esc_html(get_comments_number()) . '</a></span>'.$sep;
		}
		
		$data .= '</div>';
		
		if(isset($params['show_content'])){
			$data .= '<div class="pagelayer-wposts-excerpt">';
			if($params['show_content'] == 'excerpt'){	
				$data .= do_shortcode(get_the_excerpt());
			}elseif($params['show_content'] == 'full'){
				$data .= do_shortcode(get_the_content());	
			}
			$data .= '</div>';
		}
		
		if(isset($params['show_more'])){
			$data .= '<div class="pagelayer-wposts-mdiv"><a class="pagelayer-wposts-more pagelayer-btn-holder pagelayer-ele-link '.$params['btn_type'].' '.$params['size'].' '.$params['icon_position'].'" href="'. get_the_permalink().'">';
			
			if($params['icon']){
				$data .= '<i class="'.$params['icon'].' pagelayer-btn-icon"></i>';
			}
			
			if($params['more']){
				$data .= '<span class="pagelayer-btn-text">'.$params['more'].'</span>';
			}
			
			if($params['icon']){
				$data .= '<i class="'.$params['icon'].' pagelayer-btn-icon"></i>';
			}
			
			$data .= '</a></div>';
			
			//$data .= '<a class="pagelayer-wposts-more" href="'. get_the_permalink().'">'.$params['more'].'</a>';
		}
		
		//$data .= '<div class="pagelayer-wposts-date"><p>'.get_the_date().'</p></div>';
		$data .= '</div></div></div>';
	endwhile;
	
	return $data;	
}

// Get Post Revision
function pagelayer_get_post_revision_by_id($postID){
	
	// Insert the post revision into the database
	$post_revisions = array();
	$reviews =  wp_get_post_revisions($postID);		
	
	foreach($reviews as $values){
		
		$date_format = date_i18n('j-M @ H:i', strtotime( $values->post_modified ) );
		$user_meta = get_userdata($values->post_author);
		
		if ( false !== strpos( $values->post_name, 'autosave' ) ) {
			$type = 'autosave';
		} else {
			$type = 'revision';
		}
		
		$post_tmp_data = array(
			'ID' => $values->ID,
			'post_author_name' => $user_meta->data->display_name,
			'post_author_url' => get_avatar_url($values->post_author),
			'post_date' => $date_format,
			'post_date_ago' => human_time_diff(strtotime($values->post_modified), current_time( 'timestamp' )) . ' ago ',
			'post_type' => $type,
		);
		
		$post_revisions[] = $post_tmp_data;
	}
	
	return $post_revisions;
}

// Gets author data
function pagelayer_author_data($postID){
	
	$authorID = get_post_field('post_author', $postID);
	$data['display_name'] = get_the_author_meta( 'display_name' , $authorID ); 
	$data['description'] = get_the_author_meta( 'description' , $authorID ); 
	$data['user_url'] = get_author_posts_url( $authorID , '' ); 
	$data['avatar'] = get_avatar_url( $authorID );

	return $data;
}

// Posts Slider
function pagelayer_posts_slider($params){

	$args = array();

	if(isset($params['post']['post_type'])){
		$args['post_type'] = $params['post']['post_type'];
	}

	if(isset($params['post']['category'])){
		$args['category_name'] = $params['post']['category'];
	}

	if(isset($params['post']['tags'])){
		$args['tag'] = $params['post']['tags'];
	}

	if(isset($params['post']['order_by'])){
		$args['orderby'] = $params['post']['order_by'];
	}

	if(isset($params['post']['sort_order'])){
		$args['order'] = $params['post']['sort_order'];
	}

	if(isset($params['post']['post_count'])){
		$args['posts_per_page'] = $params['post']['post_count'];
	}
	
	$data = '';
	$postsquery = new WP_Query($args);
	if ( $postsquery->have_posts() ){
		while ( $postsquery->have_posts() ) : $postsquery->the_post();
			$data .= '<li class="pagelayer-posts-slider-col">
				<div class="pagelayer-posts-slider-post">
					<div class="pagelayer-posts-slider-featured-img">';
			$data .= '<a href="'. get_the_permalink() .'">';
			if(has_post_thumbnail( $postsquery->ID ) ){
				if($params['post']['image_size'] == "full" || $params['post']['image_size'] == "custom" ){
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $postsquery->ID ), 'single-post-thumbnail' );
					$data .= '<img class="pagelayer-img" src="'.$image[0].'" class="pagelayer-posts-slider-img">';
				}else if($params['post']['image_size'] == "thumbnail"){ 
					$data .= get_the_post_thumbnail();
				}
			}
			$data .= '</a></div>
				<div class="pagelayer-posts-slider-content">';
			$data .= '<p class="pagelayer-posts-slider-title">'. get_the_title().'</p>';
			$data .= '<div class="pagelayer-posts-slider-excerpt">';
			if($params['post']['show_excerpt'] == "true"){
				if(has_excerpt()){	
					$excerpt = get_the_excerpt();
					$data .= do_shortcode($excerpt);
				}
			}
			$data .= '</div>';
			$data .= '<a class="pagelayer-posts-slider-link" href="'. get_the_permalink().'">Read More</a>';
			$data .= '<div class="pagelayer-post-slider-date"><p>'.get_the_date().'</p></div>';
			$data .= '</div></div></li>';
		endwhile;
	}
	return $data;
}

// Gets the site logo URLs 
function pagelayer_site_logo(){
	
	if(get_theme_mod('custom_logo')){
		$logo_id = get_theme_mod('custom_logo');
		return pagelayer_image($logo_id);
	}
	
	return NULL;
}

// Create select options
function pagelayer_create_sel_options( $opt_array , $selected = ''){
	$options = '';
	foreach($opt_array as $x => $val){

		// Single item
		if(is_string($opt_array[$x])){
			$options .= pagelayer_sel_option($x, $val, $selected);
		
		// Groups
		}else{
			
			// If Label is there, then its a normal option
			if(array_key_exists('label', $opt_array[$x])){
				$options .= pagelayer_sel_option($x, $opt_array[$x]['label'], $selected);
			
			// Optgroups
			} else{
				$options .= '<optgroup label="'. $x .'">';
				$options .= pagelayer_create_sel_options($opt_array[$x], $selected);
				$options .= '</optgroup>';
			}
		}
	}
	
	return $options;	
}

// Create option HTML
function pagelayer_sel_option($val, $lang, $selected){
	return '<option value="'. $val .'" '. (($val != $selected) ? '' : 'selected="selected"') .' >'. $lang .'</option>';
}

// Get values from multi-dimensional array by key 
function pagelayer_multi_array_search(&$array, $key){
	
	if(!is_array($array)){
		return false;
	}
	
	foreach ($array as $k => $v) {
	
		if($k == $key){
			return $v;
		}
	
		if (is_array($v)) {
			$found = pagelayer_multi_array_search($v, $key);
			if(!empty($found)){
				return $found;
			}
		}
	}
	
	return false;
}

function pagelayer_get_post_term(){
	
	$args = [
		'taxonomy' => array('category','post_tag'),
		'hide_empty' => false,
	];

	$terms = get_terms( $args );
	
	$ret = array();
	foreach ( $terms as $term ) {
		$ret[$term->taxonomy.':'.$term->slug.':'.$term->term_taxonomy_id] = $term->taxonomy .': '. $term->name;
	}
	//pagelayer_print($terms);die();
	return $ret;
}

function pagelayer_get_post_author(){
	
	$args = [
		'who' => 'authors',
		'fields' => [
			'ID',
			'display_name',
			'user_nicename',
		]
	];

	$authors = new \WP_User_Query( $args );
	
	$ret = array();
	foreach ( $authors->get_results() as $author ) {
		$ret[$author->user_nicename.':'.$author->ID] = $author->display_name;
	}
	//pagelayer_print($authors->get_results());die();
	return $ret;
}

// Gets the registered post types
function pagelayer_get_public_post_types( $args = [] ) {
	
	global $pagelayer;
	
	$post_type_args = [
		'public' => true,
	];
	
	$post_type_args = wp_parse_args( $post_type_args, $args );
	
	$_post_types = get_post_types( $post_type_args, 'objects' );

	$post_types = array();

	foreach ( $_post_types as $post_type => $object ) {
		
		if($post_type == $pagelayer->builder['name']){
			continue;
		}
		
		$post_types[ $post_type ] = $object->label;
	}
	//print_r($post_types);
	
	return $post_types;
}

// Simply echo and dir
function pagelayer_json_output(&$done){

	echo json_encode($done);
	wp_die();
	
}

// Get the current query for render the product
function pagelayer_shortcode_current_query($query_args, $atts, $type){
	global $wp_query;
	
	if($type == 'pagelayer_current_query'){
		
		if ( ! is_page( wc_get_page_id( 'shop' ) ) ) {
			$query_args = $wp_query->query_vars;
		}

		add_action( "woocommerce_shortcode_before_{$type}_loop", function () {
			wc_set_loop_prop( 'is_shortcode', false );
		} );

		if(!empty($atts['paginate'])){
			$page = get_query_var( 'paged', 1 );

			if( 1 < $page ) {
				$query_args['paged'] = $page;
			}
		}

		// Always query only IDs
		$query_args['fields'] = 'ids';

	}
	
	return $query_args;
}

function pagelayer_export_content($content){
		
	// Just call do_shortcode so we can get list of media files to export
	//do_shortcode($content);
	
	$theme_url = preg_replace('/http(s?):\/\//is', '', get_stylesheet_directory_uri());
	
	$content = preg_replace('/http(s?):\/\/'.preg_quote($theme_url, '/').'/is', '{{theme_url}}', $content);
	
	// Apply a filter
	$content = apply_filters('pagelayer_export_content', $content);
	
	return $content;
	
}

// Insert a post which is a Pagelayer Post
function pagelayer_insert_content($post, &$ret){
	
	// Replace Vars
	$template_vars = pagelayer_template_vars();
	
	foreach($template_vars as $key => $val){
		$post['post_content'] = str_replace($key, $val, $post['post_content']);
	}
	
	//pagelayer_print($post);die();
	
	// Add slashes for safe insert
	$post['post_content'] = wp_slash($post['post_content']);
	
	// Now insert / update the post
	$ret = wp_insert_post($post);

	// Did we save the post ?
	if(empty($ret) || is_wp_error($ret)){
		return false;
	}

	// Convert to pagelayer accessed post
	if(!add_post_meta($ret, 'pagelayer-data', time(), true)){
		update_post_meta($ret, 'pagelayer-data', time());
	}
	
	return $ret;

}

// Gets the list of enabled fonts
function pagelayer_enabled_icons(){
		
	$stored_icons = get_option('pagelayer_icons_set');
	if(empty($stored_icons)){
		update_option('pagelayer_icons_set', ['font-awesome5']);
		$stored_icons = get_option('pagelayer_icons_set');
	}
	
	return $stored_icons;
	
}

// Install the Pro version
function pagelayer_install_pro(){
	
	global $pagelayer;
	
	// Include the necessary stuff
	include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

	// Includes necessary for Plugin_Upgrader and Plugin_Installer_Skin
	include_once( ABSPATH . 'wp-admin/includes/file.php' );
	include_once( ABSPATH . 'wp-admin/includes/misc.php' );
	include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

	// Filter to prevent the activate text
	add_filter('install_plugin_complete_actions', 'pagelayer_install_plugin_complete_actions', 10, 3);

	$upgrader = new Plugin_Upgrader( new Plugin_Installer_Skin(  ) );
	$installed = $upgrader->install(PAGELAYER_API.'download.php?version=latest&license='.$pagelayer->license['license']);
	
	if ( !is_wp_error( $installed ) && $installed ) {
		echo 'Activating Pagelayer Pro !';
		$activate = activate_plugin(PAGELAYER_PRO_BASE);
		
		if ( is_null($activate) ) {
			echo '<div id="message" class="updated"><p>'. __('Done! Pagelayer Pro is now installed and activated.', 'pagelayer'). '</p></div><br />';
			echo '<br><br><b>Done! Pagelayer Pro is now installed and activated.</b>';
		}
	}
	
	return $installed;
	
}

// Prevent pro activate text for installer
function pagelayer_install_plugin_complete_actions($install_actions, $api, $plugin_file){
	
	if($plugin_file == PAGELAYER_PRO_BASE){
		return array();
	}
	
	return $install_actions;
}

// Load license data
function pagelayer_load_license(){
	
	global $pagelayer;
	
	// Load license
	$pagelayer->license = get_option('pagelayer_license');
	
	// Update license details as well
	if(!empty($pagelayer->license) && (time() - @$pagelayer->license['last_update']) >= 86400){
		
		$resp = wp_remote_get(PAGELAYER_API.'license.php?license='.$pagelayer->license['license']);
		
		// Did we get a response ?
		if(is_array($resp)){
			
			$tosave = json_decode($resp['body'], true);
			
			// Is it the license ?
			if(!empty($tosave['license'])){
				$tosave['last_update'] = time();
				update_option('pagelayer_license', $tosave);
			}
			
		}
		
	}
	
}

// Handle hexa to rgba and also remove alpha which is ff
function pagelayer_hex8_to_rgba($val){
	
	// If opacity is ff then discard ff
	if(preg_match('/^#([a-f0-9]{6})ff$/is', $val)){
		return substr($val, 0, 7);
	}
	
	// Lets handle the RGB+opacity
	if(preg_match('/#([a-f0-9]{8})$/is', $val)){
		$val = pagelayer_hex2rgba($val);
	}
	
	return $val;
	
}

// Convert to RGBA from HEX
function pagelayer_hex2rgba($color){
 
	//Return if no color provided
	if(empty($color)){
		return;
	}
	
	//Sanitize $color if "#" is provided 
	if ($color[0] == '#'){
		$color = substr( $color, 1 );
	}
	
	//Check if color has 6 or 3 characters and get values
	if (strlen($color) == 8) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		$alpha = $color[6] . $color[7];
		//$alpha = '';
	} elseif (strlen($color) == 6) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) == 3 ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return;
	}
 
	//Convert hexadec to rgb
	$rgb =  array_map('hexdec', $hex);

	//Check if opacity is set(rgba or rgb)
	if($alpha){
		
		$alpha = number_format((float)hexdec($alpha) / 255, 2, '.', '');
		//print_r($alpha);
		if(abs($alpha) > 1){
			$alpha = 1.0;
		}
		$output = 'rgba('.implode(",",$rgb).','.$alpha.')';
	} else {
		$output = 'rgb('.implode(",",$rgb).')';
	}

	//Return rgb(a) color string
	return $output;
}

// Get social URLs
function pagelayer_get_social_urls(){
	
	$urls = array();
	
	$urls['facebook'] = get_option('pagelayer-facebook-url');
	$urls['twitter'] = get_option('pagelayer-twitter-url');
	$urls['instagram'] = get_option('pagelayer-instagram-url');
	$urls['linkedin'] = get_option('pagelayer-linkedin-url');
	$urls['youtube'] = get_option('pagelayer-youtube-url');
	$urls['google'] = get_option('pagelayer-gplus-url');
	
	foreach($urls as $k => $v){
		if(empty($v)) unset($urls[$k]);
	}
	
	return $urls;
}

function pagelayer_get_option($opt){
	$ret = get_option($opt);
	
	$opts = array('pagelayer-address' => '1, My Address, My Street, New York City, NY, USA',
				'pagelayer-phone' => '+1234567890',
				'pagelayer-copyright' => '© '.date('Y').' '.get_option('blogname'),
				'pagelayer_cf_to_email' => 'contact@domain.com');
	
	if(empty($ret)){
		return $opts[$opt];
	}
	
	return $ret;
}

// Uploads an image / media
function pagelayer_upload_media($filename, $blob){
	
	$md5 = md5($blob);
	
	// Do we have this image
	$args = array(
		'post_type' => 'attachment',
		'post_status' => 'inherit',
		'meta_query' => array(
			array(
				'key'     => 'pagelayer_image_md5',
				'value'   => $md5,
			)
		)
	);
			
	$query = new WP_Query($args);
	
	// If we found the image, return
	foreach($query->posts as $ck => $cv){
		return $cv->ID;
	}
	
	$upload = wp_upload_bits($filename, null, $blob);

	if( !empty( $upload['error'] ) ) {
		return false;
	}

	$file_path = $upload['file'];
	$file_name = basename( $file_path );
	$file_type = wp_check_filetype( $file_name, null );
	$attachment_title = sanitize_file_name( pathinfo( $file_name, PATHINFO_FILENAME ) );
	$wp_upload_dir = wp_upload_dir();

	$post_info = array(
		'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
		'post_mime_type' => $file_type['type'],
		'post_title'     => $attachment_title,
		'post_content'   => '',
		'post_status'    => 'inherit',
	);

	$attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );
	update_post_meta($attach_id, 'pagelayer_image_md5', $md5);	
	
	$lib = ABSPATH . 'site-admin/includes/image.php';
	$lib = file_exists($lib) ? $lib : ABSPATH . 'wp-admin/includes/image.php';
	
	require_once($lib);
	
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );
	wp_update_attachment_metadata( $attach_id,  $attach_data );
	
	return $attach_id;
	
}