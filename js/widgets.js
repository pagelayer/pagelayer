
pagelayer_svg_cache = {};
var pagelayer_document_width;

// For automatic row parent change
jQuery(window).resize(function(){
		
	var new_vw = jQuery(document).width();
	
	if(new_vw == pagelayer_document_width){
		return false;
	}
	
	pagelayer_document_width = new_vw;
	
	// Set a timeout to prevent bubbling
	setTimeout(function(){

		jQuery('.pagelayer-row-stretch-full').each(function(){
			var par = jQuery(this).parent();
			pagelayer_pl_row_parent_full(par);
		});
	
	}, 200);
	
});
		
// Render for row
function pagelayer_render_pl_row(el){
	pagelayer_bg_video(el);	
}

// Render for col
function pagelayer_render_pl_col(el){
	
	// Apply to wrapper
	if(!pagelayer_empty(el.atts['col'])){
		
		for(var x=1; x<=12; x++){
			if(el.$.parent().hasClass('pagelayer-col-'+x)){
				el.$.parent().removeClass('pagelayer-col-'+x);
				break;
			}
		}

		el.$.parent().addClass('pagelayer-col-'+el.atts['col']);
	}
	
	if(el.atts['col_width']){
		el.$.parent().css('width', el.atts['col_width']+'%');
	}
	
	pagelayer_bg_video(el);
}	
	
function pagelayer_bg_video(el){

	var youtubeRegExp = /youtube\.com|youtu\.be/;
	var vimeoRegExp = /vimeo\.com/;
	
	el.tmp['bg_video_src-url'] = el.tmp['bg_video_src-url'] || el.atts['bg_video_src'];
	
	var src = el.tmp['bg_video_src-url'];
	
	var iframe_src = pagelayer_video_url(el.tmp['bg_video_src-url']);
	
	if (youtubeRegExp.exec(src)) {
		
		var youtubeRegExp1 = /youtube\.com/;
		var youtubewatch = /watch/;
		var youtubeembed = /embed/;
		var youtube = /youtu\.be/;
		var videoId;
		
		if (youtubeRegExp1.exec(src)) {
			
			if (youtubewatch.exec(src)) {
				 videoId = src.split('?v=');
									
			} else if (youtubewatch.exec(src)) {
				videoId = src.split('embed/');
			}
			
		} else if (youtube.exec(src)) {
			videoId = src.split('.be/');
		}
		//console.log(frame_height);
		el.atts['vid_src'] = '<iframe src="'+iframe_src+'?autoplay=1&controls=0&showinfo=0&rel=0&loop=1&autohide=1&playlist='+videoId[1]+'" allowfullscreen="1" webkitallowfullscreen="1" mozallowfullscreen="1" frameborder="0"></iframe>';
		
	} else if (vimeoRegExp.exec(src)) {
		
		el.atts['vid_src'] = '<iframe src="'+iframe_src+'?background=1&autoplay=1&loop=1&byline=0&title=0" allowfullscreen="1" webkitallowfullscreen="1" mozallowfullscreen="1" frameborder="0"></iframe>';
		
	}else{
		
		el.atts['vid_src'] = '<video autoplay loop>'+
				'<source src="'+iframe_src+'" type="video/mp4">'+
			'</video>';
			
	}
	
}

// Load the full width row
function pagelayer_render_end_pl_row(el){
		
	// The parent
	var par = el.$.parent();
	
	// Any class with full width
	if(el.$.hasClass('pagelayer-row-stretch-full')){
		
		// Give it the full width
		pagelayer_pl_row_full(el.$);
		
		// Give full width to the parent
		pagelayer_pl_row_parent_full(par);
		
		// Also add that we had a full width
		el.$.addClass('pagelayer-row-stretch-had-full');
	
	// Did this row have full width ?
	}else if(el.$.hasClass('pagelayer-row-stretch-had-full')){
		
		// Remove style
		el.$.removeAttr('style');
		par.removeAttr('style');
		par.children('.pagelayer-ele-overlay').removeAttr('style');
		
		// Remove HAD class
		el.$.removeClass('pagelayer-row-stretch-had-full');
		
	}
	
	pagelayer_pl_row_video(el.$);
	
	el.$.find('.pagelayer-parallax-window img').each(function(){
		pagelayer_pl_row_parallax(jQuery(this));
	});
	
	// Row shape
	if('row_shape_type_top' in el.atts){
		pagelayer_render_row_shape(el, 'top')
	}
	
	if('row_shape_type_bottom' in el.atts){
		pagelayer_render_row_shape(el, 'bottom')
	}
}

