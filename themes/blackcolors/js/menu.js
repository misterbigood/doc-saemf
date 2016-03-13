/* Responsive Layout - Antonio Sanchez - http://antsanchez.com */

/* Makes the side bar fixed if the window has enough height */
function side_menu(){
	var alt_body = jQuery("body").height();
	var alt_window = jQuery(window).height();
	var alt_menu = jQuery("#barra-lateral").height();
	var anch_window = jQuery(window).width();
	if (anch_window > 768) {
		if ((alt_menu + 250) >= alt_window) {
			jQuery("#barra-lateral").css("position", "absolute");
			if (alt_body > alt_menu) {
				jQuery("#barra-lateral").height(alt_body);
			}else{
				jQuery("#barra-lateral").height(alt_menu);
			}
		}else{
			jQuery("#barra-lateral").css({position: "fixed", bottom: "0", height: "auto"});
		}
	}else{
		jQuery("#barra-lateral").css({position: "static", bottom: "auto", height: "auto"});
	}
}

/* Menu Elements */
function menu(){
	jQuery('#open-menu').click(function(a){
	    var variable = jQuery('#menu-principal').css("display");
	    if (variable == "block") {
		jQuery('#menu-principal').slideUp(500);
	    }else{
		jQuery('#menu-principal').slideDown(500);
	    } 
	})
    if(!!('ontouchstart' in window)){
	jQuery('#menu-principal .menu li:has(ul)').click(function(e) {
	    var variable = jQuery(this).find('ul').css("display");
	    if (variable == "block") {
			jQuery(this).children('ul').slideUp(1000);
	    }else{
		e.preventDefault();
			jQuery(this).children('ul').slideDown(500);
	    }
	});
    }else{
	jQuery('#menu-principal .menu li:has(ul)').hover(function(e) {
	    var variable = jQuery(this).find('ul').css("display");
	    if (variable == "block") {
			jQuery(this).children('ul').slideUp(1000);
	    }else{
		e.preventDefault();
			jQuery(this).children('ul').slideDown(500);
			jQuery(this).find('ul').css({zIndex: 99});
			jQuery(this).children('ul').css({zIndex: 100});
	    }
	});
	}
}

/* Up Button */
function up_buttom(){
    var offset = jQuery("#up_buttom").offset();
    var ventana = jQuery(window).height();
    jQuery(window).scroll(function(){
	var posicion = jQuery(window).scrollTop();
	if (posicion < 200) {
	    jQuery("#up_buttom").css("visibility", "hidden");
	}else{
	    jQuery("#up_buttom").css("visibility", "visible");
	}
	
    })
    jQuery("#up_buttom a").click(function(e){
	e.preventDefault();
	jQuery('html,body').animate({
	    scrollTop: jQuery('body').offset().top
	}, 500);
    })
}

jQuery(document).ready(function(){
	side_menu();
	menu();
	up_buttom();
	
	// Masonry
	var container = document.querySelector('#masonry-container');
	if (container) {
		imagesLoaded( container, function() {
			var msnry = new Masonry( container, {
			  // options
			  itemSelector: 'article'
			});
		});
	}
});

jQuery(window).resize(function(){
    var ancho = jQuery(window).width();
    var altura = jQuery(window).height();
    if (ancho >= 768 ) {
		jQuery('#menu-principal').css({display: "block"});
	}else{
		jQuery('#menu-principal').css({display: "none"});
	}
	side_menu();
});