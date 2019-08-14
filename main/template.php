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
	
	$theme_dir = get_template_directory();
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
	
	// If post template was not there, search for a header PGL file
	// Also when we are editing, we can render footer only when its a pagelayer-content edit
	if(	
		(empty($pagelayer->template_editor) || @$pagelayer->template_editor == 'pagelayer-content')
		 && empty($pagelayer->template_footer)
	){
		$pagelayer->template_footer = pagelayer_template_try_to_apply('footer');
	}
	
	// If we do have a header but not the footer or we have the footer and no header,
	// then we need to make sure to blank the other
	if(!empty($pagelayer->template_header) || !empty($pagelayer->template_footer)){
		add_action('get_header', 'pagelayer_get_header');
		add_action('get_footer', 'pagelayer_get_footer');
	}
	
}

// Is our template being rendered
function pagelayer_template_include($template){
	
	global $pagelayer;
	
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
		echo do_shortcode(file_get_contents(get_template_directory().'/'.$template.'.pgl'));
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
function pagelayer_template_check_conditons($ids = [], $file = false){
	
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
								
				// If the condition name is general priority
				if(empty($condi['template'])){ 
					$check = 1;
					
					// Set General Property 1
					if($priority < 1){ $priority = 1; }				
					
				// If the condition name is singular
				}elseif( $condi['template'] == 'singular' && (  is_singular() || is_404() ) ){
					
					// Check sub_template conditions
					if( empty($condi['sub_template']) ){
						$check = 1;
						
						// Set name Property 2
						if($priority < 2){ $priority = 2; }
						
					}else{
						$sub_check = 'is_'.$condi['sub_template'];
						$id_check = 0;
						
						if( $condi['sub_template'] == 'post' ){
							$sub_check = 'is_single';
						}elseif( is_numeric(strpos($condi['sub_template'] , 'author')) ){
							$exp = explode('_by_', $condi['sub_template']);
							
							if($exp[0] == $condi['sub_template']){
								$sub_check = 'is_singular';
								$id_check = ( $sub_check &&  get_the_author_meta( 'ID' ) == $condi['id'] ) ? 1 : 2;
							}else{
								$sub_check = ($exp[0] == 'post' ? 'is_single' : 'is_'.$exp[0] );
								$id_check = ( $sub_check && get_the_author_meta( 'ID' ) == $condi['id']) ? 1 : 2;
							}
							
						}elseif( ($condi['sub_template'] == 'post_tag') || ($condi['sub_template'] == 'category') ){
							$sub_check = 'is_single';
							$id_check = 2;
							$terms = get_the_terms( '', $condi['sub_template']);
							
							foreach ( $terms as $term ) {
								$id_check = ( $sub_check && ($condi['id'] == $term->term_taxonomy_id ) ) ? 1 : 2;
							}
							
						} 
						
					}
				
				// If the condition name is archives				
				}elseif( $condi['template'] == 'archives' && (  is_archive() || is_home() || is_search() ) ){
					
					if( empty($condi['sub_template']) ){
						$check = 1;
						
						// Set name Property 2
						if($priority < 2){ $priority = 2; }
						
					}else{
						$sub_check = 'is_'.$condi['sub_template'];
						if( is_numeric(strpos($condi['sub_template'] , 'tag')) ){
							$sub_check = 'is_tag';
						}elseif($condi['sub_template'] == 'posts_page'){
							$sub_check = 'is_home';
						}
						
					}
					
				}
				
				if( !empty($sub_check) && function_exists($sub_check) ){
					if(!empty($condi['id'])){
						
						if(!empty($id_check) && $id_check == 1 ){
							$check = 1;
							// Set id Property 4
							if($priority < 4){ $priority = 4; }
							
						}elseif($sub_check($condi['id'] ) && $id_check != 2 ){
							$check = 1;
							// Set id Property 4
							if($priority < 4){ $priority = 4; }
						}
						
					}elseif( $sub_check() ){
						$check = 1;
						// Set sub_template Property 3
						$set_prio = 3;
						$sub_template_prio = ['front_page', '404', 'date', 'search', 'posts_page'];
						
						foreach($sub_template_prio as $sub_prio){
							if( $condi['sub_template'] == $sub_prio ){
								$set_prio = 4;
							}
						}
												
						if($priority < $set_prio){ $priority = $set_prio; }
					}
				}
				
				// IF is set to the exclude then
				if($condi['type'] == 'exclude' && $check){
					$exclude_check = 0;
				}
				
				if($check){
					// If the template is valid for apply 
					$selected_template = $check;
				}
			}
		}
		
		// Set priority to template id
		if( $selected_template && $exclude_check ){
			$selected_templs[$id] = $priority;
		}
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