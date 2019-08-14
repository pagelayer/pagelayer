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
		'base64.js',
		'slimscroll.js',
		'vanilla-picker.min.js',
		'trumbowyg.min.js',
		'trumbowyg.js',
		'trumbowyg.fontfamily.js',
		'trumbowyg-pagelayer.js',
		'pen.js',
		// Enduser JS
		'imagesloaded.min.js',
		'nivo-lightbox.min.js',
		'owl.carousel.min.js',
		'pagelayer-frontend.js',
		'premium-frontend.js',
		'wow.min.js',
		'jquery-numerator.js',
		'simpleParallax.min.js',
		'chart.min.js'
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


