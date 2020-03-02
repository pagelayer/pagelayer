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

// Is there a tag ?
function pagelayer_render_shortcode($atts, $content = '', $tag = ''){

	global $pagelayer;
	
	$_tag = $class = $tag;
	$final_tag = $tag;
	
	// Check if the tag is inner row and col then change it to row and col tag
	if($tag == 'pl_inner_row'){
		$tag = 'pl_row';
	}elseif($tag == 'pl_inner_col'){
		$tag = 'pl_col';
		$final_tag = $tag;
	}
	
	// Clear the pagelayer tags
	if(substr($tag, 0, 3) == 'pl_'){
		$_tag = str_replace('pl_', '', $final_tag);
		$class = 'pagelayer-'.$_tag;
	}
	
	// Is there any function ?
	$func = @$pagelayer->shortcodes[$tag]['func'];
	$atts = (array) $atts;
	// Create the element array. NOTE : This is similar to the JS el and is temporary
	$el = [];
	$el['atts'] = $atts;
	$el['oAtts'] = $atts;
	$el['id'] =  !empty($atts['pagelayer-id']) ? $atts['pagelayer-id'] : pagelayer_RandomString(16);
	$el['tmp'] = [];
	$el['tag'] = $final_tag;
	$el['content'] = $content;
	$el['selector'] = '[pagelayer-id="'.$el['id'].'"]';
	$el['wrap'] = '[pagelayer-wrap-id="'.$el['id'].'"]';
	
	// Remove pagelayer-id from attr
	if( !empty($atts['pagelayer-id']) ){
		unset($el['atts']['pagelayer-id']);
		unset($el['oAtts']['pagelayer-id']);
	}
	
	$innerHTML = @$pagelayer->shortcodes[$tag]['innerHTML'];
	if(!empty($innerHTML) && !empty($content)){
		$el['oAtts'][$innerHTML] = $content;
		$el['atts'][$innerHTML] = $content;
	}
	
	// The default class
	$el['classes'][] = $class;
	
	//pagelayer_print($el);
	
	// Lets create the CSS, Classes, Attr. Also clean the dependent atts
	foreach($pagelayer->tabs as $tab){
		
		if(empty($pagelayer->shortcodes[$tag][$tab])){
			continue;
		}
		
		foreach($pagelayer->shortcodes[$tag][$tab] as $section => $Lsection){
			
			$props = empty($pagelayer->shortcodes[$tag][$section]) ? @$pagelayer->styles[$section] : @$pagelayer->shortcodes[$tag][$section];
			
			//echo $tab.' - '.$section.' - <br>';
			
			if(empty($props)){
				continue;
			}
			
			foreach($props as $prop => $param){
			
				//echo $tab.' - '.$section.' - '.$prop.'<br>';
				
				// No value set
				if(empty($el['atts'][$prop]) && empty($el['atts'][$prop.'_tablet']) && empty($el['atts'][$prop.'_mobile'])){
					continue;
				}
				
				// Clean the not required atts
				if(!empty($param['req'])){
					
					$set = true;
					
					foreach($param['req'] as $rk => $reqval){
						$except = $rk{0} == '!' ? true : false;
						$rk = $except ? substr($rk, 1) : $rk;
						$val = @$el['atts'][$rk];
						
						//echo $prop.' - '.$rk.' : '.$reqval.' == '.$val.'<br>';
						
						// The value should not be there
						if($except){
							
							if(!is_array($reqval) && $reqval == $val){
								$set = false;
								break;
							}
							
							// Its an array and a value is found, then dont show
							if(is_array($reqval) && in_array($val, $reqval)){
								$set = false;
								break;
							}
							
						// The value must be equal
						}else{
							
							 if(!is_array($reqval) && $reqval != $val){
								$set = false;
								break;
							 }
							
							// Its an array and no value is found, then dont show
							if(is_array($reqval) && !in_array($val, $reqval)){
								$set = false;
								break;
							}
						}
						
					}
					
					// Unset as we dont need
					if(empty($set)){
						unset($el['atts'][$prop]);
						unset($el['atts'][$prop.'_tablet']);
						unset($el['atts'][$prop.'_mobile']);
					}
					
				}
				
				// We could have unset the value above, so we need to check again if the value is there
				if(empty($el['atts'][$prop]) && empty($el['atts'][$prop.'_tablet']) && empty($el['atts'][$prop.'_mobile'])){
					continue;
				}
				
				// Handle the edit fields
				if(!empty($param['edit'])){
					$el['edit'][$prop] = $param['edit'];
				}
				
				// Backward compatibility of row
				if($el['tag'] == 'pl_row' && $prop == 'content_pos' && !empty($el['atts'][$prop])){
					if($el['atts'][$prop] == 'baseline'){
						$el['atts'][$prop] = $el['oAtts'][$prop] = 'flex-start';
					}else if($el['atts'][$prop] == 'end'){
						$el['atts'][$prop] = $el['oAtts'][$prop] = 'flex-end';
					}
				}
				
				// Backward compatibility of Icons
				if($param['type'] == 'icon' && !empty($el['atts'][$prop]) && !preg_match('/\s/', $el['atts'][$prop])){
					$el['atts'][$prop] = $el['oAtts'][$prop] = 'fa fa-'.$el['atts'][$prop];
				}
				
				// Backward compatibility of Box Shadow
				if($param['type'] == 'box_shadow' && !empty($el['atts'][$prop]) && substr_count($el['atts'][$prop], ',') == 3){
					$el['atts'][$prop] = $el['oAtts'][$prop] = $el['atts'][$prop].',0,';
				}
				
				// Backward compatibility of units. And also for the default set value if it is numeric
				if(!empty($param['units']) && isset($el['atts'][$prop]) && is_numeric($el['atts'][$prop])){
					$el['atts'][$prop] = $el['oAtts'][$prop] = $el['atts'][$prop].$param['units'][0];
				}
				
				// Load any attachment values
				if(in_array($param['type'], ['image', 'video', 'audio', 'media'])){
					
					$attachment = ($param['type'] == 'image') ? pagelayer_image($el['atts'][$prop]) : pagelayer_attachment($el['atts'][$prop]);
	
					if(!empty($attachment)){
						foreach($attachment as $k => $v){
							$el['tmp'][$prop.'-'.$k] = $v;
						}
					}
					
				}
				
				// Handle the AddClasses
				if(!empty($param['addClass']) && !empty($el['atts'][$prop])){
					
					// Convert to an array
					if(!is_array($param['addClass'])){
						$param['addClass'] = array($param['addClass']);
					}
					
					// Loop through
					foreach($param['addClass'] as $k => $v){
						$k = str_replace('{{element}}', '', $k);
						$el['classes'][] = [trim($k) => str_replace('{{val}}', $el['atts'][$prop], $v)];
					}
					
				}
				
				// Handle the AddClasses
				if(!empty($param['addAttr']) && !empty($el['atts'][$prop])){
					
					// Convert to an array
					if(!is_array($param['addAttr'])){
						$param['addAttr'] = array($param['addAttr']);
					}
					
					// Loop through
					foreach($param['addAttr'] as $k => $v){
						$k = str_replace('{{element}}', '', $k);
						$el['attr'][] = [trim($k) => $v];
					}
					
				}
				
				// Handle the CSS
				if(!empty($param['css'])){
					//echo $prop.'<br>';
					// Convert to an array
					if(!is_array($param['css'])){
						$param['css'] = array($param['css']);
					}
					
					$modes = [
						'desktop' => '', 
						'tablet' => '_tablet', 
						'mobile' => '_mobile'
					];
					
					// Loop the modes and check for values
					foreach($modes as $mk => $mv){
						
						$M_prop = $prop.$mv;
						
						// Any value ?
						if(empty($el['atts'][$M_prop])){
							continue;
						}
						
						// Loop through
						foreach($param['css'] as $k => $v){
							
							// Make the selector
							$selector = (!is_numeric($k) ? $k : $el['selector']);
							$selector = pagelayer_parse_el_vars($selector, $el);
							
							$ender = '';
							
							if($mk == 'tablet'){
								$selector = '@media (max-width: '.$pagelayer->settings['tablet_breakpoint'].'px) and (min-width: '.($pagelayer->settings['mobile_breakpoint'] + 1).'px){'.$selector;
								$ender = '}';
							}
							
							if($mk == 'mobile'){
								$selector = '@media (max-width: '.$pagelayer->settings['mobile_breakpoint'].'px){'.$selector;
								$ender = '}';
							}
							
							if(!empty($selector)){
							// Make the CSS
								$el['css'][] = $selector.'{'.pagelayer_css_render($v, $el['atts'][$M_prop], @$param['sep']).'}'.$ender;
							}else{
								$el['css'][] = pagelayer_parse_el_vars($el['atts'][$M_prop],$el);
							}
						}
					
					}
					
				}
				
				if($param['type'] == 'typography'){
					$val = explode(',', $el['atts'][$prop]);
					
					if(!empty($val[0]) && !in_array($val[0], $pagelayer->runtime_fonts)){
						$pagelayer->runtime_fonts[] = $val[0];
						//pagelayer_print($pagelayer->runtime_fonts);
					}
				}
				
			}
			
		}
		
	}
	
	//@pagelayer_print($el['css']);
	
	// Is there a function of the tag ?
	if(function_exists($func)){
		call_user_func_array($func, array(&$el));
	}
	
	// Create the default atts and tmp atts
	if(pagelayer_is_live()){
		pagelayer_create_sc($el);
	}
	
	$div = '<div pagelayer-id="'.$el['id'].'">
			<style pagelayer-style-id="'.$el['id'].'"></style>';
	
	$is_group = !empty($pagelayer->shortcodes[$tag]['params']['elements']) ? true : false;
	
	// If there is an HTML AND you are not a GROUP, then make use of it, or append the real content
	if(!empty($pagelayer->shortcodes[$tag]['html'])){
		
		// Create the HTML object
		$node = pQuery::parseStr($pagelayer->shortcodes[$tag]['html']);
		
		// Remove the if-ext
		foreach($node('[if-ext]') as $v){
			$reqvar = pagelayer_var($v->attr('if-ext'));
			$v->removeAttr('if-ext');
			
			// Is the element there ?
			if(empty($el['atts'][$reqvar])){
				$v->after($v->html());
				$v->remove();
			}
		}
		
		// Remove the if
		foreach($node('[if]') as $v){
			$reqvar = pagelayer_var($v->attr('if'));
			$v->removeAttr('if');
			
			// Is the element there ?
			if(empty($el['atts'][$reqvar])){
				$v->remove();
			}
		}
		
		//die($node->html());
		
		// Do we have a holder ? Mainly for groups
		if(!empty($pagelayer->shortcodes[$tag]['holder'])){
			$node->query($pagelayer->shortcodes[$tag]['holder'])->html('{{pagelayer_do_shortcode}}');
			$do_shortcode = 1;
		}
		
		$html = pagelayer_parse_vars($node->html(), $el);
		
		// Append to the DIV
		$div .= $html;
		
	// Is it a widget ?
	}elseif(!empty($pagelayer->shortcodes[$tag]['widget'])){
		
		$class = $pagelayer->shortcodes[$tag]['widget'];
		$instance = [];
		
		// Is there any existing data ?
		if(!empty($el['atts']['widget_data'])){		
			$json = trim($el['atts']['widget_data']);
			$json = json_decode($json, true);
			//pagelayer_print($json);die();
			if(!empty($json)){
				$instance = $json;
			}
		}
		
		ob_start();
		the_widget($class, $instance, array('widget_id'=>'arbitrary-instance-'.$el['id'],
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => ''
		));
		
		$div .= ob_get_contents();
		ob_end_clean();
		
	}else{
		$div .= '{{pagelayer_do_shortcode}}';
		$do_shortcode = 1;
	}
	
	// End the tag
	$div .= '</div>';
	
	// Add classes and attributes
	if(!empty($el['classes']) || !empty($el['attr'])){
	
		// Create the HTML object
		$node = pQuery::parseStr($div);
		
		// Add the editable values
		if(!empty($el['edit'])){
			
			foreach($el['edit'] as $k => $v){
				$node->query($v)->attr('pagelayer-editable', $k);
			}
			
		}
		
		// Add the classes
		if(!empty($el['classes'])){
			
			//pagelayer_print($el['classes']);
			
			foreach($el['classes'] as $k => $v){
				
				if(!is_array($v)){
					$v = [$v];
				}
				
				foreach($v as $kk => $vv){
					//echo $kk.' - '.$vv."\n";
					if(is_numeric($kk)){
						$node->query($el['selector'])->addClass($vv);
					}else{
						$node->query($kk)->addClass($vv);
					}
					
				}
				
			}
			
			//echo $node->html();
			//die();
			
		}
	
		// Add the attributes		
		if(!empty($el['attr'])){
			
			//pagelayer_print($el['attr']);
			
			foreach($el['attr'] as $k => $v){
				
				if(!is_array($v)){
					$v = [$v];
				}
				
				foreach($v as $kk => $vv){
					
					$att = explode('=', $vv, 2);
					$att[1] = pagelayer_parse_vars($att[1], $el);
					$att[1] = trim($att[1], '"');
					
					if(is_numeric($kk)){
						$node->query($el['selector'])->attr($att[0], $att[1]);
					}else{
						$node->query($kk)->attr($att[0], $att[1]);
					}
					
				}
				
			}
			
		}
		
		$div = $node->html();
		//die($div);
	
	}
		
	// Add the CSS if any or remove it
	$style = '';
	if(!empty($el['css'])){
		
		$style = '<style pagelayer-style-id="'.$el['id'].'">
'.implode("\n", pagelayer_parse_vars($el['css'], $el)).'
</style>';
	
		if(!empty($pagelayer->shortcodes[$tag]['overide_css_selector'])){
			$style = str_replace($el['selector'], $pagelayer->shortcodes[$tag]['overide_css_selector'], $style);
			$style = str_replace($el['wrap'], $pagelayer->shortcodes[$tag]['overide_css_selector'], $style);
		}
		
	}
	
	$div = str_replace('<style pagelayer-style-id="'.$el['id'].'"></style>', $style, $div);
	
	// Is there an inner content which requires a SHORTCODE ?
	if(!empty($do_shortcode)){
		$div = str_replace('{{pagelayer_do_shortcode}}', do_shortcode($el['content']), $div);
	}
	
	return $div;
	
}

