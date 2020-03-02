<?php

//////////////////////////////////////////////////////////////
//===========================================================
// givejs.php
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


// Read the file
$data = '';
$self_path = dirname(__FILE__);
$files = array(
		// Admin JS
		'pagelayer-editor.js',
		'widgets.js',
		'premium.js',
		'properties.js',
		'base-64.js',
		'slimscroll.js',
		'vanilla-picker.min.js',
		'trumbowyg.min.js',
		'trumbowyg.js',
		'trumbowyg.fontfamily.js',
		'trumbowyg.fontsize.min.js',
		'trumbowyg-pagelayer.js',
		'pen.js',
		'tlite.min.js',
		// Enduser JS
		'imagesloaded.min.js',
		'nivo-lightbox.min.js',
		'owl.carousel.min.js',
		'pagelayer-frontend.js',
		'premium-frontend.js',
		'wow.min.js',
		'jquery-numerator.js',
		'simpleParallax.min.js',
		'chart.min.js',
		'shuffle.min.js'
	);

// What files to give		
$give = @$_REQUEST['give'];

if(!empty($give)){
	
	$give = explode(',', $give);
	
	// Check all files are in the supported list
	foreach($give as $file){
		if(in_array($file, $files)){
			$final[md5($file)] = $file;
		}
	}
	
}


// Give all
if(empty($final)){
	$final = $files;
}

foreach($final as $k => $v){
	//echo $k.'<br>';
	$data .= file_get_contents($self_path.'/'.$v)."\n\n";
}

// We are zipping if possible
if(function_exists('ob_gzhandler')){
	ob_start('ob_gzhandler');
}
		
// Type javascript
header("Content-type: text/javascript; charset: UTF-8");

// Set a zero Mtime
$filetime = filemtime($self_path.'/pagelayer-editor.js');

// Are we to also serve Shortcodes ?
if(!empty($pagelayer->shortcodes)){
	$data .= 'pagelayer_shortcodes = '.json_encode($pagelayer->shortcodes).';'."\n\n";
	$data .= 'pagelayer_styles = '.json_encode($pagelayer->styles).';'."\n\n";
	$data .= 'pagelayer_groups = '.json_encode($pagelayer->groups).';'."\n\n";
}

// Add the langs as well
preg_match_all('/pagelayer_l\([\'"](\w*)[\'"]\)/is', $data, $matches);
if(!empty($matches[1])){
	foreach($matches[1] as $lk => $lv){
		$export_langs[$lv] = __pl($lv);
	}
}

// And lang string ?
if(!empty($export_langs)){
	$data .= 'pagelayer_lang = '.json_encode($export_langs).';'."\n\n";
}

// Cache Control
header("Cache-Control: must-revalidate");

// Checking if the client is validating his cache and if it is current.
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $filetime)) {
	
	// Client's cache IS current, so we just respond '304 Not Modified'.
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $filetime).' GMT', true, 304);
	
	return;
	
}else{
	
	// Image not cached or cache outdated, we respond '200 OK' and output the image.
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $filetime).' GMT', true, 200);
	
}

echo $data;


