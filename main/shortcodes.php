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

// NOTE :
// 1) There can be a holder or innterHTML for a tag. NOT BOTH
// 2) Groups can have 'html' but need the holder as well then
// 3) innerHTML defines the param to be saved in the shortcode as inner content

// Example of 'show' and 'req' prop attr
// NOTE : There can be only REQ or show
// First preference given to REQ
/*

// Single value
'show' => ['border_hover' => 'normal'],

// Two value i.e. when both the values are true
'show' => ['border_hover' => 'normal',
	'border_type' => 'solid',
],

// Exception i.e. prefix the param name with ! (exclamation)
'show' => ['!border_hover' => 'normal',
	'border_type' => 'solid',
],

// Support for MULTIPLE values of ONE parameter. 
// NOTE : This will be treated as OR for that parameter i.e. if the current value of that parameter is any ONE of the array given
'show' => ['border_hover' => ['normal', 'hover']],

*/

// Example of 'css' prop attr
/*

// Set the value to the parent element
'css' => 'background-color: {{val}}',

// Multiple vals
'css' => [
	'background-color: {{val}}',
	'-webkit-background-color: {{val}}',
	'-moz-background-color: {{val}}',
],

// Multiple vals with some selectors or direct element val
'css' => [
	'{{element}} .class' => 'background-color: {{val}}',
	'-webkit-background-color: {{val}}',
	'-moz-background-color: {{val}}',
],

*/

// Example of 'addAttr' or 'addClass'
/*

// Set the value to the parent element
'addAttr' => 'target="_blank"',

// Multiple vals
'addAttr' => [
	'target="_blank"',
	'href="/"',
	'rel="{{rel}}"',
],

// Multiple vals with some selectors or direct element val
'addAttr' => [
	'{{element}} .class' => 'target="_blank"',
	'href="/"',
	'rel="{{rel}}"',
],

*/

////////////////////////
// Default Styles
////////////////////////
global $pagelayer;

$pagelayer->anim_in_options = array(
	'' => __pl('none'),
	__pl('fading') => [
		'fadeIn' => __pl('fadein'),
		'fadeInDown' => __pl('fadeindown'),
		'fadeInUp' => __pl('fadeinup'),
		'fadeInLeft' => __pl('fadeinleft'),
		'fadeInRight' => __pl('fadeinright'),
	],
	__pl('zooming') => [
		'zoomIn' => __pl('zoomin'),
		'zoomInDown' => __pl('zoomindown'),
		'zoomInUp' => __pl('zoominup'),
		'zoomInLeft' => __pl('zoominleft'),
		'zoomInRight' => __pl('zoominright'),
	],
	__pl('bounceing') => [
		'bounceIn' => __pl('bouncein'),
		'bounceInDown' => __pl('bounceindown'),
		'bounceInUp' => __pl('bounceinup'),
		'bounceInLeft' => __pl('bounceinleft'),
		'bounceInRight' => __pl('bounceinright'),
	],
	__pl('sliding') => [
		'slideInDown' => __pl('slideindown'),
		'slideInUp' => __pl('slideinup'),
		'slideInLeft' => __pl('slideinleft'),
		'slideInRight' => __pl('slideinright'),
	],
	__pl('rotating') => [
		'rotateIn' => __pl('rotatein'),
		'rotateInDown' => __pl('rotateindown'),
		'rotateInUp' => __pl('rotateinup'),
		'rotateInLeft' => __pl('rotateinleft'),
		'rotateInRight' => __pl('rotateinright'),
	],
	__pl('effects') => [
		'lightSpeedIn' => __pl('lightspeedin'),
		'bounce' => __pl('bounce'),
		'pulse' => __pl('pulse'),
		'rubberBand' => __pl('rubberband'),
		'flash' => __pl('flash'),
		'swing' => __pl('swing'),
		'jello' => __pl('jello'),
		'tada' => __pl('tada'),
		'wobble' => __pl('wobble'),
		'rollin' => __pl('rollin'),
		'headShake' => __pl('headshake'),
		'shake' => __pl('shake'),
	],
);

$pagelayer->anim_out_options = array(
	'' => __pl('none'),
	__pl('fading') => [
		'fadeOut' => __pl('fadeout'),
		'fadeOutDown' => __pl('fadeoutdown'),
		'fadeOutUp' => __pl('fadeoutup'),
		'fadeOutLeft' => __pl('fadeoutleft'),
		'fadeOutRight' => __pl('fadeoutright'),
	],
	__pl('zooming') => [
		'zoomOut' => __pl('zoomout'),
		'zoomOutDown' => __pl('zoomoutdown'),
		'zoomOutUp' => __pl('zoomoutup'),
		'zoomOutLeft' => __pl('zoomoutleft'),
		'zoomOutRight' => __pl('zoomoutright'),
	],
	__pl('bounceing') => [
		'bounceOut' => __pl('bounceout'),
		'bounceOutDown' => __pl('bounceoutdown'),
		'bounceOutUp' => __pl('bounceoutup'),
		'bounceOutLeft' => __pl('bounceoutleft'),
		'bounceOutRight' => __pl('bounceoutright'),
	],
	__pl('sliding') => [
		'slideOutDown' => __pl('slideoutdown'),
		'slideOutUp' => __pl('slideoutup'),
		'slideOutLeft' => __pl('slideoutleft'),
		'slideOutRight' => __pl('slideoutright'),
	],
	__pl('rotating') => [
		'rotateOut' => __pl('rotateout'),
		'rotateOutDown' => __pl('rotateoutdown'),
		'rotateOutUp' => __pl('rotateoutup'),
		'rotateOutLeft' => __pl('rotateoutleft'),
		'rotateOutRight' => __pl('rotateoutright'),
	],
	__pl('effects') => [
		'lightSpeedIn' => __pl('lightspeedin'),
		'bounce' => __pl('bounce'),
		'pulse' => __pl('pulse'),
		'rubberBand' => __pl('rubberband'),
		'flash' => __pl('flash'),
		'swing' => __pl('swing'),
		'jello' => __pl('jello'),
		'tada' => __pl('tada'),
		'wobble' => __pl('wobble'),
		'rollin' => __pl('rollin'),
		'headShake' => __pl('headshake'),
		'shake' => __pl('shake'),
	],
);

$pagelayer->slider_arrow_styles = [
	'arrows_bg' => array(
		'type' => 'color',
		'label' => __pl('bg_color'),
		'default' => '#6a6969',
		'css' => [
			'{{element}} .pagelayer-owl-prev' => 'background-color: {{val}} !important',
			'{{element}} .pagelayer-owl-next' => 'background-color: {{val}} !important',
		]
	),
	'arraow_color' => array(
		'type' => 'color',
		'label' => __pl('color'),
		'default' => '#ffffff',
		'css' => [
			'{{element}} .pagelayer-owl-prev' => 'color: {{val}} !important',
			'{{element}} .pagelayer-owl-next' => 'color: {{val}} !important',
		]
	),
	'nav_size' => array(
		'type' => 'slider',
		'label' => __pl('arraow_size'),
		'min' => 0,
		'step' => 1,
		'max' => 200,
		'screen' => 1,
		'css' => [
			'{{element}} .pagelayer-owl-prev span' => 'font-size: {{val}}px !important;',
			'{{element}} .pagelayer-owl-next span' => 'font-size: {{val}}px !important;'
		]
	),
	'arraow_bg_size' => array(
		'type' => 'spinner',
		'label' => __pl('background_size'),
		'min' => 0,
		'step' => 1,
		'max' => 500,
		'default' => 20,
		'screen' => 1,
		'css' => [
			'{{element}} .pagelayer-owl-prev' => 'width: {{val}}px; height: {{val}}px',
			'{{element}} .pagelayer-owl-next' => 'width: {{val}}px; height: {{val}}px'
		]
	),
	'arraow_bg_shape' => array(
		'type' => 'spinner',
		'label' => __pl('background_shape'),
		'min' => 0,
		'step' => 1,
		'max' => 100,
		'default' => 20,
		'screen' => 1,
		'css' => [
			'{{element}} .pagelayer-owl-prev' => 'border-radius: {{val}}% !important;',
			'{{element}} .pagelayer-owl-next' => 'border-radius: {{val}}% !important;',
		]
	),
];

$pagelayer->slider_pager_styles = [
	'pager_color' => array(
		'type' => 'color',
		'label' => __pl('color'),
		'css' => ['{{element}} .pagelayer-owl-dot span' => 'background-color: {{val}} !important']
	),
	'active_pager_color' => array(
		'type' => 'color',
		'label' => __pl('active_pager_color'),
		'css' => ['{{element}} .pagelayer-owl-dot.active span' => 'background-color: {{val}} !important']
	),
	'dot_size' => array(
		'type' => 'slider',
		'label' => __pl('dot_size'),
		'min' => 0,
		'step' => 1,
		'max' => 200,
		'screen' => 1,
		'css' => [
			'{{element}} .pagelayer-owl-dot span' => 'width: {{val}}px !important; height: {{val}}px !important;'
		]
	),
];

$pagelayer->slider_options = [
	'slide_items' => array(
		'type' => 'spinner',
		'label' => __pl('number_of_items'),
		'min' => 1,
		'step' => 1,
		'max' => 10,
		'default' => 1,
		'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-items="{{slide_items}}"'],
	),
	'slidein_anim' => array(
		'type' => 'select',
		'label' => __pl('animation_in'),
		'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-animate-in="{{slidein_anim}}"'],
		'list' => $pagelayer->anim_in_options,
		'req' => ['slide_items' => '1']
	),
	'slideout_anim' => array(
		'type' => 'select',
		'label' => __pl('animation_out'),
		'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-animate-out="{{slideout_anim}}"'],
		'list' => $pagelayer->anim_out_options,
		'req' => ['slide_items' => '1']
	),
	'slide_margin' => array(
		'type' => 'slider',
		'label' => __pl('space_between'),
		'min' => 0,
		'step' => 1,
		'max' => 100,
		'default' => 10,
		'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-margin="{{slide_margin}}"'],
		'req' => ['!slide_items' => '1']
	),
	'slide_loop' => array(
		'type' => 'checkbox',
		'label' => __pl('loop'),
		'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-loop="{{slide_loop}}"'],
	),
	'slide_controls' => array(
		'type' => 'select',
		'label' => __pl('slider_controls'),
		'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-controls="{{slide_controls}}"'],
		'list' => array(
			'' => __pl('Arrows and Pager'),
			'arrows' => __pl('Arrows'),
			'pager' => __pl('Pager'),
			'none' => __pl('none'),
		)
	),
	'slide_autoplay' => array(
		'type' => 'checkbox',
		'label' => __pl('autoplay'),
		'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-autoplay="{{slide_autoplay}}"'],
	),
	'slide_timeout' => array(
		'type' => 'spinner',
		'label' => __pl('autoplay_timeout'),
		'min' => 1000,
		'step' => 200,
		'max' => 10000,
		'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-autoplay-timeout="{{slide_timeout}}"'],
		'req' => ['slide_autoplay' => 'true']
	),
	'slide_hoverpause' => array(
		'type' => 'checkbox',
		'label' => __pl('autoplay_hover_pause'),
		'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-autoplay-hover-pause="{{slide_hoverpause}}"'],
		'req' => ['slide_autoplay' => 'true']
	),
];

$pagelayer->styles['ele_bg_styles'] = [
	'ele_bg_hover' => [
		'type' => 'radio',
		'label' => __pl('Background'),
		'default' => '',
		//'no_val' => 1,// Dont set any value to element
		'list' => [
			'' => __pl('normal'),
			'hover' => __pl('hover'),
		],
	],
	'ele_bg_type' => [
		'type' => 'radio',
		'label' => __pl('Background Type'),
		'default' => '',
		'list' => [
			'' => __pl('none'),
			'color' => __pl('color'),
			'gradient' => __pl('gradient'),
			'image' => __pl('image'),
		],
		'show' => ['ele_bg_hover' => '']
	],
	'ele_bg_color' => [
		'type' => 'color',
		'label' => __pl('color'),
		'default' => '',
		'css' => 'background: {{val}};',
		'show' => ['ele_bg_hover' => ''],
		'req' => ['ele_bg_type' => 'color']
	],
	'ele_bg_gradient' => [
		'type' => 'gradient',
		'label' => '',
		'default' => '150,#44d3f6ff,23,#72e584ff,45,#2ca4ebff,100',
		'css' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%);',
		'show' => ['ele_bg_hover' => ''],
		'req' => ['ele_bg_type' => 'gradient']
	],
	'ele_img_color' => [
		'type' => 'color',
		'label' => __pl('color'),
		'default' => '',
		'desc' => __pl('fallback background color if image is failed to load.'),
		'css' => 'background: {{val}};',
		'show' => ['ele_bg_hover' => ''],
		'req' => ['ele_bg_type' => 'image']
	],
	'ele_bg_img' => [
		'type' => 'image',
		'label' => __pl('Image'),
		//'default' => '',
		'css' => 'background: url({{{ele_bg_img-url}}});',
		'show' => ['ele_bg_hover' => ''],
		'req' => ['ele_bg_type' => 'image']
	],
	'ele_bg_attachment' => [
		'type' => 'select',
		'label' => __pl('ele_bg_attachment'),
		'list' => [
			'' => __pl('default'),
			'scroll' => __pl('scroll'),
			'fixed' => __pl('fixed')
		],
		'show' => ['ele_bg_hover' => ''],
		'css' => 'background-attachment: {{val}};',
		'req' => ['ele_bg_type' => 'image']
	],
	'ele_bg_posx' => [
		'type' => 'select',
		'label' => __pl('ele_bg_posx'),
		'list' => [
			'' => __pl('default'),
			'center' => __pl('center'),
			'left' => __pl('left'),
			'right' => __pl('right')
		],
		'show' => ['ele_bg_hover' => ''],
		'css' => 'background-position-x: {{val}};',
		'req' => ['ele_bg_type' => 'image']
	],
	'ele_bg_posy' => [
		'type' => 'select',
		'label' => __pl('ele_bg_posy'),
		'list' => [
			'' => __pl('default'),
			'center' => __pl('center'),
			'top' => __pl('top'),
			'bottom' => __pl('bottom')
		],
		'show' => ['ele_bg_hover' => ''],
		'css' => 'background-position-y: {{val}};',
		'req' => ['ele_bg_type' => 'image']
	],
	'ele_bg_repeat' => [
		'type' => 'select',
		'label' => __pl('ele_bg_repeat'),
		'css' => 'background-repeat: {{val}};',
		'list' => [
			'' => __pl('default'),
			'repeat' => __pl('repeat'),
			'no-repeat' => __pl('no-repeat'),
			'repeat-x' => __pl('repeat-x'),
			'repeat-y' => __pl('repeat-y'),
		],
		'show' => ['ele_bg_hover' => ''],
		'req' => ['ele_bg_type' => 'image']
	],
	'ele_bg_size' => [
		'type' => 'select',
		'label' => __pl('ele_bg_size'),
		'css' => 'background-size: {{val}};',
		'list' => [
			'' => __pl('default'),
			'cover' => __pl('cover'),
			'contain' => __pl('contain')
		],
		'show' => ['ele_bg_hover' => ''],
		'req' => ['ele_bg_type' => 'image']
	],
	'ele_bg_hover_delay' => [
		'type' => 'spinner',
		'label' => __pl('ele_bg_hover_delay'),
		'min' => 0,
		'step' => 100,
		'max' => 5000,
		'default' => 400,
		'css' => '-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;',
		'show' => ['ele_bg_hover' => 'hover']
	],
	'ele_bg_type_hover' => [
		'type' => 'radio',
		'label' => __pl('background_type'),
		'default' => '',
		'list' => [
			'' => __pl('none'),
			'color' => __pl('color'),
			'gradient' => __pl('gradient'),
			'image' => __pl('image'),
		],
		'show' => ['ele_bg_hover' => 'hover']
	],
	'ele_bg_color_hover' => [
		'type' => 'color',
		'label' => __pl('color_hover'),
		'default' => '',
		'css' => ['{{element}}:hover' => 'background: {{val}};'],
		'show' => ['ele_bg_hover' => 'hover'],
		'req' => ['ele_bg_type_hover' => 'color']
	],
	'ele_bg_gradient_hover' => [
		'type' => 'gradient',
		'label' => '',
		'default' => '150,#44d3f6ff,25,#72e584ff,75,#2ca4ebff,100',
		'css' => ['{{element}}:hover' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%);'],
		'show' => ['ele_bg_hover' => 'hover'],
		'req' => ['ele_bg_type_hover' => 'gradient']
	],
	'ele_bg_img_hover' => [
		'type' => 'image',
		'label' => __pl('Image Hover'),
		'css' => ['{{element}}:hover' => 'background: url({{{ele_bg_img_hover-url}}});'],
		'show' => ['ele_bg_hover' => 'hover'],
		'req' => ['ele_bg_type_hover' => 'image']
	],
	'ele_bg_attachment_hover' => [
		'type' => 'select',
		'label' => __pl('ele_bg_attachment_hover'),
		'list' => [
			'' => __pl('default'),
			'scroll' => __pl('scroll'),
			'fixed' => __pl('fixed')
		],
		'show' => ['ele_bg_hover' => 'hover'],
		'css' => ['{{element}}:hover' => 'background-attachment: {{val}};'],
		'req' => ['ele_bg_type_hover' => 'image']
	],
	'ele_bg_posx_hover' => [
		'type' => 'select',
		'label' => __pl('ele_bg_posx_hover'),
		'list' => [
			'' => __pl('default'),
			'center' => __pl('center'),
			'left' => __pl('left'),
			'right' => __pl('right')
		],
		'show' => ['ele_bg_hover' => 'hover'],
		'css' => ['{{element}}:hover' => 'background-position-x: {{val}};'],
		'req' => ['ele_bg_type_hover' => 'image']
	],
	'ele_bg_posy_hover' => [
		'type' => 'select',
		'label' => __pl('ele_bg_posy_hover'),
		'list' => [
			'' => __pl('default'),
			'center' => __pl('center'),
			'top' => __pl('top'),
			'bottom' => __pl('bottom')
		],
		'show' => ['ele_bg_hover' => 'hover'],
		'css' => ['{{element}}:hover' => 'background-position-y: {{val}};'],
		'req' => ['ele_bg_type_hover' => 'image']
	],
	'ele_bg_repeat_hover' => [
		'type' => 'select',
		'label' => __pl('ele_bg_repeat_hover'),
		'css' => ['{{element}}:hover' => 'background-repeat: {{val}};'],
		'list' => [
			'' => __pl('default'),
			'repeat' => __pl('repeat'),
			'no-repeat' => __pl('no-repeat'),
			'repeat-x' => __pl('repeat-x'),
			'repeat-y' => __pl('repeat-y'),
		],
		'show' => ['ele_bg_hover' => 'hover'],
		'req' => ['ele_bg_type_hover' => 'image']
	],
	'ele_bg_size_hover' => [
		'type' => 'select',
		'label' => __pl('ele_bg_size_hover'),
		'css' => ['{{element}}:hover' => 'background-size: {{val}};'],
		'list' => [
			'' => __pl('default'),
			'cover' => __pl('cover'),
			'contain' => __pl('contain')
		],
		'show' => ['ele_bg_hover' => 'hover'],
		'req' => ['ele_bg_type_hover' => 'image']
	]
];

$pagelayer->styles['ele_styles'] = [
	'ele_margin' => [
		'type' => 'padding',
		'label' => __pl('margin'),
		'screen' => 1,
		'units' => ['px', 'em', '%'],
		'css' => 'margin-top: {{val[0]}}; margin-right: {{val[1]}}; margin-bottom: {{val[2]}}; margin-left: {{val[3]}}',
	],
	'ele_padding' => [
		'type' => 'padding',
		'label' => __pl('padding'),
		'screen' => 1,
		'units' => ['px', 'em', '%'],
		'css' => 'padding-top: {{val[0]}}; padding-right: {{val[1]}}; padding-bottom: {{val[2]}}; padding-left: {{val[3]}}',
	],
	'ele_zindex' => [
		'type' => 'slider',
		'label' => __pl('z-index'),
		'css' => 'z-index: {{val}}',
	],
	'ele_shadow' => [
		'type' => 'shadow',
		'label' => __pl('shadow'),
		'css' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}} !important;',
	]
];

$pagelayer->styles['border_styles'] = [
	'border_hover' => [
		'type' => 'radio',
		'label' => '',
		'default' => '',
		//'no_val' => 1,// Dont set any value to element
		'list' => [
			'' => __pl('normal'),
			'hover' => __pl('hover'),
		],
	],
	'border_type' => [
		'type' => 'select',
		'label' => __pl('border_type'),
		'list' => [
			'' => __pl('none'),
			'solid' => __pl('solid'),
			'double' => __pl('double'),
			'dotted' => __pl('dotted'),
			'dashed' => __pl('dashed'),
			'groove' => __pl('groove'),
		],
		'show' => ['border_hover' => ''],
		'css' => 'border-style: {{val}}',
	],
	'border_width' => [
		'type' => 'padding',
		'label' => __pl('border_width'),
		'default' => '1,1,1,1',
		'units' => ['px', 'em'],
		'show' => [
			'border_hover' => ''
		],
		'req' => [
			'!border_type' => ''
		],
		'css' => 'border-top-width: {{val[0]}}; border-right-width: {{val[1]}}; border-bottom-width: {{val[2]}}; border-left-width: {{val[3]}}',
	],
	'border_color' => [
		'type' => 'color',
		'label' => __pl('border_color'),
		'default' => '#CCC',
		'show' => [
			'border_hover' => ''
		],
		'req' => [
			'!border_type' => ''
		],
		'css' => 'border-color: {{val}}',
	],
	'border_radius' => [
		'type' => 'padding',
		'label' => __pl('border_radius'),
		'units' => ['px', 'em'],
		'show' => ['border_hover' => ''],
		'css' => 'border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -webkit-border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};-moz-border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};',
	],
	'border_shadow' => [
		'type' => 'shadow',
		'label' => __pl('text_shadow'),
		'css' => ['{{element}}' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}} !important;'],
		'show' => ['border_hover' => ''],
	],
	'border_type_hover' => [
		'type' => 'select',
		'label' => __pl('border_type'),
		'list' => [
			'' => __pl('none'),
			'solid' => __pl('solid'),
			'double' => __pl('double'),
			'dotted' => __pl('dotted'),
			'dashed' => __pl('dashed'),
			'groove' => __pl('groove'),
		],
		'show' => ['border_hover' => 'hover'],
		'css' => ['{{element}}:hover' => 'border-style: {{val}}'],
	],
	'border_width_hover' => [
		'type' => 'padding',
		'label' => __pl('border_width'),
		'units' => ['px', 'em'],
		'show' => [
			'border_hover' => 'hover'
		],
		'req' => [
			'!border_type_hover' => ''
		],
		'css' => ['{{element}}:hover' => 'border-top-width: {{val[0]}}; border-right-width: {{val[1]}}; border-bottom-width: {{val[2]}}; border-left-width: {{val[3]}}'],
	],
	'border_color_hover' => [
		'type' => 'color',
		'label' => __pl('border_color'),
		'show' => [
			'border_hover' => 'hover'
		],
		'req' => [
			'!border_type_hover' => ''
		],
		'css' => ['{{element}}:hover' => 'border-color: {{val}}'],
	],
	'border_radius_hover' => [
		'type' => 'padding',
		'label' => __pl('border_radius'),
		'units' => ['px', 'em'],
		'show' => ['border_hover' => 'hover'],
		'css' => ['{{element}}:hover' => 'border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -webkit-border-radius:  {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};-moz-border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};'],
	],
	'border_shadow_hover' => [
		'type' => 'shadow',
		'label' => __pl('text_shadow'),
		'css' => ['{{element}}:hover' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}} !important;'],
		'show' => ['border_hover' => 'hover'],
	],
];

$pagelayer->styles['animation_styles'] = [
	'animation' => [
		'type' => 'select',
		'label' => __pl('animation'),
		'default' => '',
		'addClass' => ['{{val}}',( !pagelayer_is_live() ? 'pagelayer-wow' : '' )],
		'list' => $pagelayer->anim_in_options
	],
	'animation_speed' => [
		'type' => 'select',
		'label' => __pl('animate_speed'),
		'default' => '',
		'addClass' => 'pagelayer-anim-{{val}}',
		'list' => [
			'' => __pl('normal'),
			'fast' => __pl('fast'),
			'slow' => __pl('slow'),
			'fastest' => __pl('fastest'),
			'slowest' => __pl('slowest'),
		],
		'req' => [
			'!animation' => ''
		]
	],
	'animation_delay' => [
		'type' => 'spinner',
		'label' => __pl('animation_delay'),
		'css' => '-webkit-animation-delay: {{val}}ms; animation-delay: {{val}}ms;',
		'default' => 600,
		'min' => 0,
		'max' => 90000,
		'step' => 100,
		'req' => [
			'!animation' => ''
		]
	],
];

// Resposive stuff
$pagelayer->styles['responsive_styles'] = [
	'hide_desktop' => [
		'type' => 'checkbox',
		'label' => __pl('hide_desktop'),
		'addClass' => 'pagelayer-hide-desktop'
	],
	'hide_tablet' => [
		'type' => 'checkbox',
		'label' => __pl('hide_tablet'),
		'addClass' => 'pagelayer-hide-tablet'
	],
	'hide_mobile' => [
		'type' => 'checkbox',
		'label' => __pl('hide_mobile'),
		'addClass' => 'pagelayer-hide-mobile'
	],
];

// Custom stuff
$pagelayer->styles['custom_styles'] = [
	'ele_id' => [
		'type' => 'text',
		'label' => __pl('ele_id'),
		'desc' => __pl('ele_id_desc'),
		'addAttr' => 'id="{{ele_id}}"',
	],
	'ele_classes' => [
		'type' => 'text',
		'label' => __pl('ele_classes'),
		'desc' => __pl('ele_classes_desc'),
		'addClass' => '{{val}}',
	],
];


