<?php

//////////////////////////////////////////////////////////////
//===========================================================
// tampalte.php
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

// Pagelayer Template Loading Mechanism
add_action('setup_theme', 'pagelayer_template_setup_theme', 5);
function pagelayer_template_setup_theme(){
	
	global $pagelayer;
	
	//$theme = wp_get_theme();	
	//$theme_tags = $theme->get('Tags');
	//print_r($theme);
	//echo $theme->get('Tags').' Get option';
	
	$theme_dir = get_stylesheet_directory();
	$conf = $theme_dir.'/pagelayer.conf';
	//echo get_template_directory();
	
	// Pagelayer based template ?
	if(file_exists($conf)){
		
		$pagelayer->cache['template'] = 1;
		$pagelayer->template_conf = @json_decode(file_get_contents($conf), true);
		
	// Not a pagelayer theme
	}else{
		return;
	}
	
	// ORDER of preference of every template
	// 1) POST ID as per conditions - Only Premium
	// 2) TPL file if there - Free and Premium when pagelayer.conf
	// 3) PHP file if no Posts - Free and Premium
	
	// Filter to finally INCLUDE and render our template
	add_filter('template_include', 'pagelayer_template_include', 1000, 1);
	
}

// Handle the template files if any
// NOTE : This has a priority of 100 while the posts based pagelayer_builder_template_redirect has a priority of 10
// If there are any post based templates, then that is given priority
add_action( 'template_redirect', 'pagelayer_template_redirect', 100);
function pagelayer_template_redirect(){
	
	global $pagelayer, $post;
	
	// If no conf, then we dont have to do anything
	if(empty($pagelayer->template_conf)){
		return;
	}
	
	// If post template was not there, search for a header PGL file
	// Also when we are editing, we can render header only when its a pagelayer-content edit
	if(	
		(empty($pagelayer->template_editor) || @$pagelayer->template_editor == 'pagelayer-content')
		 && empty($pagelayer->template_header)
	){
		$pagelayer->template_header = pagelayer_template_try_to_apply('header');
	}
	
	// If post template was not there, search for a header PGL file
	// Also when we are editing, we cannot render the template file as post is being rendered
	if(empty($pagelayer->template_editor) && empty($pagelayer->template_post)){
	
		// Singular style posts
		if ( is_singular() || is_404() ) {
			$pagelayer->template_post = pagelayer_template_try_to_apply('single');
		
		// Archive style posts
		} elseif ( is_archive() || is_home() || is_search() ) {
			$pagelayer->template_post = pagelayer_template_try_to_apply('archive');
		}
	
	}
	
	// If post template was not there, search for a footer PGL file
	// Also when we are editing, we can render footer only when its a pagelayer-content edit
	if(	
		(empty($pagelayer->template_editor) || @$pagelayer->template_editor == 'pagelayer-content')
		 && empty($pagelayer->template_footer)
	){
		$pagelayer->template_footer = pagelayer_template_try_to_apply('footer');
	}
	
}

// Is our template being rendered
function pagelayer_template_include($template){
	
	global $pagelayer;
	
	$pagelayer_enqueue_frontend = false;
	
	// If we do have a header but not the footer or we have the footer and no header,
	// then we need to make sure to blank the other
	if(!empty($pagelayer->template_header) || !empty($pagelayer->template_footer)){
		$pagelayer_enqueue_frontend = true;
		add_action('get_header', 'pagelayer_get_header');
		add_action('get_footer', 'pagelayer_get_footer');
	}
	
	// If we do have Popup templates, then append it in body
	if(!empty($pagelayer->template_popup_ids) && empty($pagelayer->template_editor)){
		$pagelayer_enqueue_frontend = true;
		add_action('wp_footer', 'pagelayer_builder_popup_append');
	}
	
	// If the post being shown to the user is not a Pagelayer post, then we need to enqueue forcefully
	if(empty($pagelayer->cache['enqueue_frontend']) && $pagelayer_enqueue_frontend){
		pagelayer_enqueue_frontend(true);
	}
	
	// Is there any post templates OR are we editing a pagelayer-template ?
	if(!empty($pagelayer->template_post) || !empty($pagelayer->template_editor)){
		$template = $pagelayer->template_post;
	}
	
	// Its our template OR are we editing a pagelayer-template, then render it
	if(pathinfo($template, PATHINFO_EXTENSION) == 'pgl' || !empty($pagelayer->template_post) || !empty($pagelayer->template_editor)){
	
		get_header();
		echo '<div class="pagelayer-content">';
		pagelayer_template_render($template);
		echo '</div>';
		get_footer();
		
		return false;
	}
	
	// Just return the original template if its not our file
	return $template;
	
}