// Set Row parent width
function pagelayer_pl_row_parent_full(par){
		var vw = jQuery('html').width();
		par.css({'width': vw,'max-width': '100vw'});
		par.offset({left: 0});
		par.children('.pagelayer-row').css({left: 0});
}

// Row shape render
function pagelayer_render_row_shape(el, shape_pos){
		
	var name = el.atts['row_shape_type_'+shape_pos]+'-'+shape_pos+'.svg';

	// DO we have in cache
	if(!(name in pagelayer_svg_cache)){
		// Make url and fetch
		var url = pagelayer_url+'/images/shapes/'+name;
		jQuery.get(url, function(data){
			el.$.find('.pagelayer-svg-'+shape_pos).html(data);
			pagelayer_svg_cache[name] = data;
		}, 'html');
	
	// Fill with cache
	}else{
		el.$.find('.pagelayer-svg-'+shape_pos).html(pagelayer_svg_cache[name]);
	}

}

// Load the col
function pagelayer_render_end_pl_col(el){
	
	pagelayer_pl_row_video(el.$);
	
	el.$.find('.pagelayer-parallax-window img').each(function(){
		pagelayer_pl_row_parallax(jQuery(this));
	});
	
}

// Render the image object
function pagelayer_render_pl_image(el){
	
	// Decide the image URL
	el.atts['func_id'] = el.tmp['id-'+el.atts['id-size']+'-url'] || el.tmp['id-url'];
	el.atts['func_id'] = el.atts['func_id'] || el.atts['id'];
	
	// What is the link ?
	if('link_type' in el.atts){
		
		// Custom url
		if(el.atts['link_type'] == 'custom_url'){
			el.atts['func_link'] = el.atts['link'];
		}
		
		// Link to the media file itself
		if(el.atts['link_type'] == 'media_file'){
			el.atts['func_link'] = el.tmp['id-url'] || el.atts['id'];
		}
		
		// Lightbox
		if(el.atts['link_type'] == 'lightbox'){
			el.atts['func_link'] = el.tmp['id-url'] || el.atts['id'];
		}
	}
}

// Incase if there is a lightbox
function pagelayer_render_end_pl_image(el){
	pagelayer_pl_image(el.$);
}

// Render for video
function pagelayer_render_pl_video(el){
	el.atts['video_overlay_image-url'] = el.tmp['video_overlay_image-'+el.atts['custom_size']+'-url'] || el.tmp['video_overlay_image-url'];
	el.atts['video_overlay_image-url'] = el.atts['video_overlay_image-url'] || el.atts['video_overlay_image'];	
	el.tmp['src-url'] = el.tmp['src-url'] || el.atts['src'];
	el.tmp['ele_id'] = el['id'];
	el.atts['vid_src'] = pagelayer_video_url(el.tmp['src-url']);	

	if(el.atts['autoplay'] == "true"){
		el.atts['vid_src'] +="?&autoplay=1";
	}else{
		el.atts['vid_src'] +="?&autoplay=0";
	}

	if(el.atts['mute'] == "true"){
		el.atts['vid_src'] +="&mute=1";
	}else{
		el.atts['vid_src'] +="&mute=0";
	}

	if(el.atts['loop'] == "true"){
		el.atts['vid_src'] +="&loop=1";	
	}else{
		el.atts['vid_src'] +="&loop=0";
	}	
}

// Incase if there is a lightbox
function pagelayer_render_end_pl_video(el){
	pagelayer_pl_video(el.$);
}

// Render the testimonial
function pagelayer_render_pl_testimonial(el){
	//console.log(el);
	// Decide the image URL
	el.atts['func_image'] = el.tmp['avatar-'+el.atts['custom_size']+'-url'] || el.tmp['avatar-url'];
	el.atts['func_image'] = el.atts['func_image'] || el.atts['avatar'];
	
}

// Render the stars
function pagelayer_render_end_pl_stars(el){
	pagelayer_stars();
};
 