////////////////////////
// GRID Group
////////////////////////

// ROW object
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_row', array(
		'name' => __pl('row'),
		'group' => 'grid',
		'func' => 'pagelayer_sc_row',
		'html' => '<div if="{{bg_video_src}}" class="pagelayer-background-video">{{vid_src}}</div>
			<div if="{{parallax_img}}" class="pagelayer-parallax-window">
				<img class="pagelayer-img" src="{{{parallax_img-url}}}">
			</div>
			<div if="{{row_shape_position}}" class="pagelayer-row-shape">
				<div class="pagelayer-row-svg">
					<svg if="{{row_shape_type_top}}" class="pagelayer-svg-top">{{svg_top}}</svg>
					<svg if="{{row_shape_type_bottom}}" class="pagelayer-svg-bottom">{{svg_bottom}}</svg>						
				</div>
			</div>
			<div if="{{overlay_type}}" class="pagelayer-background-overlay"></div>
			<div class="pagelayer-row-holder pagelayer-row pagelayer-auto"></div>',
		'holder' => '.pagelayer-row-holder',
		'params' => array(
			'stretch' => array(
				'type' => 'select',
				'label' => __pl('con_width'),
				'default' => 'auto',
				'list' => array(
					'auto' => __pl('auto'),
					'full' => __pl('full_width'),
					'fixed' => __pl('fixed_width')
				),
				'addClass' => 'pagelayer-row-stretch-{{val}}'
			),
			'row_width' => array(
				'type' => 'slider',
				'label' => __pl('row_width'),
				'default' => 500,
				'min' => 300,
				'max' => 3000,
				'step' => 1,
				'css' => ['{{element}}' => 'max-width: {{val}}px; margin-left: auto !important; margin-right: auto !important;'],
				'req' => array(
					'stretch' => 'fixed'
				)
			),
			'col_gap' => array(
				'type' => 'spinner',
				'label' => __pl('col_gap'),
				'default' => 10,
				'min' => 0,
				'step' => 1,
				'max' => 1000,
				'css' => ['{{element}} .pagelayer-col-holder' => 'padding: {{val}}px;'],
			),
			'width_content' => array(
				'type' => 'radio',
				'label' => __pl('Content Width'),
				'default' => 'auto',
				'list' => array(
					'auto' => __pl('auto_width'),
					'fixed' => __pl('fixed_width'),
					'full' => __pl('full_width')
				)
			),
			'fixed_width' => array(
				'type' => 'slider',
				'label' => __pl('fixed_width'),
				'default' => 500,
				'min' => 300,
				'max' => 3000,
				'step' => 1,
				'css' => ['{{element}} .pagelayer-row-holder' => 'max-width: {{val}}px; margin-left: auto; margin-right: auto;'],
				'req' => array(
					'width_content' => 'fixed'
				)
			),
			'row_height' => array(
				'type' => 'radio',
				'label' => __pl('row_height'),
				'default' => 'default',
				'addClass' => 'pagelayer-height-{{val}}',
				'list' => array(
					'default' => __pl('default'),
					'fit' => __pl('fit_to_screen'),
					'custom' => __pl('min_height')
				),
			),
			'min_height' => array(
				'type' => 'slider',
				'label' => __pl('min_height'),
				'min' => 0,
				'max' => 2000,
				'step' => 1,
				'css' => 'min-height: {{val}}px;',
				'req' => array(
					'row_height' => 'custom'
				)
			),
			'column_pos' => array(
				'type' => 'select',
				'label' => __pl('column_pos'),
				'default' => 'default',
				'css' => ['{{element}}' => '-webkit-box-align: {{val}}; -webkit-align-items: {{val}}; -ms-flex-align: {{val}}; align-items: {{val}};'],
				'list' => array(
					'' => __pl('default'),
					'flex-start' => __pl('top'),
					'center' => __pl('center'),
					'flex-end' => __pl('bottom'),
					'stretch' => __pl('Stretch')
				),
			),
			'content_pos' => array(
				'type' => 'select',
				'label' => __pl('content_pos'),
				'default' => 'default',
				'css' => ['{{element}} .pagelayer-row-holder' => '-webkit-box-align: {{val}}; -webkit-align-items: {{val}}; -ms-flex-align: {{val}}; align-items: {{val}};'],
				'list' => array(
					'' => __pl('default'),
					'baseline' => __pl('top'),
					'center' => __pl('center'),
					'end' => __pl('bottom'),
					'stretch' => __pl('Stretch')
				),
			),
		),
		'row_bg_styles' => [
			'row_bg_type' => array(
				'type' => 'radio',
				'label' => __pl('row_bg_type'),
				'list' => array(
					'' => __pl('none'),
					'video' => __pl('video'),
					'parallax' => __pl('parallax'),
				),
			),
			'bg_video_src' => array(
				'type' => 'video',
				'label' => __pl('video_src_label'),
				'desc' => __pl('video_src_desc'),
				'req' => ['row_bg_type' => 'video']
			),
			'parallax_img' => array(
				'type' => 'image',
				'label' => __pl('Image'),
				'req' => ['row_bg_type' => 'parallax']
			),
		],
		'row_bg_overlay' => [
			'overlay_state' => array(
				'type' => 'radio',
				'label' => __pl('Overlay'),
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'overlay_type' => array(
				'type' => 'radio',
				'label' => __pl('overlay_type'),
				'list' => array(
					'' => __pl('none'),
					'color' => __pl('color'),
					'image' => __pl('image'),
					'gradient' => __pl('gradient')
				),
				'show' => ['overlay_state' => ''],
			),
			'overlay_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-color: {{val}};'],
				'req' => ['overlay_type' => 'color'],
				'show' => ['overlay_state' => ''],
			),
			'overlay_gradient' => array(
				'type' => 'gradient',
				'label' => '',
				'default' => '150,#44d3f6ff,23,#72e584ff,45,#2ca4ebff,100',
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%);'],
				'show' => ['overlay_state' => ''],
				'req' => ['overlay_type' => 'gradient']
			),
			'overlay_img' => array(
				'type' => 'image',
				'label' => __pl('Image'),
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background: url({{{overlay_img-url}}});'],
				'show' => ['overlay_state' => ''],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_img_attachment' => array(
				'type' => 'select',
				'label' => __pl('overlay_img_attachment'),
				'list' => [
					'' => __pl('default'),
					'scroll' => __pl('scroll'),
					'fixed' => __pl('fixed')
				],
				'show' => ['overlay_state' => ''],
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-attachment: {{val}};'],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_bg_posx' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_posx'),
				'list' => [
					'' => __pl('default'),
					'center' => __pl('center'),
					'left' => __pl('left'),
					'right' => __pl('right')
				],
				'show' => ['overlay_state' => ''],
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-position-x: {{val}};'],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_bg_posy' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_posy'),
				'list' => [
					'' => __pl('default'),
					'center' => __pl('center'),
					'top' => __pl('top'),
					'bottom' => __pl('bottom')
				],
				'show' => ['overlay_state' => ''],
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-position-y: {{val}};'],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_bg_repeat' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_repeat'),
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-repeat: {{val}};'],
				'list' => [
					'' => __pl('default'),
					'repeat' => __pl('repeat'),
					'no-repeat' => __pl('no-repeat'),
					'repeat-x' => __pl('repeat-x'),
					'repeat-y' => __pl('repeat-y'),
				],
				'show' => ['overlay_state' => ''],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_bg_size' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_size'),
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-size: {{val}};'],
				'list' => [
					'' => __pl('default'),
					'cover' => __pl('cover'),
					'contain' => __pl('contain')
				],
				'show' => ['overlay_state' => ''],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_transperancy' => array(
				'type' => 'slider',
				'label' => __pl('overlay_transperancy'),
				'default' => 0.5,
				'min' => 0,
				'max' => 1,
				'step' => 0.1,
				'css' => ['{{element}} .pagelayer-background-overlay' => 'opacity: {{val}};'],
				'req' => array(
					'!overlay_type' => '',
				),
				'show' => ['overlay_state' => ''],
			),
			'overlay_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('overlay_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-background-overlay' => '-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;'],
				'show' => array(
					'overlay_state' => 'hover'
				),
			),
			'overlay_type_hover' => array(
				'type' => 'radio',
				'label' => __pl('overlay_type_hover'),
				'list' => array(
					'' => __pl('none'),
					'color' => __pl('color'),
					'gradient' => __pl('gradient'),
					'image' => __pl('image'),
				),
				'show' => ['overlay_state' => 'hover'],
			),
			'overlay_color_hover' => array(
				'type' => 'color',
				'label' => __pl('color'),
				//'desc' => __pl('video_src_desc'),
				'css' => ['{{element}}:hover  .pagelayer-background-overlay' => 'background: {{val}};'],
				'req' => ['overlay_type_hover' => 'color'],
				'show' => ['overlay_state' => 'hover'],
			),
			'overlay_gradient_hover' => array(
				'type' => 'gradient',
				'label' => '',
				'default' => '150,#44d3f6ff,23,#72e584ff,45,#2ca4ebff,100',
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%);'],
				'show' => ['overlay_state' => 'hover'],
				'req' => ['overlay_type_hover' => 'gradient']
			),
			'overlay_img_hover' => array(
				'type' => 'image',
				'label' => __pl('Image'),
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background: url({{{overlay_img_hover-url}}});'],
				'show' => ['overlay_state' => 'hover'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_img_attachment_hover' => array(
				'type' => 'select',
				'label' => __pl('overlay_img_attachment_hover'),
				'list' => [
					'' => __pl('default'),
					'scroll' => __pl('scroll'),
					'fixed' => __pl('fixed')
				],
				'show' => ['overlay_state' => 'hover'],
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background-attachment: {{val}};'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_bg_posx_hover' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_posx_hover'),
				'list' => [
					'' => __pl('default'),
					'center' => __pl('center'),
					'left' => __pl('left'),
					'right' => __pl('right')
				],
				'show' => ['overlay_state' => 'hover'],
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background-position-x: {{val}};'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_bg_posy_hover' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_posy_hover'),
				'list' => [
					'' => __pl('default'),
					'center' => __pl('center'),
					'top' => __pl('top'),
					'bottom' => __pl('bottom')
				],
				'show' => ['overlay_state' => 'hover'],
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background-position-y: {{val}};'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_bg_repeat_hover' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_repeat_hover'),
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background-repeat: {{val}};'],
				'list' => [
					'' => __pl('default'),
					'repeat' => __pl('repeat'),
					'no-repeat' => __pl('no-repeat'),
					'repeat-x' => __pl('repeat-x'),
					'repeat-y' => __pl('repeat-y'),
				],
				'show' => ['overlay_state' => 'hover'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_bg_size_hover' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_size_hover'),
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background-size: {{val}};'],
				'list' => [
					'' => __pl('default'),
					'cover' => __pl('cover'),
					'contain' => __pl('contain')
				],
				'show' => ['overlay_state' => 'hover'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_transperancy_hover' => array(
				'type' => 'slider',
				'label' => __pl('overlay_transperancy_hover'),
				'min' => 0,
				'max' => 1,
				'step' => 0.1,
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'opacity: {{val}};'],
				'req' => array(
					'overlay_type' => 'image',
					'overlay_type' => 'color'
				),
				'show' => ['overlay_state' => 'hover'],
			),
		],
		'shape_styles' => [
			'row_shape_position' => array(
				'type' => 'radio',
				'label' => __pl('shape_position'),
				'list' => array(
					'top' => __pl('Top'),
					'bottom' => __pl('Bottom'),
				),
			),
			'row_shape_type_top' => array(
				'type' => 'select',
				'label' => __pl('shape_type'),
				'default' => '',
				'list' => array(
					'' => __pl('none'),
					'arrow' => __pl('Arrow'),
					'bigTriangle' => __pl('BigTriangle'),
					'bigTriangleShadow' => __pl('BigTriangle Shadow'),
					'curve' => __pl('Curve'),
					'clouds' => __pl('Clouds'),
					'mountains' => __pl('Mountains'),
					'pyramids' => __pl('Pyramids'),
					'stamp' => __pl('Stamp'),
					'slit' => __pl('Slit'),
					'split' => __pl('Split'),
					'tilt' => __pl('Tilt'),
					'tiltOpacity' => __pl('Tilt Opacity'),
					'waves' => __pl('Waves'),
					'zigzag' => __pl('Zigzag'),
				),
				'show' => ['row_shape_position' => 'top'],
			),
			'row_shape_top_color' => array(
				'type' => 'color',
				'label' => __pl('shape_bg_color'),
				'default' => '#fff',
				'css' => ['{{element}} .pagelayer-svg-top .pagelayer-shape-fill' => 'fill:{{val}}'],
				'show' => ['row_shape_position' => 'top'],
			),
			'row_shape_top_width' => array(
				'type' => 'slider',
				'label' => __pl('shape_width'),
				'screen' => 1,
				'default' => 100,
				'min' => 100,
				'max' => 500,
				'css' => ['{{element}} .pagelayer-row-svg .pagelayer-svg-top' => 'width:{{val}}%'],
				'show' => ['row_shape_position' => 'top'],
			),
			'row_shape_top_height' => array(
				'type' => 'slider',
				'label' => __pl('shape_height'),
				'screen' => 1,
				'default' => 100,
				'min' => 10,
				'max' => 500,
				'css' => ['{{element}} .pagelayer-row-svg .pagelayer-svg-top' => 'height:{{val}}px'],
				'show' => ['row_shape_position' => 'top'],
			),
			'row_shape_top_flip' => array(
				'type' => 'checkbox',
				'label' => __pl('shape_flip'),
				'css' => ['{{element}} .pagelayer-row-svg .pagelayer-svg-top' => 'transform: rotateY(180deg);'],
				'show' => ['row_shape_position' => 'top'],
			),
			'row_shape_type_bottom' => array(
				'type' => 'select',
				'label' => __pl('shape_type'),
				'default' => '',
				'list' => array(
					'' => __pl('none'),
					'arrow' => __pl('Arrow'),
					'bigTriangle' => __pl('BigTriangle'),
					'bigTriangleShadow' => __pl('BigTriangle Shadow'),
					'curve' => __pl('Curve'),
					'clouds' => __pl('Clouds'),
					'mountains' => __pl('Mountains'),
					'pyramids' => __pl('Pyramids'),
					'stamp' => __pl('Stamp'),
					'slit' => __pl('Slit'),
					'split' => __pl('Split'),
					'tilt' => __pl('Tilt'),
					'tiltOpacity' => __pl('Tilt Opacity'),
					'waves' => __pl('Waves'),
					'zigzag' => __pl('Zigzag'),
				),
				'show' => ['row_shape_position' => 'bottom'],
			),
			'row_shape_bottom_color' => array(
				'type' => 'color',
				'label' => __pl('shape_bg_color'),
				'default' => '#fff',
				'css' => ['{{element}} .pagelayer-svg-bottom .pagelayer-shape-fill' => 'fill:{{val}}'],
				'show' => ['row_shape_position' => 'bottom'],
			),
			'row_shape_bottom_width' => array(
				'type' => 'slider',
				'label' => __pl('shape_width'),
				'screen' => 1,
				'default' => 100,
				'min' => 100,
				'max' => 500,
				'css' => ['{{element}} .pagelayer-row-svg .pagelayer-svg-bottom' => 'width:{{val}}%'],
				'show' => ['row_shape_position' => 'bottom'],
			),
			'row_shape_bottom_height' => array(
				'type' => 'slider',
				'label' => __pl('shape_height'),
				'screen' => 1,
				'default' => 100,
				'min' => 10,
				'max' => 500,
				'css' => ['{{element}} .pagelayer-row-svg .pagelayer-svg-bottom' => 'height:{{val}}px'],
				'show' => ['row_shape_position' => 'bottom'],
			),
			'row_shape_bottom_flip' => array(
				'type' => 'checkbox',
				'label' => __pl('shape_flip'),
				'css' => ['{{element}} .pagelayer-row-svg .pagelayer-svg-bottom' => 'transform: rotateY(180deg);'],
				'show' => ['row_shape_position' => 'bottom'],
			),
			
		],
		'styles' => [
			'row_bg_styles' => __pl('row_bg_styles'),
			'row_bg_overlay' => __pl('row_bg_overlay'),
			'shape_styles' => __pl('shape_styles'),
		],
	)
);

// Column object
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_col', array(
		'name' => __pl('column'),
		'group' => 'grid',
		'func' => 'pagelayer_sc_col',
		'html' => '<div if="{{bg_video_src}}" class="pagelayer-background-video">{{vid_src}}</div>
				<div if="{{parallax_img}}" class="pagelayer-parallax-window">
					<img class="pagelayer-img" src="{{{parallax_img-url}}}">
				</div>
				<div if="{{overlay_type}}" class="pagelayer-background-overlay"></div>
				<div class="pagelayer-col-holder"></div>',
		'holder' => '.pagelayer-col-holder',
		'params' => array(
			'content_pos' => array(
				'type' => 'select',
				'label' => __pl('content_pos'),
				'default' => '',
				'css' => ['{{element}}' => 'display:flex; -webkit-box-align: {{val}}; -webkit-align-items: {{val}}; -ms-flex-align: {{val}}; align-items: {{val}}; height: 100%;',
					'{{element}} .pagelayer-col-holder' => 'width:100%'],
				'list' => array(
					'' => __pl('default'),
					'flex-start' => __pl('top'),
					'center' => __pl('center'),
					'flex-end' => __pl('bottom')
				)
			),
			'widget_space' => array(
				'type' => 'spinner',
				'label' => __pl('widget_space'),
				'default' => get_option('pagelayer_between_widgets', 15),
				'min' => -1000,
				'step' => 1,
				'max' => 1000,
				'css' => ['{{element}} .pagelayer-col-holder > div:not(:last-child)' => 'margin-bottom: {{val}}px;'],
			),
			'col' => array(
				'type' => 'select',
				'label' => __pl('col_width'),
				'addClass' => 'pagelayer-col-{{val}}',
				'list' => array(
					'1' => __pl('1'),
					'2' => __pl('2'),
					'3' => __pl('3'),
					'4' => __pl('4'),
					'5' => __pl('5'),
					'6' => __pl('6'),
					'7' => __pl('7'),
					'8' => __pl('8'),
					'9' => __pl('9'),
					'10' => __pl('10'),
					'11' => __pl('11'),
					'12' => __pl('12'),
					'' => __pl('custom'),
				)
			),
			'col_width' => array(
				'type' => 'spinner',
				'label' => __pl('width_custom'),
				'min' => 0,
				'step' => 1,
				'max' => 1000,
				'css' => ( !pagelayer_is_live() ? 'width: {{val}}px;' : '' ),
				'req' => ['col' => ''],
			),
		),
		'col_bg_styles' => [
			'col_bg_type' => array(
				'type' => 'radio',
				'label' => __pl('col_bg_type'),
				'list' => array(
					'' => __pl('none'),
					'video' => __pl('video'),
					'parallax' => __pl('parallax'),
				),
			),
			'bg_video_src' => array(
				'type' => 'video',
				'label' => __pl('video_src_label'),
				'desc' => __pl('video_src_desc'),
				'req' => ['col_bg_type' => 'video']
			),
			'parallax_img' => array(
				'type' => 'image',
				'label' => __pl('Image'),
				'req' => ['col_bg_type' => 'parallax']
			),
		],
		'col_bg_overlay' => [
			'overlay_state' => array(
				'type' => 'radio',
				'label' => '',
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'overlay_type' => array(
				'type' => 'radio',
				'label' => __pl('overlay_type'),
				'list' => array(
					'' => __pl('none'),
					'color' => __pl('color'),
					'image' => __pl('image'),
					'gradient' => __pl('gradient')
				),
				'show' => ['overlay_state' => ''],
			),
			'overlay_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				//'desc' => __pl('video_src_desc'),
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-color: {{val}};'],
				'req' => ['overlay_type' => 'color'],
				'show' => ['overlay_state' => ''],
			),
			'overlay_gradient' => array(
				'type' => 'gradient',
				'label' => '',
				'default' => '150,#44d3f6ff,23,#72e584ff,45,#2ca4ebff,100',
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%);'],
				'show' => ['overlay_state' => ''],
				'req' => ['overlay_type' => 'gradient']
			),
			'overlay_img' => array(
				'type' => 'image',
				'label' => __pl('Image'),
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background: url({{{overlay_img-url}}});'],
				'show' => ['overlay_state' => ''],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_img_attachment' => array(
				'type' => 'select',
				'label' => __pl('overlay_img_attachment'),
				'list' => [
					'' => __pl('default'),
					'scroll' => __pl('scroll'),
					'fixed' => __pl('fixed')
				],
				'show' => ['overlay_state' => ''],
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-attachment: {{val}};'],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_bg_posx' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_posx'),
				'list' => [
					'' => __pl('default'),
					'center' => __pl('center'),
					'left' => __pl('left'),
					'right' => __pl('right')
				],
				'show' => ['overlay_state' => ''],
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-position-x: {{val}};'],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_bg_posy' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_posy'),
				'list' => [
					'' => __pl('default'),
					'center' => __pl('center'),
					'top' => __pl('top'),
					'bottom' => __pl('bottom')
				],
				'show' => ['overlay_state' => ''],
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-position-y: {{val}};'],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_bg_repeat' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_repeat'),
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-repeat: {{val}};'],
				'list' => [
					'' => __pl('default'),
					'repeat' => __pl('repeat'),
					'no-repeat' => __pl('no-repeat'),
					'repeat-x' => __pl('repeat-x'),
					'repeat-y' => __pl('repeat-y'),
				],
				'show' => ['overlay_state' => ''],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_bg_size' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_size'),
				'css' => ['{{element}} .pagelayer-background-overlay' => 'background-size: {{val}};'],
				'list' => [
					'' => __pl('default'),
					'cover' => __pl('cover'),
					'contain' => __pl('contain')
				],
				'show' => ['overlay_state' => ''],
				'req' => ['overlay_type' => 'image']
			),
			'overlay_transperancy' => array(
				'type' => 'slider',
				'label' => __pl('overlay_transperancy'),
				'default' => 0.5,
				'min' => 0,
				'max' => 1,
				'step' => 0.1,
				'css' => ['{{element}} .pagelayer-background-overlay' => 'opacity: {{val}};'],
				'req' => array(
					'!overlay_type' => '',
				),
				'show' => ['overlay_state' => ''],
			),
			'overlay_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('overlay_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-background-overlay' => '-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;'],
				'show' => array(
					'overlay_state' => 'hover'
				),
			),
			'overlay_type_hover' => array(
				'type' => 'radio',
				'label' => __pl('overlay_type_hover'),
				'list' => array(
					'' => __pl('none'),
					'color' => __pl('color'),
					'gradient' => __pl('gradient'),
					'image' => __pl('image'),
				),
				'show' => ['overlay_state' => 'hover'],
			),
			'overlay_color_hover' => array(
				'type' => 'color',
				'label' => __pl('color'),
				//'desc' => __pl('video_src_desc'),
				'css' => ['{{element}}:hover  .pagelayer-background-overlay' => 'background: {{val}};'],
				'req' => ['overlay_type_hover' => 'color'],
				'show' => ['overlay_state' => 'hover'],
			),
			'overlay_gradient_hover' => array(
				'type' => 'gradient',
				'label' => '',
				'default' => '150,#44d3f6ff,23,#72e584ff,45,#2ca4ebff,100',
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background: linear-gradient({{val[0]}}deg, {{val[1]}} {{val[2]}}%, {{val[3]}} {{val[4]}}%, {{val[5]}} {{val[6]}}%);'],
				'show' => ['overlay_state' => 'hover'],
				'req' => ['overlay_type_hover' => 'gradient']
			),
			'overlay_img_hover' => array(
				'type' => 'image',
				'label' => __pl('Image'),
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background: url({{{overlay_img_hover-url}}});'],
				'show' => ['overlay_state' => 'hover'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_img_attachment_hover' => array(
				'type' => 'select',
				'label' => __pl('overlay_img_attachment_hover'),
				'list' => [
					'' => __pl('default'),
					'scroll' => __pl('scroll'),
					'fixed' => __pl('fixed')
				],
				'show' => ['overlay_state' => 'hover'],
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background-attachment: {{val}};'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_bg_posx_hover' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_posx_hover'),
				'list' => [
					'' => __pl('default'),
					'center' => __pl('center'),
					'left' => __pl('left'),
					'right' => __pl('right')
				],
				'show' => ['overlay_state' => 'hover'],
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background-position-x: {{val}};'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_bg_posy_hover' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_posy_hover'),
				'list' => [
					'' => __pl('default'),
					'center' => __pl('center'),
					'top' => __pl('top'),
					'bottom' => __pl('bottom')
				],
				'show' => ['overlay_state' => 'hover'],
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background-position-y: {{val}};'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_bg_repeat_hover' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_repeat_hover'),
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background-repeat: {{val}};'],
				'list' => [
					'' => __pl('default'),
					'repeat' => __pl('repeat'),
					'no-repeat' => __pl('no-repeat'),
					'repeat-x' => __pl('repeat-x'),
					'repeat-y' => __pl('repeat-y'),
				],
				'show' => ['overlay_state' => 'hover'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_bg_size_hover' => array(
				'type' => 'select',
				'label' => __pl('overlay_bg_size_hover'),
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'background-size: {{val}};'],
				'list' => [
					'' => __pl('default'),
					'cover' => __pl('cover'),
					'contain' => __pl('contain')
				],
				'show' => ['overlay_state' => 'hover'],
				'req' => ['overlay_type_hover' => 'image']
			),
			'overlay_transperancy_hover' => array(
				'type' => 'slider',
				'label' => __pl('overlay_transperancy_hover'),
				'default' => 0.5,
				'min' => 0,
				'max' => 1,
				'step' => 0.1,
				'css' => ['{{element}}:hover .pagelayer-background-overlay' => 'opacity: {{val}};'],
				'req' => array(
					'!overlay_type_hover' => '',
				),
				'show' => ['overlay_state' => 'hover'],
			),
		],
		'styles' => [
			'col_bg_styles' => __pl('col_bg_styles'),
			'col_bg_overlay' => __pl('col_bg_overlay'),
		],
	)
);