// Creates the shortcode and returns a base64 encoded files
function pagelayer_create_sc(&$el){
	
	$a = $tmp = array();
	
	if(!empty($el['oAtts'])){
		
		foreach($el['oAtts'] as $k => $v){
			$el['attr'][] = 'pagelayer-a-'.$k.'="'.$v.'"';
		}
		
	}
	
	// Tmp atts
	if(!empty($el['tmp'])){
		
		foreach($el['tmp'] as $k => $v){
			$el['attr'][] = 'pagelayer-tmp-'.$k.'="'.$v.'"';
		}
		
	}
	
	// Add the tag
	$el['attr'][] = 'pagelayer-tag="'.$el['tag'].'"';
	
	// Make it a PageLayer element for editing
	$el['classes'][] = 'pagelayer-ele';
	
}

// Converts {{val}} to val
function pagelayer_var($var){
	return substr($var, 2, -2);
}

// Replace the variables
function pagelayer_parse_el_vars($str, &$el){
	
	$str = str_replace('{{element}}', $el['selector'], $str);
	$is_live = pagelayer_is_live();
	if(!empty($is_live)){
		$str = str_replace('{{wrap}}', $el['wrap'], $str);
	}else{
		$str = str_replace('{{wrap}}', $el['selector'], $str);
	}
	$str = str_replace('{{ele_id}}', $el['id'], $str);
	
	return $str;

}

