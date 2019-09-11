/*
PAGELAYER
http://pagelayer.com/
(c) Pagelayer Team
*/

pagelayer = {
	$: jQuery,
	$$ : function(select){
		return jQuery(select, window.parent.document);
	},
	p : this,
	copy_selected: '',
	mouse: {x: -1, y: -1},
	history_action : true,
}

var pagelayer_history_obj = {}, pagelayer_revision_obj = {};

// Lets start
jQuery(document).ready(function(){
	
	// Set the title of the parent window
	pagelayer.$$('head').append(pagelayer.$('title')[0].outerHTML);
	
	pagelayer.blank_img = pagelayer_url+'/images/default-image.png';

	pagelayer_shortcodes['pl_inner_row'] = JSON.parse(JSON.stringify(pagelayer_shortcodes['pl_row']));
	pagelayer_shortcodes['pl_inner_row']['name'] = 'Inner Row';
	pagelayer_groups['grid'].push('pl_inner_row');
	
	// Make the Topbar
	pagelayer_bottombar();
	
	// Make the Leftbar
	pagelayer_leftbar();	
		
	// Add widget section
	pagelayer_add_widget();
	
	// Setup the ELPD
	pagelayer_elpd_setup();
	
	// Setup the properties of the elements
	pagelayer_element_setup();
	
	// An image to show for drag
	jQuery('body').append('<img src="'+pagelayer_url+'/images/drag-ghost.png" class="pagelayer-drag-show" />');
	
	// Setup the grid drag
	pagelayer_setup_drag();
	
	// Set to desktop
	pagelayer_set_screen_mode('desktop');
	
	// Set up right click
	pagelayer_left_click();
	pagelayer_right_click();
	
	// Setup pagelayer history
	pagelayer_history_obj['action_data'] = [];
	pagelayer_history_setup(true);

});

// The jQuery Object of the ELPD
var pagelayer_elpd;

function pagelayer_closest_corner(jEle){
	var corners = [];
	var w = jEle.outerWidth();
	var h = jEle.outerHeight();	
	var topleft = jEle.offset();
	
	// 0 - Top Left
	corners.push(topleft);
	
	// 1 - Top Right
	corners.push({top: topleft.top, left: topleft.left+w});
	
	// 2 - Bottom Right
	corners.push({top: topleft.top+h, left: topleft.left+w});
	
	// 3 - Bottom Left
	corners.push({top: topleft.top+h, left: topleft.left});
	
	//console.log(corners);
	
	// Calculate the closest to the mouse
	var distances = {};
	for(var c in corners){
		var dist = Math.hypot(pagelayer.mouse.x - corners[c].left, pagelayer.mouse.y - corners[c].top);
		distances[c] = dist;
	}	
	//console.log(distances);
	
	var corner = Object.keys(distances).sort(function(a,b){return distances[a]-distances[b]})[0];
	//console.log(corner);
	
	return corner;
	
};

// Make rows and cols draggable
function pagelayer_setup_drag(){
	
	// The object to show as drag
	var shower = jQuery('.pagelayer-drag-show');
	
	// Delete any prospect
	var clear_prospect = function(){
		jQuery('.pagelayer-drag-prospect').remove();
		
		// Shows the wrap as active
		jQuery('.pagelayer-drag-ele-hover').removeClass('pagelayer-drag-ele-hover');
	}
	
	// Reset the complete drag stuff
	var reset_dragging = function(){
		pagelayer.dragging = false;
		pagelayer.drag_is_new = false;
		pagelayer.drag_mouse = {x: 0, y: 0};
		reset_on_drag();
	}
	
	// Reset the element on you were last
	var reset_on_drag = function(){
		pagelayer.drag_closest = false;
		pagelayer.drag_closest_corner = null;
	}
	
	// Scroll by
	var scrollPx = 7;
	var scrollDist = 30;
	
	// If we are too close too the window edge, then scroll
	var handle_scroll = function(e){
		
		var windowHeight = jQuery(window).height();
		var windowWidth = jQuery(window).width();
	
		// Are we to close to the top or bottom
		if(e.clientY < scrollDist){
			window.scrollBy(0, -scrollPx);
		}else if((windowHeight - e.clientY) < scrollDist){
			window.scrollBy(0, scrollPx);
		}		
		
		// Are we to close to the top or bottom
		if(e.clientX < scrollDist){
			window.scrollBy(-scrollPx, 0);
		}else if((windowWidth - e.clientX) < scrollDist){
			window.scrollBy(scrollPx, 0);
		}
		
	}
	
	// SET the values
	reset_dragging();
	
	var ondragover = function(e) {
		//console.log(e);
		
		pagelayer.mouse.x = parseInt(e.pageX);
		pagelayer.mouse.y = parseInt(e.pageY);
		//console.log(pagelayer.mouse);
		
		// Are we dragging ?
		if(pagelayer.dragging){
			
			//console.log(e);
			
			e.preventDefault();			
			//e.stopPropagation();
			
			// The wrap of the element being dragged
			var wrap = pagelayer.dragging;
			
			// New addition
			var is_new = pagelayer.drag_is_new;
			var ele;
			var tag = pagelayer_tag(wrap);
			var id = pagelayer_id(wrap);
			
			// If existing element then add we are dragging
			if(!is_new){
				
				// Start Dragging
				if(!wrap.hasClass('pagelayer-is-dragging')){
					wrap.addClass('pagelayer-is-dragging');
				}
				
				//shower.hide();
			
				ele = document.elementFromPoint(e.clientX, e.clientY);
				//console.log(ele);
				
				// Drag the show object
				//shower.show();
				//var offset = {top: (e.pageY-10)+'px', left: (e.pageX-10)+'px'}
				//shower.css(offset);
				
			}else{
				ele = document.elementFromPoint(e.clientX, e.clientY);
			}
			//console.log(e);
			
			// Have we moved more than 5px;
			var dist = Math.hypot(pagelayer.mouse.x - pagelayer.drag_mouse.x, pagelayer.mouse.y - pagelayer.drag_mouse.y);
			//console.log(dist);
			/*if(dist && dist < 5){
				return false;
			}*/
			
			// Handle the scroll
			handle_scroll(e);
			
			// Find the closest wrap
			var onWrap;
			
			// If we are a column, we can be over another column or row
			if(tag == 'pl_col'){
				onWrap = jQuery(ele).closest('.pagelayer-wrap-col,.pagelayer-wrap-row,.pagelayer-wrap-inner-row');
				//console.log(pagelayer_id(onWrap));
				
			// If we are a row, we can be over another row or a column
			}else if(tag == 'pl_row'){
				onWrap = jQuery(ele).closest('.pagelayer-wrap-row');
				//console.log(pagelayer_id(onWrap));
			
			// For inner row we restrict to 1 level only
			}else if(tag == 'pl_inner_row'){
				var ele_wrap = jQuery(ele).parents('.pagelayer-wrap-col');
				if(ele_wrap.length == 1 && !jQuery(ele).hasClass('pagelayer-wrap-col')){
					onWrap = jQuery(ele).closest('.pagelayer-wrap-ele,.pagelayer-wrap-col,.pagelayer-wrap-inner-row');
				}else{
					onWrap = jQuery(ele).closest('.pagelayer-wrap-inner-row');
				}
			// For every other element, we can be over a col or ele
			}else{
				onWrap = jQuery(ele).closest('.pagelayer-wrap-ele,.pagelayer-wrap-col,.pagelayer-wrap-inner-row');
			}
			//console.log(onWrap);
			
			// If we find nothing
			if(pagelayer_empty(onWrap) || onWrap.length < 1){
				clear_prospect();// Clear existing prospects
				reset_on_drag();// Also reset the last on item
				return false;
			}
			
			// Get the ID
			var onId = onWrap.attr('pagelayer-wrap-id');
			var onEle = onWrap.children('.pagelayer-ele');
			
			// Do we have a parent ?
			var pOnId = pagelayer_get_parent(onEle);
			
			if(pOnId){
				onId = pOnId;
				onEle = pagelayer_ele_by_id(onId);
				onWrap = pagelayer_wrap_by_id(onId);
			}
			
			var changed = false;
			
			// Was it the same ID like the one we were on before
			if(pagelayer.drag_closest != onId){
				pagelayer.drag_closest = onId;
				changed = true;
			}
			//console.log(onId+'  '+pagelayer.drag_closest)
			
			var req_corners = {0: 'top', 1: 'top', 2: 'bottom', 3: 'bottom'};
			
			// For columns we redefine the top and bottom
			if(tag == 'pl_col'){
				req_corners[1] = 'bottom';
				req_corners[3] = 'top';
			}
			
			// Determine the previous and next
			var next = wrap.next('.pagelayer-ele-wrap');
			var prev = wrap.prev('.pagelayer-ele-wrap');
			
			if(next.length == 1 && pagelayer_id(next) == onId){
				req_corners = {0: 'bottom', 1: 'bottom', 2: 'bottom', 3: 'bottom'};
			}
			
			if(prev.length == 1 && pagelayer_id(prev) == onId){
				req_corners = {0: 'top', 1: 'top', 2: 'top', 3: 'top'};
			}
			
			// Which corner are we closest to ?
			var corner_num = pagelayer_closest_corner(onWrap);
			var corner = req_corners[corner_num];
			
			//console.log(corner+' != '+pagelayer.drag_closest_corner)
			if(corner != pagelayer.drag_closest_corner){
				pagelayer.drag_closest_corner = corner;
				changed = true;
			}
			
			//console.log(changed);
			
			// If we are on our self then clear return false
			if(onId == id){
				clear_prospect();// Clear existing prospects
				reset_on_drag();// Also reset the last on item
				return false;
			}
			
			// Then lets start showing
			if(changed){
				
				// Record the mouse points
				pagelayer.drag_mouse.x = parseInt(e.pageX);
				pagelayer.drag_mouse.y = parseInt(e.pageY);
				
				// Clear any existing prospect
				clear_prospect();
				
				// Add new prospect
				var prospect = '<div class="pagelayer-drag-prospect" pagelayer-corner="'+corner+'"></div>';
				
				if(corner == 'bottom'){
					onWrap.append(prospect);
				}else if(corner == 'top'){
					onWrap.prepend(prospect);
				}
				
				prospect = jQuery('.pagelayer-drag-prospect')
				var animate_props = {height: '5px'};
				
				// For column add a special class
				if(tag == 'pl_col'){
					prospect.addClass('pagelayer-drag-prospect-col');
					animate_props['width'] = '5px';
					
					// Adjust the left and right
					var css = {};
					css[(corner == 'bottom' ? 'right' : 'left')] = '0px';
					prospect.css(css);
				}
				
				// Animate the prospect
				prospect.animate(animate_props, 200);
				
				// Highlight the wrap via overlay
				onWrap.children('.pagelayer-ele-overlay').addClass('pagelayer-drag-ele-hover');
				
			}
			
		}
	}
	
	// When mouse is pressed down
	var ondragstart = function(e){
		
		//console.log(e);
		
		// Target
		var tEle = jQuery(e.target);
		var wrap = tEle.closest('.pagelayer-ele-wrap');
		//console.log(jEle[0]);
		
		// Is it an existing element ?		
		if(wrap.length < 1){
			return false;
		}
		
		// Do we have a parent ?
		var id = pagelayer_id(wrap);
		var jEle = pagelayer_ele_by_id(id);
		var pId = pagelayer_get_parent(jEle);
		
		if(pId){
			wrap = pagelayer_wrap_by_id(pId);
		}
		
		//e.preventDefault();
		
		var tag = pagelayer_tag(wrap);
		
		e.originalEvent.dataTransfer.setData('Text', 1);
		var img = document.createElement('img');
		img.src = shower.attr('src');
		e.originalEvent.dataTransfer.setDragImage(img, 32, 32);
		
		pagelayer.dragging = wrap;
		
	}
	
	// When mouse is pressed down
	var ondrop = function(e){
		
		//console.log(e);
		
		// Stop dragging ?
		if(pagelayer.dragging){
			
			e.preventDefault();
			
			var wrap = pagelayer.dragging;
			var tag = pagelayer_tag(wrap);
			var fromEl = wrap.parent();
			var id;
			wrap.removeClass('pagelayer-is-dragging');
			
			// Find any prospect
			var prospect = jQuery('.pagelayer-drag-prospect');
			//console.log(prospect[0]);
				
			// It should be exactly 1
			if(prospect.length == 1){
				
				var onWrap = prospect.parent();
				var onId = pagelayer_id(onWrap);
				var onTag = pagelayer_tag(onWrap);
				var dropped;	
				var corner = prospect.attr('pagelayer-corner');
				var method = (corner == 'top') ? 'before' : 'after';
				
				// Create the element if it needs to be created
				if(pagelayer.drag_is_new){					
					dropped = jQuery('<div pagelayer-tag="'+tag+'"></div>');
				
				// Move the object
				}else{
					dropped = wrap;
					dropped.detach();
				}
				
				// If I am a column or row, then I go only before or after my same type !
				if((onTag == 'pl_col' || onTag == 'pl_row') && onTag == tag){
				
				// If I am a column and I am on a row 
				// OR I am a normal element and I am on column
				}else if((tag == 'pl_col' && (onTag == 'pl_row' || onTag == 'pl_inner_row')) || onTag == 'pl_col'){
					// We need to find the holder and add the prospect there
					var holder = pagelayer_shortcodes[onTag]['holder'];
					onWrap = onWrap.children('.pagelayer-ele').children(holder);
					method = (corner == 'top') ? 'prepend' : 'append';
				}
				
				// Attach or shift the element
				onWrap[method](dropped);
				//console.log(dropped);
				
				// Trigger the onadd
				if(pagelayer.drag_is_new){
					id = pagelayer_onadd(dropped);
				
				// Existing elements
				}else{
					id = pagelayer_id(wrap);
				}
				
				// Defining the variables as needed
				var jEle = pagelayer_ele_by_id(id);
				wrap = pagelayer_wrap_by_id(id);
				var toEl = wrap.parent();
				
				// Column number handle
				if(tag == 'pl_col'){
					
					var row_holder = jEle.parent().closest('.pagelayer-row-holder');
					
					// Renumber the col where you are going
					pagelayer_renumber_col(row_holder);
					
					// Renumber the old columns as well
					if(!pagelayer.drag_is_new){
						var from_row = fromEl.closest('.pagelayer-row-holder');
						pagelayer_renumber_col(from_row);
					}
				}
				
				// Handle the empty col
				if(tag != 'pl_col'){
					
					pagelayer_empty_col(toEl.closest('.pagelayer-col-holder'));
					
					if(!pagelayer.drag_is_new){
						pagelayer_empty_col(fromEl.closest('.pagelayer-col-holder'));
					}
					
				}
				
			}
			
			// Clear prospect
			clear_prospect();
		}
		
		reset_dragging();
		
	}
	
	// Add the events for inner content - as we are using the drag API	
	jQuery(document).on('dragstart', ondragstart);
	jQuery(document).on('dragover', ondragover);
	jQuery(document).on('drop', ondrop);
	
	// For addition of new elements
	pagelayer.$$('[pagelayer-tag]').on('dragstart', function(e){
		//console.log(e);
		
		var tEle = jQuery(e.target);
		var jEle = tEle.closest('.pagelayer-shortcode-drag');
		
		// Is it an existing element ?
		if(jEle.length < 1){
			return false;
		}
		
		e.originalEvent.dataTransfer.setData('tag', pagelayer_tag(jEle));
		
		pagelayer.dragging = jEle;
		pagelayer.drag_is_new = true;
		
	});
	
	// Handle editable content by removing drag
	var onmousedown = function(e){
		
		var tEle = jQuery(e.originalEvent.explicitOriginalTarget);
		
		if(tEle.closest('[pagelayer-editable]').length > 0){
			//console.log('Is Editable MouseDown');			
			tEle.parents('[draggable]').attr('draggable', 'false');
		}
	
	}
	
	// Handle editable content by adding drag that was removed
	var onmouseup = function(e){
		jQuery(document).find('[draggable=false]').attr('draggable', 'true');
	}
	
	// Handle editable contents by temprarily removing drag
	jQuery(document).on('mousedown', onmousedown);
	jQuery(document).on('mouseup', onmouseup);

};

