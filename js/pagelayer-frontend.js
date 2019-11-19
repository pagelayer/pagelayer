/*
PAGELAYER
http://pagelayer.com/
(c) Pagelayer Team
*/

var pagelayer_doc_width;

// Things to do on document load
jQuery(document).ready(function(){
	
	// Current width
	pagelayer_doc_width = jQuery(document).width();
	
	// Rows
	jQuery('.pagelayer-row-stretch-full').each(function(){
		pagelayer_pl_row_full(jQuery(this));
	});
	
	// Setup any sliders
	jQuery('.pagelayer-image_slider').each(function(){
		pagelayer_pl_image_slider(jQuery(this));
	});
	
	jQuery('.pagelayer-accordion').each(function(){
		pagelayer_pl_accordion(jQuery(this));
	});
	
	jQuery('.pagelayer-collapse').each(function(){
		pagelayer_pl_collapse(jQuery(this));
	});
	
	jQuery('.pagelayer-tabs').each(function(){
		pagelayer_pl_tabs(jQuery(this));
	});
	
	jQuery('.pagelayer-video').each(function(){
		pagelayer_pl_video(jQuery(this));
	});
	
	jQuery('.pagelayer-image').each(function(){
		pagelayer_pl_image(jQuery(this));
	});
	
	jQuery('.pagelayer-grid_gallery').each(function(){
		pagelayer_pl_grid_lightbox(jQuery(this));
	});
	
	jQuery('.pagelayer-row, .pagelayer-col>.pagelayer-col').each(function(){
		pagelayer_pl_row_video(jQuery(this));
	});
	
	jQuery('.pagelayer-parallax-window img').each(function(){
		pagelayer_pl_row_parallax(jQuery(this));
	});

	pagelayer_stars();
  
	// We need to call the is visible thing to show the widgets loading effect
	if(jQuery('.pagelayer-counter-content,.pagelayer-progress-container').length > 0){

		// First Call
		pagelayer_counter();
		pagelayer_progress();
		
		jQuery(window).scroll(function() {
			pagelayer_progress();
			pagelayer_counter();
		});
	}
	
	new WOW({boxClass:'pagelayer-wow'}).init();
	
});

// For automatic row change
jQuery(window).resize(function() {
	
	var new_vw = jQuery(document).width();
	
	if(new_vw == pagelayer_doc_width){
		return false;
	}
	
	pagelayer_doc_width = new_vw;
	
	// Remove style
	jQuery('.pagelayer-row-stretch-full').removeAttr('style');
	
	// Set a timeout to prevent bubbling
	setTimeout(function(){
		
		jQuery('.pagelayer-row-stretch-full').each(function(){
			pagelayer_pl_row_full(jQuery(this));
		});
	
	}, 200);
	
});

// Check if element is visible
function pagelayer_isVisible(ele) {
	
	var offset = jQuery(window).height();
	var viewTop = window.pageYOffset;
	var viewBottom = viewTop + offset - Math.min(ele.height(), ele.innerHeight());
	var top = ele.offset().top;
	var bottom = top + ele.innerHeight();
	
	if(top <= viewBottom && bottom >= viewTop){
		return true;
	}
	
	return false;
}
	  
// Row background video and parallax
function pagelayer_pl_row_video(jEle){
	
	var vEle = jEle.find('.pagelayer-background-video');
	
	var setup = vEle.attr('pagelayer-setup');
	if(setup && setup.length > 0){
		return true;
	}

	var frame_width = vEle.width();
	var frame_height = (frame_width/100)*56.25;
	var height = vEle.height();
	
	if(frame_height < height){
		
		frame_height = height;
		
	}
	
	vEle.children().css({'width':frame_width+'px','height':frame_height+'px'});
	
	vEle.attr('pagelayer-setup', 1);
		
}

// Row background parallax
function pagelayer_pl_row_parallax(jEle){
	
	//Parallax background
	var setup = jEle.attr('pagelayer-setup');
	if(setup && setup.length > 0){
		return true;
	}
	
	new simpleParallax(jEle);
	jEle.attr('pagelayer-setup', 1);
}

// Adjust rows
function pagelayer_pl_row_full(jEle){
	
	// Get current width
	var vw = jQuery('html').width();
	
	// Now give the row the width
	jEle.css({'width': vw, 'max-width': '100vw'});
	
	jEle.offset({left: 0});
	
};

// Modal open
function pagelayer_render_pl_modal(param){
	jQuery(param).parent().parent().find('.pagelayer-modal-content').show();
};

// Modal close
function pagelayer_pl_modal_close(param){
	jQuery(param).parent().hide();
}