// Parse the variables
function pagelayer_parse_vars($str, &$el){
	
	//pagelayer_print($el);
	if(is_array($el['tmp'])){
		foreach($el['tmp'] as $k => $v){
			$str = str_replace('{{{'.$k.'}}}', $el['tmp'][$k], $str);
		}
	}
	
	if(is_array($el['atts'])){
		foreach($el['atts'] as $k => $v){
			$str = str_replace('{{'.$k.'}}', $el['atts'][$k], $str);
		}
	}
	
	return $str;
}

// Make the rule
function pagelayer_css_render($rule, $val, $sep = ','){
	
	// Seperator
	$sep = empty($sep) ? ',' : $sep;
	
	// Replace the val
	$rule = str_replace('{{val}}', pagelayer_hex8_to_rgba($val), $rule);
	
	// If there is an array
	if(preg_match('/\{val\[\d/is', $rule)){
		$val = explode($sep, $val);
		foreach($val as $k => $v){
			$rule = str_replace('{{val['.$k.']}}', pagelayer_hex8_to_rgba($v), $rule);
		}
	}
	
	return $rule;
	
}

// Post Property Handler
function pagelayer_sc_body(&$el){
	
	global $post;
	
	$el['oAtts']['post_title'] = $post->post_title;
	$el['oAtts']['post_name'] = $post->post_name;
	$el['oAtts']['post_excerpt'] = $post->post_excerpt;
	$el['oAtts']['post_status'] = $post->post_status;
	$el['oAtts']['featured_image'] = get_post_thumbnail_id($post);
	
	// Load featured image details
	if(!empty($el['oAtts']['featured_image'])){
		
		$attachment = pagelayer_image($el['oAtts']['featured_image']);

		if(!empty($attachment)){
			foreach($attachment as $k => $v){
				$el['tmp']['featured_image-'.$k] = $v;
			}
		}
	
	}
	
}