// Handle empty col
// selector should be col holder
function pagelayer_empty_col(selector){
	
	// Loop through
	jQuery(selector).each(function(){
		
		var jEle = jQuery(this);// jEle is the COL HOLDER
		
		// Are we a col ?
		if(!jEle.hasClass('pagelayer-col-holder')){
			return;
		}
		
		// Column is becoming blank, so show add ele
		if(jEle.children().length < 1){
			//from.addClass('pagelayer-empty-col');
			jEle.append('<div class="pagelayer-add-ele pagelayer-ele-wrap"><i class="fa fa-plus"><br /><span>Empty column please Drag Widgets</span></i></div>');			
			//var h = jEle.parent().parent().children('.pagelayer-ele-overlay').height();
			//jEle.children('.pagelayer-add-ele').height(h);
			
		// Any add ele sign with non-empty columns here ?
		}else if(jEle.children('.pagelayer-add-ele').length > 0){
			jEle.children('.pagelayer-add-ele').remove();
		}
		
	});
	
};

// Reset the column widths
// The selector should be a ROW HOLDER
function pagelayer_renumber_col(selector){
	
	var pEle = jQuery(selector);
	var children = pEle.children('.pagelayer-ele-wrap');
	var cols = Math.floor(12 / (children.length));
	var obj = {col: cols};
	
	// Find out the number of cols of other cols
	children.each(function(){
		
		// This is the wrapper
		var jEle = jQuery(this);
		
		// The real element
		var Ele = jEle.find('>.pagelayer-ele');
		
		for(var x=1; x<=12; x++){
			if(jEle.hasClass('pagelayer-col-'+x)){
				jEle.removeClass('pagelayer-col-'+x);
				Ele.removeClass('pagelayer-col-'+x);
				break;
			}
		}
		jEle.addClass('pagelayer-col-'+cols);
		Ele.addClass('pagelayer-col-'+cols);
		
		// Set the att
		pagelayer_set_atts(Ele, obj);
	});
}

// Handle addition of elements from the left
// NOTE : At this point the addition is FINALIZED
// The add element cannot be prevented !
function pagelayer_onadd(jEle, toClick){
	
	toClick = arguments.length == 2 ? toClick : true;
	
	//console.log(jEle);
	var id = pagelayer_element_added(jEle);
	var jEle = jQuery("[pagelayer-id="+id+"]");
	
	if(toClick){
		//console.log('here');
		jEle.click();
	}
	
	return id;
	
};

// Add an element into the POST
function pagelayer_element_added(jEle){
	
	var sc = jEle.attr('pagelayer-tag');
	var id, par_id;
	
	// Set Pagelayer History FALSE to prevent saving attributes in action history
	pagelayer.history_action = false;

	// Generate the HTML
	html = pagelayer_create_sc(sc);
	id = pagelayer_assign_id(html);
	par_id = id;
	
	// Insert the HTML
	jEle[0].outerHTML = html[0].outerHTML;
	
	// Setup the properties of the elements
	pagelayer_element_setup("[pagelayer-id="+par_id+"], [pagelayer-id="+par_id+"] .pagelayer-ele", true);
	
	// Any children to add ?
	if(!('widget' in pagelayer_shortcodes[sc])){
	
		// The element props
		var props = pagelayer_shortcodes[sc];
		
		// Do we have to create children ?
		if('has_group' in props){
					
			var has_group = props['has_group'];		
			var gProp = props[has_group['section']][has_group['prop']];
			
			for(var i=0; i < gProp['count']; i++){
				var cid = pagelayer_element_add_child(jQuery("[pagelayer-id="+id+"]"), gProp['sc']);
				//pagelayer_element_setup('[pagelayer-id='+cid+']', true);
			}
			
		}
	
	}
	
	// Save in action history 
	var cEle = pagelayer_near_by_ele(id, sc);

	pagelayer_history_action_push({
		'title' : pagelayer_shortcodes[sc]['name'],
		'action' : 'Added',
		'pl_id' : id,
		'html' : html[0].outerHTML,
		'cEle' : cEle
	});	
	
	// Set pagelayer history TRUE
	pagelayer.history_action = true;
	
	return id;
	
};

// Add an element
function pagelayer_element_add_child(pEle, sc){
	var child = pagelayer_create_sc(sc);
	var cid = pagelayer_assign_id(child);
	pagelayer_set_parent(child, pagelayer_assign_id(pEle));
	
	// Does the parent have a holder ?
	var tag = pagelayer_tag(pEle);
	
	// There is a holder
	if('holder' in pagelayer_shortcodes[tag]){
		
		pEle.find(pagelayer_shortcodes[tag]['holder']).append(child);
		
	// No holder, just append
	}else{
		pEle.append(child);
	}
	
	pagelayer_element_setup('[pagelayer-id='+cid+']', true);
	
	// Do we have to create children ?
	if('has_group' in pagelayer_shortcodes[sc]){
				
		var has_group = pagelayer_shortcodes[sc]['has_group'];		
		var gProp = pagelayer_shortcodes[sc][has_group['section']][has_group['prop']];
		
		for(var i=0; i < gProp['count']; i++){
			var in_cid = pagelayer_element_add_child(jQuery("[pagelayer-id="+cid+"]"), gProp['sc']);
		}
		
	}
	
	return cid;
};

// Return an element by ID
function pagelayer_ele_by_id(id){
	return jQuery('[pagelayer-id='+id+']');
};

// Return the wrap by ID
function pagelayer_wrap_by_id(id){
	return jQuery('[pagelayer-wrap-id='+id+']');
};

// Give the Pagelayer ID
function pagelayer_id(jEle){
	
	var id = jEle.attr('pagelayer-wrap-id');
	if(id){
		return id;
	}
	
	id = jEle.attr('pagelayer-id');
	
	return id;
	
}

// Assign the jQuery object an ID
function pagelayer_assign_id(jEle){
	
	// Do you have the pagelayer id
	var id = jEle.attr("pagelayer-id");
	if(!id || id.length < 1){
		id = pagelayer_randstr(16);
		jEle.attr("pagelayer-id", id);
	}
	
	return id;
	
}

// Show the edit options
function pagelayer_element_clicked(selector, e){
	
	var jEle = jQuery(selector);
	e = e || false;
	//console.log(e);	
	
	// You must be a element atleast
	if(!jEle.hasClass('pagelayer-ele')){
		return false;
	}
	
	// Get the parent
	var pId = pagelayer_get_parent(jEle);
	
	// If we found a parent
	if(pId){
		jEle = pagelayer_ele_by_id(pId);	
	}
	
	// Make the editable fields active	
	//pagelayer_clear_editable();// First clear
	jEle.find('[pagelayer-editable]').each(function (){
		pagelayer_make_editable(jQuery(this), e);
	});
	
	// Lets not rebuild everything to make it faster
	if(pagelayer_is_active(jEle)){
		return false;
	}
	
	// Set this as the active element
	pagelayer_set_active(jEle);
	
	// Show the properties
	pagelayer_elpd_open(jEle);
	
}

// The edit option
function pagelayer_edit_element(selector){
	pagelayer_element_clicked(selector);
}

