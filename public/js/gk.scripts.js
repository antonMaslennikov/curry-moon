/**
 * jQuery Cookie plugin
 *
 * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.noConflict();
jQuery.cookie = function (key, value, options) {

    // key and at least value given, set cookie...
    if (arguments.length > 1 && String(value) !== "[object Object]") {
        options = jQuery.extend({}, options);

        if (value === null || value === undefined) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        value = String(value);

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};

// IE checker
function gkIsIE() {
  if (navigator.userAgent.match(/msie/i) ){ return true; }
  else { return false; }
}
//
var page_loaded = false;
//
jQuery(window).load( function() {
	setTimeout(function() {
		if(jQuery('#gkTopBar').length > 0) {
			jQuery('#gkTopBar').addClass('active');
		}
	}, 500);
	//
	page_loaded = true;

	if(jQuery(document.body).attr('data-smoothscroll') == '1') {
		// smooth anchor scrolling
		jQuery('a[href*="#"]').on('click', function (e) {
		    e.preventDefault();
		    if(this.hash !== '') {
		        if(this.hash !== '' && this.href.replace(this.hash, '') == window.location.href.replace(window.location.hash, '')) {
		            var target = jQuery(this.hash);
		            if(target.length && this.hash !== '#') {
		                jQuery('html, body').stop().animate({
		                    'scrollTop': target.offset().top
		                }, 1000, 'swing', function () {
		                    if(this.hash !== '#') {
		                        window.location.hash = target.selector;
		                    }
		                });
		            }
		        }
		    }
		});
	}
	// style area
	if(jQuery('#gkStyleArea')){
		jQuery('#gkStyleArea').find('a').each(function(i,element){
			jQuery(element).click(function(e){
	            e.preventDefault();
	            e.stopPropagation();
				changeStyle(i+1);
			});
		});
	}
	
	// font-size switcher
	if(jQuery('#gkTools') && jQuery('#gkMainbody')) {
		var current_fs = 100;
		
		jQuery('#gkMainbody').css('font-size', current_fs+"%");
		
		jQuery('#gkToolsInc').click(function(e){ 
			e.stopPropagation();
			e.preventDefault(); 
			if(current_fs < 150) {  
				jQuery('#gkMainbody').animate({ 'font-size': (current_fs + 10) + "%"}, 200); 
				current_fs += 10; 
			} 
		});
		jQuery('#gkToolsReset').click(function(e){ 
			e.stopPropagation();
			e.preventDefault(); 
			jQuery('#gkMainbody').animate({ 'font-size' : "100%"}, 200); 
			current_fs = 100; 
		});
		jQuery('#gkToolsDec').click(function(e){ 
			e.stopPropagation();
			e.preventDefault(); 
			if(current_fs > 70) { 
				jQuery('#gkMainbody').animate({ 'font-size': (current_fs - 10) + "%"}, 200); 
				current_fs -= 10; 
			} 
		});
	}
	if(jQuery('#system-message-container').length != 0){
		  jQuery('#system-message-container').each(function(i, element){
		  		(function() {
		  		     jQuery(element).fadeOut('slow');
		  		}).delay(3500);
	      });
	} 
	
	// K2 font-size switcher fix
	if(jQuery('#fontIncrease') && jQuery('.itemIntroText')) {
		jQuery('#fontIncrease').click(function() {
			jQuery('.itemIntroText').attr('class', 'itemIntroText largerFontSize');
		});
		
		jQuery('#fontDecrease').click( function() {
			jQuery('.itemIntroText').attr('class', 'itemIntroText smallerFontSize');
		});
	}
	
	// login popup
		if(jQuery('#gkPopupLogin').length > 0 || jQuery('#gkPopupCart').length > 0) {
			var popup_overlay = jQuery('#gkPopupOverlay');
			popup_overlay.css({'display': 'none', 'opacity' : 0});
			popup_overlay.fadeOut();
			
			jQuery('#gkPopupLogin').css({'display': 'block', 'opacity': 0, 'height' : 0});
			var opened_popup = null;
			var popup_login = null;
			var popup_login_h = null;
			var popup_login_fx = null;
			
			if(jQuery('#gkPopupLogin')) {
	
				popup_login = jQuery('#gkPopupLogin');
				popup_login.css('display', 'block');
				popup_login_h = popup_login.find('.gkPopupWrap').outerHeight();
				 
				jQuery('#gkLogin').click( function(e) {
					e.preventDefault();
					e.stopPropagation();
					popup_overlay.css({'opacity' : 0.01});
					popup_overlay.css({'opacity' : 0.45});
					popup_overlay.fadeIn('medium');
					
					setTimeout(function() {
						popup_login.animate({'opacity':1, 'height': popup_login_h},200, 'swing');
						opened_popup = 'login';
						popup_login.addClass('gk3Danim');
					}, 450);
	
					(function() {
						if(jQuery('#modlgn-username').length > 0) {
							jQuery('#modlgn-username').focus();
						}
					}).delay(600);
				});
			}
			
			if(jQuery('#gkPopupCart').length > 0) {
			        var btn = jQuery('.gk-cart');
			       
			     	popup_cart = jQuery('#gkPopupCart');
			        
			        popup_cart_h = popup_cart.find('.gkPopupWrap').outerHeight(); 
			        var wait_for_results = true;
			        var wait = false;
			        
			        btn.click(function(e) {
			                e.preventDefault();
			                e.stopPropagation();
			                popup_overlay.css('height', jQuery('body').outerHeight());
			                       
			                popup_overlay.css({'opacity' : 0.45});
			                popup_overlay.fadeIn('fast');
			                
			                opened_popup = 'cart';
			                
			                if(!wait) {
			                        jQuery.ajax({
			                                url: $GK_URL + 'index.php?tmpl=cart',
			                                beforeSend: function() {
			                                        btn.addClass('loading');
			                                        wait = true;
			                                },
			                                complete: function() {
			                                        var timer = (function() {
			                                                if(!wait_for_results) {
			                                                        wait_for_results = true;
			                                                        wait = false;
			                                                        clearInterval(timer);
			                                                }
			                                        });
			                                },
			                                success: function(data) {
			                                        document.getElementById('gkAjaxCart').innerHTML = data;
			                                        popup_cart.css('display', 'block');
			                                        popup_cart.css('display', 'block');
			                                        				                                                      
			                                        popup_cart.animate({'opacity':1, 'margin-top':0},200, 'swing');
			                                        popup_cart.addClass('gk3Danim');
			                                        btn.removeClass('loading');
			                                        //popup_cart.css('opacity', 0).css('margin-top', '-50px');
			                                        wait_for_results = false;
			                                        wait = false;
			                                }
			                        });
			                }
			        });
			                        
                    if(btn) {
                    	var counter = jQuery('<i id="gkCartCounter"></i>');
                    	counter.appendTo(btn);
    	                var gkCartDataRequest = function() {
    	                    jQuery.ajax({
    	                        url: $GK_URL + 'index.php?tmpl=json',
    	                        success: function(data) {
    	                            document.getElementById('gkCartCounter').innerHTML = '(' + data + ')';     
    	                        }
    	                    });    
    	                } 
    	                gkCartDataRequest();
    					
    					if(typeof Virtuemart !== 'undefined') {
    		                Virtuemart.sendtocart = function (form) {
    		                    if (Virtuemart.addtocart_popup ==1) {
    		                        Virtuemart.cartEffect(form);
    		                        setTimeout(function() {
    		                            gkCartDataRequest();
    		                        }, 1000);
    		                    } else {
    		                        form.append('<input type="hidden" name="task" value="add" />');
    		                        form.submit();
    		                    }
    		                };
    	                }
                    }
			}
			
			
			popup_overlay.click( function() {
				if(opened_popup == 'login')	{
					popup_overlay.fadeOut('medium');
					popup_overlay.css('opacity', 0.01);
					popup_login.removeClass('gk3Danim');
					setTimeout(function() {
						popup_login.animate({
							'opacity' : 0
						},350, 'swing');
					}, 100);
					
				}
				if(opened_popup == 'cart') {
				    popup_overlay.fadeOut('medium');
				    popup_overlay.css('opacity', 0.01);
				    popup_cart.removeClass('gk3Danim');
				    setTimeout(function() {
				    	popup_cart.animate({
				    		'opacity' : 0
				    	},350, 'swing');
				    }, 100);
				}  
			});
		}	
});
// Function to change styles
function changeStyle(style){
	var file1 = $GK_TMPL_URL+'/css/style'+style+'.css';
	var file2 = $GK_TMPL_URL+'/css/typography/typography.style'+style+'.css';
	jQuery('head').append('<link rel="stylesheet" href="'+file1+'" type="text/css" />');
	jQuery('head').append('<link rel="stylesheet" href="'+file2+'" type="text/css" />');
	jQuery.cookie('gk_instyle_j30_style', style, { expires: 365, path: '/' });
}