// ROW Handler
function pagelayer_sc_row(&$el){
	
	pagelayer_bg_video($el);
	
	if(!empty($el['atts']['row_shape_type_top'])){
		$path_top = PAGELAYER_DIR.'/images/shapes/'.$el['atts']['row_shape_type_top'].'-top.svg';
		$el['atts']['svg_top'] = file_get_contents($path_top);
	}
	
	if(!empty($el['atts']['row_shape_type_bottom'])){
		$path_bottom = PAGELAYER_DIR.'/images/shapes/'.$el['atts']['row_shape_type_bottom'].'-bottom.svg';
		$el['atts']['svg_bottom'] = file_get_contents($path_bottom);
	}
	
	// Row background slider
	if(!empty($el['atts']['bg_slider'])){
		$ids = explode(',', $el['atts']['bg_slider']);
		$urls = [];
		$el['atts']['slider'] = '';
		
		// Make the image URL
		foreach($ids as $k => $v){
			
			$image = pagelayer_image($v);
			$urls['i'.$v] = @$image['url'];
			
			$el['atts']['slider'] .= '<div class="pagelayer-bgimg-slide" style="background-image:url(\''.$image['url'].'\')"></div>';
			
		}
		
		if(!empty($urls)){
			$el['tmp']['bg_slider-urls'] = json_encode($urls);
		}
		
	}
}