// Setup the image slider
function pagelayer_pl_image_slider(jEle){
	
	var ul = jQuery(jEle.find('.pagelayer-image-slider-ul'));
	
	// Build the options
	var options = pagelayer_fetch_dataAttrs(ul, 'data-slides-');
	
	pagelayer_owl_init(jEle, ul, options);

}

function pagelayer_tab_show(el, pl_id) {

	jQuery('[pagelayer-id='+pl_id+']').closest('.pagelayer-tabcontainer').find('[pagelayer-id]').hide();
	jQuery('[pagelayer-id='+pl_id+']').show();
	
	jQuery(el).parent().find('.pagelayer-tablinks').each(function(){
		jQuery(this).removeClass('active');
	});
	
	jQuery(el).addClass("active");
}

var pagelayer_tab_timers = {};

function pagelayer_pl_tabs(jEle) {
	
	var default_active = '';
	var children = jEle.find('.pagelayer-tabcontainer').find('[pagelayer-id]');
	
	// Loop thru
	children.each(function(){
		var tEle = jQuery(this);
		var pl_id = tEle.attr('pagelayer-id');
		var title = tEle.attr('pagelayer-tab-title') || 'Tab';
		var func = "pagelayer_tab_show(this, '"+pl_id+"')";
		
		var icon = '';
		if(tEle.attr('pagelayer-tab-icon')){
			icon = "fa fa-"+tEle.attr('pagelayer-tab-icon');
		}
		
		// Set the default tab
		if(tEle.attr('pagelayer-default_active')){
			default_active = pl_id;
		}
		
		jEle.find('.pagelayer-tabs-holder').append('<span tab-id="'+pl_id+'" class="pagelayer-tablinks" onclick="'+func+'"> <i class="'+icon+'"></i> <span>'+title+'</span></span>');
	});

	// Set the default tab
	if(default_active.length > 0){
		pagelayer_tab_show(jEle.find('[tab-id='+default_active+']'), default_active);
	// Set the first tab as active
	}else{
		var first_tab = jEle.find('[tab-id]').first();
		pagelayer_tab_show(first_tab, first_tab.attr('tab-id'));
	}

	try{
		clearInterval(pagelayer_tab_timers[jEle.attr('pagelayer-id')])
	}catch(e){};
	
	var rotate = parseInt(jEle.attr('pagelayer-tabs-rotate'));
	
	// Are we to rotate
	if(rotate > 0){
		
		var i= 0;
		pagelayer_tab_timers[jEle.attr('pagelayer-id')] = setInterval(function () {
			
			if(i >= children.length){
				i = 0;
			}
			
			var tmp_pl_ele = jEle.find('.pagelayer-tabcontainer').find('[pagelayer-id]')[i];
			var tmp_btn_ele = jEle.find('.pagelayer-tablinks')[i]
			var tmp_pl_id = jQuery(tmp_pl_ele).attr('pagelayer-id');
			
			jEle.find('.pagelayer-tablinks').each(function(){
				jQuery(this).removeClass('active');
			});
			
			jQuery(tmp_btn_ele).addClass("active");
			pagelayer_tab_show('', tmp_pl_id);
			
			i++;
	   
		}, rotate);
	}
	
}

// Setup the Accordion
function pagelayer_pl_accordion(jEle){
	
	var holder = jEle.find('.pagelayer-accordion-holder');
	var tabs = jEle.find('.pagelayer-accordion-tabs');
	
	if(tabs.length < 1){
		return false;
	}
	
	var setup = tabs.attr('pagelayer-setup');
	
	var icon = 'fa fa-'+holder.attr('data-icon');
	var active_icon = 'fa fa-'+holder.attr('data-active_icon');
	
	tabs.find('span i').attr('class', icon);
	var currentTab = jEle.find('.pagelayer-accordion-tabs.active');
	
	if(currentTab.length < 1){
		jQuery(tabs[0]).addClass('active').next().show('slow');
		jQuery(tabs[0]).find('span i').attr('class', icon);
	}
	
	jQuery(currentTab).addClass('active').next().show('slow');
	jQuery(currentTab).find('span i').attr('class', active_icon);
	
	// Already setup ?
	if(setup && setup.length > 0){
		tabs.unbind('click');
	}

	tabs.click(function(){
		
		var currentTab = jQuery(this);
		
		if(currentTab.hasClass('active')){
			currentTab.removeClass('active').next().hide('slow');;
			currentTab.find('span i').attr('class', icon);
			return true;
		} 
		
		tabs.find('span i').attr('class', icon);
		tabs.removeClass('active').next().hide('slow');
			
		currentTab.addClass('active').next().show('slow');
		currentTab.find('span i').attr('class', active_icon);
		
	});
	
	// Set that we have setup everything
	tabs.attr('pagelayer-setup', 1);
		
}