// Setup the properties on a single click
function pagelayer_element_setup(selector, render){
	
	var selector = selector || ".pagelayer-ele";
	render = render || false;
	
	// Loop through
	jQuery(pagelayer_editable+' '+selector).each(function(){
			
		var jEle = jQuery(this);
		
		// Assign an ID if not there
		var id = pagelayer_assign_id(jEle);
		var pId = pagelayer_get_parent(jEle) || '';// Options to show on hover
		var selector = '[pagelayer-id='+id+']';
		
		if(render){
			pagelayer_sc_render(jEle);
		}
		
		// Get the tag
		var tag = pagelayer_tag(jEle);
		
		// Lets check if we are the child of a parent i.e. element of a group
		if(pagelayer_empty(pId)){
		
			// Get the parent
			var pEle = jEle.parent().closest('.pagelayer-ele');
			
			// If we found a parent
			if(pEle.length > 0){

				var pTag = pagelayer_tag(pEle);
				
				// Is the parent a group of this child ?
				if(!pagelayer_empty(pagelayer_shortcodes[pTag]) && pagelayer_is_group(pTag)){
					
					var has_group = pagelayer_shortcodes[pTag]['has_group'];		
					var child_type = pagelayer_shortcodes[pTag][has_group['section']][has_group['prop']]['sc'];
					
					// If the type is the same as jEle
					if(child_type == pagelayer_tag(jEle)){
						pId = pagelayer_assign_id(pEle);
						pagelayer_set_parent(jEle, pId);
					}
				}
			
			}
		
		}
	
		// Make the wraps
		jEle.wrap('<div class="pagelayer-ele-wrap" pagelayer-wrap-id="'+id+'"></div>');
		var wrap = jEle.parent();
		
		// For column we have to do some kidas !
		if(tag == 'pl_col'){
			
			var col;
			for(var x=1; x<=12; x++){
				if(jEle.hasClass('pagelayer-col-'+x)){
					col = 'pagelayer-col-'+x;
					break;
				}
			}
			
			var is_custom_width = pagelayer_get_att(jEle, 'col');
			var col_width = pagelayer_get_att(jEle, 'col_width');
			if(pagelayer_empty(is_custom_width) && col_width){
				wrap.css('width', col_width+'%');
			}
			
			wrap.addClass('pagelayer-col '+col);
			//jEle.removeClass('pagelayer-col '+col);
			wrap.addClass('pagelayer-wrap-col');
			
		}else if(tag == 'pl_row'){
			wrap.addClass('pagelayer-wrap-row');
		}else if(tag == 'pl_inner_row'){
			wrap.addClass('pagelayer-wrap-inner-row');
		}else{
			wrap.addClass('pagelayer-wrap-ele');
		}
		
		// Create the overlay
		wrap.prepend('<div class="pagelayer-ele-overlay"></div>');
			
		var overlay = wrap.children('.pagelayer-ele-overlay');
		var html;
		
		if(tag == 'pl_row' || tag == 'pl_inner_row'){
			
			overlay.addClass('pagelayer-row-hover');
			
			if(jEle.hasClass('pagelayer-row-stretch-full')){
				pagelayer_sc_render(jEle);
			}
			
			html = '<div class="pagelayer-row-option" pagelayer-option-edit pagelayer-option-id="'+id+'">'+
				'<i class="fa fa-clone pagelayer-eoi" onclick="pagelayer_copy_element(\''+selector+'\')" />'+
				'<i class="fa fa-trash pagelayer-eoi" onclick="pagelayer_delete_element(\''+selector+'\')" />'+
				'<i class="fa fa-pencil pagelayer-eoi" onclick="pagelayer_edit_element(\''+selector+'\', event)" />'+
			'</div>';
		
		}else if(tag == 'pl_col'){
			
			overlay.addClass('pagelayer-col-hover');
			
			html = '<div class="pagelayer-col-option" pagelayer-option-edit pagelayer-option-id="'+id+'">'+
				'<i class="fa fa-columns pagelayer-eoi" onclick="pagelayer_edit_element(\''+selector+'\', event)" />'+
			'</div>';
			
			// Is it an empty col ?
			pagelayer_empty_col(jEle.children('.pagelayer-col-holder'));
		
		}else{
		
			html = '<div class="pagelayer-ele-option" pagelayer-option-edit pagelayer-option-id="'+id+'">'+
				'<i class="fa fa-clone pagelayer-eoi" onclick="pagelayer_copy_element(\''+selector+'\')" />'+
				'<i class="fa fa-trash pagelayer-eoi" onclick="pagelayer_delete_element(\''+selector+'\')" />'+
				'<i class="fa fa-pencil pagelayer-eoi" onclick="pagelayer_edit_element(\''+selector+'\', event)" />'+
			'</div>';
		
		}
		
		// Append to the child
		overlay.append(html);
		jQuery('[pagelayer-option-id='+id+']').hide();
		
		// Setup the HOVER events ABD create WRAPS IF we dont have a parent
		if(pId.length > 0){
			return;
		}
		
		// Make the wrap draggable, but only of independent or parent elements
		wrap.attr('draggable', 'true');
		
		wrap.hover(function(){
			
			// Is there an element option shower ?
			var opts = jQuery('[pagelayer-option-id='+id+']');
			
			// Give the overlay the hover class
			opts.parent().addClass('pagelayer-ele-hover');
			
			// Show them
			opts.show();
			
		}, function(){
			
			// Is there an element option shower ?
			var opts = jQuery('[pagelayer-option-id='+id+']');
			
			// Remove hover class
			opts.parent().removeClass('pagelayer-ele-hover');
			
			// Hide opts
			opts.hide();
			
		});
		
	});
}

// Left Click
function pagelayer_left_click(){
	
	jQuery(pagelayer_editable).on('click', function(e){
		
		e.preventDefault();// Added by Jivan in Actions / Revisions version
		
		// Hide the context menu
		jQuery('.pagelayer-right-click-options').hide();
		
		// Target
		var tEle = jQuery(e.target);
		
		// If its an edit option click
		if(tEle.hasClass('pagelayer-eoi')){
			return false;
		}
		
		pagelayer_element_clicked(tEle.closest('.pagelayer-ele'), e);
		
		return false;
		
	});
};

// Right Click Menu
function pagelayer_right_click(){
	
	var html = '<div class="pagelayer-right-click-options" style="display:none;">'+
		'<ul>'+
			'<li><a class="pagelayer-right-edit">Edit</a></li>'+
			'<li><a class="pagelayer-right-duplicate"><i class="fa fa-clone" /> Duplicate</a></li>'+
			'<li><a class="pagelayer-right-copy"><i class="fa fa-files-o" /> Copy</a></li>'+
			'<li><a class="pagelayer-right-paste"><i class="fa fa-clipboard" /> Paste</a></li>'+
			'<li><a class="pagelayer-right-delete"><i class="fa fa-trash-o" /> Delete</a></li>'+
		'</ul>'+
	'</div>';
	
	jQuery('body').append(html);
	
	var $contextMenu = jQuery('.pagelayer-right-click-options');
	
	jQuery(pagelayer_editable).on('contextmenu', function(e){
		
		var tEle = jQuery(e.target);
		var jEle = tEle.closest('.pagelayer-ele-wrap').children('.pagelayer-ele');
		
		// Get the parent
		var pId = pagelayer_get_parent(jEle);
		
		// If we found a parent
		if(pId){
			jEle = pagelayer_ele_by_id(pId);
		}
		
		// The basics
		var id = pagelayer_assign_id(jEle);		
		var tag = pagelayer_tag(jEle);
		
		$contextMenu.find('.pagelayer-right-edit').attr('onclick', 'pagelayer_edit_element("[pagelayer-id='+id+']")').html('<i class="fa fa-pencil-square-o" /> Edit '+pagelayer_shortcodes[tag]['name']);
		$contextMenu.find('.pagelayer-right-duplicate').attr('onclick', 'pagelayer_copy_element("[pagelayer-id='+id+']")');
		$contextMenu.find('.pagelayer-right-copy').attr('onclick', 'pagelayer_copy_select("[pagelayer-id='+id+']")');
		$contextMenu.find('.pagelayer-right-paste').attr('onclick', 'pagelayer_paste_element("[pagelayer-id='+id+']")');
		$contextMenu.find('.pagelayer-right-delete').attr('onclick', 'pagelayer_delete_element("[pagelayer-id='+id+']")');
		
		// Are we to hide the paste ?
		if(!pagelayer_empty(pagelayer.copy_selected) && pagelayer_can_copy_to(jEle)){
			//console.log(pagelayer_can_copy_to(jEle));
			$contextMenu.find('.pagelayer-right-paste').parent().show();
		}else{
			$contextMenu.find('.pagelayer-right-paste').parent().hide();
		}
		
		$contextMenu.css({
			display: "block",
			left: e.pageX,
			top: e.pageY
		});
		
		return false;
		 
	});
	
	jQuery('html').on('click', function(e){
		$contextMenu.hide();
	});
}

// Set the parent for the group
function pagelayer_set_parent(jEle, id){
	jEle.attr('pagelayer-parent', id);
};

// Set the parent for the group
function pagelayer_get_parent(jEle){
	return jEle.attr('pagelayer-parent');
};

// Sets the screen mode
function pagelayer_set_screen_mode(mode){
	var modes = ['desktop', 'tablet', 'mobile'];
	var body = pagelayer.$$('.pagelayer-iframe-holder iframe');
	var current = '';
	
	for(var x in modes){
		
		if(body.hasClass('pagelayer-screen-'+modes[x]) && modes[x] != mode){
			current = modes[x];
			body.removeClass('pagelayer-screen-'+modes[x]);
		}
	}
	
	// Add the class
	body.addClass('pagelayer-screen-'+mode);
	
	// Add the class to the button
	pagelayer.$$('.pagelayer-mode-button').removeClass('fa-'+current).addClass('fa-'+mode);
	
	// Add the class to the button
	pagelayer.$$('.pagelayer-prop-screen').removeClass('fa-'+current).addClass('fa-'+mode);
	
	// Trigger screen change if any
	pagelayer.$$('.pagelayer-elp-screen').trigger('pagelayer-screen-changed');
	
};

// Get the current screen mode
function pagelayer_get_screen_mode(){
	var modes = ['desktop', 'tablet', 'mobile'];
	var body = pagelayer.$$('.pagelayer-iframe-holder iframe');
	
	for(var x in modes){
		if(body.hasClass('pagelayer-screen-'+modes[x])){
			return modes[x];
		}
	}
}

// Handle key press events
jQuery(document).keydown(function(event){
	//alert(String.fromCharCode(event.which));
	
	// Delete
	if(event.keyCode == 46){
		pagelayer_delete_element('[pagelayer-active]');
	}
	
	// ctrl+z handle
	if(event.keyCode == 90 && event.ctrlKey){
		pagelayer_do_history('undo');
	}
	
	// ctrl+y handle
	if(event.keyCode == 89 && event.ctrlKey){
		pagelayer_do_history('redo');
	}
	
	// ctrl+c handle
	if(event.keyCode == 67 && event.ctrlKey){
		
		// Check the active element
		if(pagelayer_active.el && pagelayer_active.el.id){
			pagelayer_copy_select("[pagelayer-id='"+pagelayer_active.el.id+"']");
		}
		
	}
	
	// ctrl+v handle
	if(event.keyCode == 86 && event.ctrlKey){
		
		// Check the active element
		if(pagelayer_active.el && pagelayer_active.el.id){
			
			var jEle = jQuery("[pagelayer-id='"+pagelayer_active.el.id+"']");
			
			// Check if the any element is copied
			if(!pagelayer_empty(pagelayer.copy_selected) && pagelayer_can_copy_to(jEle)){
				pagelayer_paste_element("[pagelayer-id='"+pagelayer_active.el.id+"']");
			}
			
		}
	}
});

// Delete an element as per the selector
function pagelayer_delete_element(selector){
	var jEle = jQuery(selector);
	
	// Anything found ?
	if(jEle.length > 0){
		
		var id = pagelayer_assign_id(jEle);
		var sc = pagelayer_tag(jEle);
		
		// Is there a wrap
		var wrap = jQuery('[pagelayer-wrap-id="'+id+'"]');
		
		var par = wrap.parent();
		
		// Save this element in history action
		if(pagelayer.history_action){	
			var cEle = pagelayer_near_by_ele(id, sc);
			
			// To save in history, we need to save only element not the wraps as we call setup if we redo or undo	
			jEle.find('style').remove();
			jEle.find('.pagelayer-ele-overlay').remove();
			
			// Unwrap the wraps
			jEle.find('.pagelayer-ele').each(function (){
				var ele = jQuery(this);
				if(ele.parent().is('.pagelayer-ele-wrap')){
					ele.unwrap();
				}
			});
						
			pagelayer_history_action_push({
				'title' : pagelayer_shortcodes[sc]['name'],
				'action' : 'Deleted',
				'pl_id' : id,
				'html' : jEle,
				'cEle' : cEle
			});
		}
		
		wrap.remove();
		
		pagelayer_empty_col(par);
		
		if(pagelayer_active.el && pagelayer_active.el.id == id){
			pagelayer.$$('.pagelayer-elpd-close').click();
		}
		
	}
	
	pagelayer_isDirty = true;
};