// Column Handler
function pagelayer_sc_col(&$el){
	
	// Add the default col class
	$el['classes'][] = 'pagelayer-col';
	
	//return do_shortcode($el['content']);
	
	pagelayer_bg_video($el);
	
	// Column background slider
	if(!empty($el['atts']['bg_slider'])){
		$ids = explode(',', $el['atts']['bg_slider']);
		$urls = [];
		$el['atts']['slider'] = '';
		
		// Make the image URL
		foreach($ids as $k => $v){
			
			$image = pagelayer_image($v);
			$urls['i'.$v] = @$image['url'];
			
			$el['atts']['slider'] .= '<div class="pagelayer-bgimg-slide" style="background-image:url(\''.$image['url'].'\')"></div>';
			
		}
		
		if(!empty($urls)){
			$el['tmp']['bg_slider-urls'] = json_encode($urls);
		}
		
	}
	
}

// Just for BG handling
function pagelayer_bg_video(&$el){
	
	if(empty($el['tmp']['bg_video_src-url'])){
		return false;
	}
	
	// Get the video URL for the iframe
	$iframe_src = pagelayer_video_url($el['tmp']['bg_video_src-url']);
	
	$source = esc_url( $el['tmp']['bg_video_src-url'] );
	$source = str_replace('&amp;', '&', $source);
	$url = parse_url($source);

	$youtubeRegExp = '/youtube\.com|youtu\.be/is';
	$vimeoRegExp = '/vimeo\.com/is';
	
	if (!empty($source)) {
		
		if (preg_match($youtubeRegExp, $source)) {
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
			
			$el['atts']['vid_src'] = '<iframe src="'.$iframe_src.'?autoplay=1&controls=0&showinfo=0&rel=0&loop=1&autohide=1&playlist='.$videoId.'" allowfullscreen="1" webkitallowfullscreen="1" mozallowfullscreen="1" frameborder="0"></iframe>';
			
		} else if (preg_match($vimeoRegExp, $source)) {
			
			$el['atts']['vid_src'] = '<iframe src="'.$iframe_src.'?background=1&autoplay=1&loop=1&byline=0&title=0" allowfullscreen="1" webkitallowfullscreen="1" mozallowfullscreen="1" frameborder="0"></iframe>';
			
		}else{
			
			$el['atts']['vid_src'] = '<video autoplay loop>'.
					'<source src="'.$iframe_src.'" type="video/mp4">'.
				'</video>';
			
		}
	}
}