////////////////////////
// TEXT Group
////////////////////////

// Heading object
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_heading', array(
		'name' => __pl('title'),
		'group' => 'text',
		'func' => 'pagelayer_sc_heading',
		'innerHTML' => 'text',
		'html' => '<a if-ext="{{link}}" href="{{link}}">
			<div class="pagelayer-heading-holder">{{text}}</div>
		</a>',
		'params' => array(
			'text' => array(
				'type' => 'textarea',
				'label' => __pl('Edit Title'),
				'default' => '<h2>Your Heading</h2>',
				'desc' => __pl('Edit the heading here'),
				'edit' => '.pagelayer-heading-holder', // Edit the text and also mirror the same
			),
			'link' => array(
				'label' => __pl('image_link_label'),
				'desc' => __pl('image_link_desc'),
				'type' => 'link',
			),
			'target' => array(
				'label' => __pl('open_link_in_new_window'),
				'type' => 'checkbox',
				'addAttr' => ['{{element}} a' => 'target="_blank"'],
				'req' => array(
					'!link' => ''
				)
			),
			'align' => array(
				'label' => __pl('obj_align_label'),
				'type' => 'radio',
				'addAttr' => 'align="{{align}}"',
				'screen' => 1,
				'css' => ['{{element}}' => 'text-align: {{val}}'],
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				)
			),
		),
		'heading_styles' => [
			'color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#111111ff',
				'css' => ['{{element}} .pagelayer-heading-holder *' => 'color:{{val}}', '{{element}} .pagelayer-heading-holder' => 'color:{{val}}'],
			),
			'heading_typo' => array(
				'type' => 'typography',
				'label' => __pl('typography'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-heading-holder *' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
				'{{element}} .pagelayer-heading-holder' => 'font-family: {{val[0]}} !important; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'heading_text_shadow' => array(
				'type' => 'shadow',
				'label' => __pl('text_shadow'),
				'css' => ['{{element}} .pagelayer-heading-holder' => 'text-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}} !important;'],
			),
		],
		'styles' => [
			'heading_styles' => __pl('heading_styles')
		],
	)
);

// Rich Text object
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_text', array(
		'name' => __pl('Rich Text'),
		'group' => 'text',
		'func' => 'pagelayer_sc_code',
		'innerHTML' => 'text',
		'html' => '<div class="pagelayer-text-holder">{{text}}</div>',
		'params' => array(
			'text' => array(
				'type' => 'editor',
				'label' => __pl('Edit Rich Text'),
				'default' => 'Lorem ipsum dolor sit amet',
				'desc' => __pl('Edit the content here or edit directly in the Editor'),
				'edit' => '.pagelayer-text-holder', // Edit the text and also mirror the same
			)
		)
	)
);

// Quote
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_quote', array(
		'name' => __pl('quote'),
		'group' => 'text',
		'innerHTML' => 'quote_content',
		'html' => '<div class="pagelayer-quote-holder pagelayer-quote-{{quote_style}}">
				<i if="{{quotation_pos}}" class="fa fa-quote-left pagelayer-quotation-{{quotation_pos}}"></i>
				<div if="{{quote_content}}" class="pagelayer-quote-content">
					<i if="{{double_indent}}" class="fa fa-quote-left"></i>
					{{quote_content}}
					<i if="{{double_indent}}" class="fa fa-quote-right"></i>
				</div>
				<div if="{{cite}}" class="pagelayer-quote-cite">
					<a if-ext="{{cite_url}}" href="{{cite_url}}">
						<span class="pagelayer-cite-holder">{{cite}}</span>
					</a>
				</div>
			</div>',
		'func' => 'pagelayer_sc_quote',
		'params' => array(		
			'quote_content' => array(
				'type' => 'textarea',
				'label' => __pl('quotes_content_label'),
				'default' => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.',
				'edit' => '.pagelayer-quote-content',
			),
			'quote_background_color' => array(
				'type' => 'color',
				'label' => __pl('bg_color'),
				'default' => '#eeeeee',
				'css' => ['{{element}} .pagelayer-quote-holder' => 'background-color: {{val}}']
			),
			'quote_content_color' => array(
				'type' => 'color',
				'label' => __pl('quotes_content_color_label'),
				'default' => '#050505',
				'css' => ['{{element}} .pagelayer-quote-content' => 'color:{{val}}'],
			),
			'quote_content_typo' => array(
				'type' => 'typography',
				'label' => __pl('quote_content_typo'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-quote-content' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		),
		'qoute_styles' => [		
			'quote_style' => array(
				'type' => 'select',
				'label' => __pl('style'),
				'default' => 'quotation',
				'list' => array(
					'default' => __pl('default'),
					'quotation' => __pl('quotation'),
					'double' => __pl('double_quotation')
				)
			),
			'quotation_pos' => array(
				'type' => 'radio',
				'label' => __pl('quotation_pos_label'),
				'default' => 'default',
				'css' => ['{{element}} .pagelayer-quote-holder' => 'position: relative;',
					'{{element}} .pagelayer-quote-content' => 'position: relative; z-index:1;',
					'{{element}} .pagelayer-quote-cite' => 'position: relative; z-index:1;'],
				'list' => array(
					'default' => __pl('default'),
					'overlay' => __pl('overlay')
				),
				'req' => array(
					'quote_style' => 'quotation'
				),
			),
			'quotation_size' => array(
				'type' => 'slider',
				'label' => __pl('quotation_size_label'),
				'min' => 1,
				'step' => 1,
				'max' => 1000,
				'default' => 70,
				'screen' => 1,
				'css' => ['{{element}} i' => 'font-size: {{val}}px;'],
				'req' => array(
					'quote_style' => ['quotation','double']
				)
			),
			'quotation_color' => array(
				'type' => 'color',
				'label' => __pl('quotation_color_label'),
				'default' => '#dadadaff',
				'css' => ['{{element}} i' => 'color:{{val}}'],
				'req' => array(
					'quote_style' => ['quotation','double']
				)
			),
			'quotation_top' => array(
				'type' => 'slider',
				'label' => __pl('quotation_top_label'),
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 0,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-quotation-overlay' => 'top: {{val}}%;'],
				'req' => array(
					'quote_style' => 'quotation',
					'quotation_pos' => 'overlay'
				)
			),
			'quotation_left' => array(
				'type' => 'slider',
				'label' => __pl('quotation_left_label'),
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-quotation-overlay' => 'left: {{val}}%;'],
				'req' => array(
					'quote_style' => 'quotation',
					'quotation_pos' => 'overlay'
				)
			),
			'double_indent' => array(
				'type' => 'slider',
				'label' => __pl('quotation_double_indent_label'),
				'min' => 1,
				'step' => 1,
				'max' => 500,
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .fa-quote-right' => 'padding-left: {{val}}px;',
					'{{element}} .fa-quote-left' => 'padding-right: {{val}}px;'],
				'req' => array(
					'quote_style' => 'double'
				)
			),
			'align' => array(
				'label' => __pl('obj_align_label'),
				'type' => 'radio',
				'default' => 'left',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-quote-holder' => 'text-align: {{val}};'],
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				)
			)				
		],
		'cite_styles' => [
			'cite' => array(
				'type' => 'text',
				'label' => __pl('quotes_cite_label'),
				'default' => '- John Smith',
				'desc' => __pl('quotes_cite_desc'),
				'edit' => '.pagelayer-cite-holder',
			),
			'cite_url' => array(
				'type' => 'link',
				'label' => __pl('quotes_url_label'),
				'desc' => __pl('quotes_url_desc'),
			),
			'cite_text_color' => array(
				'type' => 'color',
				'label' => __pl('quotes_cite_color_label'),
				'default' => '#3f3f3f',
				'css' => ['{{element}} .pagelayer-quote-cite span' => 'color:{{val}}']
			),
			'cite_typo' => array(
				'type' => 'typography',
				'label' => __pl('cite_typo'),
				'default' => ',16,italic,,,,solid,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-quote-cite' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			)
		],
		'border_style' => [
			'quote_border_width' => array(
				'type' => 'spinner',
				'label' => __pl('quote_left_border_width'),
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 5,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-quote-holder' => 'border-left-width: {{val}}px; border-left-style: solid;']
			), 
			'quote_border_color' => array(
				'type' => 'color',
				'label' => __pl('quote_border_color'),
				'default' => '#02CC90',
				'css' => ['{{element}} .pagelayer-quote-holder' => 'border-left-color: {{val}}']
			),
			'quote_lpadding' => array(
				'type' => 'spinner',
				'label' => __pl('quote_left_padding'),
				'min' => 1,
				'step' => 1,
				'max' => 100,
				'default' => 30,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-quote-holder' => 'padding-left: {{val}}px; padding-right: 10px;']
			),
			'quote_vpadding' => array(
				'type' => 'spinner',
				'label' => __pl('quote_vertical_padding'),
				'min' => 1,
				'step' => 1,
				'max' => 100,
				'default' => 20,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-quote-holder' => 'padding-top: {{val}}px; padding-bottom: {{val}}px;']
			)
		],
		'styles' => [
			'qoute_styles' => __pl('qoute_styles'),
			'cite_styles' => __pl('cite_styles'),
			'border_style' => __pl('left_border'),
		],
	)
);

pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_list_item', array(
		'name' => __pl('list_item'),
		'group' => 'text',
		'not_visible' => 1,
		'func' => 'pagelayer_sc_list_item',
		'innerHTML' => 'item',
		'html' => '<li if="{{item}}" class="pagelayer-list-li">
				<a if-ext="{{item_url}}" class="pagelayer-list-url pagelayer-ele-link" href="{{item_url}}">
					<span class="pagelayer-list-icon-holder">
						<i if="{{show_icon}}" class="pagelayer-list-icon fa fa-{{icon}}"></i>
						<span if="{{item}}" class="pagelayer-list-item">{{item}}</span>
					</span>
				</a>
			</li>',
		'params' => array(
			'item' => array(
				'type' => 'text',
				'label' => __pl('list_items_label'),
				'default' => __pl('list_items_default'),
			),
			'item_url' => array(
				'type' => 'text',
				'label' => __pl('list_item_url_label'),
				'default' => ''
			),
			'show_icon' => array(
				'type' => 'checkbox',
				'label' => __pl('list_show_icon'),
				'default' => 'true'
			),
			'icon' => array(
				'type' => 'icon',
				'label' => __pl('list_icon_label'),
				'default' => 'star',
				'req' => array(
					'show_icon' => 'true'
				)
			)
		)
	)
);

// List
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_list', array(
		'name' => __pl('list'),
		'group' => 'text',
		'func' => 'pagelayer_sc_list',
		'has_group' => [
			'section' => 'params', 
			'prop' => 'elements'
		],
		'holder' => '.pagelayer-list-ul',
		'html' => '<ul class="pagelayer-list-ul pagelayer-list-type-{{list_type}}">
			</ul>',
		'params' => array(
			'elements' => array(
				'type' => 'group',
				'label' => __pl('List Item'),
				'sc' => PAGELAYER_SC_PREFIX.'_list_item',
				'item_label' => array(
					'default' => __pl('List Item'),
					'param' => 'item'
				),
				'count' => 2,
				'text' => __pl('Add List Item'),
			),
			'list_type' => array(
				'type' => 'select',
				'label' => __pl('style'),
				'default' => 'none',
				'css' => ['{{element}} li' => 'list-style-type: {{val}};'],
				'list' => array(
					'none' => __pl('none'),
					'circle' => __pl('list_list_type_circle'),
					'disc' => __pl('list_list_type_disc'),
					'square' => __pl('list_list_type_square'),
					'armenian' => __pl('list_list_type_armenian'),
					'georgian' => __pl('list_list_type_georgian'),
					'decimal' => '1, 2, 3, 4',
					'decimal-leading-zero' => '01, 02, 03, 04',
					'lower-latin' => 'a, b, c, d',
					'lower-roman' => 'i, ii, iii, iv',
					'lower-greek' => 'α, β, γ, δ',
					'upper-latin' => 'A, B, C, D',
					'upper-roman' => 'I, II, III, IV'
				)
			),
			'spacing' => array(
				'type' => 'slider',
				'label' => __pl('list_spacing_label'),
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-list-ul div:not(:last-child)' => 'padding-bottom: calc({{val}}px/2);',
					'{{element}} .pagelayer-list-ul div:not(:first-child)' => 'margin-top: calc({{val}}px/2)'],
			),
			'side_spacing' => array(
				'type' => 'slider',
				'label' => __pl('list_side_spacing_label'),
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-list_item' => 'padding-left: {{val}}px; padding-right: {{val}}px;'],
			),
		),
		'text_style' => [
			'list_color' => array(
				'type' => 'color',
				'label' => __pl('list_color_label'),
				'default' => '#555555',
				'css' => ['{{element}} li' => 'color:{{val}}'],
			),
			'list_typo' => array(
				'type' => 'typography',
				'label' => __pl('list_typo'),
				'screen' => 1,
				'css' => [
					'{{element}} li' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
					'{{element}} li > a' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'
				],
			),
			'item_indent' => array(
				'type' => 'slider',
				'label' => __pl('list_item_indent_label'),
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-list-item' => 'margin-left: {{val}}px;'],
			),
		],
		'icon_style' => [
			'icon_color' => array(
				'type' => 'color',
				'label' => __pl('list_icon_color_label'),
				'default' => '#3E8EF7',
				'css' => ['{{element}} i' => 'color:{{val}}'],
			),
			'icon_size' => array(
				'type' => 'slider',
				'label' => __pl('list_icon_size_label'),
				'min' => 0,
				'step' => 1,
				'max' => 150,
				'screen' => 1,
				'css' => ['{{element}} i' => 'font-size: {{val}}px'],
			),
		],
		'divider' => [
			'icon_border_type' => array(
				'type' => 'select',
				'label' => __pl('type'),
				'css' => ['{{element}} .pagelayer-list-ul > div:not(:last-child)' => 'border-bottom-style: {{val}};'],
				'default' => 'solid',
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
			),
			'icon_border_color' => array(
				'type' => 'color',
				'label' => __pl('color'),
				'default' => '#cbd2dc78',
				'css' => ['{{element}} .pagelayer-list-ul > div' => 'border-bottom-color: {{val}};'],
				'req' => array(
					'!icon_border_type' => ''
				),
			),
			'icon_border_width' => array(
				'type' => 'slider',
				'label' => __pl('border_width'),
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 3,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-list-ul > div' => 'border-bottom-width: {{val[0]}}px;'],
				'req' => [
					'!icon_border_type' => ''
				]
			),
		],
		'styles' => [
			'text_style' => __pl('text_style'),
			'icon_style' => __pl('icon_style'),
			'divider' => __pl('divider'),
		]
	)
);

// Icon
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_icon', array(
		'name' => __pl('icon'),
		'group' => 'text',
		'func' => 'pagelayer_sc_icon',
		'html' => '<div class="pagelayer-icon-holder">
					<a if-ext="{{link}}" class="pagelayer-ele-link" href="{{link}}">
						<i class="fa fa-{{icon}} {{bg_shape}} {{icon_size}} pagelayer-animation-{{anim_hover}}"></i>
					</a>
				</div>',
		'params' => array(
			'icon' => array(
				'type' => 'icon',
				'label' => __pl('list_icon_label'),
				'default' => 'star',
			),
			'icon_background_size' => array(
				'type' => 'spinner',
				'label' => __pl('service_box_icon_background_size'),
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} i' => 'padding: calc(0.5em + {{val}}px);'],
				'min' => 1,
				'max' => 500,
				'step' => 1,
			),
			'link' => array(
				'type' => 'link',
				'label' => __pl('icon_link_field_label'),
				'default' => ''
			),
			'target' => array(
				'type' => 'checkbox',
				'label' => __pl('open_link_in_new_window'),
				'addAttr' => ['{{element}} a' => 'target="_blank"']
			),
			'icon_alignment' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'center',
				'screen' => 1,
				'css' => 'text-align: {{val}}',
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				),
			),
		),
		'icon_style' => [
			'icon_hover' => array(
				'type' => 'radio',
				'label' => '',
				'default' => '',
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'icon_color_style' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_color_label'),
				'css' => ['{{element}} i' => 'height: 1em; width: 1em; position: relative; color: {{val}};',
					'{{element}} i:before' => 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'],
				'default' => '#3e8ef7',
				'show' => array(
					'icon_hover' => ''
				),
			),
			'bg_shape' => array(
				'type' => 'select',
				'label' => __pl('icon_background_shape'),
				'default' => '',
				'list' => array(
					'' => __pl('icon_shape_none'),
					'pagelayer-icon-circle' => __pl('icon_shape_circle'),
					'pagelayer-icon-square' => __pl('icon_shape_square'),
					'pagelayer-icon-rounded' => __pl('icon_shape_rounded')
				),
				'show' => array(
					'icon_hover' => ''
				),
			),
			'icon_size' => array(
				'type' => 'select',
				'label' => __pl('obj_size_label'),
				'default' => 'pagelayer-icon-large',
				'list' => array(
					'pagelayer-icon-mini' => __pl('mini'),
					'pagelayer-icon-small' => __pl('small'),
					'pagelayer-icon-large' => __pl('large'),
					'pagelayer-icon-extra-large' => __pl('extra_large'),
					'pagelayer-icon-double-large' => __pl('double_large'),
					'pagelayer-icon-custom' => __pl('custom'),
				),
				'show' => array(
					'icon_hover' => ''
				),
			),
			'icon_size_custom' => array(
				'type' => 'spinner',
				'label' => __pl('service_box_icon_custom_size_label'),
				'desc' => __pl('service_box_icon_custom_size_desc'),
				'min' => 1,
				'step' => 1,
				'max' => 500,
				'default' => 26,
				'screen' => 1,
				'css' => ['{{element}} i' => 'font-size: {{val}}px'],
				'req' => array(
					'icon_size' => 'pagelayer-icon-custom'
				),
				'show' => array(
					'icon_hover' => ''
				),
			),
			'icon_rotate' => array(
				'type' => 'spinner',
				'label' => __pl('service_box_icon_rotate'),
				'default' => 0,
				'css' => ['{{element}} i' => 'transform: rotate({{val}}deg)'],
				'min' => 0,
				'max' => 360,
				'step' => 1,
				'screen' => 1,
				'show' => array(
					'icon_hover' => ''
				),
			),
			'bg_color' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_background_color_label'),
				'default' => '#42414f',
				'css' => ['{{element}} i' => 'background-color: {{val}};'],
				'req' => array(
					'!bg_shape' => ''
				),
				'show' => array(
					'icon_hover' => ''
				),
			),
			'icon_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('animation_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'screen' => 1,
				'css' => ['{{element}} i' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => array(
					'icon_hover' => 'hover'
				),
			),
			'icon_color_style_hover' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_color_label'),
				'css' => ['{{element}} i:hover' => 'height: 1em; width: 1em; position: relative; color: {{val}}',
					'{{element}} i:before' => 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);'],
				'show' => array(
					'icon_hover' => 'hover'
				),
			),
			'icon_size_custom_hover' => array(
				'type' => 'spinner',
				'label' => __pl('service_box_icon_custom_size_label'),
				'desc' => __pl('service_box_icon_custom_size_desc'),
				'min' => 1,
				'step' => 1,
				'max' => 500,
				'screen' => 1,
				'css' => ['{{element}} i:hover' => 'font-size: {{val}}px'],
				'req' => array(
					'icon_size' => 'pagelayer-icon-custom'
				),
				'show' => array(
					'icon_hover' => 'hover'
				),
			),
			'anim_hover' => array(
				'type' => 'select',
				'label' => __pl('icon_animation'),
				'list' => [
					'' => __pl('none'),
					'grow' => __pl('Grow'),
					'shrink' => __pl('Shrink'),
					'pulse' => __pl('Pulse'),
					'pulse-grow' => __pl('Pulse Grow'),
					'pulse-shrink' => __pl('Pulse Shrink'),
					'push' => __pl('Push'),
					'pop' => __pl('Pop'),
					'buzz' => __pl('Buzz'),
					'buzz-out' => __pl('Buzz Out'),
					'float' => __pl('Float'),
					'sink' => __pl('Sink'),
					'bob' => __pl('Bob'),
					'hang' => __pl('Hang'),
					'bounce-in' => __pl('Bounce In'),
					'bounce-out' => __pl('Bounce Out'),
					'rotate' => __pl('Rotate'),
					'grow-rotate' => __pl('Grow Rotate'),
					'skew-forward' => __pl('Skew Forward'),
					'skew-backward' => __pl('Skew Backward'),
					'wobble-vertical' => __pl('Wobble Vertical'),
					'wobble-horizontal' => __pl('Wobble Horizontal'),
					'wobble-bottom-to-right' => __pl('Wobble Bottom To Right'),
					'wobble-top-to-right' => __pl('Wobble Top To Right'),
					'wobble-top' => __pl('Wobble Top'),
					'wobble-bottom' => __pl('Wobble Bottom'),
					'wobble-skew' => __pl('Wobble Skew'),
				],
				'show' => array(
					'icon_hover' => 'hover',
				),
			),
			'icon_rotate_hover' => array(
				'type' => 'spinner',
				'label' => __pl('service_box_icon_rotate'),
				'default' => 0,
				'css' => ['{{element}} i:hover' => 'transform: rotate({{val}}deg)'],
				'min' => 0,
				'max' => 360,
				'step' => 1,
				'screen' => 1,
				'show' => array(
					'icon_hover' => 'hover'
				),
			),
			'bg_color_hover' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_background_color_label'),
				'css' => ['{{element}} i:hover' => 'background-color: {{val}};'],
				'req' => array(
					'!bg_shape' => ''
				),
				'show' => array(
					'icon_hover' => 'hover'
				),
			),
			'icon_background_size_hover' => array(
				'type' => 'spinner',
				'label' => __pl('service_box_icon_background_size'),
				'css' => ['{{element}} i:hover' => 'padding: calc(0.5em + {{val}}px)'],
				'min' => 1,
				'max' => 500,
				'step' => 1,
				'screen' => 1,
				'req' => array(
					'!bg_shape' => ''
				),
				'show' => array(
					'icon_hover' => 'hover'
				),
			)
		],
		'border_style' => [
			'icon_border_hover' => array(
				'type' => 'radio',
				'label' => '',
				'default' => '',
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				),
			),
			'icon_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} i' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'icon_border_hover' => ''
				),
			),
			'icon_border_color' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} i' => 'border-color: {{val}};'],
				'req' => array(
					'!icon_border_type' => ''
				),
				'show' => array(
					'icon_border_hover' => ''
				),
			),
			'icon_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} i' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!icon_border_type' => ''
				],
				'show' => array(
					'icon_border_hover' => ''
				),
			),
			'icon_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} i' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!icon_border_type' => ''
				),
				'show' => array(
					'icon_border_hover' => ''
				),
			),
			'icon_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} i:hover' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'icon_border_hover' => 'hover'
				),
			),
			'icon_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'css' => ['{{element}} i:hover' => 'border-color: {{val}};'],
				'default' => '#3e8ef7',
				'req' => array(
					'!icon_border_type_hover' => ''
				),
				'show' => array(
					'icon_border_hover' => 'hover'
				),
			),
			'icon_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} i:hover' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!icon_border_type_hover' => ''
				],
				'show' => array(
					'icon_border_hover' => 'hover'
				),
			),
			'icon_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} i:hover' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!icon_border_type_hover' => ''
				),
				'show' => array(
					'icon_border_hover' => 'hover'
				),
			),
		],
		'styles' => [
			'icon_style' => __pl('icon_style_hover'),
			'border_style' => __pl('border'),
		]
	)
);

