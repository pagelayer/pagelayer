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


function pagelayer_live_body(){

global $post;

echo '
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet"> 
	<link rel="stylesheet" href="'.PAGELAYER_CSS.'/givecss.php?give=pagelayer-editor.css,trumbowyg.min.css,pagelayer-icons.css&ver='.PAGELAYER_VERSION.'">';
	
	$icons = pagelayer_enabled_icons();
	
	// Load all icons
	foreach($icons as $icon){
		echo '<link rel="stylesheet" href="'.PAGELAYER_CSS.'/givecss.php?give='.$icon.'.min.css&ver='.PAGELAYER_VERSION.'">';
	}
	
	do_action('pagelayer_live_body_head');

echo '
</head>

<body class="pagelayer-normalize pagelayer-body">
<table class="pagelayer-normalize pagelayer-body-table" cellpadding="0" cellspacing="0">
<tr>
	<td valign="top" width="270" class="pagelayer-leftbar-table">
		<table class="pagelayer-normalize" cellpadding="0" cellspacing="0">
			<tr class="pagelayer-close-bar">
				<td>
					<div class="pagelayer-close-bar-icons">
						<i class="pagelayer-leftbar-minimize fa fa-minus"></i>
						<i class="pagelayer-leftbar-close fa fa-close"></i>
					</div>
				</td>
			</tr>
			<tr height="45">
				<td class="pagelayer-topbar-holder" valign="middle" align="center">
					<div class="pagelayer-elpd-header" style="display:none">
						<div class="pagelayer-elpd-close"><i class="pli pli-cross" aria-hidden="true"></i></div>
						<div class="pagelayer-elpd-title pagelayer-topbar-mover">Edit</div>
					</div>
					<div class="pagelayer-logo">
						<span class="pagelayer-options-icon pli pli-menu" style="display:none"></span>
						<img src="'.PAGELAYER_LOGO.'" width="32" /><span class="pagelayer-logo-text pagelayer-topbar-mover">'.PAGELAYER_BRAND_TEXT.'</span>
						<span class="pagelayer-settings-icon pli pli-service" aria-hidden="true"></span>
					</div>
				</td>
			</tr>
			<tr height="*" valign="top">
				<td style="position: relative;"><div class="pagelayer-leftbar-holder"></div></td>
			</tr>
			<tr height="35" class="pagelayer-bottombar-row">
				<td><div class="pagelayer-bottombar-holder"></div></td>
			</tr>
		</table>
		<div class="pagelayer-leftbar-toggle">&lsaquo;</div>
	</td>
	<td class="pagelayer-iframe" valign="top">
		<div class="pagelayer-iframe-top-bar">';
		do_action('pagelayer_iframe_top_bar');
echo '
		</div>
		<div class="pagelayer-iframe-holder">
			<iframe src="'.(pagelayer_shortlink(0).'&pagelayer-iframe=1&'.$_SERVER['QUERY_STRING']).'" class="pagelayer-normalize" id="pagelayer-iframe"></iframe>
		</div>
	</td>
</tr>
</table>

<script>
var pagelayer_iframe_cw = document.getElementById("pagelayer-iframe").contentWindow;
</script>

</body>';

die();

}