// Setup the Collapse
function pagelayer_pl_collapse(jEle){
	
	var holder = jEle.find('.pagelayer-collapse-holder');
	var tabs = jEle.find('.pagelayer-accordion_item');
		
	if(tabs.length < 1){
		return false;
	}
		
	var setup = tabs.attr('pagelayer-setup');
	var icon = 'fa fa-'+holder.attr('data-icon');
	var active_icon = 'fa fa-'+holder.attr('data-active_icon');
	var activeTabs = jEle.find('.pagelayer-accordion_item.active');

	tabs.find('span i').attr('class', icon);
	jQuery(activeTabs).addClass('active').children('.pagelayer-accordion-panel').show('slow');
	jQuery(activeTabs).find('span i').attr('class', active_icon);
		
	// Already setup ?
	if(setup && setup.length > 0){
		tabs.unbind('click');
	}

	tabs.click(function(){
		
		var currentTab = jQuery(this);
		
		if(currentTab.hasClass('active')){
			currentTab.removeClass('active').children('.pagelayer-accordion-panel').hide('slow');;
			currentTab.find('span i').attr('class', icon);
			return true;
		}
			
		currentTab.addClass('active').children('.pagelayer-accordion-panel').show('slow');
		currentTab.find('span i').attr('class', active_icon);
		
	});
	
	// Set that we have setup everything
	tabs.attr('pagelayer-setup', 1);
	
}

// Counter
function pagelayer_counter(){
	
	jQuery('.pagelayer-counter-content').each(function(){
		
		var jEle = jQuery(this);
		
		if(pagelayer_isVisible(jEle)){
			
			var setup = jEle.attr('pagelayer-setup');
			
			// Already setup ?
			if(setup && setup.length > 0){
				return true;
			}
			
			var options = {};
			options['duration'] = jEle.children('.pagelayer-counter-display').attr('pagelayer-counter-animation-duration');
			options['delimiter'] = jEle.children('.pagelayer-counter-display').attr('pagelayer-counter-seperator-type');
			options['toValue'] = jEle.children('.pagelayer-counter-display').attr('pagelayer-counter-last-value');					
			jEle.children('.pagelayer-counter-display').numerator( options );
		
			// Set that we have setup everything
			jEle.attr('pagelayer-setup', 1);
			
		}
	});
}

function pagelayer_progress(){
	jQuery('.pagelayer-progress-container').each(function(){
		var jEle = jQuery(this);
		
		if(pagelayer_isVisible(jEle)){
			
			var setup = jEle.attr('pagelayer-setup');
			if(setup && setup.length > 0){
				return true;
			}
			
			var progress_width = jEle.children('.pagelayer-progress-bar').attr('pagelayer-progress-width');
			if(progress_width == undefined){
				progress_width = "1";
			}
			
			var width = 0;
			var interval;
			
			var progress = function(){
				if (width >= progress_width) {
					clearInterval(interval);
				} else {
					width++;
					jEle.children('.pagelayer-progress-bar').css('width', width + '%'); 
					jEle.find('.pagelayer-progress-percent').text(width * 1  + '%');
				}
			}
			interval = setInterval(progress, 30);
			jEle.attr('pagelayer-setup', 1);
			
		}
	});
}

// Dismiss Alert Function
function pagelayer_dismiss_alert(x){
	jQuery(x).parent().parent().fadeOut();
}

// Video light box handler
function pagelayer_pl_video(jEle){
	
	// A tag will be there ONLY if the lightbox is on
	var overlayval = jEle.find('.pagelayer-video-overlay');	
	var a = jEle.find(".pagelayer-video-holder a");
	
	// No lightbox
	if(a.length < 1 && pagelayer_empty(overlayval)){
		return;
	}

	a.nivoLightbox({
		effect: "fadeScale",
	});
	
	jEle.find(".pagelayer-video-holder .pagelayer-video-overlay").on("click", function(ev) {

		var target = jQuery(ev.target);

		if (!target.parent("a").length) {
			jQuery(this).hide();
			jQuery(this).parent().find("#embed_video")[0].src += "?rel=0&autoplay=1";
		}
	});
	
}

// Image light box handler
function pagelayer_pl_image(jEle){
	// A tag will be there ONLY if the lightbox is on
	var a = jEle.find("[pagelayer-image-link-type=lightbox]");
	
	// No lightbox
	if(a.length < 1){
		return;
	}
	
	a.nivoLightbox({
		effect: "fadeScale",
	});
}