// Badge
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_badge', array(
		'name' => __pl('Badge'),
		'group' => 'text',
		'func' => 'pagelayer_sc_badge',
		'innerHTML' => 'title',
		'html' => '<div class="pagelayer-badge-span">
					<span if="{{text}}" class="pagelayer-badge-text">{{text}}</span>
					<a if-ext="{{badge_url}}" class="pagelayer-ele-link" href="{{badge_url}}">
						<span if="{{badge_text}}" class="pagelayer-badge-title pagelayer-badge-details pagelayer-badge-{{badge_notification_type}} pagelayer-badge-{{badge_style_type}}">{{badge_text}}</span>
					</a>
				</div>
				<a if-ext="{{badge_url}}" class="pagelayer-ele-link" href="{{badge_url}}">
					<button class="pagelayer-badge-title pagelayer-badge-btn pagelayer-btn-{{badge_btn_type}}">
						<span if="{{text}}" class="pagelayer-badge-text">{{text}}</span>
						<span if="{{badge_text}}" class="pagelayer-badge-details pagelayer-badge-{{badge_notification_type}} pagelayer-badge-{{badge_style_type}}">{{badge_text}}</span>
					</button>
				</a>',
		'params' => array(
			'badge_text' => array(
				'type' => 'text',
				'label' => __pl('badge_text'),
				'default' => 'Badge',				
			),
			'badge_url' => array(
				'type' => 'link',
				'label' => __pl('badge_url_label'),
				'default' => '',
			),
			'badge_notification_type' => array(
				'type' => 'select',
				'label' => __pl('badge_notification_type'),
				'default' => 'primary',
				'list' => [					
					'primary' => __pl('Primary'),
					'secondary' => __pl('Secondary'),
					'success' => __pl('Success'),
					'warning' => __pl('Warning'),
					'danger' => __pl('Danger'),
					'info' => __pl('Info'),
					'light' => __pl('Light'),
					'dark' => __pl('Dark'),
					'custom' => __pl('Custom'),
				],				
			),
			'badge_style_type' => array(
				'type' => 'select',
				'label' => __pl('badge_style'),
				'default' => 'normal',
				'list' => [					
					'normal' => __pl('Normal'),
					'pills' => __pl('Pills'),					
				],				
			),
			'badge_vertical_align' => array(
				'type' => 'select',
				'label' => __pl('badge_vertical_align'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-badge-details' => 'vertical-align:{{val}};'],
				'list' => [
					'' => __pl('none'),
					'top' => __pl('Top'),					
					'bottom' => __pl('Bottom'),
				],
				'req' => array(
					'badge_button' => '',
				)							
			),
		),
		'text_style' => [
			'text' => array(
				'type' => 'text',
				'label' => __pl('text'),
				'default' => 'Your custom text',
			),
			'text_color' => array(
				'type' => 'color',
				'label' => __pl('badge_text_color_label'),
				'default' => '#000000',
				'css' => ['{{element}} .pagelayer-badge-text' => 'color:{{val}};'],											
			),
			'text_style' => array(
				'type' => 'typography',
				'label' => __pl('text_size'),
				'default' => ',25,,400,,,,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-badge-text' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		],
		'badge_style' => [
			'badge_text_color' => array(
				'type' => 'color',
				'label' => __pl('badge_text_color_label'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-badge-custom' => 'color:{{val}};'],				
				'req' => ['badge_notification_type' => 'custom'],
			),
			'badge_spacing' => array(
				'type' => 'slider',
				'label' => __pl('badge_spacing'),
				'default' => 2,
				'min' => 1,
				'max' => 100,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-badge-details' => 'margin-left:{{val}}px;'],
			),
			'badge_background_color' => array(
				'type' => 'color',
				'label' => __pl('badge_text_background_label'),
				'default' => '#4982eeff',
				'css' => ['{{element}} .pagelayer-badge-custom' => 'background-color:{{val}};'],
				'req' => ['badge_notification_type' => 'custom'],
			),
			'badge_vspacing' => array(
				'type' => 'slider',
				'label' => __pl('quote_vertical_padding'),
				'default' => 2,
				'min' => 0,
				'max' => 100,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-badge-details' => 'padding-top:{{val}}px; padding-bottom:{{val}}px;'],
			),
			'badge_hspacing' => array(
				'type' => 'slider',
				'label' => __pl('horizontal_spacing'),
				'default' => 2,
				'min' => 0,
				'max' => 100,
				'screen' => 1,				
				'css' => ['{{element}} .pagelayer-badge-details' => 'padding-left:{{val}}px; padding-right:{{val}}px;'],
			),
			'badge_text_style' => array(
				'type' => 'typography',
				'label' => __pl('badge_text_size'),
				'default' => ',16,,400,,,,,,,',
				'css' => ['{{element}} .pagelayer-badge-details' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),									
		],
		'btn_badge' => [
			'badge_button' => array(
				'type' => 'checkbox',
				'label' => __pl('button_badge'),
				'css' => ['{{element}} .pagelayer-badge-btn '=> 'display : block;',
					'{{element}} .pagelayer-badge-span'=> 'display : none;',
				],						
			),
			'badge_btn_type' => array(
				'type' => 'select',
				'label' => __pl('badge_btn_type'),
				'default' => 'warning',
				'list' => [					
					'primary' => __pl('Primary'),
					'secondary' => __pl('Secondary'),
					'success' => __pl('Success'),
					'warning' => __pl('Warning'),
					'danger' => __pl('Danger'),
					'info' => __pl('Info'),
					'light' => __pl('Light'),
					'dark' => __pl('Dark'),
					'custom' => __pl('Custom'),
				],
				'req' => array(
					'badge_button' => 'true',
				)				
			),
			'badge_btn_hover' => array(
				'type' => 'radio',
				'label' => '',
				'default' => '',
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				),
				'req' => array(
					'badge_button' => 'true',
					'badge_btn_type' => 'custom',
				),
			),						
			'badge_btn_background_color' => array(
				'type' => 'color',
				'label' => __pl('badge_btn_background_label'),
				'default' => '#4982eeff',
				'css' => ['{{element}} .pagelayer-badge-btn' => 'background-color:{{val}};'],
				'req' => [
					'badge_btn_type' => 'custom',
					'badge_button' => 'true',
				],
				'show' => ['badge_btn_hover' => ''],
			),					
			'badge_btn_background_color_hover' => array(
				'type' => 'color',
				'label' => __pl('badge_btn_background_label'),
				'default' => '#4982eeff',
				'css' => ['{{element}} .pagelayer-badge-btn:hover' => 'background-color:{{val}};'],			
				'show' => ['badge_btn_hover' => 'hover'],
			),
		],
		'styles' => [
			'text_style' => __pl('text'),
			'badge_style' => __pl('badge_style'),
			'btn_badge' => __pl('btn_badge'),
		]
	)
);

// Tooltip
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_tooltip', array(
		'name' => __pl('Tooltip'),
		'group' => 'text',
		'func' => 'pagelayer_sc_tooltip',
		'innerHTML' => 'tooltip_text',
		'html' => '<div class="pagelayer-tooltip-container">
					<span if="{{tooltip_icon}}" class="pagelayer-tooltip-icon"><i class="fa fa-{{tooltip_icon}}"></i></span>
					<span if="{{text}}" class="pagelayer-tooltip-title">{{text}}</span>
					<div if="{{tooltip_text}}" class="pagelayer-tooltip-text pagelayer-tooltip-{{tooltip_position}}">
						<span>{{tooltip_text}}</span>
					</div>
				</div>',
		'params' => array(
			'text' => array(
				'type' => 'text',
				'label' => __pl('text'),
				'default' => __pl('hover_me'),
				'edit' => '.pagelayer-tooltip-title',
			),
			'tooltip_text' => array(
				'type' => 'editor',
				'label' => __pl('tooltip_text'),
				'default' => 'Hey there, I have an amazing tooltip !',
				'edit' => '.pagelayer-tooltip',
			),
		),
		'text_style' => [
			'tooltip_align' => array(
				'label' => __pl('tooltip_align'),
				'type' => 'select',
				'default' => 'center',
				'screen' => 1,
				'css' => 'text-align: {{val}};',
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				)
			),
			'text_color' => array(
				'type' => 'color',
				'label' => __pl('tooltip_title_color'),
				'css' => ['{{element}} .pagelayer-tooltip-title' => 'color:{{val}};'],				
			),
			'text_size' => array(
				'type' => 'typography',
				'label' => __pl('tooltip_title_size'),
				'default' => ',25,,400,,,,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-tooltip-title' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
				'{{element}} .pagelayer-tooltip-icon .fa' => 'font-size: {{val[1]}}px !important;'],
			),
			'tooltip_text_shadow' => array(
				'type' => 'shadow',
				'label' => __pl('tooltip_text_shadow'),
				'css' => ['{{element}} .pagelayer-tooltip-title' => 'text-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}};',
				'{{element}} .pagelayer-tooltip-icon .fa' => 'text-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}};'],
			),
		],
		'tooltip_style' => [
			'tooltip_position' => array(
				'type' => 'select',
				'label' => __pl('tooltip_positon'),
				'default' => 'top',
				'list' => [
					'top' => __pl('Top'),					
					'right' => __pl('Right'),
					'bottom' => __pl('Bottom'),
					'left' => __pl('Left'),
				],											
			),
			'tooltip_width' => array(
				'label' => __pl('tooltip-width'),
				'type' => 'slider',
				'min' => 100,
				'max' => 500,
				'default' => 200,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-tooltip-text' => 'width:{{val}}px;'],
			),
			'tooltip_spacing' => array(
				'label' => __pl('tooltip_spacing'),
				'type' => 'slider',
				'min' => 0,
				'max' => 100,
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-tooltip-text' => 'padding:{{val}}px;'],
			),
			'tooltip_background' => array(
				'type' => 'color',
				'label' => __pl('tooltip_background_color'),
				'default' => '#333333',
				'css' => ['{{element}} .pagelayer-tooltip-text' => 'background-color:{{val}};',
					'{{element}} .pagelayer-tooltip-top:after' => 'border-top-color:{{val}};',
					'{{element}} .pagelayer-tooltip-right:after' => 'border-right-color:{{val}};',
					'{{element}} .pagelayer-tooltip-bottom:after' => 'border-bottom-color:{{val}};',
					'{{element}} .pagelayer-tooltip-left:after' => 'border-left-color:{{val}};',
				],				
			),
			'tooltip_color' => array(
				'type' => 'color',
				'label' => __pl('tooltip_text_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-tooltip-text' => 'color:{{val}};'],				
			),
			'tooltip_text_size' => array(
				'type' => 'typography',
				'label' => __pl('tooltip_text_size'),
				'default' => ',18,,400,,,,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-tooltip-text' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'tooltip_shadow' => array(
				'type' => 'shadow',
				'label' => __pl('tooltip_shadow'),
				'css' => ['{{element}} .pagelayer-tooltip-text' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}};'],
			),
		],
		'icon_style' => [
			'show_icon' => array(
				'type' => 'checkbox',
				'label' => __pl('show_icon'),										
			),
			'tooltip_icon' => array(
				'type' => 'icon',
				'label' => __pl('tooltip_icon'),
				'default' => 'exclamation-circle',
				'req' => array(
					'show_icon' => 'true',
				)	
			),
			'icon_color' => array(
				'type' => 'color',
				'label' => __pl('tooltip_icon_color'),
				'default' => '#3E8EF7',
				'css' => ['{{element}} .pagelayer-tooltip-icon' => 'color:{{val}};'],
				'req' => array(
					'show_icon' => 'true',
				)
			),
			'icon_spacing' => array(
				'label' => __pl('icon_space'),
				'type' => 'slider',
				'min' => 0,
				'max' => 100,
				'default' => 4,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-tooltip-icon' => 'margin-left:{{val}}px; margin-right:{{val}}px;'],
				'req' => array(
					'show_icon' => 'true',
				)
			),
			'tooltip_icon_alignment' => array(
				'label' => __pl('tooltip_icon_alignment'),
				'type' => 'radio',
				'default' => 'right',
				'css' => ['{{element}} .pagelayer-tooltip-icon' => 'float: {{val}};'],
				'list' => array(
					'left' => __pl('left'),
					'right' => __pl('right'),
				),
				'req' => array(
					'show_icon' => 'true',
				)
			)
		],
		'styles' => [
			'icon_style' => __pl('icon'),
			'text_style' => __pl('text_style'),
			'tooltip_style' => __pl('tooltip_style'),
		]
	)
);


////////////////////////
// Image Group
////////////////////////

// Image
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_image', array(
		'name' => __pl('image'),
		'group' => 'image',
		'func' => 'pagelayer_sc_image',
		'innerHTML' => 'text',
		'html' => '<div class="pagelayer-image-holder">
			<a if-ext="{{link_type}}" class="pagelayer-ele-link" href="{{func_link}}" pagelayer-image-link-type="{{link_type}}">
				<img class="pagelayer-img" src="{{func_id}}" title="{{{id-title}}}" alt="{{{id-alt}}}" />
				<div if="{{overlay}}" class="pagelayer-image-overlay {{content_position}}">
					<div class="pagelayer-image-overlay-content">
						<i if="{{icon}}" class="pagelayer-image-overlay-icon fa fa-{{icon}}"></i>
						<div if="{{text}}" class="pagelayer-image-overlay-text">{{text}}</div>
					</div>
				</div>
			</a>
		</div>
		<p if="{{caption}}" class="pagelayer-image-caption">{{caption}}</p>',
		'params' => array(
			'id' => array(
				'label' => __pl('image_src_label'),
				'desc' => __pl('image_src_desc'),
				'type' => 'image',
				'default' => PAGELAYER_URL.'/images/default-image.png',
			),
			'id-size' => array(
				'label' => __pl('obj_image_size_label'),
				'type' => 'select',
				'default' => 'full',
				'list' => array(
					'full' => __pl('full'),
					'large' => __pl('large'),
					'medium' => __pl('medium'),
					'thumbnail' => __pl('thumbnail'),
					'custom' => __pl('custom')
				)
			),
			'custom_size' => array(
				'label' => __pl('image_custom_size_label'),
				'type' => 'text',
				'default' => '100x100',
				'sep' => 'x',
				'css' => ['{{element}} img' => 'width: {{val[0]}}px; height: {{val[1]}}px;'],
				'req' => array(
					'id-size' => 'custom'
				),
			),
			'align' => array(
				'label' => __pl('obj_align_label'),
				'type' => 'radio',
				'default' => 'center',
				'addAttr' => 'align="{{align}}"',
				'css' => ['{{element}} .pagelayer-image-holder' => 'text-align: {{val}}', '{{element}} .pagelayer-image-holder .pagelayer-image-overlay-content' => 'text-align: {{val}}'],
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				)
			),
			'max-width' => array(
				'label' => __pl('max-width-percent'),
				'type' => 'slider',
				'min' => 0,
				'max' => 100,
				'screen' => 1,
				'css' => ['{{element}} img' => 'max-width: {{val}}%'],
			),
			'img_filter' => array(
				'type' => 'filter',
				'label' => __pl('filter'),
				'default' => '0,100,100,0,0,100,100',
				'css' => ['{{element}} img' => 'filter: blur({{val[0]}}px) brightness({{val[1]}}%) contrast({{val[2]}}%) grayscale({{val[3]}}%) hue-rotate({{val[4]}}deg) opacity({{val[5]}}%) saturate({{val[6]}}%)'],
			),
			'img_shadow' => array(
				'type' => 'shadow',
				'label' => __pl('shadow'),
				'screen' => 1,
				'css' => ['{{element}} img' => 'box-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}} !important;'],
			),
		),
		// Image related Styles
		'link_settings' => [
			'link_type' => array(
				'label' => __pl('image_link_label'),
				'type' => 'select',
				'default' => '',
				'list' => array(
					'' => __pl('none'),
					'custom_url' => __pl('custom_url'),
					'media_file' => __pl('media_file'),
					'lightbox' => __pl('lightbox')
				)
			),
			'link' => array(
				'label' => __pl('image_link_label'),
				'desc' => __pl('image_link_desc'),
				'type' => 'link',
				'req' => array(
					'link_type' => 'custom_url'
				)
			),
			'rel' => array(
				'label' => __pl('image_rel_label'),
				'type' => 'text',
				'default' => '',
				'addAttr' => ['{{element}} a' => 'rel="{{rel}}"'],
				'req' => array(
					'link_type' => 'media_file'
				)
			),
			'target' => array(
				'label' => __pl('open_link_in_new_window'),
				'type' => 'checkbox',
				'addAttr' => ['{{element}} a' => 'target="_blank"'],
				'req' => array(
					'link_type' => ['custom_url', 'media_file']
				)
			),
		],
		// Caption related Styles
		'caption_style' => [
			'caption' => array(
				'label' => __pl('gallery_grid_caption_label'),
				'desc' => __pl('gallery_grid_caption_desc'),
				'type' => 'text',
				'edit' => '.pagelayer-image-caption'
			),
			'caption_color' => array(
				'label' => __pl('Caption Color'),
				'type' => 'color',
				'default' => '#3E8EF7',
				'css' => ['{{element}} .pagelayer-image-caption' => 'color: {{val}}'],
			)
		],
		'overlay_style' => [
			'overlay' => array(
				'label' => __pl('image_overlay_effect_label'),
				'desc' => __pl('image_overlay_effect_desc'),
				'type' => 'checkbox',
			),
			'icon' => array(
				'label' => __pl('icon'),
				'type' => 'icon',
				'default' => 'star',
				'req' => array(
					'overlay' => 'true'
				)
			),
			'icon_color' => array(
				'label' => __pl('icon_color'),
				'type' => 'color',
				'default' => '#e6cf03',
				'css' => ['{{element}} .pagelayer-image-overlay-icon' => 'color: {{val}}'],
				'req' => array(
					'overlay' => 'true'
				)
			),
			'icon_size' => array(
				'label' => __pl('icon_custom_size'),
				'desc' => __pl('icon_custom_size_desc'),
				'type' => 'spinner',
				'min' => 0,
				'step' => 1,
				'max' => 500,
				'default' => 50,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-image-overlay-icon' => 'font-size: {{val}}px'],
				'req' => array(
					'overlay' => 'true'
				)
			),
			'text' => array(
				'label' => __pl('content'),
				'type' => 'editor',
				'default' => '<p>Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.</p>',
				'text' => __pl('open_in_pleditor'),
				'req' => array(
					'overlay' => 'true'
				)
			),		
			'overlay_bg' => array(
				'label' => __pl('image_overlay_background'),
				'type' => 'color',
				'default' => 'rgba(0,0,0,.6)',
				'css' => ['{{element}} .pagelayer-image-overlay' => 'background: {{val}}'],
				'req' => array(
					'overlay' => 'true'
				)
			),
			'content_position' => array(
				'label' => __pl('Overlay Content Position'),
				'type' => 'radio',
				'default' => 'center',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-image-overlay' => 'display:-webkit-flex;display:flex;-webkit-align-items:{{val}}; align-items:{{val}};'],
				'list' => array(
					'flex-start' => __pl('Top'),
					'center' => __pl('Middle'),
					'flex-end' => __pl('Bottom'),
				),
				'req' => array(
					'overlay' => 'true'
				)
			),
			'show_always' => array(
				'label' => __pl('image_show_always'),
				'type' => 'checkbox',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-image-overlay' => 'opacity:1;'],
				'req' => array(
					'overlay' => 'true'
				)
			)
		],
		'styles' => [
			'link_settings' => __pl('link_settings'),
			'caption_style' => __pl('caption_style'),
			'overlay_style' => __pl('overlay_style'),
		],
	)
);

// Image Slider
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_image_slider', array(
		'name' => __pl('Image Slider'),
		'group' => 'image',
		'html' => '<div class="pagelayer-image-slider-div">
			<ul class="pagelayer-image-slider-ul pagelayer-owl-holder pagelayer-owl-carousel pagelayer-owl-theme">{{ul}}</ul>
		</div>',
		'func' => 'pagelayer_sc_image_slider',
		'settings' => [
			'params' => __pl('Image Slider'),
			'slider_options' => __pl('slider_options'),
		],
		'params' => array(
			'ids' => array(
				'type' => 'multi_image',
				'label' => __pl('image_slider_ids_label'),
				'desc' => __pl('media_library_images_ids_desc'),
				'text' => __pl('image_slider_ids_text'),
			),
			'size' => array(
				'type' => 'select',
				'label' => __pl('obj_image_size_label'),
				'default' => 'full',
				'list' => array(
					'full' => __pl('full'),
					'large' => __pl('large'),
					'medium' => __pl('medium'),
					'thumbnail' => __pl('thumbnail'),
					'custom' => __pl('custom')
				)
			),
			'custom_size' => array(
				'type' => 'dimension',
				'label' => __pl('image_custom_size_label'),
				'default' => '200,200',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-image-slider-ul' => 'width: {{val[0]}}px; height: {{val[1]}}px;'],
				'req' => array(
					'size' => 'custom'
				),
			),
			'link_type' => array(
				'label' => __pl('image_link_label'),
				'type' => 'select',
				'list' => array(
					'' => __pl('none'),
					'custom_url' => __pl('custom_url'),
					'media_file' => __pl('media_file'),
				)
			),
			'link' => array(
				'label' => __pl('image_link_url'),
				'desc' => __pl('image_link_desc'),
				'type' => 'link',
				'req' => array(
					'link_type' => 'custom_url'
				)
			),
			'target' => array(
				'label' => __pl('open_link_in_new_window'),
				'type' => 'checkbox',
				'addAttr' => ['{{element}} a' => 'target="_blank"'],
				'req' => array(
					'link_type' => ['custom_url', 'media_file']
				)
			),
		),
		'slider_options' => [
			'slide_items' => array(
				'type' => 'spinner',
				'label' => __pl('number_of_items'),
				'min' => 1,
				'step' => 1,
				'max' => 10,
				'default' => 1,
				'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-items="{{slide_items}}"'],
			),
			'slider_animation' => array(
				'type' => 'select',
				'label' => __pl('animation_in'),
				'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-animate-in="{{slider_animation}}"'],
				'list' => $pagelayer->anim_in_options,
				'req' => ['slide_items' => '1']
			),
			'slideout_anim' => array(
				'type' => 'select',
				'label' => __pl('animation_out'),
				'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-animate-out="{{slideout_anim}}"'],
				'list' => $pagelayer->anim_out_options,
				'req' => ['slide_items' => '1']
			),
			'controls' => array(
				'type' => 'select',
				'label' => __pl('slider_controls'),
				'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-controls="{{controls}}"'],
				'list' => array(
					'' => __pl('Arrows and Pager'),
					'arrows' => __pl('Arrows'),
					'pager' => __pl('Pager'),
					'none' => __pl('none'),
				)
			),
			'pause' => array(
				'type' => 'slider',
				'label' => __pl('image_slider_slideshow_speed_label'),
				'default' => 5000,
				'min' => 200,
				'max' => 20000,
				'step' => 100,
				'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-autoplay-timeout="{{pause}}"'],
			),
			'speed' => array(
				'type' => 'slider',
				'label' => __pl('slider_animation_speed'),
				'addAttr' => ['.pagelayer-image-slider-ul' => 'data-slides-smart-speed="{{speed}}"'],
				'default' => 800,
				'min' => 200,
				'max' => 10000,
				'step' => 100
			),
			'loop' => array(
				'type' => 'checkbox',
				'label' => __pl('image_slider_loop'),
				'desc' => __pl('image_slider_loop_desc'),
				'default' => 'true',
				'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-loop="{{loop}}"'],
			),
			'adaptive_height' => array(
				'type' => 'checkbox',
				'label' => __pl('slider_height'),
				'desc' => __pl('slider_height_desc'),
				'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-auto-height="{{adaptive_height}}"'],
			),
			'auto' => array(
				'type' => 'checkbox',
				'label' => __pl('image_slider_auto'),
				'desc' => __pl('image_slider_auto_desc'),
				'default' => 'true',
				'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-autoplay="{{auto}}"'],
			),
			'auto_hover' => [
				'type' => 'checkbox',
				'label' => __pl('auto_hover'),
				'desc' => __pl('auto_hover_desc'),
				'default' => 'true',
				'addAttr' => ['{{element}} .pagelayer-owl-holder' => 'data-slides-autoplay-hover-pause="{{auto_hover}}"'],
			],
		],
		'arrow_styles' => $pagelayer->slider_arrow_styles,
		'pager_styles' => $pagelayer->slider_pager_styles,
		'styles' => [
			'slider_options' => __pl('slider_options'),
			'arrow_styles' => __pl('arrow_styles'),
			'pager_styles' => __pl('pager_styles'),
		],
	)
);

// Grid Gallery
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_grid_gallery', array(
		'name' => __pl('Grid Gallery'),
		'group' => 'image',
		'func' => 'pagelayer_sc_grid_gallery',
		'html' =>	'<div class="pagelayer-grid-gallery-container">
					{{ul}}
		</div>',
		'params' => array(
			'ids' => array(
				'type' => 'multi_image',
				'label' => __pl('grid_gallery_images'),
				'desc' => __pl('media_library_images_ids_desc'),
				'text' => __pl('image_slider_ids_text'),
			),
			'columns' => array(
				'type' => 'select',
				'label' => __pl('columns_count'),
				'default' => 3,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-grid-gallery-ul' => 'display: grid; grid-template-columns: repeat({{val}},1fr);'],
				'list' => array(
					1 => __pl('1'),
					2 => __pl('2'),
					3 => __pl('3'),
					4 => __pl('4'),
					5 => __pl('5'),
					6 => __pl('6'),
					7=> __pl('7'),
					8 => __pl('8'),
					9 => __pl('9'),
					10 => __pl('10')
				)
			),
			'col_gap' => array(
				'type' => 'slider',
				'label' => __pl('col_gap'),
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 0,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-grid-gallery-ul' => 'grid-column-gap: {{val}}px;'],
			),
			'row_gap' => array(
				'type' => 'slider',
				'label' => __pl('row_gap'),
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 0,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-grid-gallery-ul' => 'grid-row-gap: {{val}}px;'],
			),
			'size' => array(
				'type' => 'select',
				'label' => __pl('obj_image_size_label'),
				'default' => 'thumbnail',
				'list' => array(
					'full' => __pl('full'),
					'large' => __pl('large'),
					'medium' => __pl('medium'),
					'thumbnail' => __pl('thumbnail'),
					'custom' => __pl('custom')
				)
			),
			'custom_size' => array(
				'type' => 'dimension',
				'desc' => __pl('image_custom_size_label'),
				'req' => array(
					'size' => 'custom'
				),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-gallery-item img' => 'height: {{val[0]}}px; width: {{val[1]}}px;'],
			),
			'link_to' => array(
				'type' => 'select',
				'label' => __pl('image_link_label'),
				'default' => 'lightbox',
				'list' => array(
					'' => __pl('none'),
					'media_file' => __pl('media_file'),
					'attachment' => __pl('attachment_page'),
					'lightbox' => __pl('lightbox'),
				)
			),
			'rel' => array(
				'type' => 'text',
				'label' => __pl('image_rel_label'),
				'default' => '',
				'addAttr' => ['{{element}} a' => 'rel="{{rel}}"'],
				'req' => array(
					'link_to' => 'media_file'
				)
			),
			'target' => array(
				'type' => 'checkbox',
				'label' => __pl('open_link_in_new_window'),
				'addAttr' => ['{{element}} a' => 'target="_blank"'],
				'req' => array(
					'!link_to' => ['lightbox', '']
				)
			),
			'caption' => array(
				'type' => 'checkbox',
				'label' => __pl('gallery_grid_caption_label'),
				'desc' => __pl('gallery_grid_caption_desc'),
				'default' => '',
			),
			'align' => array(
				'label' => __pl('obj_align_label'),
				'type' => 'radio',
				'default' => 'left',
				'addAttr' => 'align="{{align}}"',
				'css' => ['{{element}} .pagelayer-grid-gallery-container' => 'text-align: {{val}}', '{{element}} .pagelayer-grid-gallery-container .pagelayer-grid-gallery-ul' => 'text-align: {{val}}'],
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				)
			),
			'caption_color' => array(
				'label' => __pl('Caption Color'),
				'type' => 'color',
				'default' => '#e6cf03',
				'css' => ['{{element}} .pagelayer-grid-gallery-caption' => 'color: {{val}}'],
				'req' => array(
					'caption' => 'true'
				)
			)
		)
	)
);