// Expects the file to include or the POST ID
function pagelayer_template_render($template){
	
	global $pagelayer;
	
	// $template can be blank, e.g. blank header / footer
	if(empty($template)){
		return;
	}
	
	if(is_numeric($template)){
		echo pagelayer_get_post_content($template);
	}else{
		echo do_shortcode(file_get_contents(get_stylesheet_directory().'/'.$template.'.pgl'));
	}
	
}

// For check which template will be applied
function pagelayer_template_try_to_apply($type){
	
	global $pagelayer;
	
	// Get templates id by type
	$ids = [];
	
	// Find the templates by type
	foreach($pagelayer->template_conf as $k => $v){
		if($v['type'] == $type){
			$ids[] = $k;
		}
	}
	
	$file = pagelayer_template_check_conditons($ids, true);
	
	if( !empty($ids) && !empty($file) ){
		return $file;
	}
	
	return false;
	
}

// Check conditions of template post ids / template files
function pagelayer_template_check_conditons($ids = [], $file = false, $return_all = false){
	
	global $pagelayer;
	
	$selected_templs = [];
	
	foreach( $ids as $id ){
		
		$priority  = 0;	
		$selected_template = 0;
		$exclude_check = 1;
		
		// File based
		if($file){
			$pagelayer_template_conditions = $pagelayer->template_conf[$id]['conditions'];
		
		// Post Template based
		}else{
			$pagelayer_template_conditions = get_post_meta( $id, 'pagelayer_template_conditions', true );
		}
		
		if( !empty($pagelayer_template_conditions) ){
			foreach( $pagelayer_template_conditions as $condi ){
				
				$check = 0;
				
				// Get template array
				$tmpl_array = pagelayer_multi_array_search( $pagelayer->builder['dispay_on'], $condi['template'] );
				
				// Get sub_template array
				$sub_tmpl_array =  pagelayer_multi_array_search( $pagelayer->builder[$condi['template'].'_templates'], $condi['sub_template']);
				
				// If the condition name is general priority
				if(empty($condi['template'])){
					
					$check = 1;
					$set_prio = 1;  // Set General Property 1
					
				// If the condition name is singular
				}elseif( array_key_exists('check_conditions', $tmpl_array) ){
					
					// If the condition callback is false, continue the loop
					if( is_callable($tmpl_array['check_conditions']) ){
						if( empty($tmpl_array['check_conditions']($condi)) ){
							continue;
						}
					}elseif( empty($tmpl_array['check_conditions']) ){
						continue;
					}
					
					// Check sub_template conditions
					if( empty($condi['sub_template']) ){
						$check = 1;
						$set_prio = 2;  // Set all sub_template Property 2
					}elseif( array_key_exists('check_conditions', $sub_tmpl_array ) ){
						
						// If the condition callback is false, continue the loop 
						if( is_callable($sub_tmpl_array['check_conditions']) ){
							if( empty($sub_tmpl_array['check_conditions']($condi)) ){
								continue;
							}
						}elseif( empty($sub_tmpl_array['check_conditions']) ){
							continue;
						}
						
						$check = 1;
						
						if( !empty($condi['id']) ){
							$set_prio = 4; // Set id Property 4
						}else{
							$set_prio = 3;// Set sub_template Property 3
							// If no id section then Property 
							if($sub_tmpl_array['no_id_section']){ $set_prio = 4; } 
						}
					}
				}
				
				// IF is set to the exclude then
				if($condi['type'] == 'exclude' && $check){
					$exclude_check = 0;
				}
				
				if($check){
					// If the template is valid for apply 
					$selected_template = $check;
					
					// Set priority
					if($priority < $set_prio){ $priority = $set_prio; }
				}
			}
		}
		
		// Set priority to template id
		if( $selected_template && $exclude_check ){
			$selected_templs[$id] = $priority;
		}
	}
	
	// Return all ids with priority
	if($return_all){
		return $selected_templs;
	}
	
	$gprior = 0; 
	$sel_id = '';
	foreach( $selected_templs as $id => $prior ){
		if($gprior <= $prior){
			$gprior = $prior;
			$sel_id = $id;
		}
	}
	
	return $sel_id;
}