// Image Handler
function pagelayer_sc_social(&$el){
	$icon = explode(' fa-', $el['atts']['icon']);
	$el['classes'][] = ['.pagelayer-icon-holder' => 'pagelayer-'.$icon[1]];
}

// Image Handler
function pagelayer_sc_image(&$el){
	
	// Decide the image URL
	$el['atts']['func_id'] = @$el['tmp']['id-'.$el['atts']['id-size'].'-url'];
	$el['atts']['func_id'] = empty($el['atts']['func_id']) ? @$el['tmp']['id-url'] : $el['atts']['func_id'];
	
	// What is the link ?
	if(!empty($el['atts']['link_type'])){
		
		// Custom url
		if($el['atts']['link_type'] == 'custom_url'){
			$el['atts']['func_link'] = $el['atts']['link'];
		}
		
		// Link to the media file itself
		if($el['atts']['link_type'] == 'media_file'){
			$el['atts']['func_link'] = $el['atts']['func_id'];
		}
		
		// Lightbox
		if($el['atts']['link_type'] == 'lightbox'){
			$el['atts']['func_link'] = $el['atts']['func_id'];
		}
		
	}
	
	//pagelayer_print($el);
	
}

// Image Slider Handler
function pagelayer_sc_image_slider(&$el){
	
	$ids = explode(',', $el['atts']['ids']);
	$urls = [];
	$all_urls = [];
	$final_urls = [];
	$ul = [];
	$size = $el['atts']['size'];
	
	// Make the image URL
	foreach($ids as $k => $v){
		
		$image = pagelayer_image($v);
		
		$final_urls[$v] = empty($image[$size.'-url']) ? @$image['url'] : $image[$size.'-url'];
		
		$urls['i'.$v] = @$image['url'];
		
		foreach($image as $kk => $vv){
			$si = strstr($kk, '-url', true);
			if(!empty($si)){
				$all_urls['i'.$v][$si] = $vv;
			}
		}
		
		$li = '<li class="pagelayer-slider-item">';
		
		// Any Link ?
		if(!empty($el['atts']['link_type'])){
			$link = ($el['atts']['link_type'] == 'media_file' ? $final_urls[$v] : @$el['atts']['link']);
			$li .= '<a href="'.$link.'">';
		}
		
		// The Image
		$li .= '<img class="pagelayer-img" src="'.$final_urls[$v].'">';
		
		if(!empty($el['atts']['link_type'])){
			$li .= '</a>';
		}
		
		$li .= '</li>';
		
		$ul[] = $li;
		
	}
	
	//pagelayer_print($urls);
	//pagelayer_print($final_urls);
	//pagelayer_print($all_urls);
	
	// Make the TMP vars
	if(!empty($urls)){
		$el['tmp']['ids-urls'] = json_encode($urls);
		$el['tmp']['ids-all-urls'] = json_encode($all_urls);
		$el['atts']['ul'] = implode('', $ul);
	
		// Which arrows to show
		if(in_array(@$el['atts']['controls'], ['arrows', 'none'])){
			$el['attr'][] = ['.pagelayer-image-slider-ul' => 'data-pager="false"'];
		}
		
		if(in_array(@$el['atts']['controls'], ['pager', 'none'])){
			$el['attr'][] = ['.pagelayer-image-slider-ul' => 'data-controls="false"'];
		}
	}
	
};