function pagelayer_stars(){
	jQuery('.pagelayer-stars-container').each(function(){
		var jEle = jQuery(this);
		var setup = jEle.attr('pagelayer-setup');
		if(setup && setup.length > 0){
			return true;
		}
		var count = jEle.attr('pagelayer-stars-count');
		i = 0;
		var stars = "";
		while(i < count){			
			stars +='<div class="pagelayer-stars-icon pagelayer-stars-empty"><i class="fa fa-star" aria-hidden="true"></i></div>';
			i++;
		}

		jEle.empty();
		jEle.append(stars);
		var starsval = jEle.attr('pagelayer-stars-value');
		starsval = starsval.split('.');		
		var fullstars = starsval[0];
		var value =  starsval[1];
		var halfstar = parseInt(fullstars) + 1;
		var emptystars = parseInt(fullstars) + 2;
		jEle.children('.pagelayer-stars-icon').attr("class","pagelayer-stars-icon");
		jEle.children('.pagelayer-stars-icon:nth-child(-n+'+ fullstars +')').addClass('pagelayer-stars-full'); 
		if(value != undefined){
			jEle.children('.pagelayer-stars-icon:nth-child('+ halfstar +')').addClass('pagelayer-stars-'+value);		
		}else{
			jEle.children('.pagelayer-stars-icon:nth-child('+ halfstar +')').addClass('pagelayer-stars-empty');
		}
		jEle.children('.pagelayer-stars-icon:nth-child(n+'+ emptystars +')').addClass('pagelayer-stars-empty'); 		
		jEle.attr('pagelayer-setup', 1);
	});
}

//Grid Gallery Lightbox
function pagelayer_pl_grid_lightbox(jEle){

	// A tag will be there ONLY if the lightbox is on
	var a = jEle.find("[pagelayer-grid-gallery-type=lightbox]");
	
	// No lightbox
	if(a.length < 1){
		return;
	}
	
	a.nivoLightbox({
		effect: "fadeScale",
		keyboardNav: true,
		clickImgToClose: false,
		clickOverlayToClose: true,
	});
}

// PHP equivalent empty()
function pagelayer_empty(mixed_var) {

  var undef, key, i, len;
  var emptyValues = [undef, null, false, 0, '', '0'];

  for (i = 0, len = emptyValues.length; i < len; i++) {
	if (mixed_var === emptyValues[i]) {
	  return true;
	}
  }

  if (typeof mixed_var === 'object') {
	for (key in mixed_var) {
	  // TODO: should we check for own properties only?
	  //if (mixed_var.hasOwnProperty(key)) {
	  return false;
	  //}
	}
	return true;
  }

  return false;
};

function pagelayer_fetch_dataAttrs(ele, prefix){
	
	var options = {};
	
	jQuery.each(ele.get(0).attributes, function(i, attrib){
		
		//console.log(attrib);
		if(attrib.name.includes(prefix)){
			
			var opt_name = attrib.name.substring(prefix.length);
			
			// Check for any Uppercase attribute
			if(opt_name.includes('-')){
				
				opt_name = opt_name.split('-');
				//console.log(opt_name);
				var opt_arr = [];
				jQuery.each(opt_name, function(key, value) {
					if(key != 0){
						opt_arr.push(value.charAt(0).toUpperCase() + value.slice(1));
					}else{
						opt_arr.push(value);
					}
				});
				//console.log(opt_arr);
				opt_name = opt_arr.join('');
			}
			
			// Make the values correct
			var val = attrib.value;
			if(val == 'true') val = true;
			if(val == 'false') val = false;
			if(jQuery.isNumeric(val)) val = parseInt(val);
			
			options[opt_name] = val;
		}
	});
	
	//console.log(options);
	
	if(options['controls']){
		switch(options['controls']){
			case 'arrows':
				options['nav'] = true;
				options['dots'] = false;
				break;
			case 'pager':
				options['dots'] = true;
				options['nav'] = false;
				break;
			case 'none':
				options['nav'] = false;
				options['dots'] = false;
				break;
		}
	}else{
		options['nav'] = true;
		options['dots'] = true;
	}
	
	if(options['animateIn']){
		switch(options['controls']){
			case 'horizontal':
				options['animateIn'] = 'slideInLeft';
				break;
			case 'vertical':
				options['animateIn'] = 'slideInDown';
				break;
			case 'kenburns':
				options['animateIn'] = 'zoomIn';
				break;
			default:
				options['animateIn'] = options['animateIn'];
		}
	}
	
	if(!options['items']){
		options['items'] = 1;
	}
	options['responsive'] = {
		0:{items: 1},
		500:{items: options['items']}
	}
	// If we are in editor don't loop the Owl items
	if (window.location.href.indexOf('pagelayer-live=1') > -1) {
		//console.log('here');
		options['loop'] = false;
	}
	
	return options;
}

function pagelayer_owl_init(jEle, ul, options){
	
	//console.log(options);
	var setup = jEle.attr('pagelayer-setup');

	// Already setup ?
	if(setup && setup.length > 0){
		return true;
	}
	
	ul.pagelayerOwlCarousel(options);
	
	// Set that we have setup everything
	jEle.attr('pagelayer-setup', 1);
	
}