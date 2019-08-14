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
	<link rel="stylesheet" href="'.PAGELAYER_CSS.'/givecss.php?give=pagelayer-editor.css,trumbowyg.min.css&ver='.PAGELAYER_VERSION.'">
	<link rel="stylesheet" href="'.PAGELAYER_CSS.'/font-awesome.min.css?ver='.PAGELAYER_VERSION.'">
</head>

<body class="pagelayer-normalize pagelayer-body">
<div id="trumbowyg-icons">
	'.file_get_contents(PAGELAYER_DIR.'/fonts/trumbowyg.svg').'
</div>
<table class="pagelayer-normalize" cellpadding="0" cellspacing="0">
<tr>
	<td valign="top" width="270" class="pagelayer-leftbar-table">
		<table class="pagelayer-normalize" cellpadding="0" cellspacing="0">
			<tr height="45">
				<td class="pagelayer-topbar-holder" valign="middle" align="center">
					<div class="pagelayer-elpd-header" style="display:none">
						<div class="pagelayer-elpd-close"><i class="fa fa-times" aria-hidden="true"></i></div>
						<div class="pagelayer-elpd-title">Edit</div>
					</div>
					<div class="pagelayer-logo">
						<img src="'.PAGELAYER_URL.'/images/pagelayer-logo-40.png" width="32" /><span class="pagelayer-logo-text">pagelayer</span>
						<span class="pagelayer-settings-icon fa fa-cog" aria-hidden="true"></span>
					</div>
				</td>
			</tr>
			<tr height="*" valign="top">
				<td style="position: relative;"><div class="pagelayer-leftbar-holder"></div></td>
			</tr>
			<tr height="35">
				<td class="pagelayer-bottombar-holder"></td>
			</tr>
		</table>
	</td>
	<td valign="middle" class="pagelayer-leftbar-toggle-h">
		<div class="pagelayer-leftbar-toggle">&lsaquo;</div>
	</td>
	<td class="pagelayer-iframe">
		<div class="pagelayer-iframe-holder">
			<iframe src="'.(pagelayer_shortlink(0).'&pagelayer-iframe=1&'.$_SERVER['QUERY_STRING']).'" class="pagelayer-normalize"></iframe>
		</div>
	</td>
</tr>
</table>

</body>';

die();

}