//Grid Gallery Handler
function pagelayer_sc_grid_gallery(&$el){
	
	$ids = explode(',', $el['atts']['ids']);
	$urls = [];
	$all_urls = [];
	$final_urls = [];
	$ul = [];
	$size = $el['atts']['size'];
	$i = 0;
	$col = $el['atts']['columns'];
	$gallery_rand = 'gallery-id-'.floor((rand() * 100) + 1);
	
	$ul[] = '<ul class="pagelayer-grid-gallery-ul">';
	// Make the image URL
	foreach($ids as $k => $v){
		
		$image = pagelayer_image($v);
		
		$final_urls[$v] = empty($image[$size.'-url']) ? @$image['url'] : $image[$size.'-url'];
		
		$urls['i'.$v] = @$image['url'];
		$links['i'.$v] = @$image['link'];
		$titles['i'.$v] = @$image['title'];
		$captions['i'.$v] = @$image['caption'];
		
		foreach($image as $kk => $vv){
			$si = strstr($kk, '-url', true);
			if(!empty($si)){
				$all_urls['i'.$v][$si] = $vv;
			}
		}
		
		/* if(($i % $col) == 0 && $i != 0 ){
			$ul[] = '</ul><ul class="pagelayer-grid-gallery-ul">';
		} */
		
		$li = '<li class="pagelayer-gallery-item" >';
		
		if(empty($el['atts']['link_to'])){
			$li .= '<div>';
		}
		
		// Any Link ?
		if(!empty($el['atts']['link_to']) &&  $el['atts']['link_to'] == 'media_file'){
			$link = ($el['atts']['link_to'] == 'media_file' ? $final_urls[$v] : @$el['atts']['link']);
			$li .= '<a href="'.$link.'" class="pagelayer-ele-link">';
		}
		
		// Any Link ?
		if(!empty($el['atts']['link_to']) &&  $el['atts']['link_to'] == 'attachment' ){
			$link = $image['link'];
			$li .= '<a href="'.$link.'" class="pagelayer-ele-link">';
		}
		
		if(!empty($el['atts']['link_to']) && $el['atts']['link_to'] == 'lightbox'){			
			$li .= '<a href="'.$image['url'].'" data-lightbox-gallery="'.$gallery_rand.'" alt="'.$image['alt'].'" class="pagelayer-ele-link" pagelayer-grid-gallery-type="'.$el['atts']['link_to'].'">';
		}
		// The Image
		$li .= '<img class="pagelayer-img" src="'.$final_urls[$v].'" title="'.$image['title'].'" alt="'.$image['alt'].'">';
		
		if(!empty($el['atts']['caption'])){
			$li .= '<span class="pagelayer-grid-gallery-caption">'.$image['caption'].'</span>';
		}
		
		if(!empty($el['atts']['link_to'])){
			$li .= '</a>';
		}
		
		if(empty($el['atts']['link_to'])){
			$li .= '</div>';
		}
		
		$li .= '</li>';
		
		$ul[] = $li;
		$i++;
	}
	
	$ul[] = '</ul>';
	
	//pagelayer_print($urls);
	//pagelayer_print($final_urls);
	//pagelayer_print($all_urls);
	
	// Make the TMP vars
	if(!empty($urls)){
		$el['tmp']['ids-urls'] = json_encode($urls);
		$el['tmp']['ids-all-urls'] = json_encode($all_urls);
		$el['tmp']['ids-all-links'] = json_encode($links);
		$el['tmp']['ids-all-titles'] = json_encode($titles);
		$el['tmp']['ids-all-captions'] = json_encode($captions);
		$el['atts']['ul'] = implode('', $ul);
		$el['tmp']['gallery-random-id'] = $gallery_rand;
	
	}
}

