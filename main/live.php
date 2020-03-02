<?php

//////////////////////////////////////////////////////////////
//===========================================================
// live.php
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


class PageLayer_LiveEditor{

	// The constructor
	function __construct() {

		global $pagelayer;

		$GLOBALS['concatenate_scripts'] = true;

		// Convert the post to a PageLayer Post first
		$this->check_post();

		// Add the shortcodes
		// TODO : Make a json file
		add_action('wp_head', array($this, 'wp_head'), 999);

		// Add the Body Class Filter
		//add_filter('body_class', array($this, 'body_class'));

		// Add the content handler
		add_filter('the_content', array($this, 'the_content'));
		
		// Build the Shortcodes MD5 for cache
		$scmd5 = md5(json_encode($pagelayer->shortcodes).json_encode($pagelayer->groups).json_encode($pagelayer->styles));
		
		// Enqueue our Editor's JS
		wp_register_script('pagelayer-editor', admin_url( 'admin-ajax.php?action=pagelayer_givejs' ).'&give=pagelayer-editor.js,widgets.js,'.(defined('PAGELAYER_PREMIUM') ? 'premium.js,' : '').'properties.js,base-64.js,slimscroll.js,vanilla-picker.min.js,trumbowyg.js,trumbowyg.fontfamily.js,trumbowyg-pagelayer.js,pen.js,tlite.min.js&pagelayer_nonce=1&scmd5='.$scmd5, array('jquery'), PAGELAYER_VERSION);
		wp_enqueue_script('pagelayer-editor');

		// Enqueue the Editor's CSS
		wp_register_style('pagelayer-editor', PAGELAYER_CSS.'/givecss.php?give=pagelayer-editor-frontend.css,pen.css'.(defined('PAGELAYER_PREMIUM') ? ',owl.theme.default.min.css,owl.carousel.min.css' : ''), array(), PAGELAYER_VERSION);
		wp_enqueue_style('pagelayer-editor');

		// Enqueue the DateTime picker CSS
		/* wp_register_style('datetime-picker', PAGELAYER_CSS.'/datetime-picker.css', array(), PAGELAYER_VERSION);
		wp_enqueue_style('datetime-picker'); */

		// Enqueue the media library
		if(!did_action('wp_enqueue_media')){
			wp_enqueue_media();
		}

		// Force the Frontend CSS and JS if not already loaded
		pagelayer_enqueue_frontend(true);

		// Hide Admin Bar
		show_admin_bar(false);
		remove_action('wp_head', '_admin_bar_bump_cb');
		
		// Load custom widgets
		do_action('pagelayer_custom_editor_enqueue');

		// Add the footer scripts
		add_action('wp_footer', array($this, 'wp_footer'), 1);

	}

	// Add our body class
	function body_class($classes){
		return array_merge($classes, array('pagelayer-body'));
	}

	// Header function to add certain things
	function wp_head(){

		global $pagelayer, $post, $wp_query;
		
		// Export the post props
		$_post = clone $post;
		unset($_post->post_content);
		
		// Add template type
		if(!empty($pagelayer->template_editor)){
			$_post->pagelayer_template_type = get_post_meta($_post->ID, 'pagelayer_template_type', true); 	
		}
		
		$returnURL = (!is_page($post->ID) ? admin_url('edit.php') : admin_url('edit.php?post_type=page') );
		
		echo '
<script type="text/javascript">
pagelayer_ver = "'.PAGELAYER_VERSION.'";
pagelayer_pro = '.(int)defined('PAGELAYER_PREMIUM').';
pagelayer_pro_url = "'.PAGELAYER_PRO_URL.'";
pagelayer_api_url = "'.PAGELAYER_API.'";
pagelayer_ajax_url = "'.admin_url( 'admin-ajax.php' ).'?&";
pagelayer_ajax_nonce = "'.wp_create_nonce('pagelayer_ajax').'";
pagelayer_media_ajax_nonce = "'.wp_create_nonce('media-form').'";
pagelayer_preview_nonce = "'. wp_create_nonce( 'post_preview_' . $post->ID ).'";
pagelayer_url = "'.PAGELAYER_URL.'";
pagelayer_postID = "'.$post->ID.'";
pagelayer_post_permalink = "'.get_permalink($post->ID).'";
pagelayer_tabs = '.json_encode($pagelayer->tabs).';
pagelayer_isDirty = false;
pagelayer_returnURL = "'.$returnURL.'";
pagelayer_theme_vars = '.json_encode( pagelayer_template_vars() ).';
pagelayer_revision_obj = '.json_encode( pagelayer_get_post_revision_by_id( $post->ID ) ).';
pagelayer_author = '.json_encode(pagelayer_author_data($post->ID)).';
pagelayer_site_logo = '.json_encode(pagelayer_site_logo()).';
pagelayer_support_FI = "'. ( current_theme_supports('post-thumbnails') )  .'";	
pagelayer_editable = ".'.(!empty($pagelayer->template_editor) ? $pagelayer->template_editor : 'entry-content').'";
pagelayer_wp_query = '. json_encode($wp_query->query_vars) .';
pagelayer_post =  '. @json_encode($_post) .';
pagelayer_loaded_icons =  '.json_encode(pagelayer_enabled_icons()).';
pagelayer_social_urls =  '.json_encode(pagelayer_get_social_urls()).';
pagelayer_shortcodes.pl_post_props.params.post_title.default = "'.pagelayer_escapeHTML($post->post_title).'";
pagelayer_shortcodes.pl_post_props.params.post_name.default = "'.pagelayer_escapeHTML($post->post_name).'";
pagelayer_shortcodes.pl_post_props.params.post_status.default = "'.$_post->post_status.'";
</script>';

		
		do_action('pagelayer_editor_wp_head');

	}

	// Footer function to add certain things
	function wp_footer(){
		wp_enqueue_script('heartbeat');
		_wp_footer_scripts();
	}

	// Convert to Pagelayer post
	function check_post(){

		global $post;

		// Is this a Pagelayer post
		$data = get_post_meta($post->ID, 'pagelayer-data', true);

		if(empty($data)){

			// Is it a Gutenburg Post ?
			if(false){

			// Regular post
			}else{

				// Add our surrounding tag
				$post->post_content = '['.PAGELAYER_SC_PREFIX.'_row]
['.PAGELAYER_SC_PREFIX.'_col col=12]
['.PAGELAYER_SC_PREFIX.'_text]
'.$post->post_content.'
[/'.PAGELAYER_SC_PREFIX.'_text]
[/'.PAGELAYER_SC_PREFIX.'_col]
[/'.PAGELAYER_SC_PREFIX.'_row]';

				// Update the post
				$new_post = array(
							'ID' => $post->ID,
							'post_content' => $post->post_content,
						);

				// Update the post into the database
				wp_update_post($new_post);

			}

			// Convert to pagelayer accessed post
			if(!add_post_meta($post->ID, 'pagelayer-data', time(), true)){
				update_post_meta($post->ID, 'pagelayer-data', time());
			}
		}

	}

	// Add certain things
	function the_content($content) {

		global $post;

		// Check if we're inside the main loop in a single post page.
		if ( is_single() && in_the_loop() && is_main_query() ) {
			return $content;
		}
	 
		return $content;

	}

}