// Render the service box
function pagelayer_render_pl_service(el){
	
	// Decide the image URL
	el.atts['func_image'] = el.tmp['service_image-'+el.atts['service_image_size']+'-url'] || el.tmp['service_image-url'];
	el.atts['func_image'] = el.atts['func_image'] || el.atts['service_image'];
	
}

// Render the counter
function pagelayer_render_end_pl_counter(el){
	pagelayer_counter();
};

// Render the progress
function pagelayer_render_end_pl_progress(el){
	pagelayer_progress();
};
 
// Render the image slider
function pagelayer_render_pl_image_slider(el){
	
	// The URLs
	var img_urls = !pagelayer_empty(el.tmp['ids-urls']) ? JSON.parse(el.tmp['ids-urls']) : [];
	var all_urls = !pagelayer_empty(el.tmp['ids-all-urls']) ? JSON.parse(el.tmp['ids-all-urls']) : [];
	//console.log(img_urls);
		
	var ul = '';
	var is_link = 'link_type' in el.atts && !pagelayer_empty(el.atts['link_type']) ? true : false;
	
	// Create figure HTML
	for (var x in img_urls){
		
		// Use the default URL first
		var url = img_urls[x];
		
		// But if we have a custom size, use that
		if(el.atts['size'] != 'custom' && x in all_urls && el.atts['size'] in all_urls[x]){
			url = all_urls[x][el.atts['size']];
		}
		
		ul += '<li class="pagelayer-slider-item">';
		
		if(is_link){
			var link = (el.atts['link_type'] == 'media_file' ? url : (el.atts['link'] || ''))
			ul += '<a href="'+link+'">';
		}
		
		ul += '<img class="pagelayer-img" src="'+url+'">';
		
		if(is_link){
			ul += '</a>';
		}
		
		ul += '</li>';
	}
	
	if(pagelayer_empty(ul)){
		ul = '<h4 style="text-align:center;">'+ pagelayer_l('Please select Images from left side Widget properties.')+'</h4>';
	}
	
	el.atts['ul'] = ul;
	
	// Which arrows to show
	if('controls' in el.atts && (el.atts['controls'] == 'arrows' || el.atts['controls'] == 'none')){
		el.CSS.attr.push({'sel': '.pagelayer-image-slider-ul', 'val': 'data-pager="false"'});
	}
	
	if('controls' in el.atts && (el.atts['controls'] == 'pager' || el.atts['controls'] == 'none')){
		el.CSS.attr.push({'sel': '.pagelayer-image-slider-ul', 'val': 'data-controls="false"'});
	}
	
};

// Render the image slider
function pagelayer_render_end_pl_image_slider(el){
	pagelayer_owl_destroy(el.$, '.pagelayer-image-slider-ul');
	pagelayer_pl_image_slider(el.$);
};