////////////////////////
// Button Group
////////////////////////

// Button
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_btn', array(
		'name' => __pl('button'),
		'group' => 'button',
		'func' => 'pagelayer_sc_btn',
		'html' => '<a class="pagelayer-btn-holder pagelayer-ele-link {{type}} {{size}} {{icon_position}}">
					<i if="{{icon}}" class="fa fa-{{icon}} pagelayer-btn-icon"></i>
					<span if="{{text}}" class="pagelayer-btn-text">{{text}}</span>
					<i if="{{icon}}" class="fa fa-{{icon}} pagelayer-btn-icon"></i>
				</a>',
		'params' => array(
			'text' => array(
				'type' => 'text',
				'label' => __pl('button_text_label'),
				'default' => __pl('button_name')
			),
			'link' => array(
				'type' => 'link',
				'label' => __pl('button_link_label'),
				'desc' => __pl('button_link_desc'),
				'addAttr' => ['{{element}} .pagelayer-btn-holder' => 'href="{{link}}"']
			),
			'target' => array(
				'type' => 'checkbox',
				'label' => __pl('open_link_in_new_window'),
				'addAttr' => ['{{element}} a' => 'target="_blank"']
			),
			'full_width' => array(
				'type' => 'checkbox',
				'label' => __pl('stretch'),
				'screen' => 1,
				'css' => ['{{element}} a' => 'width: 100%; text-align: center;']
			),
			'btn_typo' => array(
				'type' => 'typography',
				'label' => __pl('quote_content_typo'),
				'screen' => 1,
				'css' => [
					'{{element}} .pagelayer-btn-text' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
					'{{element}} .pagelayer-btn-holder' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;',
				],
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('obj_align_label'),
				'default' => 'left',
				'screen' => 1,
				'css' => 'text-align: {{val}}',
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				),
				'req' => array(
					'full_width' => ''
				)
			),
		),
		'icon_style' => [
			'icon' => array(
				'type' => 'icon',
				'label' => __pl('service_box_font_icon_label'),
				'default' => '',
			),
			'icon_position' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'pagelayer-btn-icon-left',
				'list' => array(
					'pagelayer-btn-icon-left' => __pl('left'),
					'pagelayer-btn-icon-right' => __pl('right')
				),
			),
			'icon_spacing' => array(
				'type' => 'slider',
				'label' => __pl('icon_spacing'),
				'min' => 1,
				'step' => 1,
				'max' => 100,
				'default' => 5,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-icon' => 'padding: 0 {{val}}px;'],
				'req' => array(
					'!icon' => 'none'
				),
			),
		],
		'btn_style' => [
			'type' => array(
				'type' => 'select',
				'label' => __pl('button_type_label'),
				'default' => 'pagelayer-btn-default',
				//'addClass' => ['{{element}} .pagelayer-btn-holder' => '{{val}}'],
				'list' => array(
					'pagelayer-btn-default' => __pl('btn_type_default'),
					'pagelayer-btn-primary' => __pl('btn_type_primary'),
					'pagelayer-btn-secondary' => __pl('btn_type_secondary'),
					'pagelayer-btn-success' => __pl('btn_type_success'),
					'pagelayer-btn-info' => __pl('btn_type_info'),
					'pagelayer-btn-warning' => __pl('btn_type_warning'),
					'pagelayer-btn-danger' => __pl('btn_type_danger'),
					'pagelayer-btn-dark' => __pl('btn_type_dark'),
					'pagelayer-btn-light' => __pl('btn_type_light'),
					'pagelayer-btn-link' => __pl('btn_type_link'),
					'pagelayer-btn-custom' => __pl('btn_type_custom')
				),
			),
			'size' => array(
				'type' => 'select',
				'label' => __pl('button_size_label'),
				'default' => 'pagelayer-btn-large',
				'list' => array(
					'pagelayer-btn-mini' => __pl('mini'),
					'pagelayer-btn-small' => __pl('small'),
					'pagelayer-btn-large' => __pl('large'),
					'pagelayer-btn-extra-large' => __pl('extra_large'),
					'pagelayer-btn-double-large' => __pl('double_large'),
					'pagelayer-btn-custom' => __pl('custom'),
				)
			),
			'btn_custom_size' => array(
				'type' => 'spinner',
				'label' => __pl('btn_custom_size'),
				'min' => 1,
				'step' => 1,
				'max' => 100,
				'default' => 5,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-holder' => 'padding: calc({{val}}px / 2) {{val}}px;'],
				'req' => array(
					'size' => 'pagelayer-btn-custom'
				),
			),
			'btn_hover' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'default' => '',
				//'no_val' => 1,// Dont set any value to element
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				),
				'req' => array(
					'type' => 'pagelayer-btn-custom',
				),
			),
			'btn_bg_color' => array(
				'type' => 'color',
				'label' => __pl('btn_bg_color_label'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-btn-holder' => 'background-color: {{val}};'],
				'req' => array(
					'type' => 'pagelayer-btn-custom',
				),
				'show' => array(
					'btn_hover' => ''
				),
			),
			'btn_color' => array(
				'type' => 'color',
				'label' => __pl('btn_color_label'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-btn-holder' => 'color: {{val}};'],
				'req' => array(
					'type' => 'pagelayer-btn-custom',
				),
				'show' => array(
					'btn_hover' => ''
				),
			),
			'btn_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('btn_hover_delay_label'),
				'desc' => __pl('btn_hover_delay_desc'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-btn-holder' => '-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;'],
				'show' => array(
					'btn_hover' => 'hover'
				),
			),
			'btn_bg_color_hover' => array(
				'type' => 'color',
				'label' => __pl('btn_bg_color_hover_label'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'background-color: {{val}};'],
				'req' => array(
					'type' => 'pagelayer-btn-custom',
				),
				'show' => array(
					'btn_hover' => 'hover'
				),
			),
			'btn_color_hover' => array(
				'type' => 'color',
				'label' => __pl('btn_color_hover_label'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'color: {{val}};'],
				'req' => array(
					'type' => 'pagelayer-btn-custom',
				),
				'show' => array(
					'btn_hover' => 'hover'
				),
			),
		],
		'border_style' => [
			'btn_bor_hover' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'default' => '',
				//'no_val' => 1,// Dont set any value to element
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				)
			),	
			'btn_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-btn-holder' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'btn_bor_hover' => ''
				),
			),
			'btn_border_color' => array(
				'type' => 'color',
				'label' => __pl('border_color_label'),
				'default' => '#42414f',
				'css' => ['{{element}} .pagelayer-btn-holder' => 'border-color: {{val}};'],
				'req' => array(
					'!btn_border_type' => ''
				),
				'show' => array(
					'btn_bor_hover' => ''
				),
			),
			'btn_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-holder' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!btn_border_type' => ''
				],
				'show' => array(
					'btn_bor_hover' => ''
				),
			),
			'btn_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-holder' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!btn_border_type' => ''
				),
				'show' => array(
					'btn_bor_hover' => ''
				),
			),
			'btn_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => array(
					'btn_bor_hover' => 'hover'
				),
			),
			'btn_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('border_color_hover_label'),
				'default' => '#42414f',
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-color: {{val}};'],
				'req' => array(
					'!btn_border_type_hover' => ''
				),
				'show' => array(
					'btn_bor_hover' => 'hover'
				),
			),
			'btn_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_width_hover'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!btn_border_type_hover' => ''
				],
				'show' => array(
					'btn_bor_hover' => 'hover'
				),
			),
			'btn_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius_hover'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-btn-holder:hover' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!btn_border_type_hover' => ''
				),
				'show' => array(
					'btn_bor_hover' => 'hover'
				),
			),
		],
		'styles' => [
			'btn_style' => __pl('btn_style'),
			'icon_style' => __pl('icon'),
			'border_style' => __pl('border_style'),
		]
	)
);


// Social Profile Item
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_social', array(
		'name' => __pl('Social Profile'),
		'group' => 'button',
		'func' => 'pagelayer_sc_social',
		'not_visible' => 1,
		'html' => '<div class="pagelayer-icon-holder pagelayer-{{icon}}-icon">
					<a if-ext="{{social_url}}" class="pagelayer-ele-link" href="{{social_url}}">
						<i class="pagelayer-social-fa fa fa-{{icon}}"></i>
					</a>
				</div>',
		'params' => array(
			'icon' => array(
				'type' => 'icon',
				'label' => __pl('list_icon_label'),
				'default' => 'facebook-official',
				'list' => ['facebook', 'facebook-official', 'facebook-square', 'twitter', 'twitter-square', 'google-plus', 'google-plus-square', 'instagram', 'linkedin', 'linkedin-square', 'behance', 'behance-square', 'pinterest', 'pinterest-p', 'pinterest-square', 'reddit-alien', 'reddit-square', 'reddit', 'rss', 'rss-square', 'skype', 'slideshare', 'snapchat', 'snapchat-ghost', 'snapchat-square', 'soundcloud', 'spotify', 'stack-overflow', 'steam', 'steam-square', 'stumbleupon', 'telegram', 'thumb-tack', 'tripadvisor', 'tumblr', 'tumblr-square', 'twitch', 'vimeo', 'vimeo-square', 'vk', 'weibo', 'weixin', 'whatsapp', 'wordpress', 'xing', 'xing-square', 'yelp', 'youtube', 'youtube-square', 'youtube-play', '500px', 'flickr', 'android', 'github', 'github-square', 'gitlab', 'apple', 'jsfiddle', 'houzz', 'bitbucket', 'bitbucket-square', 'codepen', 'delicious', 'medium', 'meetup', 'mixcloud', 'dribbble', 'foursquare'],
			),
			'social_url' => array(
				'type' => 'link',
				'label' => __pl('social_url_label')
			)
		)
	)
);

// Social Profile
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_social_grp', array(
		'name' => __pl('Social Profile'),
		'group' => 'button',
		'func' => 'pagelayer_sc_social_grp',
		'has_group' => [
			'section' => 'params', 
			'prop' => 'elements'
		],
		'params' => array(
			'elements' => array(
				'type' => 'group',
				'label' => __pl('social'),
				'sc' => PAGELAYER_SC_PREFIX.'_social',
				'item_label' => array(
					'default' => __pl('social'),
					'param' => 'icon'
				),
				'count' => 3,
				'text' => strtr(__pl('add_new_item'), array('%name%' => __pl('social_name'))),
			),
		),
		'layout_style' => [
			'bg_shape' => array(
				'type' => 'select',
				'label' => __pl('icon_background_shape'),
				'default' => '',
				'css' => ['{{element}} .fa' => 'height:1em; width:1em; position: absolute; top: 50%; left: 50%; transform: translate(-50% , -50%);',
				'{{element}} .pagelayer-icon-holder' => 'position: relative; min-height: 1em; min-width: 1em;'],
				'addClass' => '{{val}}',
				'list' => array(
					'' => __pl('icon_shape_none'),
					'pagelayer-social-shape-circle' => __pl('icon_shape_circle'),
					'pagelayer-social-shape-square' => __pl('icon_shape_square'),
					'pagelayer-social-shape-rounded' => __pl('icon_shape_rounded')
				),
			),
			'bg_size' => array(
				'type' => 'spinner',
				'label' => __pl('social_grp_size_label'),
				'css' => ['{{element}} .pagelayer-icon-holder' => 'padding: calc(0.5em + {{val}}px);'],
				'min' => 1,
				'step' => 1,
				'max' => 500,
				'default' => 10,
				'screen' => 1,
				'req' => array(
					'!bg_shape' => ''
				)
			),
			'align' => array(
				'type' => 'radio',
				'label' => __pl('obj_align_label'),
				'default' => 'center',
				'css' => 'text-align: {{val}}',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right')
				)
			),
			'group_layout' => array(
				'type' => 'radio',
				'label' => __pl('layout'),
				'default' => 'pagelayer-btn-grp-horizontal',
				'screen' => 1,
				'css' => ['{{element}} > div' => 'display: inline-block;'],
				'list' => array(
					'pagelayer-btn-grp-horizontal' => __pl('horizontal'),
					'' => __pl('vertical')
				)
			),
			'icon_spacing' => array(
				'type' => 'spinner',
				'label' => __pl('icon_spacing'),
				'css' => ['{{element}} .pagelayer-social' => 'padding: {{val}}px;'],
				'min' => 0,
				'step' => 1,
				'max' => 100,
				'default' => 3,
				'screen' => 1,
			)
		],
		'icon_style' => [
			'icon_size' => array(
				'type' => 'spinner',
				'label' => __pl('social_grp_size_label'),
				'css' => ['{{element}} .pagelayer-social-fa' => 'font-size: {{val}}px;',
					'{{element}} .pagelayer-icon-holder' => 'font-size: {{val}}px;'],
				'min' => 1,
				'step' => 1,
				'max' => 500,
				'default' => 40,
				'screen' => 1,
			),
			'color_scheme' => array(
				'type' => 'select',
				'label' => __pl('color'),
				'default' => 'pagelayer-scheme-official',
				'addClass' => '{{val}}',
				'list' => array(
					'' => __pl('custom'),
					'pagelayer-scheme-official' => __pl('official')
				)
			),
			'social_hover' => array(
				'type' => 'radio',
				'label' => __pl('state'),
				'default' => '',
				//'no_val' => 1,// Dont set any value to element
				'list' => array(
					'' => __pl('normal'),
					'hover' => __pl('hover'),
				)
			),
			'icon_color' => array(
				'type' => 'color',
				'label' => __pl('social_color_label'),
				'default' => '#333333',
				'css' => ['{{element}} .pagelayer-social-fa' => 'color: {{val}} !important;'],
				'req' => array(
					'color_scheme' => ''
				),
				'show' => ['social_hover' => '']
			),
			'icon_bg_color' => array(
				'type' => 'color',
				'label' => __pl('social_bg_color_label'),
				'default' => '#3E8EF7',
				'css' => ['{{element}} .pagelayer-icon-holder' => 'background-color: {{val}} !important;'],
				'req' => array(
					'!bg_shape' => '',
					'color_scheme' => ''
				),
				'show' => ['social_hover' => '']
			),
			'icon_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-icon-holder' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['social_hover' => '']
			),
			'icon_border_color' => array(
				'type' => 'color',
				'label' => __pl('service_box_icon_border_color_label'),
				'default' => '#42414f',
				'css' => ['{{element}} .pagelayer-icon-holder' => 'border-color: {{val}};'],
				'req' => array(
					'!icon_border_type' => ''
				),
				'show' => ['social_hover' => '']
			),
			'icon_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-icon-holder' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!icon_border_type' => ''
				],
				'show' => ['social_hover' => '']
			),
			'icon_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-icon-holder' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!icon_border_type' => ''
				),
				'show' => ['social_hover' => '']
			),
			'social_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('btn_hover_delay_label'),
				'desc' => __pl('btn_hover_delay_desc'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-icon-holder' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;',
				'{{element}} .pagelayer-social-fa' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => array(
					'social_hover' => 'hover'
				),
			),
			'anim_hover' => array(
				'type' => 'select',
				'label' => __pl('icon_animation'),
				'list' => [
					'' => __pl('none'),
					'grow' => __pl('Grow'),
					'shrink' => __pl('Shrink'),
					'pulse' => __pl('Pulse'),
					'pulse-grow' => __pl('Pulse Grow'),
					'pulse-shrink' => __pl('Pulse Shrink'),
					'push' => __pl('Push'),
					'pop' => __pl('Pop'),
					'buzz' => __pl('Buzz'),
					'buzz-out' => __pl('Buzz Out'),
					'float' => __pl('Float'),
					'sink' => __pl('Sink'),
					'bob' => __pl('Bob'),
					'hang' => __pl('Hang'),
					'bounce-in' => __pl('Bounce In'),
					'bounce-out' => __pl('Bounce Out'),
					'rotate' => __pl('Rotate'),
					'grow-rotate' => __pl('Grow Rotate'),
					'skew-forward' => __pl('Skew Forward'),
					'skew-backward' => __pl('Skew Backward'),
					'wobble-vertical' => __pl('Wobble Vertical'),
					'wobble-horizontal' => __pl('Wobble Horizontal'),
					'wobble-bottom-to-right' => __pl('Wobble Bottom To Right'),
					'wobble-top-to-right' => __pl('Wobble Top To Right'),
					'wobble-top' => __pl('Wobble Top'),
					'wobble-bottom' => __pl('Wobble Bottom'),
					'wobble-skew' => __pl('Wobble Skew'),
				],
				'show' => array(
					'social_hover' => 'hover',
				),
				'addAttr' => 'pagelayer-animation="{{anim_hover}}"',
			),
			'icon_color_hover' => array(
				'type' => 'color',
				'label' => __pl('social_color_label'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-icon-holder:hover .pagelayer-social-fa' => 'color: {{val}} !important;'],
				'req' => array(
					'color_scheme' => ''
				),
				'show' => ['social_hover' => 'hover']
			),
			'icon_bg_color_hover' => array(
				'type' => 'color',
				'label' => __pl('social_bg_color_label'),
				'default' => '#3E8EF7',
				'css' => ['{{element}} .pagelayer-icon-holder:hover' => 'background-color: {{val}} !important;'],
				'req' => array(
					'!bg_shape' => '',
					'color_scheme' => ''
				),
				'show' => ['social_hover' => 'hover']
			),
			'icon_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-icon-holder:hover' => 'border-style: {{val}}'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['social_hover' => 'hover']
			),
			'icon_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('border_color_hover_label'),
				'default' => '#42414f',
				'css' => ['{{element}} .pagelayer-icon-holder:hover' => 'border-color: {{val}};'],
				'req' => array(
					'!icon_border_type_hover' => ''
				),
				'show' => ['social_hover' => 'hover']
			),
			'icon_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_width_hover'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-icon-holder:hover' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
				'req' => [
					'!icon_border_type_hover' => ''
				],
				'show' => ['social_hover' => 'hover']
			),
			'icon_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius_hover'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-icon-holder:hover' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => array(
					'!icon_border_type_hover' => ''
				),
				'show' => ['social_hover' => 'hover']
			),
		],
		'styles' => [
			'layout_style' => __pl('layout_style'),
			'icon_style' => __pl('icon'),
		]
	)
);

////////////////////////
// Media Group
////////////////////////

// Video
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_video', array(
		'name' => __pl('video'),
		'group' => 'media',
		'func' => 'pagelayer_sc_video',
		'html' => '<div class="pagelayer-video-holder pagelayer-video-{{video_ratio}}">
			<iframe if="{{src}}" id="embed_video" class="pagelayer-video-iframe" width="100%" height="auto" src="{{vid_src}}"></iframe>
			<a if-ext={{lightbox}} href="{{{src-url}}}">
				<div if={{overlay}} class="pagelayer-video-overlay" style="background-image:url({{video_overlay_image-url}});">
					<i class="fa fa-{{play_icon}}" aria-hidden="true"></i>
				</div>
			</a>
		</div>',
		'params' => array(
			'src' => array(
				'type' => 'video',
				'label' => __pl('video_src_label'),
				'default' => 'https://www.youtube.com/watch?v=VT7WfVp1owQ',
				'desc' => __pl('video_src_desc'),				
			),
			'lightbox' => array(
				'type' => 'checkbox',
				'label' => __pl('Lightbox'),
				'desc' => __pl('Open the video on Lightbox'),
				'default' => '',
			),
			'autoplay' => array(
				'type' => 'checkbox',
				'label' => __pl('Autoplay'),
				'req' => [
					'!overlay' => 'true',
					'!lightbox' => 'true',
				],						
			),
			'mute' => array(
				'type' => 'checkbox',
				'label' => __pl('Mute'),						
			),			
			'loop' => array(
				'type' => 'checkbox',
				'label' => __pl('loop'),						
			),
			'video_ratio' => array(
				'type' => 'select',
				'label' => __pl('aspect_ratio'),
				'default' => 'aspect-8-5',
				'list' => array(
					'aspect-1-1' => __pl('1:1'),
					'aspect-3-2' => __pl('3:2'),
					'aspect-4-3' => __pl('4:3'),
					'aspect-8-5' => __pl('8:5'),
					'aspect-16-9' => __pl('16:9'),
				),				
			),
		),
		'overlay_style' =>[
			'overlay' => array(
				'type' => 'checkbox',
				'label' => __pl('Overlay'),
				'desc' => __pl('Enable this option to set the picture as overlay'),
				'default' => '',
			),
			'video_overlay_image' => array(
				'type' => 'image',
				'label' =>  __pl('Custom thumbnail'),
				'default' => PAGELAYER_URL.'/images/default-image.png',
				'desc' => __pl('Use this option to set a picture from the media library'),
				'req' => array(
					'overlay' => 'true',
				),
			),
			'play_icon' => array(
				'type' => 'icon',
				'label' => __pl('list_icon_label'),
				'default' => 'play-circle',
				'req' => array(
					'overlay' => 'true'
				)
			),
			'icon_color' => array(
				'type' => 'color',
				'label' => __pl('service_heading_color'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-video-overlay i' => 'color:{{val}}'],
				'req' => array(
					'overlay' => 'true'
				)
			),
			'icon_size' => array(
				'type' => 'spinner',
				'label' => __pl('size'),
				'min' => '0',
				'max' => '700',
				'screen' => 1,
				'default' => '80',
				'css' => ['{{element}} .pagelayer-video-overlay i' => 'font-size:{{val}}px;'],
				'req' => array(
					'overlay' => 'true'
				)
			),
			'tooltip_text_shadow' => array(
				'type' => 'shadow',
				'label' => __pl('shadow'),
				'css' => ['{{element}} .pagelayer-video-overlay i' => 'text-shadow: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}};'],
				'req' => array(
					'overlay' => 'true'
				)
			),
		],
		'styles' => [
			'overlay_style' => __pl('overlay_style'),
		],
	)
);


////////////////////////
// Other Group
////////////////////////