// The header to substitute
function pagelayer_get_header($name) {
	
	global $pagelayer;
	
	// Output default header always if we have a header or footer
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
	<?php
	
	// Output our content
	if(!empty($pagelayer->template_header)){
		
		echo '
	<header class="pagelayer-header">';
		
		// Render the content
		pagelayer_template_render($pagelayer->template_header);
		
		echo '
	</header>';
		
	}
		
	// Avoid running wp_head hooks again
	remove_all_actions('wp_head');

	$templates = [];
	$name = (string) $name;
	if ($name !== '') {
		$templates[] = 'header-'.$name.'.php';
	}

	$templates[] = 'header.php';
	
	// Since, we already outputted our header, we need to do a locate_template for the existing theme
	// This is because, locate_template has the 3rd param as require once, so in the get_header 
	// the header.php will not load again
	ob_start();
	locate_template( $templates, true );
	ob_get_clean();
	
}

// The footer to load
function pagelayer_get_footer($name) {
	
	global $pagelayer;
	
	// Output our content
	if(!empty($pagelayer->template_footer)){
		
		echo '
	<footer class="pagelayer-footer">';
	
		pagelayer_template_render($pagelayer->template_footer);
		
		echo '
	</footer>';
	
	}
	
	// Output default footer always if we have a header or footer		
	wp_footer();
	echo '</body>
	</html>';
	
	// Avoid running wp_footer hooks again
	remove_all_actions( 'wp_footer' );
	

	$templates = [];
	$name = (string) $name;
	if ($name !== '') {
		$templates[] = 'footer-'.$name.'.php';
	}

	$templates[] = 'footer.php';
	
	// Since, we already outputted our footer, we need to do a locate_template for the existing theme
	// This is because, locate_template has the 3rd param as require once, so in the get_header 
	// the footer.php will not load again
	ob_start();
	locate_template( $templates, true );
	ob_get_clean();
	
}

// Any sidebar to load ?
function pagelayer_get_sidebar($name) {
	
	global $pagelayer;
	
	// Output our content
	if(!empty($pagelayer->template_sidebar)){
		pagelayer_template_render($pagelayer->template_sidebar);
	}

	$templates = [];
	$name = (string) $name;
	if ($name !== '') {
		$templates[] = 'sidebar-'.$name.'.php';
	}

	$templates[] = 'sidebar.php';
	
	// Since, we already outputted our sidebar, we need to do a locate_template for the existing theme
	// This is because, locate_template has the 3rd param as require once, so in the get_header 
	// the sidebar.php will not load again
	ob_start();
	locate_template( $templates, true );
	ob_get_clean();
	
}

// Get the custom post content by id
function pagelayer_get_post_content($id){
	global $pagelayer;
	
	// Get the content
	$post = get_post($id);
	
	$content = $post->post_content;
	pagelayer_load_shortcodes();
	//$content = do_shortcode( $content );
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	
	return $content;		

}

// Vars that can be used in template files
function pagelayer_template_vars(){
	
	$replacers['{{theme_url}}'] = get_stylesheet_directory_uri();
	$replacers['{{theme_images}}'] = get_stylesheet_directory_uri().'/images/';
	$replacers['{{themes_dir}}'] = dirname(get_stylesheet_directory_uri());
	$replacers['{{pl_site_url}}'] = home_url();
	
	return $replacers;
	
}