// Render the grid gallery
function pagelayer_render_pl_grid_gallery(el){
	
	// The URLs
	var img_urls = !pagelayer_empty(el.tmp['ids-urls']) ? JSON.parse(el.tmp['ids-urls']) : [];
	var all_urls = !pagelayer_empty(el.tmp['ids-all-urls']) ? JSON.parse(el.tmp['ids-all-urls']) : [];
	var img_title = !pagelayer_empty(el.tmp['ids-all-titles']) ? JSON.parse(el.tmp['ids-all-titles']) : [];
	var img_links = !pagelayer_empty(el.tmp['ids-all-links']) ? JSON.parse(el.tmp['ids-all-links']) : [];
	var img_captions = !pagelayer_empty(el.tmp['ids-all-captions']) ? JSON.parse(el.tmp['ids-all-captions']) : [];
	//console.log(img_urls);
		
	var ul = '';
	var is_link = 'link_to' in el.atts && !pagelayer_empty(el.atts['link_to']) ? true : false;
	var col = el.atts['columns'];
	
	var i = 0;
	
	ul += '<ul class="pagelayer-grid-gallery-ul">';
	var gallery_rand = 'gallery-id-'+Math.floor((Math.random() * 100) + 1);
	
	// Create figure HTML
	for (var x in img_urls){
		
		/* if(i % col == 0 && i != 0){
			ul += '</ul><ul class="pagelayer-grid-gallery-ul">';
		} */
		
		// Use the default URL first
		var url = img_urls[x];
		
		// But if we have a custom size, use that
		if(el.atts['size'] != 'custom' && x in all_urls && el.atts['size'] in all_urls[x]){
			url = all_urls[x][el.atts['size']];
		}

		
		ul += '<li class="pagelayer-gallery-item" >';
		
		if(!is_link){
			ul += '<div>';
		}
		
		if(is_link && el.atts['link_to'] == 'media_file'){
			var link = (el.atts['link_to'] == 'media_file' ? url : (el.atts['link'] || ''))
			ul += '<a href="'+link+'" class="pagelayer-ele-link">';
		}
		
		if(is_link && el.atts['link_to'] == 'attachment'){
			var link = img_links[x];
			ul += '<a href="'+link+'" class="pagelayer-ele-link">';
		}
		
		if(is_link && el.atts['link_to'] == 'lightbox'){			
			ul += '<a href="'+img_urls[x]+'" class="pagelayer-ele-link" data-lightbox-gallery="'+gallery_rand+'" alt="'+img_title[x]+'" pagelayer-grid-gallery-type="'+el.atts['link_to']+'">'
		}
		
		ul += '<img class="pagelayer-img" src="'+url+'" title="'+img_title[x]+'" alt="'+img_title[x]+'">';
		
		if(el.atts['caption'] == 'true'){
			ul += '<span class="pagelayer-grid-gallery-caption">'+img_captions[x]+'</span>';
		}
		
		if(is_link){
			ul += '</a>';
		}
		
		if(!is_link){
			ul += '</div>';
		}
		
		ul += '</li>';
		i++;
	}
	ul += '</ul>';
	
	el.tmp['gallery-random-id'] = gallery_rand;
	
	el.atts['ul'] = ul;

};

// Render for tabs
function pagelayer_render_html_pl_tabs(el){
	el.CSS.attr.push({'sel': '{{element}}', 'val': 'pagelayer-tabs-rotate="'+el.atts["rotate"]+'"'});
};

// Render the accordion item
function pagelayer_render_end_pl_tabs(el){
	pagelayer_pl_tabs(el.$);
}

// Render the accordion item
function pagelayer_render_end_pl_accordion(el){
	pagelayer_pl_accordion(el.$);
};

// Render the collapse item
function pagelayer_render_end_pl_collapse(el){
	pagelayer_pl_collapse(el.$);	
};

// Shortcode Handler
var pagelayer_shortcodes_timer;
function pagelayer_render_pl_shortcodes(el){
			
	// Clear any previous timeout
	clearTimeout(pagelayer_shortcodes_timer);
	
	// Set a timer for constant change
	pagelayer_shortcodes_timer = setTimeout(function(){
		
		// Make the call
		jQuery.ajax({
			url: pagelayer_ajax_url+'&action=pagelayer_do_shortcodes',
			type: 'POST',
			data: {
				pagelayer_nonce: pagelayer_ajax_nonce,
				shortcode_data: el.atts['data']
			},
			success:function(data) {
				el.$.find('.pagelayer-shortcodes-container').html(data);
			}
		});
	
	}, 500);
	
};

// Render the widget area i.e. Sidebars
function pagelayer_render_pl_wp_widgets(el){
			
	// Clear any previous timeout
	clearTimeout(pagelayer_shortcodes_timer);
	
	// Set a timer for constant change
	pagelayer_shortcodes_timer = setTimeout(function(){
	
		// Make the call
		jQuery.ajax({
			url: pagelayer_ajax_url+'&action=pagelayer_fetch_sidebar',
			type: 'POST',
			data: {
				pagelayer_nonce: pagelayer_ajax_nonce,
				sidebar: el.atts['sidebar']
			},
			success:function(data) {
				el.$.find('.pagelayer-wp-sidebar-holder').html(data);
			}
		});
	
	}, 500);

};

function pagelayer_owl_destroy(jEle, slides_class){
	
	var ul = jEle.find(slides_class);
	var setup = jEle.attr('pagelayer-setup');
	
	// Already setup ?
	if(setup && setup.length > 0){
		if(ul.children('.pagelayer-ele-wrap')){
			ul.pagelayerOwlCarousel('destroy');
			ul.find('[class^="pagelayer-owl-"]').remove();
			jEle.removeAttr('pagelayer-setup');
		}
	}
}