// Service Box
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_service', array(
		'name' => __pl('Image Box'),
		'group' => 'other',
		'func' => 'pagelayer_sc_service',
		'innerHTML' => 'service_text',
		'html' => '<div class="pagelayer-service-container pagelayer-service-align-{{service_alignment}} pagelayer-service-vertical-{{service_vertical_alignment}}">
			<div if="{{service_image}}" class="pagelayer-service-image">
				<img class="pagelayer-img pagelayer-animation-{{anim_hover}}" src="{{func_image}}">
			</div>
			<div class="pagelayer-service-details">
				<div if={{service_heading}} class="pagelayer-service-heading">{{service_heading}}</div>
				<div if={{service_text}} class="pagelayer-service-text">{{service_text}}</div>
				<a if="{{service_button}}" href="{{service_button_url}}" class="pagelayer-service-btn {{service_button_type}} pagelayer-ele-link pagelayer-button {{service_button_size}}">{{service_button_text}}</a>
			</div>
		</div>',
		'params' => [
			'service_image' => array(
				'type' => 'image',
				'label' => __pl('service_box_image_icon_label'),
				'default' => PAGELAYER_URL.'/images/default-image.png',
			),
			'service_image_size' => array(
				'type' => 'radio',
				'label' => __pl('service_box_image_icon_size_label'),
				'default' => 'full',
				'list' => array(
					'full' => __pl('full'),
					'thumbnail' => __pl('thumbnail'),
					'custom' => __pl('custom'),
				)
			),
			'service_image_custom_size' => array(
				'type' => 'slider',
				'label' => __pl('service_img_custom_size_label'),
				'min' => '0',
				'max' => '2000',
				'screen' => 1,
				'default' => '200',
				'css' => ['{{element}} .pagelayer-service-image img' => 'width:{{val}}px; height: auto;'],
				'req' => array(
					'service_image_size' => 'custom',
				)
			),
			'anim_hover' => array(
				'type' => 'select',
				'label' => __pl('icon_animation'),
				'list' => [
					'' => __pl('none'),
					'grow' => __pl('Grow'),
					'shrink' => __pl('Shrink'),
					'pulse' => __pl('Pulse'),
					'pulse-grow' => __pl('Pulse Grow'),
					'pulse-shrink' => __pl('Pulse Shrink'),
					'push' => __pl('Push'),
					'pop' => __pl('Pop'),
					'buzz' => __pl('Buzz'),
					'buzz-out' => __pl('Buzz Out'),
					'float' => __pl('Float'),
					'sink' => __pl('Sink'),
					'bob' => __pl('Bob'),
					'hang' => __pl('Hang'),
					'bounce-in' => __pl('Bounce In'),
					'bounce-out' => __pl('Bounce Out'),
					'rotate' => __pl('Rotate'),
					'grow-rotate' => __pl('Grow Rotate'),
					'skew-forward' => __pl('Skew Forward'),
					'skew-backward' => __pl('Skew Backward'),
					'wobble-vertical' => __pl('Wobble Vertical'),
					'wobble-horizontal' => __pl('Wobble Horizontal'),
					'wobble-bottom-to-right' => __pl('Wobble Bottom To Right'),
					'wobble-top-to-right' => __pl('Wobble Top To Right'),
					'wobble-top' => __pl('Wobble Top'),
					'wobble-bottom' => __pl('Wobble Bottom'),
					'wobble-skew' => __pl('Wobble Skew'),
				],
			)
		],
		'service_img_style' => [		
			'service_alignment' => array(
				'type' => 'radio',
				'label' => __pl('service_box_media_alignment'),
				'default' => 'top',
				'list' => array(
					'left' => __pl('left'),
					'top' => __pl('top'),
					'right' => __pl('right'),
				),
			),
			'service_vertical_alignment' => array(
				'type' => 'radio',
				'label' => __pl('service_box_media_vertical_alignment'),
				'default' => 'top',
				'list' => array(
					'top' => __pl('top'),
					'middle' => __pl('middle'),
					'bottom' => __pl('bottom'),
				),
				'req' => ['!service_alignment' => 'top']
			),
			'service_image_spacing' => array(
				'type' => 'padding',
				'label' => __pl('service_image_spacing'),
				'css' => ['{{element}} .pagelayer-service-image img' => 'padding-top:{{val[0]}}px; padding-right:{{val[1]}}px; padding-bottom:{{val[2]}}px; padding-left:{{val[3]}}px;'],
			),
			'img_bor_state' => array(
				'type' => 'radio',
				'label' => __pl('icon_state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('Normal'),
					'hover' => __pl('Hover'),
				),
			),
			'img_border_type' => array(
				'type' => 'select',
				'label' => __pl('icon_border_type'),
				'css' => ['{{element}} .pagelayer-service-image img' =>'border-style: {{val}};'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['img_bor_state' => 'normal'],
			),
			'img_border_color' => array(
				'type' => 'color',
				'label' => __pl('icon_border_color_label'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-service-image img' => 'border-color: {{val}};'],
				'req' => [
					'!img_border_type' => '',
				],
				'show' => ['img_bor_state' => 'normal'],
			),
			'img_border_width' => array(
				'type' => 'padding',
				'label' => __pl('icon_border_width'),
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-service-image img' =>'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'],
				'req' => [
					'!img_border_type' => '',
				],
				'show' => ['img_bor_state' => 'normal'],
			),
			'img_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'units' => ['px', 'em', '%'],
				'css' =>  ['{{element}} .pagelayer-service-image img' => 'border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -webkit-border-radius:  {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -moz-border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};'],
				'req' => [
					'!img_border_type' => '',
				],
				'show' => ['img_bor_state' => 'normal'],
			),
			'img_transition' => array(
				'type' => 'spinner',
				'label' => __pl('ele_bg_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-service-image img' =>'-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;'],
				'show' => ['img_bor_state' => 'hover'],
			),
			'img_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('icon_border_type_hover'),
				'css' => ['{{element}}:hover .pagelayer-service-image img' =>'border-style: {{val}};'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['img_bor_state' => 'hover'],
			),
			'img_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('icon_border_color_hover_label'),
				'default' => '#3e8ef7',
				'css' => ['{{element}}:hover .pagelayer-service-image img' => 'border-color: {{val}};'],
				'req' => [
					'!img_border_type_hover' => '',
				],
				'show' => ['img_bor_state' => 'hover'],
			),
			'img_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('icon_border_width_hover'),
				'screen' => 1,
				'css' =>  ['{{element}}:hover .pagelayer-service-image img' =>'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'],
				'req' => [
					'!img_border_type_hover' => '',
				],
				'show' => ['img_bor_state' => 'hover'],
			),
			'img_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'units' => ['px', 'em', '%'],
				'css' =>  ['{{element}}:hover .pagelayer-service-image img' => 'border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -webkit-border-radius:  {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}}; -moz-border-radius: {{val[0]}} {{val[1]}} {{val[2]}} {{val[3]}};'],
				'req' => [
					'!img_border_type_hover' => '',
				],
				'show' => ['img_bor_state' => 'hover'],
			),
		],
		'service_heading_style' => [
			'service_heading' => array(
				'type' => 'textarea',
				'label' => __pl('service_box_heading_label'),
				'default' => 'This is an Image Box',
				'text' => __pl('open_in_wpeditor'),
			),
			'service_heading_spacing' => array(
				'type' => 'slider',
				'label' => __pl('service_heading_spacing'),
				'min' => '0',
				'max' => '200',
				'default' => '10',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-heading' => 'margin-bottom: {{val}}px !important;'],
			),
			'heading_state' => array(
				'type' => 'radio',
				'label' => __pl('icon_state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('Normal'),
					'hover' => __pl('Hover'),
				),
			),
			'service_heading_color' => array(
				'type' => 'color',
				'label' => __pl('service_heading_color'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-service-heading' => 'color:{{val}}'],
				'show' => ['heading_state' => 'normal'],
			),
			'service_heading_typo' => array(
				'type' => 'typography',
				'label' => __pl('service_heading_typo'),
				'default' => ',28,,600,,,,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-heading' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'show' => ['heading_state' => 'normal'],
			),
			'heading_transition' => array(
				'type' => 'spinner',
				'label' => __pl('ele_bg_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-service-heading' =>'-webkit-transition: all {{val}}ms !important; transition: all {{val}}ms !important;'],
				'show' => ['heading_state' => 'hover'],
			),
			'heading_color_hover' => array(
				'type' => 'color',
				'label' => __pl('service_heading_color'),
				'css' => ['{{element}}:hover .pagelayer-service-heading' => 'color:{{val}}'],
				'show' => ['heading_state' => 'hover'],
			),
			'heading_typo_hover' => array(
				'type' => 'typography',
				'label' => __pl('service_heading_typo'),
				'screen' => 1,
				'css' => ['{{element}}:hover .pagelayer-service-heading' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'show' => ['heading_state' => 'hover'],
			),
		],
		//service content style
		'service_content_style' =>[
			'service_text_alignment' => array(
				'type' => 'radio',
				'label' => __pl('service_box_text_alignment'),
				'default' => 'center',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
					'justify' => __pl('justify'),
				),
				'css' => ['{{element}} .pagelayer-service-details' => 'text-align:{{val}};'],
			),
			'service_text' => array(
				'type' => 'editor',
				'label' => __pl('service_box_text_label'),
				'default' => 'Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
				'text' => __pl('open_in_wpeditor'),
			)
		],
		//service button style
		'service_btn_style' =>[
			'service_button' => array(
				'type' => 'checkbox',
				'label' => __pl('show_btn')
			),
			'service_button_type' => array(
				'type' => 'select',
				'label' => __pl('type'),
				'default' => 'pagelayer-btn-default',
				'list' => array(
					'pagelayer-btn-default' => __pl('btn_type_default'),
					'pagelayer-btn-primary' => __pl('btn_type_primary'),
					'pagelayer-btn-secondary' => __pl('btn_type_secondary'),
					'pagelayer-btn-success' => __pl('btn_type_success'),
					'pagelayer-btn-info' => __pl('btn_type_info'),
					'pagelayer-btn-warning' => __pl('btn_type_warning'),
					'pagelayer-btn-danger' => __pl('btn_type_danger'),
					'pagelayer-btn-dark' => __pl('btn_type_dark'),
					'pagelayer-btn-light' => __pl('btn_type_light'),
					'pagelayer-btn-link' => __pl('btn_type_link'),
					'pagelayer-btn-custom' => __pl('btn_type_custom')
				),
				'req' => array(
					'service_button' => 'true'
				),
			),
			'service_button_size' => array(
				'type' => 'select',
				'label' => __pl('button_size_label'),
				'default' => 'pagelayer-btn-small',
				'list' => array(
					'pagelayer-btn-mini' => __pl('mini'),
					'pagelayer-btn-small' => __pl('small'),
					'pagelayer-btn-large' => __pl('large'),
					'pagelayer-btn-extra-large' => __pl('extra_large'),
					'pagelayer-btn-double-large' => __pl('double_large'),
					//'pagelayer-btn-custom' => __pl('custom'),
				),
				'req' => array(
					'service_button' => 'true'
				)
			),
			'service_button_url' => array(
				'type' => 'link',
				'label' => __pl('service_btn_url_label'),
				'req' => array(
					'service_button' => 'true'
				),
			),
			'service_button_text' => array(
				'type' => 'text',
				'label' => __pl('service_button_text_label'),
				'default' => 'Click Here!',
				'req' => array(
					'service_button' => 'true'
				),
			),
			'service_btn_spacing' => array(
				'type' => 'slider',
				'label' => __pl('service_btn_spacing'),
				'min' => '0',
				'max' => '200',
				'default' => '10',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-btn' => 'margin-top: {{val}}px;'],
				'req' => [
					'service_button' => 'true',
				]
			),
			'service_button_font_size' => array(
				'type' => 'slider',
				'label' => __pl('iconbox_btn_text_size'),
				'min' => '0',
				'max' => '50',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-btn' => 'font-size:{{val}}px;'],
				'req' => [
					'service_button' => 'true',
					'iconbox_button_type' => 'pagelayer-btn-custom',
				]
			),
			'service_btn_state' => array(
				'type' => 'radio',
				'label' => __pl('button_state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('Normal'),
					'hover' => __pl('Hover'),
				),
				'req' => array(
					'service_button' => 'true',
					'service_button_type' => 'pagelayer-btn-custom'
				),
			),
			'service_button_color' => array(
				'type' => 'color',
				'label' => __pl('iconbox_button_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-service-btn' => 'color:{{val}};'],
				'req' => [
					'service_button' => 'true',
					'service_button_type' => 'pagelayer-btn-custom',
				],
				'show' => ['service_btn_state' => 'normal']
			),
			'service_button_bg_color' => array(
				'type' => 'color',
				'label' => __pl('service_button_bg_color'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-service-btn' => 'background-color:{{val}};'],
				'req' => [
					'service_button' => 'true',
					'service_button_type' => 'pagelayer-btn-custom',
				],
				'show' => ['service_btn_state' => 'normal']
			),
			'service_btn_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('service_btn_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-service-btn' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => ['service_btn_state' => 'hover'],
			),
			'service_button_color_hover' => array(
				'type' => 'color',
				'label' => __pl('iconbox_button_color'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-service-btn:hover' => 'color:{{val}};'],
				'show' => ['service_btn_state' => 'hover'],
			),
			'service_button_bg_color_hover' => array(
				'type' => 'color',
				'label' => __pl('service_button_bg_color_hover'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-service-btn:hover' => 'background-color:{{val}};'],
				'show' => ['service_btn_state' => 'hover'],
			),
		],
		'styles' => [
			'service_img_style' => __pl('service_img_style'),
			'service_heading_style' => __pl('service_heading_style'),
			'service_content_style' => __pl('service_content_style'),
			'service_btn_style' => __pl('service_btn_style'),
		],
	)
);

// Icon Box
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_iconbox', array(
		'name' => __pl('Icon Box'),
		'group' => 'other',
		'func' => 'pagelayer_sc_iconbox',
		'innerHTML' => 'service_text',
		'html' => '<div class="pagelayer-service-container pagelayer-service-align-{{service_alignment}} pagelayer-service-vertical-{{service_vertical_alignment}}">
			<div class="pagelayer-service-icon pagelayer-service-{{service_icon_view}}">
				<i class="fa fa-{{service_icon}} pagelayer-icon-{{service_icon_shape_type}} pagelayer-animation-{{anim_hover}}" aria-hidden="true"></i>
			</div>
			<div class="pagelayer-service-details">
				<div if="{{service_heading}}" class="pagelayer-service-heading">{{service_heading}}</div>
				<div if="{{service_text}}" class="pagelayer-service-text">{{service_text}}</div>
				<a if="{{service_button}}" href="{{service_button_url}}" class="pagelayer-service-btn pagelayer-button pagelayer-ele-link {{iconbox_button_type}} {{service_button_size}}">
					<span if="{{service_button_text}}">{{service_button_text}}</span>
				</a>
			</div>
		</div>',
		'params' => array(
			'service_icon' => array(
				'type' => 'icon',
				'label' => __pl('iconbox_font_icon_label'),
				'default' => 'exclamation-circle',
			),
			'service_alignment' => array(
				'type' => 'radio',
				'label' => __pl('iconbox_box_media_alignment'),
				'default' => 'top',
				'list' => array(
					'left' => __pl('left'),
					'top' => __pl('top'),
					'right' => __pl('right'),
				),
			),
			'service_vertical_alignment' => array(
				'type' => 'radio',
				'label' => __pl('iconbox_box_media_vertical_alignment'),
				'default' => 'middle',
				'list' => array(
					'top' => __pl('top'),
					'middle' => __pl('middle'),
					'bottom' => __pl('bottom'),
				),
				'req' => array(
					'!service_alignment' => 'top'
				)
			),
		),
		// icon style
		'service_icon_style' => [
			'service_icon_view' => array(
				'type' => 'select',
				'label' => __pl('iconbox_icon_view'),
				'default' => 'default',
				'list' =>array(
					'default' => __pl('Default'),
					'stacked' => __pl('Stacked'),
					'framed' => __pl('Framed'),
				),
			),
			'service_icon_shape_type' => array(
				'type' => 'select',
				'label' => __pl('iconbox_icon_shape_label'),
				'default' => '',
				'list' =>array(
					'square' => __pl('Square'),
					'circle' => __pl('Circle'),
				),
				'req' => ['!service_icon_view' => 'default'],
			),
			'service_icon_padding' => array(
				'type' => 'slider',
				'label' => __pl('service_icon_padding'),
				'min' => '0',
				'max' => '200',
				'default' => '15',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-icon i' => 'padding: calc(0.5em + {{val}}px);'],
				'req' => ['!service_icon_view' => 'default'],
			),
			'service_icon_spacing' => array(
				'type' => 'padding',
				'label' => __pl('service_icon_spacing'),
				'css' => ['{{element}} .pagelayer-service-icon' => 'padding-top:{{val[0]}}px; padding-right:{{val[1]}}px; padding-bottom:{{val[2]}}px; padding-left:{{val[3]}}px;'],
			),
			'service_icon_state' => array(
				'type' => 'radio',
				'label' => __pl('icon_state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('Normal'),
					'hover' => __pl('Hover'),
				),
			),
			'service_icon_color' => array(
				'type' => 'color',
				'label' => __pl('iconbox_icon_color'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-service-icon i' => 'color:{{val}};'],
				'show' => ['service_icon_state' => 'normal'],
			),
			'service_icon_background_color' => array(
				'type' => 'color',
				'label' => __pl('service_icon_background_color'),
				'default' => '#eff0f0ff',
				'css' => ['{{element}} .pagelayer-service-icon.pagelayer-service-stacked i' => 'background-color:{{val}};'],
				'show' => ['service_icon_state' => 'normal'],
				'req' => ['service_icon_view' => 'stacked']
			),
			'service_icon_font_size' => array(
				'type' => 'slider',
				'label' => __pl('service_icon_size'),
				'min' => '0',
				'max' => '300',
				'default' => '75',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-icon' => 'font-size:{{val}}px;'],
				'show' => ['service_icon_state' => 'normal'],
			),
			'service_rotate' => array(
				'type' => 'slider',
				'label' => __pl('service_icon_rotate'),
				'min' => '0',
				'max' => '360',
				'default' => '0',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-icon i' => 'transform: rotate({{val}}deg);'],
				'show' => ['service_icon_state' => 'normal'],
			),
			'service_icon_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('service_icon_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-service-icon i' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => ['service_icon_state' => 'hover'],
			),
			'anim_hover' => array(
				'type' => 'select',
				'label' => __pl('icon_animation'),
				'list' => [
					'' => __pl('none'),
					'grow' => __pl('Grow'),
					'shrink' => __pl('Shrink'),
					'pulse' => __pl('Pulse'),
					'pulse-grow' => __pl('Pulse Grow'),
					'pulse-shrink' => __pl('Pulse Shrink'),
					'push' => __pl('Push'),
					'pop' => __pl('Pop'),
					'buzz' => __pl('Buzz'),
					'buzz-out' => __pl('Buzz Out'),
					'float' => __pl('Float'),
					'sink' => __pl('Sink'),
					'bob' => __pl('Bob'),
					'hang' => __pl('Hang'),
					'bounce-in' => __pl('Bounce In'),
					'bounce-out' => __pl('Bounce Out'),
					'rotate' => __pl('Rotate'),
					'grow-rotate' => __pl('Grow Rotate'),
					'skew-forward' => __pl('Skew Forward'),
					'skew-backward' => __pl('Skew Backward'),
					'wobble-vertical' => __pl('Wobble Vertical'),
					'wobble-horizontal' => __pl('Wobble Horizontal'),
					'wobble-bottom-to-right' => __pl('Wobble Bottom To Right'),
					'wobble-top-to-right' => __pl('Wobble Top To Right'),
					'wobble-top' => __pl('Wobble Top'),
					'wobble-bottom' => __pl('Wobble Bottom'),
					'wobble-skew' => __pl('Wobble Skew'),
				],
				'show' => array(
					'service_icon_state' => 'hover',
				),
			),
			'service_icon_color_hover' => array(
				'type' => 'color',
				'label' => __pl('service_icon_color_hover'),
				'css' => ['{{element}}:hover .pagelayer-service-icon i' => 'color:{{val}};'],
				'show' => ['service_icon_state' => 'hover'],
			),
			'service_icon_background_color_hover' => array(
				'type' => 'color',
				'label' => __pl('service_icon_background_color_hover'),
				'default' => '',
				'css' => ['{{element}}:hover .pagelayer-service-icon.pagelayer-service-stacked i' => 'background-color:{{val}};'],
				'show' => ['service_icon_state' => 'hover'],
				'req' => ['service_icon_view' => 'stacked']
			),
			'service_icon_size_hover' => array(
				'type' => 'slider',
				'label' => __pl('service_icon_size_hover'),
				'min' => '0',
				'max' => '300',
				'screen' => 1,
				'css' => ['{{element}}:hover .pagelayer-service-icon' => 'font-size:{{val}}px;'],
				'show' => ['service_icon_state' => 'hover'],
			),
			'service_rotate_hover' => array(
				'type' => 'slider',
				'label' => __pl('service_rotate_hover'),
				'min' => '0',
				'max' => '360',
				'screen' => 1,
				'css' => ['{{element}}:hover .pagelayer-service-icon i' => 'transform: rotate({{val}}deg);'],
				'show' => ['service_icon_state' => 'hover'],
			),
		],
		'service_icon_border' => [
			'service_bor_state' => array(
				'type' => 'radio',
				'label' => __pl('icon_state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('Normal'),
					'hover' => __pl('Hover'),
				),
			),
			'service_icon_border_type' => array(
				'type' => 'select',
				'label' => __pl('icon_border_type'),
				'css' => ['{{element}} .pagelayer-service-icon i' =>'border-style: {{val}};'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['service_bor_state' => 'normal'],
			),
			'service_icon_border_color' => array(
				'type' => 'color',
				'label' => __pl('icon_border_color_label'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-service-icon i' => 'border-color: {{val}};'],
				'req' => [
					'!service_icon_border_type' => '',
				],
				'show' => ['service_bor_state' => 'normal'],
			),
			'service_icon_border_width' => array(
				'type' => 'padding',
				'label' => __pl('icon_border_width'),
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-service-icon i' =>'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'],
				'req' => [
					'!service_icon_border_type' => '',
				],
				'show' => ['service_bor_state' => 'normal'],
			),
			'service_icon_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-service-icon i ' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => [
					'!service_icon_border_type' => '',
				],
				'show' => ['service_bor_state' => 'normal'],
			),
			'service_icon_border_type_hover' => array(
				'type' => 'select',
				'label' => __pl('icon_border_type_hover'),
				'css' => ['{{element}}:hover .pagelayer-service-icon i' =>'border-style: {{val}};'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['service_bor_state' => 'hover'],
			),
			'service_icon_border_color_hover' => array(
				'type' => 'color',
				'label' => __pl('icon_border_color_hover_label'),
				'default' => '#3e8ef7',
				'css' => ['{{element}}:hover .pagelayer-service-icon i' => 'border-color: {{val}};'],
				'req' => [
					'!service_icon_border_type_hover' => '',
				],
				'show' => ['service_bor_state' => 'hover'],
			),
			'service_icon_border_width_hover' => array(
				'type' => 'padding',
				'label' => __pl('icon_border_width_hover'),
				'screen' => 1,
				'css' =>  ['{{element}}:hover .pagelayer-service-icon i' =>'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'],
				'req' => [
					'!service_icon_border_type_hover' => '',
				],
				'show' => ['service_bor_state' => 'hover'],
			),
			'service_icon_border_radius_hover' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' =>  ['{{element}}:hover .pagelayer-service-icon i' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => [
					'!service_icon_border_type_hover' => '',
				],
				'show' => ['service_bor_state' => 'hover'],
			),
		],		
		'service_heading_style' =>[
			'service_heading' => array(
				'type' => 'textarea',
				'label' => __pl('iconbox_box_heading_label'),
				'default' => 'This is Icon Box',
				'text' => __pl('open_in_wpeditor'),
			),
			'service_heading_spacing' => array(
				'type' => 'slider',
				'label' => __pl('service_heading_spacing'),
				'min' => '0',
				'max' => '200',
				'default' => '10',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-heading' => 'margin-bottom: {{val}}px;'],
			),
			'heading_state' => array(
				'type' => 'radio',
				'label' => __pl('icon_state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('Normal'),
					'hover' => __pl('Hover'),
				),
			),
			'service_heading_color' => array(
				'type' => 'color',
				'label' => __pl('service_heading_color'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-service-heading' => 'color:{{val}}'],
				'show' => ['heading_state' => 'normal']
			),
			'service_heading_typo' => array(
				'type' => 'typography',
				'label' => __pl('service_heading_typo'),
				'default' => ',28,,600,,,,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-heading' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'show' => ['heading_state' => 'normal']
			),
			'heading_delay' => array(
				'type' => 'spinner',
				'label' => __pl('service_icon_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-service-heading' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => ['heading_state' => 'hover']
			),
			'heading_color_hover' => array(
				'type' => 'color',
				'label' => __pl('service_heading_color'),
				'css' => ['{{element}}:hover .pagelayer-service-heading' => 'color:{{val}}'],
				'show' => ['heading_state' => 'hover']
			),
			'heading_typo_hover' => array(
				'type' => 'typography',
				'label' => __pl('service_heading_typo'),
				'screen' => 1,
				'css' => ['{{element}}:hover .pagelayer-service-heading' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
				'show' => ['heading_state' => 'hover']
			),
		],
		//service content style
		'service_content_style' =>[
			'service_text_alignment' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'center',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
					'justify' => __pl('justify'),
				),
				'css' => ['{{element}} .pagelayer-service-details' => 'text-align:{{val}};'],
			),
			'service_text' => array(
				'type' => 'editor',
				'label' => __pl('iconbox_box_text_label'),
				'default' => 'Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.',
				'text' => __pl('open_in_wpeditor'),
			),
		],
		//service button style
		'service_btn_style' =>[
			'service_button' => array(
				'type' => 'checkbox',
				'label' => __pl('show_btn'),
			),
			'iconbox_button_type' => array(
				'type' => 'select',
				'label' => __pl('Button Type'),
				'default' => 'pagelayer-btn-primary',
				'list' => array(
					'pagelayer-btn-default' => __pl('btn_type_default'),
					'pagelayer-btn-primary' => __pl('btn_type_primary'),
					'pagelayer-btn-secondary' => __pl('btn_type_secondary'),
					'pagelayer-btn-success' => __pl('btn_type_success'),
					'pagelayer-btn-info' => __pl('btn_type_info'),
					'pagelayer-btn-warning' => __pl('btn_type_warning'),
					'pagelayer-btn-danger' => __pl('btn_type_danger'),
					'pagelayer-btn-dark' => __pl('btn_type_dark'),
					'pagelayer-btn-light' => __pl('btn_type_light'),
					'pagelayer-btn-link' => __pl('btn_type_link'),
					'pagelayer-btn-custom' => __pl('btn_type_custom')
				),
				'req' => array(
					'service_button' => 'true'
				),
			),
			'service_button_size' => array(
				'type' => 'select',
				'label' => __pl('button_size_label'),
				'default' => 'pagelayer-btn-small',
				'list' => array(
					'pagelayer-btn-mini' => __pl('mini'),
					'pagelayer-btn-small' => __pl('small'),
					'pagelayer-btn-large' => __pl('large'),
					'pagelayer-btn-extra-large' => __pl('extra_large'),
					'pagelayer-btn-double-large' => __pl('double_large'),
					//'pagelayer-btn-custom' => __pl('custom'),
				),
				'req' => array(
					'service_button' => 'true'
				)
			),
			'service_button_url' => array(
				'type' => 'link',
				'label' => __pl('iconbox_btn_url_label'),
				'req' => array(
					'service_button' => 'true'
				),
			),
			'service_button_text' => array(
				'type' => 'text',
				'label' => __pl('iconbox_button_text_label'),
				'default' => 'Click Here!',
				'req' => array(
					'service_button' => 'true'
				),
			),
			'service_btn_spacing' => array(
				'type' => 'slider',
				'label' => __pl('service_btn_spacing'),
				'min' => '0',
				'max' => '200',
				'default' => '10',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-btn' => 'margin-top: {{val}}px;'],
				'req' => [
					'service_button' => 'true',
				]
			),
			'service_button_font_size' => array(
				'type' => 'slider',
				'label' => __pl('iconbox_btn_text_size'),
				'min' => '0',
				'max' => '50',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-service-btn' => 'font-size:{{val}}px;'],
				'req' => [
					'service_button' => 'true',
					'iconbox_button_type' => 'pagelayer-btn-custom',
				]
			),
			'service_btn_state' => array(
				'type' => 'radio',
				'label' => __pl('button_state'),
				'default' => 'normal',
				'list' => array(
					'normal' => __pl('Normal'),
					'hover' => __pl('Hover'),
				),
				'req' => array(
					'service_button' => 'true',
					'iconbox_button_type' => 'pagelayer-btn-custom'
				),
			),
			'service_button_color' => array(
				'type' => 'color',
				'label' => __pl('iconbox_button_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-service-btn' => 'color:{{val}};'],
				'req' => [
					'service_button' => 'true',
					'iconbox_button_type' => 'pagelayer-btn-custom',
				],
				'show' => ['service_btn_state' => 'normal']
			),
			'service_button_bg_color' => array(
				'type' => 'color',
				'label' => __pl('service_button_bg_color'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-service-btn' => 'background-color:{{val}};'],
				'req' => [
					'service_button' => 'true',
					'iconbox_button_type' => 'pagelayer-btn-custom',
				],
				'show' => ['service_btn_state' => 'normal']
			),
			'service_btn_hover_delay' => array(
				'type' => 'spinner',
				'label' => __pl('service_btn_hover_delay'),
				'min' => 0,
				'step' => 100,
				'max' => 5000,
				'default' => 400,
				'css' => ['{{element}} .pagelayer-service-btn' => '-webkit-transition: all {{val}}ms; transition: all {{val}}ms;'],
				'show' => ['service_btn_state' => 'hover'],
			),
			'service_button_color_hover' => array(
				'type' => 'color',
				'label' => __pl('iconbox_button_color'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-service-btn:hover' => 'color:{{val}};'],
				'show' => ['service_btn_state' => 'hover'],
			),
			'service_button_bg_color_hover' => array(
				'type' => 'color',
				'label' => __pl('service_button_bg_color_hover'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-service-btn:hover' => 'background-color:{{val}};'],
				'show' => ['service_btn_state' => 'hover'],
			),
		],
		'styles' => [
			'service_icon_style' => __pl('service_icon_style'),
			'service_icon_border' => __pl('service_icon_border'),
			'service_heading_style' => __pl('service_heading_style'),
			'service_content_style' => __pl('service_content_style'),
			'service_btn_style' => __pl('service_btn_style'),
		],
	)
);

// Tabs
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_tabs', array(
		'name' => __pl('tabs'),
		'group' => 'other',
		'func' => 'pagelayer_sc_tabs',
		'has_group' => [
			'section' => 'params', 
			'prop' => 'elements'
		],
		'holder' => '.pagelayer-tabcontainer',
		'html' => '<div class="pagelayer-tabs-holder"></div>
			<div class="pagelayer-tabcontainer"></div>',
		'params' => array(
			'elements' => array(
				'type' => 'group',
				'label' => __pl('Tabs list'),
				'sc' => PAGELAYER_SC_PREFIX.'_tab',
				'item_label' => array(
					'default' => __pl('tab'),
					'param' => 'title',
				),
				'count' => 2,
				'text' => strtr(__pl('add_new_item'), array('%name%' => __pl('tab_name'))),
			),
			'vertical' => array(
				'type' => 'checkbox',
				'label' => __pl('tabs_vertical'),
			),
			'vertical_width' => array(
				'type' => 'slider',
				'label' => __pl('Tabs container width'),
				'default' => 30,
				'min' => 0,
				'max' => 70,
				'step' => 1,
				'screen' => 1,
				'css' => ['{{element}}' => 'width:100%; display: -webkit-flex;
				display: flex;', '{{element}} .pagelayer-tabs-holder' => '-webkit-flex-basis: {{val}}%; flex-basis:{{val}}%', '{{element}} .pagelayer-tabcontainer' => '-webkit-flex-basis: calc(100% - {{val}}%); flex-basis:calc(100% - {{val}}%)', '{{element}} .pagelayer-tabs-holder .pagelayer-tablinks' => 'width: 100%;'],
				'req' => array(
					'vertical' => 'true',
				)
			),
			'rotate' => array(
				'type' => 'radio',
				'label' => __pl('tabs_rotate'),
				'list' => array(
					'' => __pl('disable'),
					'3000' => '3',
					'5000' => '5',
					'10000' => '10',
					'15000' => '15',
				),
				'addAttr' => 'pagelayer-tabs-rotate="{{rotate}}"'
			)
		),
		'tabs_holder_styles' => [
			'tabs_holder_bg_color' => array(
				'type' => 'color',
				'label' => __pl('Background color'),
				'default' => '#f1f1f1',
				'css' => ['{{element}} .pagelayer-tabs-holder' => 'background-color:{{val}}'],
			),
			'tabs_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'default' => 'solid',
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'css' => ['{{element}} .pagelayer-tabcontainer' => 'border-style: {{val}}', '{{element}} .pagelayer-tabs-holder' =>'border-style: {{val}}'],
			),
			'tabs_border_color' => array(
				'type' => 'color',
				'label' => __pl('border_color'),
				'default' => '#cccccc',
				'req' => [
					'!tabs_border_type' => ''
				],
				'css' => ['{{element}} .pagelayer-tabcontainer' => 'border-color: {{val}}','{{element}} .pagelayer-tabs-holder' => 'border-color: {{val}}'],
			),
			'tabs_holder_border_width' => array(
				'type' => 'padding',
				'label' => __pl('Border Width'),
				'default' => '1,1,0,1',
				'screen' => 1,
				'req' => [
					'!tabs_border_type' => ''
				],
				'css' => ['{{element}} .pagelayer-tabs-holder' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
			),
			'tabs_holder_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('Border Radius'),
				'default' => '1,1,0,1',
				'screen' => 1,
				'req' => [
					'!tabs_border_type' => ''
				],
				'css' => ['{{element}} .pagelayer-tabs-holder' => 'border-top-left-radius: {{val[0]}}px; border-top-right-radius: {{val[1]}}px; border-bottom-right-radius: {{val[2]}}px; border-bottom-left-radius: {{val[3]}}px'],
			),
		],
		'tabs_styles' => [
			'tabs_holder_align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'default' => 'left',
				'screen' => 1,
				'list' => array(
					'left' => __pl('Left'),
					'center' => __pl('Center'),
					'right' => __pl('Right'),
				),
				'css' => ['{{element}} .pagelayer-tabs-holder' => 'text-align:{{val}}'],
			),
			'tabs_color' => array(
				'type' => 'color',
				'label' => __pl('Color '),
				'default' => '#444',
				'css' => ['{{element}} .pagelayer-tabs-holder .pagelayer-tablinks' => 'color:{{val}}'],
			),
			'tabs_bg_color' => array(
				'type' => 'color',
				'label' => __pl('Background color'),
				'default' => '#f1f1f1',
				'css' => ['{{element}} .pagelayer-tabs-holder .pagelayer-tablinks' => 'background-color:{{val}}'],
			),
			'tabs_active_color' => array(
				'type' => 'color',
				'label' => __pl('Active Tab Color'),
				'default' => '#fff',
				'css' => ['{{element}} .pagelayer-tabs-holder .pagelayer-tablinks.active' => 'color:{{val}}', '{{element}} .pagelayer-tabs-holder .pagelayer-tablinks:hover' => 'color:{{val}}'],
			),
			'tabs_active_bg_color' => array(
				'type' => 'color',
				'label' => __pl('Active Tab Background Color'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-tabs-holder .pagelayer-tablinks.active'=> 'background-color:{{val}}', '{{element}} .pagelayer-tabs-holder .pagelayer-tablinks:hover' => 'background-color:{{val}}'],
			),
			'tab_title_typo' => array(
				'type' => 'typography',
				'label' => __pl('tab_title_typo'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-tabs-holder .pagelayer-tablinks' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'tabs_icon_align' => array(
				'type' => 'radio',
				'label' => __pl('Icon Position'),
				'default' => 'left',
				'list' => array(
					'left' => __pl('Left'),
					'right' => __pl('Right'),
				),
				'css' => ['{{element}} .pagelayer-tabs-holder .pagelayer-tablinks .fa'=> 'float:{{val}};'],
			),
			'tabs_icon_spacing' => array(
				'type' => 'slider',
				'label' => __pl('tabs_icon_spacing'),
				'default' => 10,
				'max' => 50,
				'min' => 0,
				'steps' => 1,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-tabs-holder .pagelayer-tablinks .fa'=> 'padding:0px {{val}}px;padding-{{tabs_icon_align}}:0px;'],
			),
		],
		'content_styles' => [
			'tabs_content_typo' => array(
				'type' => 'typography',
				'label' => __pl('tab_content_typo'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-tabcontainer' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'tabs_content_color' => array(
				'type' => 'color',
				'label' => __pl('Color'),
				'default' => '#000000',
				'css' => ['{{element}} .pagelayer-tab' => 'color:{{val}}'],
			),
			'tabs_content_bg_color' => array(
				'type' => 'color',
				'label' => __pl('Background Color'),
				'default' => '#fff',
				'css' => ['{{element}} .pagelayer-tab'=> 'background-color:{{val}}'],
			),
			'tab_padding' => array(
				'type' => 'slider',
				'label' => __pl('tabs_padding_label'),
				'default' => 15,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'screen' => 1,
				'css' => ['{{element}}.pagelayer-tabs .pagelayer-tabcontainer [pagelayer-id]' => 'padding: {{val}}px;'],
			),
			'tabs_content_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'default' => '1,1,1,1',
				'screen' => 1,
				'req' => [
					'!tabs_border_type' => ''
				],
				'css' => ['{{element}} .pagelayer-tabcontainer' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px'],
			),
			'tabs_content_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'req' => [
					'!tabs_border_type' => ''
				],
				'css' => ['{{element}} .pagelayer-tabcontainer' => 'border-top-left-radius: {{val[0]}}px; border-top-right-radius: {{val[1]}}px; border-bottom-right-radius: {{val[2]}}px; border-bottom-left-radius: {{val[3]}}px'],
			),
		],
		'styles' => [
			'tabs_holder_styles' => __pl('tabs_holder_styles'),
			'tabs_styles' => __pl('Tabs'),
			'content_styles' => __pl('content'),
		],
	)
);


// Tab
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_tab', array(
		'name' => __pl('tab'),
		'group' => 'other',
		'not_visible' => 1,
		'func' => 'pagelayer_sc_tab',
		'innerHTML' => 'text',
		'html' => '<div class="pagelayer-tabcontent">{{text}}</div>',
		'params' => array(
			'default_active' => array(
				'type' => 'checkbox',
				'label' => __pl('Default active tab'),
				'addAttr' => 'pagelayer-default_active="1"'
			),
			'tab_icon' => array(
				'type' => 'icon',
				'label' => __pl('icon'),
				'addAttr' => 'pagelayer-tab-icon="{{tab_icon}}"',
			),
			'title' => array(
				'type' => 'text',
				'label' => __pl('title'),
				'default' => 'Lorem',
				'addAttr' => 'pagelayer-tab-title="{{title}}"'
			),
			'text' => array(
				'type' => 'editor',
				'label' => __pl('Edit Rich Text'),
				'default' => 'Lorem ipsum dolor sit amet',
				'desc' => __pl('Edit the content here or edit directly in the Editor'),
				'edit' => '.pagelayer-text-holder',
			),
		)
	)
);