// Testimonial Handler
function pagelayer_sc_testimonial(&$el){
	
	$el['atts']['func_image'] = @$el['tmp']['avatar-'.$el['atts']['custom_size'].'-url'];
	$el['atts']['func_image'] = empty($el['atts']['func_image']) ? @$el['tmp']['avatar-url'] : $el['atts']['func_image'];
	
	if(!empty($image)){
		foreach($image as $k => $v){
			$el['tmp']['avatar-'.$k] = $v;
		}
	}
	
}

// Video Handler
function pagelayer_sc_video(&$el){
	
	$el['atts']['video_overlay_image-url'] = empty($el['tmp']['video_overlay_image-'.$el['atts']['custom_size'].'-url']) ? $el['tmp']['video_overlay_image-url'] : $el['tmp']['video_overlay_image-'.$el['atts']['custom_size'].'-url'];
	$el['atts']['video_overlay_image-url'] = empty($el['atts']['video_overlay_image-url']) ? $el['atts']['video_overlay_image'] : $el['atts']['video_overlay_image-url'];
	
	// Get the video URL for the iframe
	$el['atts']['vid_src'] = pagelayer_video_url($el['tmp']['src-url']);
	
	if($el['atts']['autoplay'] == "true"){
		$el['atts']['vid_src'] .="?&autoplay=1";
	}else{
		$el['atts']['vid_src'] .="?&autoplay=0";
	}

	if($el['atts']['mute'] == "true"){
		$el['atts']['vid_src'] .="&mute=1";
	}else{
		$el['atts']['vid_src'] .="&mute=0";
	}

	if($el['atts']['loop'] == "true"){
		$el['atts']['vid_src'] .="&loop=1";	
	}else{
		$el['atts']['vid_src'] .="&loop=0";
	}

	$el['tmp']['ele_id'] = $el['id'];
	
}


// Shortcodes Handler
function pagelayer_sc_shortcodes(&$el){
	$is_live = pagelayer_is_live();
	if(empty($is_live)){
		$el['tmp']['shortcode'] = do_shortcode($el['atts']['data']);
	}
}

// Shortcodes Handler
function pagelayer_sc_wp_widgets(&$el){
	
	global $wp_registered_sidebars;
	
	$data = '';	
	foreach($wp_registered_sidebars as $v){
		if($el['atts']['sidebar'] == $v['id']){
			ob_start();
			dynamic_sidebar($v['id']);
			$data = ob_get_clean();
		}
	}
	
	$el['tmp']['data'] = $data;
}


// Service Handler
function pagelayer_sc_service(&$el){
	
	if(!empty($el['atts']['service_image'])){		
		$el['atts']['func_image'] = @$el['tmp']['service_image-'.$el['atts']['service_image_size'].'-url'];
		$el['atts']['func_image'] = empty($el['atts']['func_image']) ? @$el['tmp']['service_image-url'] : $el['atts']['func_image'];
	}
}


/*pagelayer_print($atts);
pagelayer_print($content);
die();*/

/////////////////////////////////////
// Miscellaneous Shortcode Functions
/////////////////////////////////////

// The font family list
function pagelayer_font_family(){
	return array(
		'arial' => 'Arial',				
		'terminal' => 'Terminal'
	);
}

// Supported Icons
function pagelayer_icon_class_list(){
	return array();
}
