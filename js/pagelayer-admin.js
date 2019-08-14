// Lets start
jQuery(document).ready(function(){
	
	var pl_admin_tabs = function(){
		jQuery('.nav-tab-wrapper a').click(function(){

			var tEle = jQuery(this);
			
			// Limit effect to the container element.
			var context = tEle.closest('.nav-tab-wrapper ').parent();
			context.find('.nav-tab-wrapper a').removeClass('nav-tab-active');
			tEle.addClass('nav-tab-active');
			context.find('.pagelayer-tab-panel').hide();
			context.find(tEle.attr('href')).show();
			
		});

		// Make setting nav-tab-active optional.
		jQuery('.nav-tab-wrapper.pagelayer-wrapper').each(function(){
					
			var jEle = jQuery(this);
			var hash = location.hash.slice(1);
			
			if(hash){
				var active_tab_ele = jEle.find('[href="#'+hash+'"]');
				if (active_tab_ele.length > 0){
					active_tab_ele.click();
				}
			}else{
				jEle.find('a').first().click();
			}
			
		});
	}

	var pl_admin_accordion = function(){
		
		jQuery('.pagelayer-acc-wrapper .pagelayer-acc-tab').click(function(){

			var tEle = jQuery(this);
			
			if(tEle.hasClass('nav-tab-active')){
				tEle.toggleClass('nav-tab-active').next('.pagelayer-acc-panel').toggle();
			}else{
				// Limit effect to the container element.
				var context = tEle.closest('.pagelayer-acc-wrapper ');
				context.find('.pagelayer-acc-tab').removeClass('nav-tab-active');
				context.find('.pagelayer-acc-panel').hide();
				tEle.addClass('nav-tab-active');
				tEle.next('.pagelayer-acc-panel').show();
			}
		});
		
		// Make setting nav-tab-active optional.
		jQuery('.pagelayer-acc-wrapper').each(function(){
					
			var jEle = jQuery(this);
			
			var active_acc_ele = jEle.find('nav-tab-active');
			if (active_acc_ele.length > 0){
				active_acc_ele.click();
			}else{
				jEle.find('.pagelayer-acc-tab').first().click();
			}
			
		});
	}
	
	pl_admin_tabs();
	pl_admin_accordion();
	
});