// Accordion
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_accordion', array(
		'name' => __pl('accordion'),
		'group' => 'other',
		'func' => 'pagelayer_sc_accordion',
		'has_group' => [
			'section' => 'params', 
			'prop' => 'elements'
		],
		'holder' => '.pagelayer-accordion-holder',
		'html' => '<div class="pagelayer-accordion-holder" data-icon="{{icon}}" data-active_icon="{{active_icon}}"></div>',
		'params' => array(
			'elements' => array(
				'type' => 'group',
				'label' => __pl('Accordions'),
				'sc' => PAGELAYER_SC_PREFIX.'_accordion_item',
				'item_label' => array(
					'default' => __pl('accordion_item_title_label'),
					'param' => 'title'
				),
				'count' => 2,
				'text' => strtr(__pl('add_new_item'), array('%name%' => __pl('accordion_name'))),				
			),		
			'acc_space' => array(
				'type' => 'slider',
				'label' => __pl('Space Between'),
				'default' => 0,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-accordion_item' => 'margin-bottom:{{val}}px;'],
			),	
		),
		'icon_styles' => [
			'icon' => array(
				'type' => 'icon',
				'label' => __pl('list_icon_label'),
				'default' => 'plus',
			),
			'active_icon' => array(
				'type' => 'icon',
				'label' => __pl('Active Icon'),
				'default' => 'minus',
			),
			'icon_align' => array(
				'type' => 'radio',
				'label' => __pl('Alignment'),
				'default' => 'left',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-accordion-tabs span' => 'float:{{val}}'],
				'list' => array(
					'left' => __pl('left'),
					'right' => __pl('right'),
				)
			
			),
			'icon_padding' => array(
				'type' => 'slider',
				'label' => __pl('Spacing'),
				'default' => 10,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-accordion-tabs span' => 'padding:0px {{val}}px; padding-{{icon_align}}:0px;'],
			),	
		],
		'tabs_styles' => [
			'tabs_color' => array(
				'type' => 'color',
				'label' => __pl('Color '),
				'default' => '#444444',
				'css' => ['{{element}} .pagelayer-accordion-tabs' => 'color:{{val}}'],
			),
			'tabs_bg_color' => array(
				'type' => 'color',
				'label' => __pl('Background Color '),
				'default' => '#eeeeee',
				'css' => ['{{element}} .pagelayer-accordion-tabs' => 'background-color:{{val}}'],
			),
			'tabs_active_color' => array(
				'type' => 'color',
				'label' => __pl('Active Tab Color '),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-accordion-tabs.active' => 'color:{{val}}', '{{element}} .pagelayer-accordion-tabs:hover' => 'color:{{val}}'],
			),
			'tabs_active_bg_color' => array(
				'type' => 'color',
				'label' => __pl('Active Tab Background Color '),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-accordion-tabs.active'=> 'background-color:{{val}}', '{{element}} .pagelayer-accordion-tabs:hover' => 'background-color:{{val}}'],
			),
			'tab_padding' => array(
				'type' => 'slider',
				'label' => __pl('tabs_padding_label'),
				'default' => 15,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-accordion-tabs' => 'padding: {{val}}px;'],
			),
			'accordion_title_typo' => array(
				'type' => 'typography',
				'label' => __pl('accordion_title_typo'),
				'css' => ['{{element}} .pagelayer-accordion-tabs' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		],
		'content_styles' => [
			'tabs_content_bg_color' => array(
				'type' => 'color',
				'label' => __pl('Background Color'),
				'default' => '#fff',
				'css' => ['{{element}} .pagelayer-accordion_item .pagelayer-accordion-panel'=> 'background-color:{{val}}'],
			),
			'acc_content_typo' => array(
				'type' => 'typography',
				'label' => __pl('accordion_content_typo'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-accordion-panel' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
			'acc_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'css' => ['{{element}} .pagelayer-accordion_item' => 'border-style: {{val}}', '{{element}} .pagelayer-accordion_item .pagelayer-accordion-panel' =>'border-style: {{val}}'],
			),
			'acc_border_color' => array(
				'type' => 'color',
				'label' => __pl('border_color'),
				'default' => '#cccccc',
				'req' => [
					'!acc_border_type' => ''
				],
				'css' => ['{{element}} .pagelayer-accordion_item' => 'border-color: {{val}}', '{{element}} .pagelayer-accordion_item .pagelayer-accordion-panel' =>'border-color: {{val}}'],
			),
			'acc_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'default' => '1,1,1,1',
				'screen' => 1,
				'req' => [
					'!acc_border_type' => ''
				],
				'css' => ['{{element}} .pagelayer-accordion_item' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px', '{{element}} .pagelayer-accordion_item .pagelayer-accordion-panel' => 'border-width: {{val[0]}}px 0 0 0'],
			)
		],
		'styles' => [
			'icon_styles' => __pl('icon'),
			'tabs_styles' => __pl('Tabs'),
			'content_styles' => __pl('Content'),
		],
	)
);

// Accordion item
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_accordion_item', array(
		'name' => __pl('Accordion item'),
		'group' => 'other',
		'not_visible' => 1,
		'func' => 'pagelayer_sc_accordion_item',
		'innerHTML' => 'text',
		'html' => '<a if="{{title}}" class="pagelayer-accordion-tabs">{{title}}<span class="pagelayer-accordion-icon"><i></i></span></a>
		<div if="{{text}}" class="pagelayer-accordion-panel">{{text}}</div>',
		'params' => array(
			'default_active' => array(
				'type' => 'checkbox',
				'label' => __pl('Default active tab'),
				'addClass' => 'active'
			),
			'title' => array(
				'type' => 'text',
				'label' => __pl('title'),
				'default' => 'Lorem',
				'desc' => __pl(''),
			),
			'text' => array(
				'type' => 'editor',
				'label' => __pl('Edit Rich Text'),
				'default' => 'Lorem ipsum dolor sit amet',
				'desc' => __pl('Edit the content here or edit directly in the Editor'),
				'edit' => '.pagelayer-text-holder',
			),
		)
	)
);

// Toggle / Collapse
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_collapse', array(
		'name' => __pl('Collapse'),
		'group' => 'other',
		'func' => 'pagelayer_sc_collapse',
		'has_group' => [
			'section' => 'params', 
			'prop' => 'elements'
		],
		'holder' => '.pagelayer-collapse-holder',
		'html' => '<div class="pagelayer-collapse-holder" data-icon="{{icon}}" data-active_icon="{{active_icon}}"></div>',
		'params' => array(
			'elements' => array(
				'type' => 'group',
				'label' => __pl('Collapse Items'),
				'sc' => PAGELAYER_SC_PREFIX.'_accordion_item',
				'item_label' => array(
					'default' => __pl('tab'),
					'param' => 'title',
				),
				'count' => 2,
				'text' => strtr(__pl('add_new_item'), array('%name%' => __pl('tab_name'))),
			),
			'acc_space' => array(
				'type' => 'slider',
				'label' => __pl('Space Between'),
				'default' => 0,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-accordion_item' => 'margin-bottom:{{val}}px;'],
			),
			
		),
		'icon_style' => [
			'icon' => array(
				'type' => 'icon',
				'label' => __pl('list_icon_label'),
				'default' => 'plus',
			),
			'active_icon' => array(
				'type' => 'icon',
				'label' => __pl('Active icon'),
				'default' => 'minus'
			),
			'icon_align' => array(
				'type' => 'radio',
				'label' => __pl('Alignment'),
				'default' => 'left',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-accordion-tabs span' => 'float:{{val}}'],
				'list' => array(
					'left' => __pl('left'),
					'right' => __pl('right'),
				)
			
			),
			'icon_padding' => array(
				'type' => 'slider',
				'label' => __pl('Spacing'),
				'default' => 10,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-accordion-tabs span' => 'padding:0px {{val}}px; padding-{{icon_align}}:0px;'],
			),
		],
		'tabs_styles' => [
			'tabs_color' => array(
				'type' => 'color',
				'label' => __pl('Color '),
				'default' => '#444',
				'css' => ['{{element}} .pagelayer-accordion-tabs' => 'color:{{val}}'],
			),
			'tabs_bg_color' => array(
				'type' => 'color',
				'label' => __pl('Background Color '),
				'default' => '#eee',
				'css' => ['{{element}} .pagelayer-accordion-tabs' => 'background-color:{{val}}'],
			),
			'tabs_active_color' => array(
				'type' => 'color',
				'label' => __pl('Active Tab Color '),
				'default' => '#fff',
				'css' => ['{{element}} .pagelayer-accordion-tabs.active' => 'color:{{val}}', '{{element}} .pagelayer-accordion-tabs:hover' => 'color:{{val}}'],
			),
			'tabs_active_bg_color' => array(
				'type' => 'color',
				'label' => __pl('Active Tab Background Color '),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-accordion-tabs.active'=> 'background-color:{{val}}', '{{element}} .pagelayer-accordion-tabs:hover' => 'background-color:{{val}}'],
			),
			'tab_padding' => array(
				'type' => 'slider',
				'label' => __pl('tabs_padding_label'),
				'default' => 15,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-accordion-tabs' => 'padding: {{val}}px;'],
			),
			'collapse_title_typo' => array(
				'type' => 'typography',
				'label' => __pl('collapsse_title_typo'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-accordion-tabs' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		],
		'content_styles' => [
			'tabs_content_bg_color' => array(
				'type' => 'color',
				'label' => __pl('Background Color '),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-accordion_item .pagelayer-accordion-panel'=> 'background-color:{{val}}'],
			),
			'acc_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
				'show' => ['border_hover' => ''],
				'css' => ['{{element}} .pagelayer-accordion_item' => 'border-style: {{val}}', '{{element}} .pagelayer-accordion_item .pagelayer-accordion-panel' =>'border-style: {{val}}'],
			),
			'acc_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'default' => '1,1,1,1',
				'screen' => 1,
				'req' => [
					'!acc_border_type' => ''
				],
				'css' => ['{{element}} .pagelayer-accordion_item' => 'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px', '{{element}} .pagelayer-accordion_item .pagelayer-accordion-panel' => 'border-width: {{val[0]}}px 0 0 0'],
			),
			'acc_border_color' => array(
				'type' => 'color',
				'label' => __pl('border_color'),
				'default' => '#cccccc',
				'req' => [
					'!acc_border_type' => ''
				],
				'css' => ['{{element}} .pagelayer-accordion_item' => 'border-color: {{val}}', '{{element}} .pagelayer-accordion_item .pagelayer-accordion-panel' =>'border-color: {{val}}'],
			),
		],
		'styles' => [
			'icon_style' => __pl('icon'),
			'tabs_styles' => __pl('Tabs'),
			'content_styles' => __pl('Content'),
		],
	)
);

// Space
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_space', array(
		'name' => __pl('space'),
		'group' => 'other',
		'func' => 'pagelayer_sc_space',
		'html' => '<div class="pagelayer-space-holder"></div>',
		'params' => array(
			'height' => array(
				'type' => 'slider',
				'label' => __pl('Space Height'),
				'screen' => 1,
				'units' => ['px', '%'],
				'css' => ['{{element}} .pagelayer-space-holder' => 'height: {{val}};'],
				'default' => '10',
				'min' => 0,
				'max' => 1000,
				'step' => 1
			)
		)
	)
);

// Embed
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_embed', array(
		'name' => __pl('embed'),
		'group' => 'other',
		'func' => 'pagelayer_sc_embed',
		'innerHTML' => 'data',
		'html' => '<div if={{data}} class="pagelayer-embed-container">{{data}}</div>',
		'params' => array(
			'data' => array(
				'type' => 'textarea',
				'label' => __pl('embed_paste_code'),
				'default' => '<p>Paste HTML code here...</P>',
				'desc' => '',
			),
		)
	)
);

// Shortcodes
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_shortcodes', array(
		'name' => __pl('shortcodes'),
		'group' => 'other',
		'func' => 'pagelayer_sc_shortcodes',
		'innerHTML' => 'data',
		'holder' => '.pagelayer-shortcodes-container',
		'html' => '<div class="pagelayer-shortcodes-container">{{{shortcode}}}</div>',
		'params' => array(
			'data' => array(
				'type' => 'textarea',
				'label' => __pl('shortcodes_paste_code'),
				'desc' => 'Paste short codes here',
			),
		)
	)
);

// Google Maps
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_google_maps', array(
		'name' => __pl('Google Maps'),
		'group' => 'other',
		'func' => 'pagelayer_sc_google_maps',
		'innerHTML' => 'address',
		'html' => '<div class="pagelayer-google-maps-holder">
			<iframe marginheight="0" scrolling="no" marginwidth="0" frameborder="0" src="https://maps.google.com/maps?q={{address}}&t=m&z={{zoom}}&output=embed&iwloc=near" aria-label="{{address}}"></iframe>
		</div>',
		'params' => array(
			'address' => array(
				'type' => 'text',
				'label' => __pl('google_map_address_label'),
				'default' => 'New York, New York, USA',
				'desc' => __pl('google_map_address_desc')
			),
			'noscroll' => array(
				'type' => 'checkbox',
				'label' => __pl('google_map_noscroll'),
				'css' => ['{{element}} iframe' => 'pointer-events: none;'],
			),
			'zoom' => array(
				'type' => 'slider',
				'label' => __pl('google_map_zoom_label'),
				'default' => 10,
				'min' => 0,
				'max' => 20
			),
			'height' => array(
				'type' => 'slider',
				'label' => __pl('google_map_height'),
				'screen' => 1,
				'default' => 300,
				'min' => 100,
				'max' => 1000,
				'css' => ['{{element}} iframe' => 'height: {{val}}px'],
			),
		)
	)
);

