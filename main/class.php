<?php

//////////////////////////////////////////////////////////////
//===========================================================
// class.php
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

// PageLayer Class
class PageLayer{

	// All Settings
	var $settings = array();

	// Cache
	var $cache = array();

	// Common Styles Params
	var $styles = array();

	// All Shortcodes
	var $shortcodes = array();

	// All Shortcodes Groups
	var $groups = array();

	// Builder definition
	var $builder = array();

	// The Lang Strings
	var $l = array();
	
	// Runtime fonts
	var $runtime_fonts = array();

	// Array of all the template paths
	var $all_template_paths = array();

	// Tabs visible in the left panel
	var $tabs = ['settings', 'options'];

	// Icons set
	var $icons = ['font-awesome5'];
	
	// For exporting templates
	var $media_to_export = array();

	function __construct() {

		// Load the langs
		$this->l = @file_get_contents(PAGELAYER_DIR.'/languages/en.json');
		$this->l = @json_decode($this->l, true);

	}

}