// Select an element
function pagelayer_copy_select(selector){
	pagelayer.copy_selected = selector;
}

function pagelayer_can_copy_to(to){
	var jEle = jQuery(pagelayer.copy_selected);
	var tEle = jQuery(to);
	
	var eTag = pagelayer_tag(jEle);
	var tTag = pagelayer_tag(tEle);
	//console.log(eTag+' - '+tTag);
	// Final to
	var fTo;
	
	// Selected element is a Row, can go only after a row
	if(eTag == 'pl_row'){
		fTo = tEle.closest('.pagelayer-ele.pagelayer-row');
		if(fTo.length != 1) return false;
		return fTo;
	}
	
	// Selected element is a Column, can go only after a col
	if(eTag == 'pl_col'){
		fTo = tEle.closest('.pagelayer-ele.pagelayer-col');
		if(fTo.length != 1) return false;
		return fTo;
	}
	
	// Is the TARGET a row or column when the selected item is a element
	if(tTag == 'pl_row' || tTag == 'pl_col'){
		return false;
	}
	
	return tEle;
	
}

// Select an element
function pagelayer_paste_element(to){
	
	var fTo = pagelayer_can_copy_to(to);
	
	// Is it a valid to
	if(!fTo){
		return false;
	}
	
	pagelayer_copy_element(pagelayer.copy_selected, fTo);
}

// Copy an element
// Note : insertAfter should always be an pagelayer-ele
function pagelayer_copy_element(selector, insertAfter){
	var src = jQuery(selector);
	var html = src[0].outerHTML;
	var tag = pagelayer_tag(src);
	insertAfter = insertAfter || src;
	insertAfter = insertAfter.parent();
	
	var jEle = jQuery(html);
	jEle.removeAttr('pagelayer-id');
	jEle.find('[pagelayer-id]').removeAttr('pagelayer-id');
	jEle.find('[pagelayer-parent]').removeAttr('pagelayer-parent');// Remove the parent attribute as it will be reset during pagelayer_element_setup
	jEle.find('style').remove();
	jEle.find('.pagelayer-ele-overlay').remove();
	
	// Unwrap the wraps
	jEle.find('.pagelayer-ele').each(function (){
		var ele = jQuery(this);
		if(ele.parent().is('.pagelayer-ele-wrap')){
			ele.unwrap();
		}
	});
	
	// Give it an ID
	var id = pagelayer_assign_id(jEle);
	
	jQuery(insertAfter).after(jEle);
	
	pagelayer_element_setup('[pagelayer-id='+id+'], [pagelayer-id='+id+'] .pagelayer-ele', true);
	
	// Save this element in history action
	if(pagelayer.history_action){
		var cEle = pagelayer_near_by_ele(id, tag);
		pagelayer_history_action_push({
			'title' : pagelayer_shortcodes[tag]['name'],
			'action' : 'Copied',
			'pl_id' : id,
			'html' : jEle,
			'cEle' : cEle
		});
	}
	
	//If column then renumber columns
	if(tag == 'pl_col'){
		var row = src.parent().closest('.pagelayer-row');
		pagelayer_renumber_col(row);
	}
	
	pagelayer_isDirty = true;
	
	return id;
};

// Language key
function pagelayer_l(k){
	return k;
}

// Set the given jELE as active
function pagelayer_set_active(jEle){
	
	// Make all other element as inactive
	jQuery('[pagelayer-active]').each(function(){	
		var $j = jQuery(this);
		$j.removeAttr('pagelayer-active');
	});
	
	jEle.attr('pagelayer-active', 1);
	
	// Add and remove the class
	jQuery('.pagelayer-active').removeClass('pagelayer-active');
	
	jEle.parent().children('.pagelayer-ele-overlay').addClass('pagelayer-active');
	
}

function pagelayer_sc(sc){
	return sc.replace('pl_', '');
};

// Create a HTML dom element of the Short code
// Return the jEle
function pagelayer_create_sc(sc){
	
	var html;
	var _sc = pagelayer_sc(sc);
	var func = window['pagelayer_sc_'+sc];
	
	// Generate the HTML
	if(typeof func == 'function'){
		html = window['pagelayer_sc_'+sc]();
	}else{
		html = '<div '+pagelayer_sc_atts('pagelayer-'+_sc)+'></div>';
	}
	
	html = jQuery(html);
	
	// Add the tag
	html.attr('pagelayer-tag', sc);
	
	// Give it an ID
	id = pagelayer_assign_id(html);
	
	// Try to set the default values over 5 loops
	pagelayer_set_default_atts(html, 5);
	
	return html;
	
};

// Returns a list of default attributes to set as per the current selection
function pagelayer_set_default_atts(jEle, set){
	
	set = set || 0;
	var hasSet = false;
	
	for(var i = 1; i <= set;i++){
		
		//console.log('[pagelayer_set_default_atts] Loop :'+i);
		//console.log(jEle);
		
		// Get existing data
		var el = pagelayer_data(jEle, true);
		
		// If it is the last loop and we are greater than 1
		if(i > 1 && i == set){
			console.log('[pagelayer_default_atts] Still vars to set. Please check your shortcode params !');
		}
		
		// We are supposed to set !
		if('set' in el && !pagelayer_empty(el.set)){		
			pagelayer_set_atts(jEle, el.set);
			hasSet = true;
		}else{
			break;
		}
	}
	
	return hasSet;
}

// Returns the tag
function pagelayer_tag(jEle){
	
	// It could be the wrap
	if(jEle.hasClass('pagelayer-ele-wrap')){
		return jEle.children('.pagelayer-ele').attr('pagelayer-tag');
	}
	
	// It could be the row or col holder
	if(jEle.hasClass('pagelayer-row-holder') || jEle.hasClass('pagelayer-col-holder')){
		return jEle.parent().attr('pagelayer-tag');
	}
	
	return jEle.attr('pagelayer-tag');
}

// Gets a single attribute value
function pagelayer_get_att(jEle, att){
	return jEle.attr('pagelayer-a-'+att);
};

// Gets a single attribute value
function pagelayer_get_tmp_att(jEle, att){
	return jEle.attr('pagelayer-tmp-'+att);
};

// This function will just set atts and not do anything else
// Atts can be string or object. If its string, then val is needed
function pagelayer_set_atts(jEle, atts, val){
	
	if(typeof atts == 'string'){
		var tmp = {};
		tmp[atts] = val;
		atts = tmp;
	}
	
	if(typeof atts != 'object'){
		return false;
	}
	
	var tag = pagelayer_tag(jEle);
	var trigger_onchange = 0;
	
	if(pagelayer_empty(tag)){
		console.log('Set atts found no tag');
		console.log(jEle);
		return;
	}
		
	// All props
	var all_props = pagelayer_shortcodes[tag];//console.log(tag);console.log(jEle);
	var trigger_props = {};
	var no_val = {};
	var defaults = {};
	
	// Loop through all props
	for(var i in pagelayer_tabs){
		
		var tab = pagelayer_tabs[i];

		for(var section in all_props[tab]){
			
			var props = section in pagelayer_shortcodes[tag] ? pagelayer_shortcodes[tag][section] : pagelayer_styles[section];
			
			for(var x in props){
				
				if('default' in props[x]){
					defaults[x] = 1;
				}
				
				// Dont set any val, but we set temp value
				if('no_val' in props[x]){
					no_val[x] = 1;
				}
				
				if('req' in props[x] || 'show' in props[x]){
					var show = 'req' in props[x] ? props[x]['req'] : props[x]['show'];
					for(var showParam in show){
						var val = show[showParam];
						var except = showParam.substr(0, 1) == '!' ? true : false;
						showParam = except ? showParam.substr(1) : showParam;
						trigger_props[showParam] = 1;
					}
					
				}
				
			}
			
		}
		
	}
	
	for(var x in atts){
		
		// Are we to trigger change
		if(x in trigger_props){
			trigger_onchange = 1;
		}
		
		//console.log(x+'-'+atts[x]);
		
		if(x in no_val){
			pagelayer_set_tmp_atts(jEle, x, atts[x]);
			continue;
		}
		
		// Record History
		if(pagelayer.history_action){
			var attr_label = '';
			for(var section in all_props){
				for(var props in all_props[section]){
					if(props == x){
						attr_label = all_props[section][props]['label'];
					}
				}
					
			}
				
			var old_val = pagelayer_get_att(jEle, x) || '';
			pagelayer_history_action_push({
				'title' : all_props['name'],
				'subTitle' : attr_label,
				'action' : 'Edited',
				'attrType' : 'a_attr',
				'pl_id' : pagelayer_id(jEle),
				'atts' : x,
				'oldVal' : old_val,
				'newVal' : atts[x]
			});
		}
			
		// Remove the attribute if its BLANK and there is no default for it
		// If there is a default, we set it to blank to keep record of the current val
		if(atts[x].length < 1){
			
			// Dont remove the value as there is a default
			if(!(x in defaults)){
				jEle.removeAttr('pagelayer-a-'+x);
			// Otherwise blank it
			}else{
				jEle.attr('pagelayer-a-'+x, atts[x]);
			}
			
			// Remove the tmp atts anyway
			var to_del = new Array();
			var regexp = new RegExp('pagelayer\-tmp\-'+x, 'gi');
			
			jQuery.each(jEle[0].attributes, function(index, att){
				if(!att) return;
				if(att.name.match(regexp)){
					to_del.push(att.name);
				}
			});
			
			//console.log(to_del);
			for(var n in to_del){
				jEle.removeAttr(to_del[n]);
			}
		
		// Set the value
		}else{
			jEle.attr('pagelayer-a-'+x, atts[x]);
		}
		
		// Are you the active element
		if(pagelayer_is_active(jEle)){
			
			// TODO : Record Undo and Redo
			
		}
		
	}
	
	// Trigger the change of the parameter and show the required properties
	if(trigger_onchange){
		pagelayer_elpd_show_rows();
	}
	
	pagelayer_isDirty = true;
};

// This function will just set atts and not do anything else
// Atts can be string or object. If its string, then val is needed
function pagelayer_set_tmp_atts(jEle, atts, val){
	
	if(typeof atts == 'string'){
		var tmp = {};
		tmp[atts] = val;
		atts = tmp;
	}
	
	if(typeof atts != 'object'){
		return false;
	}
	
	for(var x in atts){
	
		// Record history
		if(pagelayer.history_action){
				
			var old_val = pagelayer_get_tmp_att(jEle, x) || '';
			pagelayer_history_action_push({
				'title' : pagelayer_shortcodes[pagelayer_tag(jEle)]['name'],
				'subTitle' : x,
				'action' : 'Edited',
				'attrType' : 'tmp_attr',
				'pl_id' : pagelayer_id(jEle),
				'atts' : x,
				'oldVal' : old_val,
				'newVal' : atts[x]
			});
			
		}
			
		jEle.attr('pagelayer-tmp-'+x, atts[x]);
	}
	
};

// Set the att and classes of an HTML which is not yet created
function pagelayer_sc_atts(classes, atts){
	
	if(typeof atts != 'object'){
		atts = new Object();
	}
	
	var r = new Array();
	
	for(var x in atts){
		r.push('pagelayer-a-'+x+'="'+atts[x]+'"');
	}
	
	return 'class="'+classes+' pagelayer-ele" '+r.join(' ');
}

// Is the jEle the active element ?
function pagelayer_is_active(jEle){
	
	// Is this the active Element ?
	if(pagelayer_empty(pagelayer_active.el) || jEle.attr('pagelayer-id') != pagelayer_active.el.id){
		return false;
	}
	
	return true;
	
};

// Removes {{}} from the variable name
function pagelayer_var(val){
	return val.substring(2, (val.length - 2));
}