// Testimonial
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_testimonial', array(
		'name' => __pl('testimonial'),
		'group' => 'other',
		'func' => 'pagelayer_sc_testimonial',
		'innerHTML' => 'quote_content',
		'html' => '<div if="{{quote_content}}" class="pagelayer-testimonial-content">{{quote_content}}</div>
		<div class="pagelayer-testimonial-author-details">
			<div class="pagelayer-{{image_position}}">
				<img if="{{avatar}}" class="pagelayer-img pagelayer-testimonial-image pagelayer-testimonial-{{img_shape}}" src="{{func_image}}" />
			</div>
			<div class="pagelayer-{{image_position}}">
				<div if="{{cite}}" class="pagelayer-testimonial-cite">
					<a if-ext="{{cite_url}}" class="pagelayer-ele-link" href="{{cite_url}}">
						<span class="pagelayer-testimonial-author">{{cite}}</span>
					</a>
					<span if="{{designation}}" class="pagelayer-testimonial-author-title">
						{{designation}}
					</span>
				</div>
			</div>
		</div>',
		'params' => array(
			'image_position' => array(
				'type' => 'select',
				'label' => __pl('position'),
				'default' => 'aside-position',
				'list' =>array(
					'aside-position' => __pl('aside'),
					'top-position' => __pl('top')
				),
			),
			'alignment' => array(
				'type' => 'radio',
				'label' => __pl('testimonial_alignment_label'),
				'default' => 'center',
				'css' =>'text-align:{{val}};',
				'screen' => 1,
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				),
			),
			'author-spacing' => array(
				'type' => 'slider',
				'label' => __pl('author_spacing'),
				'min' => '0',
				'max' => '100',
				'default' => '20',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-testimonial-author-details' => 'margin-top:{{val}}px;'],
			),
		),
		// Styles
		'content_style' => [
			'quote_content' => array(
				'type' => 'editor',
				'label' => __pl('testimonial_content_label'),
				'text' => __pl('Edit Testimonial content'),
				'edit' => '.pagelayer-testimonial-content',
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
			)
		],
		'avatar_style' => [
			'avatar' => array(
				'type' => 'image',
				'label' => __pl('testimonial_image_label'),
				'desc' => __pl('testimonial_image_desc'),
				'default' => PAGELAYER_URL.'/images/default-image.png',
			),
			'testimonial_image_size' => array(
				'label' => __pl('testimonial_image_sizes'),
				'type' => 'slider',
				'min' => 0,
				'max' => 500,
				'default' => 100,
				'screen' => 1,
				'css' => ['{{element}}  .pagelayer-testimonial-image' => 'width:{{val}}px !important; height:{{val}}px !important;'],
			),
			'img_shape' => array(
				'type' => 'select',
				'label' => __pl('image_shape'),
				'default' => 'circle',
				'list' =>array(
					'square' => __pl('square'),
					'circle' => __pl('circle'),
				),
			),
			'testimonial_border_type' => array(
				'type' => 'select',
				'label' => __pl('border_type'),
				'css' => ['{{element}} .pagelayer-testimonial-image' =>'border-style: {{val}};'],
				'list' => [
					'' => __pl('none'),
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				]
			),
			'testimonial_border_color' => array(
				'type' => 'color',
				'label' => __pl('testimonial_border_color_label'),
				'default' => '#42414f',
				'css' => ['{{element}} .pagelayer-testimonial-image' => 'border-color: {{val}};'],
				'req' => ['!testimonial_border_type' => '']
			),
			'testimonial_border_width' => array(
				'type' => 'padding',
				'label' => __pl('border_width'),
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-testimonial-image' =>'border-top-width: {{val[0]}}px; border-right-width: {{val[1]}}px; border-bottom-width: {{val[2]}}px; border-left-width: {{val[3]}}px;'],
				'req' => ['!testimonial_border_type' => '']
			),
			'testimonial_border_radius' => array(
				'type' => 'padding',
				'label' => __pl('border_radius'),
				'screen' => 1,
				'css' =>  ['{{element}} .pagelayer-testimonial-image' => 'border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px; -webkit-border-radius:  {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;-moz-border-radius: {{val[0]}}px {{val[1]}}px {{val[2]}}px {{val[3]}}px;'],
				'req' => ['!testimonial_border_type' => '']
			),
		],
		'cite_style' => [
			'cite' => array(
				'type' => 'text',
				'label' => __pl('testimonial_cite_label'),
				'default' => 'John Smith',
				'desc' => __pl('testimonial_cite_desc'),
				'edit' => '.pagelayer-testimonial-author',
			),
			'cite_color' => array(
				'type' => 'color',
				'label' => __pl('testimonial_name_color_label'),
				'default' => '#426870ff',
				'css' => ['{{element}}  .pagelayer-testimonial-author ' => 'color:{{val}}'],
			),
			'cite_style' => array(
				'type' => 'typography',
				'label' => __pl('cite_style'),
				'default' => ',20,,100,,none,,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-testimonial-author' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],			
			),			
			'cite_url' => array(
				'type' => 'link',
				'label' => __pl('testimonial_url_label'),
				'default' => '',
				'desc' => __pl('testimonial_url_desc'),
			),
			'cite_spacing' => array(
				'type' => 'padding',
				'label' => __pl('cite_spacing'),
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-testimonial-cite' => 'margin-top: {{val[0]}}px; margin-right: {{val[1]}}px; margin-bottom: {{val[2]}}px; margin-left: {{val[3]}}px'],
			),
		],
		'designation_style' => [
			'designation' => array(
				'type' => 'text',
				'label' => __pl('testimonial_designation_label'),
				'default' => 'Web Developer',
				'desc' => __pl('testimonial_cite_title_size_desc'),
				'edit' => '.pagelayer-testimonial-author-title',
			),
			'designation_color' => array(
				'type' => 'color',
				'label' => __pl('testimonial_title_color_label'),
				'default' => '#9cafc0ff',
				'css' => ['{{element}} .pagelayer-testimonial-author-title' => 'color:{{val}}'],
			),
			'cite_designation_style' => array(
				'type' => 'typography',
				'label' => __pl('cite_designation_style'),
				'default' => ',16,,100,,,,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-testimonial-author-title' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],			
			),
		],
		'styles' => [
			'avatar_style' => __pl('avatar_style'),
			'cite_style' => __pl('cite'),
			'designation_style' => __pl('designation'),
			'content_style' => __pl('content_style'),
		],

	)
);

// Progress object - Make a group
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_progress', array(
		'name' => __pl('Progress Bars'),
		'group' => 'other',
		'func' => 'pagelayer_sc_progress',
		'innerHTML' => 'progress_text',
		'html' =>'<div if="{{title}}" class="pagelayer-progress-title">{{title}}</div>
			<div class="pagelayer-progress-container">					
				<div if="{{progress_percentage}}" class="pagelayer-progress-bar pagelayer-progress-{{progress_type}}" style="width:{{progress_percentage}}%;">
					<span if="{{progress_text}}" class="pagelayer-progress-text">{{progress_text}}</span>
					<span if="{{progress_percentage}}" class="pagelayer-progress-percent"></span>
				</div>					
			</div>',
		'params' => array(
			'progress_type' => array(
				'type' => 'select',
				'label' => __pl('progress_type'),
				'default' => 'primary',
				'list' => [
					'primary' => __pl('Primary'),
					'secondary' => __pl('Secondary'),
					'success' => __pl('Success'),
					'warning' => __pl('Warning'),
					'danger' => __pl('Danger'),
					'' => __pl('custom')
				],
			),
			'progress_color' => array(
				'type' => 'color',
				'label' => __pl('progress_bar_color'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-progress-bar' => 'background-color:{{val}};'],
				'req' => ['progress_type' => '']
			),
			'progress_height' => array(
				'type' => 'slider',
				'label' => __pl('progress_height'),
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-progress-bar' => 'height:{{val}}px;',
					'{{element}} .pagelayer-progress-percent' => 'line-height:{{val}}px; font-size: calc({{val}}px / 2);',
					'{{element}} .pagelayer-progress-text' => 'line-height:{{val}}px; font-size: calc({{val}}px / 2);',
				],
				'default' => 40,
			),
		),
		// Styles
		'heading_style' => [
			'title' => array(
				'type' => 'text',
				'label' => __pl('progress_title'),
				'default' => 'Progress',
			),
			'title_color' => array(
				'type' => 'color',
				'label' => __pl('title_color'),
				'default' => '#768589ff',
				'css' => ['{{element}} .pagelayer-progress-title' => 'color:{{val}};'],
			),
			'title_style' => array(
				'type' => 'typography',
				'label' => __pl('title_size'),
				'default' => ',25,,100,,,,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-progress-title' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		],
		'percentage_style' => [
			'progress_text'=> array(
				'type' => 'text',
				'label' => __pl('progress_text'),
				'default' => 'Designing',
			),
			'progress_text_color' => array(
				'type' => 'color',
				'label' => __pl('progress_text_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-progress-text' => 'color:{{val}};'],
			),
			'progress_percentage' => array(
				'type' => 'slider',
				'label' => __pl('percentage'),
				'min' => 0,
				'max' => 100,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-progress-container > .pagelayer-progress-bar:after' => 'width:{{val}}%;',],
				'addAttr' => ['{{element}} .pagelayer-progress-bar' => 'pagelayer-progress-width="{{progress_percentage}}"'],
				'default' => 75,
			),
			'progress_percent_color' => array(
				'type' => 'color',
				'label' => __pl('progress_percent_color'),
				'default' => '#ffffff',
				'css' => ['{{element}} .pagelayer-progress-percent' => 'color:{{val}};'],
			),
			'hide_percentage' => array(
				'type' => 'checkbox',
				'label' => __pl('hide_percentage'),
				'screen' => 1,
				'default' => '',
				'css' => ['{{element}} .pagelayer-progress-percent' => 'display: none;']
			),
		],
		'styles' => [
			'heading_style' => __pl('heading_style'),
			'percentage_style' => __pl('percentage'),
		]
	)
);

// Color Block
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_block', array(
		'name' => __pl('Color Block'),
		'group' => 'other',
		'func' => 'pagelayer_sc_block',
		'params' => array(
			'block_color' => array(
				'type' => 'color',
				'label' => __pl('block_color'),
				'default' => '#CCC',
				'css' => ['{{element}}' => 'background:{{val}}'],
			),
			'block_height' => array(
				'type' => 'spinner',
				'label' => __pl('block_height'),
				'default' => '200',
				'screen' => 1,
				'min' => 1,
				'max' => 1000,
				'step' => 1,
				'css' => ['{{element}}' => 'height:{{val}}px'],
			),
		)
	)
);

// Alert
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_alert', array(
		'name' => __pl('Alert'),
		'group' => 'other',
		'func' => 'pagelayer_sc_alert',
		'innerHTML' => 'alert_content',
		'html' => '<div class="pagelayer-alert-content">
				<i if="{{alert_icon}}" class="pagelayer-alert-icon fa fa-{{alert_icon}}"></i>
				<div if="{{alert_title}}" class="pagelayer-alert-title">{{alert_title}}</div>
				<div if="{{is_dismissible}}" class="pagelayer-alert-close" onclick="pagelayer_dismiss_alert(this);"></div>
			</div>
			<span if="{{alert_content}}" class="pagelayer-alert-text">{{alert_content}}</span>',
		'params' => array(
			'alert_type' => array(
				'type' => 'select',
				'label' => __pl('Type'),
				'default' => 'alert-primary',
				'addClass' => 'pagelayer-{{val}}',
				'list' => array(
					'alert-primary' => __pl('alert_type_primary'),
					'alert-secondary' => __pl('alert_type_secondary'),
					'alert-success' => __pl('alert_type_success'),
					'alert-info' => __pl('alert_type_info'),
					'alert-warning' => __pl('alert_type_warning'),
					'alert-danger' => __pl('alert_type_danger'),
					'alert-dark' => __pl('alert_type_dark'),
					'alert-custom' => __pl('alert_type_custom'),
				)
			),
			'alert_bg_color' => array(
				'type' => 'color',
				'label' => __pl('alert_bg_color'),
				'css' => 'background-color: {{val}}',
				'req' => ['alert_type' => 'alert-custom']
			),
			'is_dismissible' => array(
				'type' => 'checkbox',
				'label' => __pl('is_dismissible'),
				'default' => 'true',
				'addClass' => 'pagelayer-alert-dismissible'
			)
		),
		'icon_style' => [
			'alert_icon' => array(
				'type' => 'icon',
				'label' => __pl('alert_icon'),
				'default' => 'exclamation',
			),
			'alert_icon_color' => array(
				'type' => 'color',
				'label' => __pl('alert_icon_color'),
				'css' => ['{{element}} .pagelayer-alert-icon' => 'color: {{val}}'],
			),
			'alert_font_size' => array(
				'label' => __pl('alert_font_size'),
				'type' => 'slider',
				'min' => 0,
				'max' => 500,
				'default' => 30,
				'screen' => 1,
				'css' => ['{{element}}  .pagelayer-alert-icon' => 'font-size:{{val}}px;'],
			),
			'alert_icon_spacing' => array(
				'label' => __pl('alert_icon_spacing'),
				'type' => 'slider',
				'min' => 0,
				'max' => 200,
				'default' => 5,
				'screen' => 1,
				'css' => ['{{element}}  .pagelayer-alert-icon' => 'margin-right:{{val}}px;'],
			),
		],
		'title_style' => [
			'alert_title' => array(
				'type' => 'text',
				'label' => __pl('alert_title'),
				'default' => 'This is an Alert',
				'edit' => '.pagelayer-alert-title',
			),
			'alert_title_color' => array(
				'type' => 'color',
				'label' => __pl('alert_title_color'),
				'default' => '',
				'css' => ['{{element}}  .pagelayer-alert-title' => 'color:{{val}}'],
			),
			'title_typo' => array(
				'type' => 'typography',
				'label' => __pl('title_typo'),
				'default' => ',22,,600,,,,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-alert-title' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		],
		'content_style' => [
			'alert_content' => array(
				'type' => 'textarea',
				'label' => __pl('alert_content'),
				'default' => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet, Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.',
				'edit' => '.pagelayer-alert-text',
			),
			'alert_content_color' => array(
				'type' => 'color',
				'label' => __pl('alert_content_color'),
				'default' => '',
				'css' => ['{{element}} .pagelayer-alert-text' => 'color:{{val}}',
					'{{element}} .pagelayer-alert-text *' => 'color:{{val}}'],
				'req' => ['!alert_content' => ''],
			),
			'content_typo' => array(
				'type' => 'typography',
				'label' => __pl('title_typo'),
				'default' => ',13,,,,,,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-alert-text' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],
			),
		],
		'styles' => [
			'icon_style' => __pl('icon'),
			'title_style' => __pl('title_style'),
			'content_style' => __pl('content_style'),
		],
	)
);

// Anchor
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_anchor', array(
		'name' => __pl('Anchor'),
		'group' => 'other',
		'func' => 'pagelayer_sc_anchor',
		'html' => '<div id="{{title}}" class="pagelayer-anchor-holder"></div>',
		'params' => array(
			'title' => array(
				'type' => 'text',
				'label' => __pl('Anchor ID'),
				'desc' => __pl('Note : Please enter the name of Unique ID that you want to use as an Anchor (Without #)'),
			),
		)
	)
);

// Star object
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_stars', array(
		'name' => __pl('Stars'),
		'group' => 'other',
		'func' => 'pagelayer_sc_stars',
		'html' => '<span if="{{rating_title}}" class="pagelayer-stars-title">{{rating_title}}</span>
					<div class="pagelayer-stars-container" title="{{number_of_ratings}}/{{number_of_stars}}" pagelayer-stars-value="{{number_of_ratings}}" pagelayer-stars-count="{{number_of_stars}}">					
					</div>',
		'params' => array(
			'number_of_stars' => array(
				'type' => 'spinner',
				'label' => __pl('stars_count'),
				'min' => 0,
				'max' => 10,
				'step' => 1,
				'default' => 5,
			),
			'number_of_ratings' => array(
				'type' => 'spinner',
				'label' => __pl('stars_rating'),
				'min' => 0,
				'max' => 10,
				'step' => .1,
				'default' => 2.5,				
			),
			'ratings_align' => array(
				'type' => 'radio',
				'label' => __pl('alignment'),
				'css' => ['{{element}}' => 'text-align: {{val}}'],
				'screen' => 1,
				'list' => array(
					'left' => 'Left',
					'center' => 'Center',
					'right' => 'Right'
				),				
			),								
		),
		'title_style' => [
			'rating_title' => array(
				'type' => 'text',
				'label' => __pl('rating_title'),
				'default' => 'Rate us',				
			),
			'title_color' => array(
				'type' => 'color',
				'label' => __pl('title_color'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-stars-title' => 'color: {{val}}'],
			),
			'title_style' => array(
				'type' => 'typography',
				'label' => __pl('counter_number_size'),
				'default' => ',25,,600,,,solid,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-stars-title' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],					
			),
		],
		'stars_style' => [
			'stars_color' => array(
				'type' => 'color',
				'label' => __pl('stars_color'),
				'default' => '#3e8ef7',
				'css' => ['{{element}} .pagelayer-stars-icon:before' => 'color: {{val}}'],
			),
			'unmarked_stars_color' => array(
				'type' => 'color',
				'label' => __pl('unmarked_star_color'),
				'default' => '#ccd6df',
				'css' => ['{{element}} .pagelayer-stars-container' => 'color: {{val}}'],
			),
			'stars_font_size' => array(
				'label' => __pl('stars_font_size'),
				'type' => 'slider',
				'min' => 0,
				'max' => 100,
				'default' => 30,
				'screen' => 1,
				'css' => ['{{element}}  .pagelayer-stars-container' => 'font-size:{{val}}px;'],
			),
			'stars_spacing' => array(
				'label' => __pl('stars_spacing'),
				'type' => 'slider',
				'min' => 0,
				'max' => 100,
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}}  .pagelayer-stars-icon' => 'margin-left:{{val}}px;'],
			),
		],		
		'styles' => [
			'title_style' => __pl('title'),
			'stars_style' => __pl('stars_style'),
		],		
	)
);

// Divider
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_divider', array(
		'name' => __pl('Divider'),
		'group' => 'other',
		'func' => 'pagelayer_sc_divider',
		'html' => '<div class="pagelayer-divider-holder">
			<span class="pagelayer-divider-seperator"></span>
		</div>',
		'params' => array(
			'divider_style' => array(
				'type' => 'select',
				'label' => __pl('divider_border_type'),
				'css' => ['{{element}} .pagelayer-divider-seperator' =>'border-top-style: {{val}};'],
				'default' => 'solid',
				'list' => [
					'solid' => __pl('solid'),
					'double' => __pl('double'),
					'dotted' => __pl('dotted'),
					'dashed' => __pl('dashed'),
					'groove' => __pl('groove'),
				],
			),
			'divider_color' => array(
				'type' => 'color',
				'label' => __pl('divider_color'),
				'default' => '#3E8EF7',
				'css' => ['{{element}} .pagelayer-divider-seperator' => 'border-top-color: {{val}};'],
			),
			'divider_weight' => array(
				'type' => 'slider',
				'label' => __pl('divider_border_weight'),
				'min' => 1,
				'max' => 30,
				'default' => 3,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-divider-seperator' =>'border-top-width: {{val}}px;'],
			),
			'divider_widht' => array(
				'type' => 'slider',
				'label' => __pl('divider_border_width'),
				'min' => 1,
				'max' => 100,
				'default' => 50,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-divider-seperator' =>'width: {{val}}%;'],
			),
			'divider_gap' => array(
				'type' => 'slider',
				'label' => __pl('divider_gap'),
				'min' => 1,
				'max' => 100,
				'default' => 10,
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-divider-holder' =>'padding-top: {{val}}px; padding-bottom: {{val}}px;'],
			),
			'divider_alignment' => array(
				'type' => 'radio',
				'label' => __pl('divider_alignment'),
				'default' => 'center',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-divider-holder' => 'text-align: {{val}};'],
				'list' => array(
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				)
			),
		),
	)
);

// Counter
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_counter', array(
		'name' => __pl('Counter'),
		'group' => 'other',
		'func' => 'pagelayer_sc_counter',
		'html' => '<div class="pagelayer-counter-holder">
			<div if="{{counter_start_number}}" class="pagelayer-counter-content">
				<span if="{{number_prefix}}">{{number_prefix}}</span><span class="pagelayer-counter-display">{{counter_start_number}}</span><span if="{{number_suffix}}">{{number_suffix}}</span>
			</div>
			<span if="{{counter_text}}" class="pagelayer-counter-info">{{counter_text}}</span>
		</div>',
		'params' => array(
			'counter_start_number' => array(
				'type' => 'spinner',
				'label' => __pl('starting_number'),
				'min' => '0',
				'default' => '1',
				'addAttr' => ['{{element}} .pagelayer-counter-display' => 'pagelayer-counter-initial-value="{{counter_start_number}}"'],
			),
			'counter_end_number' => array(
				'type' => 'spinner',
				'label' => __pl('Ending_number'),
				'min' => '0',
				'default' => '200',
				'addAttr' => ['{{element}} .pagelayer-counter-display' => 'pagelayer-counter-last-value="{{counter_end_number}}"'],	
			),
			'animation_duration' => array(
				'type' => 'spinner',
				'label' => __pl('counter_animation_duration'),
				'min' => '500',
				'max' => '500000',
				'default' =>'2000',
				'addAttr' => ['{{element}} .pagelayer-counter-display' => 'pagelayer-counter-animation-duration="{{animation_duration}}"'],
			),
			'counter_align' => array(
				'type' => 'radio',
				'label' => __pl('counter_align'),
				'default' => 'center',
				'css' => 'text-align: {{val}};',
				'screen' => 1,
				'list' => [
					'left' => __pl('left'),
					'center' => __pl('center'),
					'right' => __pl('right'),
				]			
			),
		),
		// Styles
		'counter_style' => [
			'counter_number_color' => array(
				'type' => 'color',
				'label' => __pl('counter_number_color_label'),
				'default' => '#3E8EF7',
				'css' => ['{{element}} .pagelayer-counter-content' => 'color:{{val}};'],
			),
			'number_prefix' => array(
				'type' => 'text',
				'label' => __pl('number_prefix'),
			),
			'number_suffix' => array(
				'type' => 'text',
				'label' => __pl('number_suffix'),
			),
			'thousand_seperator' => array(
				'type' => 'checkbox',
				'label' => __pl('thousand_seperator'),
				'addAttr' => ['{{element}} .pagelayer-counter-display' => 'pagelayer-counter-seperator="{{thousand_seperator}}"'],
			),
			'thousand_seperator_type' => array(
				'type' => 'select',
				'label' => __pl('thousand_seperator_type'),
				'default' => ',',
				'list' => [
					',' => __pl('Default'),
					'.' => __pl('Dot'),
					'&nbsp;' => __pl('Space'),
				],
				'addAttr' => ['{{element}} .pagelayer-counter-display' => 'pagelayer-counter-seperator-type="{{thousand_seperator_type}}"'],
				'req' => array(
					'thousand_seperator' => 'true',
				),
			),
			'counter_number_style' => array(
				'type' => 'typography',
				'label' => __pl('counter_number_size'),
				'default' => ',60,,600,,,solid,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-counter-content' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],					
			),
		],
		'counter_label_style' => [
			'counter_text' => array(
				'type' => 'text',
				'label' => __pl('counter_text'),
				'default' => 'Counter',
				'edit' => '.pagelayer-counter-info'
			),
			'counter_text_color' => array(
				'type' => 'color',
				'label' => __pl('counter_text_color_label'),
				'default' => '#333333',
				'css' => ['{{element}} .pagelayer-counter-info' => 'color:{{val}};'],
			),
			'counter_text_style' => array(
				'type' => 'typography',
				'label' => __pl('counter_text_style'),
				'default' => ',25,,400,,,solid,,,,',
				'screen' => 1,
				'css' => ['{{element}} .pagelayer-counter-info' => 'font-family: {{val[0]}}; font-size: {{val[1]}}px !important; font-style: {{val[2]}} !important; font-weight: {{val[3]}} !important; font-variant: {{val[4]}} !important; text-decoration-line: {{val[5]}} !important; text-decoration-style: {{val[6]}} !important; line-height: {{val[7]}}em !important; text-transform: {{val[8]}} !important; letter-spacing: {{val[9]}}px !important; word-spacing: {{val[10]}}px !important;'],					
			),			
		],		
		'styles' => [
			'counter_label_style' => __pl('counter_label_style'),
			'counter_style' => __pl('counter_style'),
		],
	)			
);



////////////////////////
// WordPress Group
////////////////////////

// Make a list of Widget Items
global $wp_registered_sidebars;
$pagelayer_wp_widgets = array();
$pagelayer_wp_widget_default = '';

if(!empty($wp_registered_sidebars)){
	foreach($wp_registered_sidebars as $v){
		if(empty($pagelayer_wp_widget_default)){
			$pagelayer_wp_widget_default = $v['id'];
		}
		$pagelayer_wp_widgets[$v['id']] = $v['name'];
	}
}else{
	$pagelayer_wp_widgets['no'] = __pl('wp_widgets_area_no_sidebars');
}

// Widgets Area
pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_wp_widgets', array(
		'name' => __pl('Sidebars / Widget Area'),
		'group' => 'wordpress',
		'func' => 'pagelayer_sc_wp_widgets',
		'html' => '<div class="pagelayer-wp-sidebar-title">{{title}}</div>
			<div class="pagelayer-wp-sidebar-holder">{{{data}}}</div>',
		'params' => array(
			'title' => array(
				'type' => 'text',
				'label' => __pl('parameters_title'),
				'default' => 'Title',
				'desc' => __pl('wp_widgets_area_description')
			),
			'sidebar' => array(
				'type' => 'select',
				'label' => __pl('wp_widgets_area_select'),
				'default' => $pagelayer_wp_widget_default,
				'desc' => '',
				'list' => $pagelayer_wp_widgets
			)
		)
	)
);

// Load the wordpress widgets, IF ALLOWED !
if(current_user_can('edit_theme_options')){
	
	// Include the widgets
	//include_once(ABSPATH . 'wp-admin/includes/widgets.php');
	
	//pagelayer_print($GLOBALS['wp_widget_factory']->widgets);die();
	
	foreach($GLOBALS['wp_widget_factory']->widgets as $widget_key => $widget){
		
		pagelayer_add_shortcode(PAGELAYER_SC_PREFIX.'_wp_'.$widget->id_base, array(
				'name' => $widget->name,
				'group' => 'wordpress',
				'func' => 'pagelayer_does_not_exist',
				'innerHTML' => 'widget_data',
				'widget' => $widget_key
			)
		);
		
	}
}

// Its premium
if(defined('PAGELAYER_PREMIUM')){
	include_once(dirname(__FILE__).'/premium.php');
}
