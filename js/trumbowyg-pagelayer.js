(function ($) {
    'use strict';
  // jshint camelcase:true


    function hex(x) {
        return ('0' + parseInt(x).toString(16)).slice(-2);
    }

    function colorToHex(rgb) {
        if (rgb.search('rgb') === -1) {
            return rgb.replace('#', '');
        } else if (rgb === 'rgba(0, 0, 0, 0)') {
            return 'transparent';
        } else {
            rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
            return hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
        }
    }

    function colorTagHandler(element, trumbowyg) {
        var tags = [];

        if (!element.style) {
            return tags;
        }

        // background color
        if (element.style.backgroundColor !== '') {
            var backColor = colorToHex(element.style.backgroundColor);
            if (trumbowyg.o.plugins.colors.colorList.indexOf(backColor) >= 0) {
                tags.push('backColor' + backColor);
            } else {
                tags.push('backColorFree');
            }
        }

        // text color
        var foreColor;
        if (element.style.color !== '') {
            foreColor = colorToHex(element.style.color);
        } else if (element.hasAttribute('color')) {
            foreColor = colorToHex(element.getAttribute('color'));
        }
        if (foreColor) {
            if (trumbowyg.o.plugins.colors.colorList.indexOf(foreColor) >= 0) {
                tags.push('foreColor' + foreColor);
            } else {
                tags.push('foreColorFree');
            }
        }

        return tags;
    }

    var defaultOptions = {
        colorList: ['ffffff', '000000', 'eeece1', '1f497d', '4f81bd', 'c0504d', '9bbb59', '8064a2', '4bacc6', 'f79646', 'ffff00', 'f2f2f2', '7f7f7f', 'ddd9c3', 'c6d9f0', 'dbe5f1', 'f2dcdb', 'ebf1dd', 'e5e0ec', 'dbeef3', 'fdeada', 'fff2ca', 'd8d8d8', '595959', 'c4bd97', '8db3e2', 'b8cce4', 'e5b9b7', 'd7e3bc', 'ccc1d9', 'b7dde8', 'fbd5b5', 'ffe694', 'bfbfbf', '3f3f3f', '938953', '548dd4', '95b3d7', 'd99694', 'c3d69b', 'b2a2c7', 'b7dde8', 'fac08f', 'f2c314', 'a5a5a5', '262626', '494429', '17365d', '366092', '953734', '76923c', '5f497a', '92cddc', 'e36c09', 'c09100', '7f7f7f', '0c0c0c', '1d1b10', '0f243e', '244061', '632423', '4f6128', '3f3151', '31859b', '974806', '7f6000']
    };
 
    // If WP media is a button
    function openwpmediaDef(trumbowyg) {
        return {
            fn: function() {
                // WP media button logic
				
				var func_media = window['pagelayer_select_frame'];
				
				if(typeof func_media == 'function'){
										
					// Load the frame
					var frame = pagelayer_select_frame('image');
					
					// On select update the stuff
					frame.on({'select': function(){
							var state = frame.state();
							var url = '', alt = '', id = '';
							
							// External URL
							if('props' in state){
								
								url = state.props.attributes.url;
								alt = state.props.attributes.alt;
							
							// Internal from gallery
							}else{
							
								var attachment = frame.state().get('selection').first().toJSON();
								//console.log(attachment);
								
								// Set the new and URL
								url = attachment.url;
								alt = attachment.alt;
								id = attachment.id;
								
							}

							trumbowyg.execCmd('insertImage', url, false, true);
							var $img = $('img[src="' + url + '"]:not([alt])', trumbowyg.$box);
							
							$img.attr('alt', alt);
							$img.attr('pl-media-id', id);
							
							trumbowyg.syncCode;
							trumbowyg.$c.trigger('tbwchange');

							return true;
						}
					});
					
					frame.open();
					
				}
				
            },
			ico: 'insert-image'
			
        }
    }

    $.extend(true, $.trumbowyg, {
        // Add some translations
        langs: {
            en: {
                wpmedia: 'Insert Image',
				foreColor: 'Text color',
                backColor: 'Background color'
            }
        },
        // Add our plugin to Trumbowyg registred plugins
        plugins: {
            wpmedia: {
                init: function(trumbowyg) {
					var t = $(this);
                    // Fill current Trumbowyg instance with WP media default options
                    trumbowyg.o.plugins.wpmedia = $.extend(true, {},
                        defaultOptions,
                        trumbowyg.o.plugins.wpmedia || {}
                    );
					
                    // If WP media is a 
                    trumbowyg.addBtnDef('wpmedia', openwpmediaDef(trumbowyg));
					
                },
            },
			color: {
                init: function (trumbowyg) {
                    trumbowyg.o.plugins.colors = trumbowyg.o.plugins.colors || defaultOptions;
                    var foreColorBtnDef = {
                            dropdown: buildDropdown('foreColor', trumbowyg)
                        },
                        backColorBtnDef = {
                            dropdown: buildDropdown('backColor', trumbowyg)
                        };

                    trumbowyg.addBtnDef('foreColor', foreColorBtnDef);
                    trumbowyg.addBtnDef('backColor', backColorBtnDef);
                },
                tagHandler: colorTagHandler
            }
        }
    });

    function buildDropdown(fn, trumbowyg) {
        var dropdown = [];

        $.each(trumbowyg.o.plugins.colors.colorList, function (i, color) {
            var btn = fn + color,
                btnDef = {
                    fn: fn,
                    forceCss: true,
                    param: '#' + color,
                    style: 'background-color: #' + color + ';'
                };
            trumbowyg.addBtnDef(btn, btnDef);
            dropdown.push(btn);
        });

        var removeColorButtonName = fn + 'Remove',
            removeColorBtnDef = {
                fn: 'removeFormat',
                param: fn,
                style: 'background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAQAAAAECAYAAACp8Z5+AAAAG0lEQVQIW2NkQAAfEJMRmwBYhoGBYQtMBYoAADziAp0jtJTgAAAAAElFTkSuQmCC);'
            };
        trumbowyg.addBtnDef(removeColorButtonName, removeColorBtnDef);
        dropdown.push(removeColorButtonName);

        // add free color btn
        var freeColorButtonName = fn + 'Free',
            freeColorBtnDef = {
                fn: function () {
                    trumbowyg.openModalInsert(trumbowyg.lang[fn],
                        {
                            color: {
                                label: fn,
                                forceCss: true,
                                type: 'color',
                                value: '#FFFFFF'
                            }
                        },
                        // callback
                        function (values) {
                            trumbowyg.execCmd(fn, values.color);
                            return true;
                        }
                    );
                },
                text: '#',
                // style adjust for displaying the text
                style: 'text-indent: 0;line-height: 20px;padding: 0 5px;'
            };
        trumbowyg.addBtnDef(freeColorButtonName, freeColorBtnDef);
        dropdown.push(freeColorButtonName);

        return dropdown;
    }
})(jQuery);
 
 