// Take care of the CSS
function pagelayer_css_render(css, val, seperator){
	//console.log('CSS '+css+' | '+val);
	
	// Seperator
	seperator = seperator || ',';
	
	// Replace the val
	css = css.split('{{val}}').join(val);
	
	// If there is an array
	if(css.match(/val\[\d/)){
		val = val.split(seperator);
		for(var i in val){
			css = css.split('{{val['+i+']}}').join(val[i]);
		}
	}
	
	//console.log('Final CSS '+css);
	
	return css;
	
};

// Replace the variables
function pagelayer_parse_vars(str, el){
	
	for(var x in el.tmp){
		str = str.split('{{{'+x+'}}}').join(el.tmp[x]);
	}
	
	for(var x in el.atts){
		str = str.split('{{'+x+'}}').join(el.atts[x]);
	}
	
	return str;
};

// Render the Element
function pagelayer_sc_render(jEle){
	
	// We render only the active element
	if(!pagelayer_is_active(jEle)){
		//return false;
	}
	
  //console.log('Rendering');
	
  // Handle the CSS part
	// Get the id, tag, atts, data, etc
	var el = pagelayer_data(jEle, true);
	var all_props = pagelayer_shortcodes[el.tag];
	var elCSS = {
		classes: [],
		remove_classes: [],
		attr: [],
		remove_attr: [],
		css: [],
		edit: [],
		sel: '[pagelayer-id="'+el.id+'"]'
	};
	
	// Create a reference
	el.CSS = elCSS;
	
	//console.log(el.atts);
	
	for(var i in pagelayer_tabs){
		var tab = pagelayer_tabs[i];
		for(var section in all_props[tab]){	//console.log(tab+' '+section);
	
			var props = section in pagelayer_shortcodes[el.tag] ? pagelayer_shortcodes[el.tag][section] : pagelayer_styles[section];//console.log(props);
	
			// Loop the props
			for(var x in props){
				
				// pagelayer_data will return attributes even if they are BLANK e.g. attr=""
				// Render doesnt consider BLANK values as values, and we are unsetting them now
				// If in any situation you need to consider blank values, please handle in the JS / PHP function of the Shortcode
				if(x in el.atts && el.atts[x].length < 1){
					delete el.atts[x];
				}
				
				// Any editor ?
				if('edit' in props[x]){
					elCSS.edit.push({prop: x, sel: props[x]['edit']});
				}
				
				// Do we have a addClass ?
				// We are checking before the element has a value so that we can add or remove the class
				if('addClass' in props[x]){
					
					var addClasses;
					
					// Convert the string to an array
					if(typeof props[x]['addClass'] === 'string'){
						addClasses = [props[x]['addClass']];
					}else{
						addClasses = props[x]['addClass'];
					}
					
					for(var c in addClasses){
							
						// The selector
						var tSel = jQuery.isNumeric(c) ? '' : c;
						
						// If there is a VAL
						// NOTE : Only val is allowed when there is a list
						if(addClasses[c].match(/\{\{val\}\}/) && 'list' in props[x]){
							
							for(var l in props[x]['list']){
								
								var tmp = {'sel': tSel, 'val': addClasses[c].replace('{{val}}', l)};
								
								if(el.atts[x] == l){
									elCSS['classes'].push(tmp);
								}else{
									elCSS['remove_classes'].push(tmp);
								}
								
							}
							
						}else{
							
							var tmp = {'sel': tSel, 'val': addClasses[c]};
							
							// If the value is there
							if(x in el.atts){
								elCSS['classes'].push(tmp);
							}else{
								elCSS['remove_classes'].push(tmp);
							}
						
						}
					}
				}
				
				// Do we have a addAttr ? 
				// We are checking before the element has a value so that we can add or remove the attr
				if('addAttr' in props[x]){
					
					var addAttr;
					
					// Convert the string to an array
					if(typeof props[x]['addAttr'] === 'string'){
						addAttr = [props[x]['addAttr']];
					}else{
						addAttr = props[x]['addAttr'];
					}
					
					for(var c in addAttr){
							
						// The selector
						var tSel = jQuery.isNumeric(c) ? '' : c;
						var tmp = {'sel': tSel, 'val': addAttr[c]};
						
						// If the value is there
						if(x in el.atts){
							elCSS['attr'].push(tmp);
						}else{
							elCSS['remove_attr'].push(tmp);
						}
					}
				}
				
				// Do we have a CSS ? 
				if('css' in props[x]){
					
					var css;
	
					// Convert the string to an array
					if(typeof props[x]['css'] === 'string'){
						css = [props[x]['css']];
					}else{
						css = props[x]['css'];
					}
					
					// Screen modes
					var modes = {desktop: '', tablet: '_tablet', mobile: '_mobile'};
					
					for(var m in modes){
						
						var xm = x+modes[m];
						
						// If the value is there
						if(!(xm in el.atts)){
							continue;
						}
					
						for(var c in css){
								
							// The selector
							var tSel = jQuery.isNumeric(c) ? '{{element}}' : c;
							var tmp = {
								sel: tSel, 
								val: pagelayer_css_render(css[c], el.atts[xm], (props[x].sep || ',')),
							};
							
							// Is this a tablet
							if(m == 'tablet'){
								tmp.sel = '@media (max-width: 768px) and (min-width: 361px){'+tmp.sel;
								tmp.val = tmp.val+'}';
							}
							
							// Is this a mobile mode ?
							if(m == 'mobile'){
								tmp.sel = '@media (max-width: 360px){'+tmp.sel;
								tmp.val = tmp.val+'}';
							}
							
							// Push to store
							elCSS.css.push(tmp);
						}
					
					}
					
				}
				
			}
			
		}
		
	}
	
	// If there is an HTML, then process it
	if('html' in pagelayer_shortcodes[el.tag]){
		
		el.iHTML = jQuery('<div>'+pagelayer_shortcodes[el.tag]['html']+'</div>');
		
		// Lets process the 'if-ext'
		el.iHTML.find('[if-ext]').each(function (){
			var $j = jQuery(this);
			var reqvar = pagelayer_var($j.attr('if-ext'));
			$j.removeAttr('if-ext');
			
			// Is the element there ?
			if(!(reqvar in el.atts && !pagelayer_empty(el.atts[reqvar]))){
				//console.log('HERE');
				$j[0].outerHTML = $j.html();
			}
			
		});
		
		// Lets process the 'if'
		el.iHTML.find('[if]').each(function (){
			var $j = jQuery(this);
			var reqvar = pagelayer_var($j.attr('if'));
			$j.removeAttr('if');
			
			// Is the element there ?
			if(!(reqvar in el.atts && !pagelayer_empty(el.atts[reqvar]))){
				//console.log('HERE');
				$j.remove();
			}
			
		});
	
		// Is there a function to render ?
		var fn = window['pagelayer_render_'+jEle.attr('pagelayer-tag')];
		
		if(typeof fn == 'function'){
			fn(el);
		}
		
		//console.log(el.atts);
		
		// Parse the variables
		var new_html = pagelayer_parse_vars(el.iHTML.html(), el);
		el.iHTML.html(new_html);
		
		// Do we have to wrap the innerHTML ?
		if('holder' in pagelayer_shortcodes[el.tag]){
			
			var hSel = pagelayer_shortcodes[el.tag]['holder'];
			var holder = jEle.find(hSel).first();
			
			// Detach the holder
			holder.detach();
			
			// Add the new HTML
			el.$.html(el.iHTML.html());
			
			// reAttach the children only
			el.$.find(hSel).html(holder.children());
		
		// No holder
		}else{
		
			//console.log(el.iHTML.html());
			el.$.html(el.iHTML.html());
		
		}
		
	// Rows, Cols and Groups
	}else{
	
		// Is there a function to render ?
		var fn = window['pagelayer_sc_render_'+jEle.attr('pagelayer-tag')];
		
		if(typeof fn == 'function'){
			fn(el);
		}
		
	}
	
	// Is there a function to render after HTML insertion but before CSS and attr ?
	var post = window['pagelayer_render_html_'+jEle.attr('pagelayer-tag')];
	
	if(typeof post == 'function'){
		post(el);
	}
	
	////////////////////////////
	// Are there any edit fields ?
	////////////////////////////
	
	if(elCSS.edit.length > 0){
		
		for(var c in elCSS.edit){
			var prop = elCSS.edit[c]['prop'];
			var tSel = elCSS.edit[c]['sel'];
			var node = tSel.length < 1 ? jEle : jEle.find(tSel);
			node.attr('pagelayer-editable', prop);
		}
		
	}
	
	////////////////////////////
	// Are there any addClass ?
	////////////////////////////
	
	// If we have any classes to add
	if(elCSS.classes.length > 0){
		//console.log(elCSS.classes);
		
		for(var c in elCSS.classes){
			var tSel = elCSS.classes[c]['sel'];
			var node = tSel.length < 1 ? jEle : jEle.find(tSel);
			if(!node.hasClass(elCSS.classes[c]['val'])){
				node.addClass(elCSS.classes[c]['val']);
			}
		}
	}
	
	// If we have any classes to remove
	if(elCSS.remove_classes.length > 0){
		//console.log(elCSS.remove_classes);
		
		for(var c in elCSS.remove_classes){
			var tSel = elCSS.remove_classes[c]['sel'];
			var node = tSel.length < 1 ? jEle : jEle.find(tSel);
			if(node.hasClass(elCSS.remove_classes[c]['val'])){
				node.removeClass(elCSS.remove_classes[c]['val']);
			}
		}
	}
	
	////////////////////////////
	// Are there any addAttr ?
	////////////////////////////
	
	// If we have any attributes to add
	if(elCSS.attr.length > 0){
		//console.log(elCSS.attr);
		
		for(var c in elCSS.attr){
			var tSel = elCSS.attr[c]['sel'].replace('{{element}}', '');
			var node = tSel.length < 1 ? jEle : jEle.find(tSel);
			var att = elCSS.attr[c]['val'].split('=');
			att[1] = pagelayer_parse_vars(att[1], el);
			att[1] = pagelayer_trim(att[1], '"');
			
			// Is it the same val ?
			if(!node.attr(att[0]) != att[1]){				
				node.attr(att[0], att[1]);
			}
		}
	}
	
	// If we have any attributes to add
	if(elCSS.remove_attr.length > 0){
		//console.log(elCSS.remove_attr);
		
		for(var c in elCSS.remove_attr){
			var tSel = elCSS.remove_attr[c]['sel'].replace('{{element}}', '');
			var node = tSel.length < 1 ? jEle : jEle.find(tSel);
			var att = elCSS.remove_attr[c]['val'].split('=');
			
			if(node.is('['+att[0]+']')){
				node.removeAttr(att[0]);
			}
		}
	}
	
	// The style element
	var style = pagelayer.$('[pagelayer-style-id='+el.id+']');
	
	// If we have any RULES CSS, then handle it
	if(elCSS.css.length > 0){
		
		// Did we find it ?
		if(style.length < 1){
			jEle.prepend('<style pagelayer-style-id="'+el.id+'"></style>');
		}
		
		// Get it again
		style = pagelayer.$('[pagelayer-style-id='+el.id+']');
		
		// Make the rules
		var rules = [];
		
		// Loop
		for(var c in elCSS.css){
			var tSel = elCSS.css[c]['sel'].replace('{{element}}', elCSS.sel);
			var rule = pagelayer_parse_vars(elCSS.css[c]['val'], el);
			rules.push(tSel+'{'+rule+'}');
		}
		
		// Set the style
		style.html(rules.join("\n"));
		//console.log(style);
	}else{
		style.remove();
	}
	
	// Is there a function to render at the end ?
	var end = window['pagelayer_render_end_'+jEle.attr('pagelayer-tag')];
	
	if(typeof end == 'function'){
		end(el);
	}
	
	// If the element have any parent
	var par = pagelayer_get_parent(jEle);
	if(par){
		pagelayer_sc_render(pagelayer_ele_by_id(par));
	}
	
};

// Is the given tag a group
function pagelayer_is_group(tag){
	
	if('has_group' in pagelayer_shortcodes[tag] && !pagelayer_empty(pagelayer_shortcodes[tag]['has_group'])){
		return true;
	}
	
	return false;
	
}

// Save the post
function pagelayer_save(){
	
	var post = pagelayer_generate_sc(pagelayer_editable);//alert(post);return;
	var pagelayerajaxurl = pagelayer_ajax_url+'&action=pagelayer_save_content&postID='+pagelayer_postID;
	jQuery.ajax({
		type: "POST",
		url: pagelayerajaxurl,
		data: { 
			pagelayer_update_content : post, 
			pagelayer_nonce: pagelayer_ajax_nonce
		},
		success: function(response, status, xhr){
			//alert(data);
			var obj = jQuery.parseJSON(response);
			//alert(obj);
			if(obj['error']){
				alert(obj['error']);
			}else{
				pagelayer_isDirty = false;
				alert(obj['success']);
				pagelayer_get_revision();
			}
		},
		error: function(errorThrown){
			console.log(errorThrown);
		}
	});
	
};

//Close the Editor
function pagelayer_close(){
	if(pagelayer_isDirty == true){
		var r =	confirm('Your Data has not been Saved yet! \n Press OK to stay on the Page.'+
		'\n Press Cancel to Close Editor. ');
		if(r == false){
			window.top.location.href = pagelayer_returnURL;
		}
	}else{
		window.top.location.href = pagelayer_returnURL;
	}
};

// Generate Shortcode Post to save
function pagelayer_generate_sc(selector){
	
	var txt = '';
	
	jQuery(selector).children(".pagelayer-ele-wrap").each(function(){
		
		var jEle = jQuery(this).children('.pagelayer-ele');
		
		// The ID
		var id = jEle.attr('pagelayer-id');
		
		// If there is an Add element wrapper
		if(pagelayer_empty(id)){
			return;
		}
		
		// Find the type of tag
		var tag = jEle.attr('pagelayer-tag');
		var final_tag = tag;
		var closestEle = jQuery(this).closest('.pagelayer-col-holder');
		
		// Define inner row | Note : Commented as we now have a new widget of type inner_row
		/*if(tag == 'pl_row' && closestEle.length > 0 && closestEle.closest(pagelayer_editable).length > 0){
			final_tag = 'pl_inner_row';
		}*/

		// Define inner column
		if(tag == 'pl_col' && closestEle.length > 0 && closestEle.closest(pagelayer_editable).length > 0){
			final_tag = 'pl_inner_col';
		}
		//console.log(tag);
		
		// Is there an innerHTML ele
		var inner = '';
		if('innerHTML' in pagelayer_shortcodes[tag]){
			inner = pagelayer_shortcodes[tag]['innerHTML'];
		}
		
		// Create the tag
		var data = '['+final_tag;
		
		// Get the attributes to store
		jQuery.each(jEle[0].attributes, function(i, attrib){
			var res = attrib.name.match(/^pagelayer-a-(.+)/i);
			if(res && res[1] != inner){
				//console.log(attrib.name+' '+res[1]);
				data += ' '+res[1]+'="'+pagelayer_escapeHTML(attrib.value)+'"';
			}
		});
		
		data = data+']';
		
		// Add to the text
		txt = txt+data+"\n";
		
		// Any internal function to handle the save ?
		var func = window['pagelayer_tag_'+tag];
		if(typeof func == 'function'){
			
			var content = '';
			content = func(jEle);
			
			if(content.length > 0){
				txt = txt+content;
			}
			
		// If its a Row or Column or Group then it will have children
		}else if(jEle.hasClass('pagelayer-row') || jEle.hasClass('pagelayer-col') || jEle.hasClass('pagelayer-inner_row') || pagelayer_is_group(tag)){
			
			var sel = "[pagelayer-id="+id+"]";
			
			// Any holder which holds children ?
			if('holder' in pagelayer_shortcodes[tag]){
				sel = sel+' '+pagelayer_shortcodes[tag]['holder'];
			}
			
			// Select the top-most element
			sel = jQuery(sel).first();
			
			// Any child selector - Majorly for owl carousel
			// NOTE : Child selector should be very specific with immediate child selection at all levels
			var child_selector = false;			
			if('child_selector' in pagelayer_shortcodes[tag]){
				sel = sel.find(pagelayer_shortcodes[tag]['child_selector']);
			}
			
			var childrens = pagelayer_generate_sc(sel);
			if(childrens.length > 0){
				txt = txt+childrens;
			}
		
		// Its a normal element so we might need to handle the content
		}else{
			
			var content = '';
			if(inner.length > 0){
				content = jEle.attr('pagelayer-a-'+inner);
				if(!content){
					content = '';
				}
			}else{
				content = '';//jEle.html();
			}
			
			if(content.length > 0){
				txt = txt+content;
			}
			
		}
		
		// Close the tag
		txt = txt+"[/"+final_tag+"]\n";
		
	});
	
	return txt;
	
};

// Escape charaters in attributes
var pagelayer_escapeChars = {
	'\\]' : '#93',
	'\\[' : '#91',
	//'=' : '#61',
	'<' : 'lt',
	'>' : 'gt',
	'"' : 'quot',
	//'&' : 'amp',
	'\'' : '#39'
};

// To unescape
var pagelayer_unescapeChars = {
	'#93' : ']',
	'#91' : '[',
	//'#61' : '=',
	'lt' : '<',
	'gt' : '>',
	'quot' : '"',
	//'amp' : '&',
	'#39' : '\''
};

var pagelayer_escaperegex_S = '[';
for(var key in pagelayer_escapeChars) {
	pagelayer_escaperegex_S += key;
}
pagelayer_escaperegex_S += ']';

var pagelayer_escaperegex = new RegExp( pagelayer_escaperegex_S, 'g');

// The function which escapes everything
function pagelayer_escapeHTML(str) {
	return str.replace(pagelayer_escaperegex, function(m) {
		if(m == '[' || m == ']') m = '\\'+m;
		return '&' + pagelayer_escapeChars[m] + ';';
	});
};

// This will unescape everything
function pagelayer_unescapeHTML(str){
	return str.replace(/\&([^;]+);/g, function (entity, entityCode) {
		if(entityCode in pagelayer_unescapeChars){
			return pagelayer_unescapeChars[entityCode];
		}else{
			return entity;
		}
	});
};

// Show the required leftbar tab
function pagelayer_leftbar_tab(tab){	
	pagelayer.$$('.pagelayer-leftbar-tab').hide();
	pagelayer.$$('#'+tab).show();	
}

// Sets up the leftbar
function pagelayer_leftbar(){
	
	// Toggle the holder
	pagelayer.$$('.pagelayer-leftbar-toggle').on('click', function(){
		pagelayer.$$('.pagelayer-leftbar-table').toggle();
	});
	
	var html = '<div class="pagelayer-leftbar">'+
	'<div class="pagelayer-leftbar-scroll">'+
		'<div id="pagelayer-shortcodes" class="pagelayer-leftbar-tab pagelayer-shortcodes">'+
			'<div class="pagelayer-leftbar-search">'+
				'<i class="fa fa-search" /><input class="pagelayer-search-field" />'+
			'</div>';
		
	for(var x in pagelayer_groups){
		
		// Title
		html += '<div class="pagelayer-leftbar-group"><h5>'+x+'</h5>';
		
		// Indivdual icon
		for(var y in pagelayer_groups[x]){
			
			var sc = pagelayer_groups[x][y];
			
			if(!(sc in pagelayer_shortcodes) || 'not_visible' in pagelayer_shortcodes[sc]){
				continue;
			}
			
			html += '<div class="pagelayer-shortcode-drag" draggable="true" pagelayer-tag="'+sc+'">'+
				'<div class="pagelayer-sc">'+
					'<center class="pagelayer-shortcode-inner">';
					
					if('icon' in pagelayer_shortcodes[sc]){
						html += '<i class="pagelayer-shortcode fa fa-'+pagelayer_shortcodes[sc]['icon']+'"></i>';
					}else{
						html += '<i class="pagelayer-shortcode pagelayer-'+sc+'"></i>';
					}
					
					html += '</center>'+
					'<span class="pagelayer-shortcode-text">'+pagelayer_shortcodes[sc]['name']+'</span>'+
				'</div>'+
			'</div>';
			
		}
		
		html += '</div>';
		
	}
	
	html += '</div>'+
		'<div id="pagelayer-elpd" class="pagelayer-leftbar-tab pagelayer-elpd"></div>'+
		'<div id="pagelayer-history" class="pagelayer-leftbar-tab pagelayer-history"></div>'+
		'<div id="pagelayer-post-settings" class="pagelayer-leftbar-tab pagelayer-post-settings"></div>'+
	'</div>'+
'</div>';

	pagelayer.$$('.pagelayer-leftbar-holder').prepend(html);
	pagelayer_leftbar_tab('pagelayer-shortcodes');
	
	pagelayer.$$('.pagelayer-leftbar-scroll').slimScroll({
		height: '100%',
		railVisible: false,
		alwaysVisible: true,
		color: '#000',
		size: '5px',
	});
	
	// Hide the ones which are not supposed to be shown
	pagelayer.$$('.pagelayer-search-field').on('input', function(){
		
		var val = jQuery(this).val();
		var re = new RegExp(val, 'i');
		
		// Show only the required tags
		pagelayer.$$('.pagelayer-leftbar-group').each(function(){
			
			var group = jQuery(this);
			var res = group.find('[pagelayer-tag]');
			var hidden = 0;
			
			res.each(function(){
				
				var tEle = jQuery(this);
				if(tEle.find('.pagelayer-shortcode-text').html().match(re)){
					tEle.show();
				}else{
					hidden += 1;
					tEle.hide();
				}
				
			});
			
			// Hide the whole group
			if(hidden == res.length){
				group.hide();
			}else{
				group.show();
			}
				
		});
	});
	
	// On click Pagelayer setting icon
	pagelayer.$$('.pagelayer-settings-icon').click(function(){
		pagelayer.$$('.pagelayer-elpd-header').show().find('.pagelayer-elpd-title').text('Post Settings');
		pagelayer.$$('.pagelayer-logo').hide();
		pagelayer_leftbar_tab('pagelayer-post-settings');
		pagelayer_active = {};
		pagelayer_post_settings();
	});
	
};

// Post setting holder
function pagelayer_post_settings(){
	
	// Start pagelayer settings holder
	var setting_holder = '<div class="pagelayer-post-settings-holder">';
	
	// Start pagelayer settings content body
	setting_holder += '<div class="pagelayer-post-settings-acc">'+
		'<div class="pagelayer-post-settings-lable"><span>Title</span><i class="pagelayer-toggle fa"></i></div>'+
		'<div class="pagelayer-post-settings-fields">'+
			'<label for="post_title">Post Title</label> '+
			'<input type="text" name="post_title" value="'+ pagelayer_postTitle +'" id="title" spellcheck="true" autocomplete="off">'+
			'<button class="pagelayer-post-settings-apply pagelayer-success-btn" disabled>Apply</button>'+
		'</div>'+
	'</div>';
	
	/*if(pagelayer_postType == pagelayer_cpt['name']){
		setting_holder += '<div class="pagelayer-post-settings-acc">'+
			'<div class="pagelayer-post-settings-lable"><span>Custom Template type</span><i class="pagelayer-toggle fa"></i></div>'+
			'<div class="pagelayer-post-settings-fields">'+ pagelayer_CPT_condi +
			'</div>'+
		'</div>';
	}*/
	
	/* if(pagelayer_support_FI){
		setting_holder += '<div class="pagelayer-post-settings-acc">'+
			'<div class="pagelayer-post-settings-lable"><span>Featured Image</span><i class="pagelayer-toggle fa"></i></div>'+
			'<div class="pagelayer-post-settings-fields">Featured Image'+
			'</div>'+
		'</div>';
	} */
	
	// End pagelayer settings content body
	
	// End pagelayer settings holder
	setting_holder += '</div>';
	var holder = pagelayer.$$('#pagelayer-post-settings');
	holder.html(setting_holder);
	
	// On change input fields enable the apply button
	holder.find('input, select, textarea').on('change input', function(){
		jQuery(this).closest('.pagelayer-post-settings-fields').find('.pagelayer-post-settings-apply').removeAttr('disabled');
	});
	
	// On click the label toggle the setting field holder
	holder.find('.pagelayer-post-settings-lable').on('click', function(){
		
		var lEle = jQuery(this);
		var lEle_next = lEle.next('.pagelayer-post-settings-fields');
		
		holder.find('.pagelayer-post-settings-fields').each(function(){
			var fEle = jQuery(this);
			if(!fEle.is(lEle_next)){
				fEle.hide().prev('.pagelayer-post-settings-lable').find('.pagelayer-toggle').removeClass('pagelayer-open');
			}
		});
		
		lEle.next('.pagelayer-post-settings-fields').toggle();
		lEle.find('.pagelayer-toggle').toggleClass('pagelayer-open');
		
	});
	
	// On click apply button
	holder.find('.pagelayer-post-settings-apply').click(function(){
		var fEle = jQuery(this).closest('.pagelayer-post-settings-fields');
		var data = fEle.find('input, select, textarea').serialize();
		
		jQuery.ajax({
			url: pagelayer_ajax_url+'action=pagelayer_save_settings&postID='+pagelayer_postID,
			type: 'post',
			data : 'pagelayer_nonce='+ pagelayer_ajax_nonce +'&'+ data,
			success: function(response, status, xhr){
			//alert(data);
				var obj = jQuery.parseJSON(response);
				//alert(obj);
				if(obj['error']){
					alert(obj['error']);
				}else{
					alert(obj['success']);
					window.location.reload(); 
				}
			},
			error: function(errorThrown){
				console.log(errorThrown);
			}
		});
		
	});
	
	return;
}

// Get the closest element and method
function pagelayer_near_by_ele(id, sc){
	
	// Get the previous element of the id element
	var prevEle_id = jQuery('[pagelayer-wrap-id="'+id+'"]').prev().attr('pagelayer-wrap-id') || '';
	var method, cEle, args = {};

	if(prevEle_id.length > 0){
		
		// If have previous element of the id element
		// Set the method and previous element selector
		args = {'method' : 'after', 'cEle' : '[pagelayer-wrap-id="'+prevEle_id+'"]'};
		
	}else{
		
		// If don't have previous element of the id element then get parent element
		if(sc == "pl_row"){
			args = {'method' : 'prepend', 'cEle' : pagelayer_editable};
		}else{
			
			// Get the parent element 
			var pEle_id = pagelayer_id(jQuery('[pagelayer-wrap-id="'+id+'"]').closest('.pagelayer-ele'));
			
			// Get the parent element tag
			var pEle_tag = pagelayer_tag(jQuery('[pagelayer-id="'+pEle_id+'"]'));
			var holder = pagelayer_shortcodes[pEle_tag]['holder'] || '';
			args = {'method' : 'prepend', 'cEle' : '[pagelayer-id="'+pEle_id+'"] '+ holder+' '};
			
		}
		
	}
	
	return args;
	
};

// Push the action data in the pagelayer_history_obj object
function pagelayer_history_action_push(args){
	
	var history_obj_len = pagelayer_history_obj['action_data'].length;
	
	// If the history_obj_len is less then 1 then set the data in array 0 position 
	if(history_obj_len < 1){
		pagelayer_history_obj['action_data'][0] = {'title' : 'Start Editing', 'action' : 'Start' };
		pagelayer_history_obj['current_active_item_id'] = 0;
	}
	
	// Remove the second array element if the history_obj_len greater then 100
	if(history_obj_len > 100){
		pagelayer_history_obj['action_data'].splice(1, 1);
	}
	
	// Get current active history action id 
	var action_id = parseInt(pagelayer_history_obj['current_active_item_id']) || 0;
	
	// Remove the all array element after the active array element  
	var del_ele = history_obj_len - action_id - 1;
	pagelayer_history_obj['action_data'].splice(action_id + 1, del_ele);
	
	// Check if the same attr set as current active history
	if(args.action == "Edited" && history_obj_len > 1){
		var atts = pagelayer_history_obj['action_data'][action_id] || '';
		if(atts['atts'] == args['atts']){
			args['oldVal'] = atts['oldVal'];
			pagelayer_history_obj['action_data'][action_id] = args;
			pagelayer_history_setup();
			return true;
		}	
	}

	pagelayer_history_obj['action_data'].push(args);
	pagelayer_history_obj['current_active_item_id'] = pagelayer_history_obj['action_data'].length - 1;
	pagelayer_history_setup();
	
}

// Setup pagelayer history
function pagelayer_history_setup(force){
	
	var force = force || false;
	
	// If the history tab is visible, only then setup
	if(!pagelayer.$$('#pagelayer-history').is(':visible') && !force){
		return;
	}
	
	// The current active action id
	var current_id = pagelayer_history_obj['current_active_item_id'];
	
	// pagelayer-HISTORY - Element Properties Dialog
	var pagelayer_history_html = '<div class="pagelayer-history-tabs">'+
			'<div class="pagelayer-history-tab" pagelayer-history-tab="actions" pagelayer-history-active-tab="1">Actions</div>'+
			'<div class="pagelayer-history-tab" pagelayer-history-tab="revisions">Revisions</div>'+
		'</div>'+
		'<div class="pagelayer-history-body">'+
			'<div class="pagelayer-history-section active" pagelayer-show-tab="actions">';
	
	// Any actions	
	if(pagelayer_history_obj['action_data'].length > 0){
		
		for(var x in pagelayer_history_obj['action_data']){
			
			if(pagelayer_empty(pagelayer_history_obj['action_data'][x])){continue;}
			
			var title = pagelayer_history_obj['action_data'][x]['title'] || '';
			var subTitle = pagelayer_history_obj['action_data'][x]['subTitle'] || '';
			var action = pagelayer_history_obj['action_data'][x]['action'] || '';
			var tmp_attr = pagelayer_history_obj['action_data'][x]['attrType'] || '';
			var eAttr = '';
			
			if(!pagelayer_empty(tmp_attr) && tmp_attr == "tmp_attr"){
				eAttr = "pagelayer-history-hidden";
			}
			
			pagelayer_history_html += '<div class="pagelayer-history-holder '+((current_id == x) ? 'current_active_item' : '' )+' '+eAttr+'" history-action-id="'+x+'" >'+
				'<div class="pagelayer-history-detail-holder">'+
					'<span class="pagelayer-history-title"><b> '+title+' </b></span>'+
					'<span class="pagelayer-history-subtitle"> '+subTitle+' </span>'+
					'<span class="pagelayer-history-action"><i> '+action+' </i></span>'+
				'</div>'+
				'<div class="pagelayer-history-icon">'+
					'<span class="pagelayer-history-check fa fa-check" aria-hidden="true"></span>'+
				'</div>'+
			'</div>';
		}
		
	}else{
		pagelayer_history_html += 'No Actions history available yet';
	}

	pagelayer_history_html += '</div>'+
	'<div class="pagelayer-history-section" pagelayer-show-tab="revisions">';
	
	// Any revisions ?
	if(pagelayer_revision_obj){
		for(var x in pagelayer_revision_obj){
			pagelayer_history_html += '<div class="pagelayer-revision-holder" revision-id="'+pagelayer_revision_obj[x]['ID']+'">'+
				'<div class="pagelayer-revision-img-holder">'+
					'<img src="'+pagelayer_revision_obj[x]['post_author_url']+'" />'+ 
				'</div>'+
				'<div class="pagelayer-revision-detail-holder">'+
					'<div class="pagelayer-revision-date">'+
						pagelayer_revision_obj[x]['post_date_ago']+
						'('+pagelayer_revision_obj[x]['post_date']+')'+
					'</div>'+
					'<div class="pagelayer-revision-author">'+
						pagelayer_revision_obj[x]['post_type'] +' by '+
						pagelayer_revision_obj[x]['post_author_name']+
					'</div>'+
				'</div>'+
				'<div class="pagelayer-revision-icon-holder">'+
					'<i class="pagelayer-revision-delete fa fa-times"></i>'+ 
				'</div>'+
			'</div>';
		}
			
	}else{
		pagelayer_history_html += 'No Revisions history available';
	}
		
	pagelayer_history_html += '</div>'+
		'</div>';
	
	// Create the dialog box
	pagelayer.$$('#pagelayer-history').html(pagelayer_history_html);
	var holder = pagelayer.$$('#pagelayer-history');
	
	// Set active history holder
	holder.find('.pagelayer-history-holder').on('click', function(){
		var hEle = jQuery(this);
		var prev_item_id = pagelayer_history_obj['current_active_item_id'];
		hEle.parent().children().removeClass('current_active_item');
		hEle.addClass('current_active_item');
		var do_item_id = parseInt(hEle.attr('history-action-id'));
		pagelayer_history_action_setup(do_item_id, prev_item_id);
	});
	
	// Apply revision
	holder.find('.pagelayer-revision-holder').on('click', function(){
		var revision_id = jQuery(this).attr('revision-id');
		
		jQuery.ajax({
			url: pagelayer_ajax_url+'&action=pagelayer_apply_revision&revisionID='+revision_id,
			type: 'post',
			data: {
				pagelayer_nonce: pagelayer_ajax_nonce,
				'pagelayer-live' : 1,
			},
			success: function(response, status, xhr){
			
				var obj = jQuery.parseJSON(response);
				if(obj['error']){
					alert(obj['error']);
				}else{
					jQuery(pagelayer_editable).html(obj['content']);
					alert(obj['success']);
					pagelayer_element_setup();
					pagelayer_add_widget();
				}
			}
		});
	});
	
	// Delete the revision
	holder.find('.pagelayer-revision-delete').click(function(e){
		
		e.stopPropagation();
		var rEle = jQuery(this).closest('.pagelayer-revision-holder');
		var revision_id = rEle.attr('revision-id');
		
		if(confirm("Are you sure you want to delete the revision ?")){
			jQuery.ajax({
				url: pagelayer_ajax_url+'&action=pagelayer_delete_revision&revisionID='+revision_id,
				type: 'post',
				data: {pagelayer_nonce: pagelayer_ajax_nonce},
				success: function(response, status, xhr){
				
					var obj = jQuery.parseJSON(response);
					if(obj['error']){
						alert(obj['error']);
					}else{
						alert(obj['success']);
						rEle.hide();
					}
					
				}
			});
		}

	});
	
	// The tabs
	holder.find('.pagelayer-history-tab').on('click', function(){	
		var attr = 'pagelayer-history-active-tab';
		holder.find('.pagelayer-history-tab').each(function(){
			jQuery(this).removeAttr(attr);
		});
		jQuery(this).attr(attr, 1);
		
		// Get the active tab
		var active_tab = holder.find('[pagelayer-history-active-tab]').attr('pagelayer-history-tab');
		
		// Trigger the showing of rows
		holder.find('[pagelayer-show-tab]').each(function(){
			var sec = jQuery(this);
			
			// Is it the active tab ? 
			if(sec.attr('pagelayer-show-tab') != active_tab){
				sec.hide();
			}else{
				sec.show();
			}
		});
	});
}

// Get evisions Handler
function pagelayer_get_revision(){

	jQuery.ajax({
		url: pagelayer_ajax_url+'&action=pagelayer_get_revision&postID='+pagelayer_postID,
		type: 'post',
		data: {
			pagelayer_nonce: pagelayer_ajax_nonce,
		},
		//async:false,
		success: function(response, status, xhr){
			var obj = jQuery.parseJSON(response);
			
			if(!pagelayer_empty(obj['error'])){
				alert(obj['error']);
			}else{
				pagelayer_revision_obj = obj;
				pagelayer_history_setup(true);
			}
		}
	});
};

// Do the history action - use for ctrl-z and ctrl-y 
function pagelayer_do_history(action){
	
	var cur_id = pagelayer_history_obj['current_active_item_id'];
	var new_id = cur_id; 
	var action_data_len = pagelayer_history_obj['action_data'].length;
	
	
	if(action == 'undo'){
		
		// You cannot undo from the first movement
		if(cur_id == 0){
			return true;
		}
		
		for(var i = (cur_id - 1); i => 0; i--){
		
			var action = pagelayer_history_obj['action_data'][i];
			
			if('attrType' in action && action['attrType'] == 'tmp_attr'){
				continue;
			}
			
			new_id = i;
			break;
			
		}
		
	}else if(action == 'redo'){
		for(var i = cur_id + 1; i < action_data_len; i++){
			
			var action = pagelayer_history_obj['action_data'][i];
			
			if('attrType' in action && action['attrType'] == 'tmp_attr'){
				continue;
			}
			
			new_id = i;
			break;
			
		}
	}
	
	// Do the action
	pagelayer_history_action_setup(new_id, cur_id);
	pagelayer_history_setup();
	
};

// Action setup handle on ctrl-z and ctrl-y 
function pagelayer_history_action_setup(current_item_id, prev_item_id){
	
	// Set this as the current active
	pagelayer_history_obj['current_active_item_id'] = current_item_id;

	// Delete the element
	var delete_ele = function(id){
		
		// Set Pagelayer History FALSE to prevent saving delete action in action history
		pagelayer.history_action = false;
		
		pagelayer_delete_element('[pagelayer-id='+id+']');
		
		// Set Pagelayer History TRUE
		pagelayer.history_action = true;
		
	};
	
	// Re-setup the element
	var resetup_ele = function(history_array){
		jQuery(history_array.cEle.cEle)[history_array.cEle.method](history_array.html);
		pagelayer_element_setup('[pagelayer-id='+history_array.pl_id+'], [pagelayer-id='+history_array.pl_id+'] .pagelayer-ele', true);
		pagelayer_empty_col(jQuery('[pagelayer-id="'+history_array.pl_id+'"]').closest('.pagelayer-col-holder'));
	};
	
	// Re-setup the element attr
	var reset_ele_attr = function(hEle, atts, val, attrType){
		
		// Set Pagelayer History FALSE to prevent saving attributes in action history
		pagelayer.history_action = false;
		if(attrType == "tmp_attr"){
			pagelayer_set_tmp_atts(hEle, atts, val);
		}else{		
			pagelayer_set_atts(hEle, atts, val);
		}
		
		// The property holder
		var holder = pagelayer.$$('.pagelayer-elpd-body');
		holder.html(' ');
		pagelayer_sc_render(hEle);
		pagelayer_elpd_generate(hEle, holder);
		pagelayer.history_action = true;
		
	};
	
	if(prev_item_id > current_item_id){
		
		// All Actions for undo here
		var i = parseInt(prev_item_id);
		
		for(i; i > current_item_id; i--){
			
			var history_array = pagelayer_history_obj['action_data'][i];
			var action = history_array.action;
			var id = history_array.pl_id;
			
			if(action == "Edited"){
				hEle = jQuery('[pagelayer-id="'+id+'"]');
				reset_ele_attr(hEle, history_array.atts, history_array.oldVal, history_array.attrType);
			}else if(action == "Added"){
				delete_ele(id);
			}else if(action == "Deleted"){
				resetup_ele(history_array);
			}else if(action == "Copied"){
				delete_ele(id);
			}
			
		}
		
	}else{
		
		// All Actions for redo here
		var i = parseInt(prev_item_id)+1;
		
		for(i; i <= current_item_id; i++){
			
			var history_array = pagelayer_history_obj['action_data'][i];
			var action = history_array.action;
			var id = history_array.pl_id;
			
			if(action == "Edited"){
				hEle = jQuery('[pagelayer-id="'+id+'"]');
				reset_ele_attr(hEle, history_array.atts, history_array.newVal, history_array.attrType);
			}else if(action == "Added"){
				resetup_ele(history_array);
				
				if(history_array.tag != "pl_row" && history_array.tag != "pl_col" ){
					// Ensure the column is not empty
					pagelayer_empty_col(history_array.cEle.cEle);
				}
			}else if(action == "Deleted"){
				delete_ele(id);
			}else if(action == "Copied"){
				resetup_ele(history_array);
			}
			
		}
	}
	
};

// Report an error
function pagelayer_error(error, func){
	var prefix = func || '';
	alert(prefix+error);
};

function pagelayer_bottombar(){
	var holder = pagelayer.$$('.pagelayer-bottombar-holder');
	var html = '<div class="pagelayer-bottombar">'+
		'<div class="pagelayer-bottombar-rightbuttons">'+
			'<button class="pagelayer-update-button pagelayer-success-btn">Update</button>'+
			'<button class="pagelayer-close-button">Close</button>'+
			'<div class="pagelayer-mode-wrapper">'+
				'<i class="pagelayer-mode-button fa fa-desktop"></i>'+
				'<div class="pagelayer-mode-buttons-wrapper">'+
					'<i class="screen-mode fa fa-desktop" pagelayer-mode-data="desktop"></i>'+
					'<i class="screen-mode fa fa-tablet" pagelayer-mode-data="tablet"></i>'+
					'<i class="screen-mode fa fa-mobile" pagelayer-mode-data="mobile"></i>'+
				'</div>'+
			'</div>'+
			'<i class="pagelayer-history-icon fa fa-history"></i>'+
		'</div>'+
	'</div>';
	
	holder.html(html);
	holder.find('.pagelayer-update-button').on('click', function(){
		pagelayer_save();
		pagelayer_history_setup();// Setup history tab after update
	});
	
	holder.find('.pagelayer-close-button').on('click', function(){
		pagelayer_close();
	});
	holder.find('.screen-mode').on('click', function(){
		var screen_mode = jQuery(this).attr('pagelayer-mode-data');
		pagelayer_set_screen_mode(screen_mode);
		holder.find('.pagelayer-mode-buttons-wrapper').toggle();
	});
	
	holder.find('.pagelayer-mode-button').on('click', function(){
		holder.find('.pagelayer-mode-buttons-wrapper').toggle();
	});
	
	holder.find('.pagelayer-history-icon').click(function(){
		pagelayer.$$('.pagelayer-elpd-header').show().find('.pagelayer-elpd-title').text('Pagelayer History');
		pagelayer.$$('.pagelayer-logo').hide();
		pagelayer_leftbar_tab('pagelayer-history');
		pagelayer_active = {};
		pagelayer_history_setup();	
	});
};


///////////////////////////////
// Miscellaneuos Functions
///////////////////////////////

// Generates a random string of "n" characters
function pagelayer_randstr(n, special){
    var text = '';
    var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
	special = special || 0;
	if(special){
		possible = possible + '&#$%@';
	}
	
    for(var i=0; i < n; i++){
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
};

function pagelayer_update_site_title(val){
	if(!pagelayer_empty(val)){
		var	site_name = jQuery('.pagelayer-wp-title').attr('pagelayer-a-site_name');
		if(!pagelayer_empty(site_name)){
			var site_title = jQuery.ajax({
				url: pagelayer_ajax_url+'&action=pagelayer_fetch_site_title',
				type: 'post',
				async: false
			}).responseText;
			
			if(site_title != site_name){
				var site_title = jQuery.ajax({
					url: pagelayer_ajax_url+'&action=pagelayer_update_site_title',
					type: 'post',
					data: {'site_title': site_name},
					async: false
				}).responseText;
			}
		}
	}
}

function pagelayer_trim(str, charlist){
	//  discuss at: http://locutus.io/php/trim/
	
	if(typeof str != 'string'){
		return str;
	}
	
	var whitespace = [' ', '\n', '\r', '\t', '\f', '\x0b', '\xa0', '\u2000', '\u2001', '\u2002', '\u2003', '\u2004', '\u2005', '\u2006', '\u2007', '\u2008', '\u2009', '\u200a', '\u200b', '\u2028', '\u2029', '\u3000' ].join('')
	var l = 0
	var i = 0
	str += ''

	if (charlist) {
		whitespace = (charlist + '').replace(/([[\]().?/*{}+$^:])/g, '$1')
	}

	l = str.length
	for (i = 0; i < l; i++) {
		if (whitespace.indexOf(str.charAt(i)) === -1) {
			str = str.substring(i)
			break
		}
	}

	l = str.length
	for (i = l - 1; i >= 0; i--) {
		if (whitespace.indexOf(str.charAt(i)) === -1) {
			str = str.substring(0, i + 1)
			break
		}
	}

	return whitespace.indexOf(str.charAt(0)) === -1 ? str : ''
};

// Convert the regular URL of a Video to a Embed URL
function pagelayer_video_url(src){

	var youtubeRegExp = /youtube\.com|youtu\.be/;
	var vimeoRegExp = /vimeo\.com/;
	var match = '';
	var videoId = '';
		
	if (youtubeRegExp.exec(src)) {
		match = 'youtube';
	} else if (vimeoRegExp.exec(src)) {
		match = 'vimeo';
	}
	
	switch(match){
		case 'youtube':
				
			var youtubeRegExp1 = /youtube\.com/;
			var youtubewatch = /watch/;
			var youtubeembed = /embed/;
			var youtube = /youtu\.be/;

			if (youtubeRegExp1.exec(src)) {
				
				if (youtubewatch.exec(src)) {
					 videoId = src.split('?v=');
										
				} else if (youtubewatch.exec(src)) {
					videoId = src.split('embed/');
				}
				
			} else if (youtube.exec(src)) {
				videoId = src.split('.be/');
			}
			
			return '//youtube.com/embed/'+videoId[1];
			
			break;
			
		case 'vimeo':
		
			var vimeoplayer = /player\.vimeo\.com/;
			var vimeovideo = /video/;
			
			if (vimeoplayer.exec(src) && vimeovideo.exec(src)) {
				videoId = src.split('video/');
			} else if (vimeoRegExp.exec(src)) {
				videoId = src.split('.com/');
			}
			
			return '//player.vimeo.com/video/'+videoId[1];
			
			break;
		default:
			return src;
	}
};

// Add widget section
function pagelayer_add_widget(){
	
	html='<div class="pagelayer-add-widget-area">'+
		'<i class="pagelayer-add-row fa fa-plus-circle"></i>'+
		'<p>Click here to add new row OR drag widgets</p>'+
	'</div>';
	
	jQuery(pagelayer_editable).append(html);
	
	var add_area = jQuery('.pagelayer-add-widget-area');
	
	// Add a code before this
	var add_sc = function(tag){
		
		// Create Row		
		var row = jQuery('<div pagelayer-tag="pl_row"></div>');
		add_area.before(row);
		var row_id = pagelayer_onadd(row, false);
		var rEle = pagelayer_ele_by_id(row_id);
		
		if(tag == 'pl_row'){
			rEle.click();
			return row_id;
		}
		
		// Create Column
		var col = jQuery('<div pagelayer-tag="pl_col"></div>');
		rEle.find('.pagelayer-row-holder').append(col);
		var col_id = pagelayer_onadd(col, false);
		var cEle = pagelayer_ele_by_id(col_id);
		
		if(tag == 'pl_col'){
			cEle.click();
			return col_id;
		}
		
		// Create element
		var ele = jQuery('<div pagelayer-tag="'+tag+'"></div>');
		cEle.find('.pagelayer-col-holder').append(ele);
		var id = pagelayer_onadd(ele);
		
		// Ensure the column is not empty
		pagelayer_empty_col(cEle.find('.pagelayer-col-holder'));
		
		return id;
		
	}
	
	// Handle Click
	add_area.on('click', function(e){
		e.stopPropagation();
		add_sc('pl_col');
	});
	
	// Handle Drag over
	add_area.on('dragover', function(e){
		//console.log(e)
		add_area.addClass('pagelayer-add-widget-drag');
	});
	
	// Handle Drag Leave
	add_area.on('dragleave', function(e){
		//console.log(e)
		add_area.removeClass('pagelayer-add-widget-drag');
	});
	
	// Handle On Drop
	add_area.on('drop', function(e){
		
		//console.log(e);
		//console.log(e.originalEvent.dataTransfer.getData('tag'));
		add_area.removeClass('pagelayer-add-widget-drag');
		jQuery('.pagelayer-is-dragging').removeClass('pagelayer-is-dragging');
		
		var tag = e.originalEvent.dataTransfer.getData('tag');
		
		// Is it an existing element ?
		if(tag.length < 1){
			return false;
		}
		
		e.preventDefault();
		
		//console.log(tag);
		
		add_sc(tag);
	});
}
