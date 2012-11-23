$(function() {
		   

/* ----- CONTENT ----- */

	// Accordion
	$('.accordionTitle').click(function() {
		$('.accordionTitle').removeClass('a-open');
		$('.accordionContent').slideUp(200);
		if($(this).next().is(':hidden') == true) {
			$(this).addClass('a-open');
			$(this).next().slideDown(200);
		}
	});
	$('.accordionContent').hide();
	
	// Tabs
	$('.panes div').hide();
	$(".tabs a:first").addClass("selected");
	$(".tabs_table").each(function(){
		$(this).find('.panes div:first').show();
		$(this).find('a:first').addClass("selected");
	});
	$('.tabs a').click(function(){
		var which = $(this).attr("rel");
		$(this).parents(".tabs_table").find(".selected").removeClass("selected");
		$(this).addClass("selected");
		$(this).parents(".tabs_table").find(".panes").find("div").hide();
		$(this).parents(".tabs_table").find(".panes").find("#"+which).fadeIn(800);
	});


/* ----- MAIN NAVIGATION ----- */
	
	$("#main-nav ul li").each(function(){
		if( $("ul", this).size() ) $(this).addClass("dd");
	});


	
/* ----- SLIDER ----- */
	
	$('#big-slider').nivoSlider({
		effect: 'random', // Specify sets like: 'fold,fade,sliceDown'
        slices: 9, // For slice animations
        boxCols: 3, // For box animations
        boxRows: 3, // For box animations
        animSpeed: 500, // Slide transition speed
        pauseTime: 6000, // How long each slide will show
        startSlide: 0, // Set starting Slide (0 index)
        directionNav: false, // Next & Prev navigation
        directionNavHide: true, // Only show on hover
        controlNav: true, // 1,2,3... navigation
        controlNavThumbs: false, // Use thumbnails for Control Nav
        controlNavThumbsFromRel: false, // Use image rel for thumbs
        controlNavThumbsSearch: '.jpg', // Replace this with...
        controlNavThumbsReplace: '_thumb.jpg', // ...this in thumb Image src
        keyboardNav: true, // Use left & right arrows
        pauseOnHover: true, // Stop animation while hovering
        manualAdvance: false, // Force manual transitions
        captionOpacity: 1, // Universal caption opacity
        prevText: '', // Prev directionNav text
        nextText: '', // Next directionNav text
        randomStart: false, // Start on a random slide
        beforeChange: function(){}, // Triggers before a slide transition
        afterChange: function(){
/*				$(".nivo-caption p").style.removeAttribute('filter');*/
				$(".nivo-caption, .nivo-caption p").removeAttr('filter');
			}, // Triggers after a slide transition
        slideshowEnd: function(){}, // Triggers after all slides have been shown
        lastSlide: function(){}, // Triggers when last slide is shown
        afterLoad: function(){} // Triggers when slider has loaded
	});
	
	

/* ----- IMAGES, GALLERIES, prettySociable ----- */

	/* catch alone images for prettyPhoto */
	$("a[href$='.jpg'], a[href$='.png'], a[href$='.gif']").not("#portfolio-media a").attr("rel", "prettyPhoto");

	/* catch gallery images for prettyPhoto */
	$(".gallery").each(function(i) {
		$("a[href$='.jpg'], a[href$='.png'], a[href$='.gif']", this).attr("rel", 'prettyPhoto[pp_gal' + i + ']');
	});
	
	/* prettyPhoto initialization */
	$("a[rel^='prettyPhoto']").prettyPhoto({
		animation_speed: 'fast', /* fast/slow/normal */
		slideshow: 5000, /* false OR interval time in ms */
		autoplay_slideshow: false, /* true/false */
		opacity: 0.80, /* Value between 0 and 1 */
		show_title: true, /* true/false */
		allow_resize: true, /* Resize the photos bigger than viewport. true/false */
		default_width: 500,
		default_height: 344,
		counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
		theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
		horizontal_padding: 20, /* The padding on each side of the picture */
		hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettyPhoto */
		wmode: 'opaque', /* Set the flash wmode attribute */
		autoplay: true, /* Automatically start videos: True/False */
		modal: false, /* If set to true, only the close button will close the window */
		deeplinking: true, /* Allow prettyPhoto to update the url to enable deeplinking. */
		overlay_gallery: true, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
		keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
		changepicturecallback: function(){}, /* Called everytime an item is shown/changed */
		callback: function(){}, /* Called when prettyPhoto is closed */
		ie6_fallback: true,
		social_tools: false
	});	
	
	$("#portfolio-media a").click(function(event){
		event.preventDefault();
		
		$("#portfolio-media li.act").removeClass("act");
		$(this).parent("li").addClass("act");
		
		$("#full-size img").attr("src", $(this).attr("href"));
	});
	
	$.prettySociable({
		animationSpeed: 'fast', /* fast/slow/normal */
		opacity: 0.85, /* Value between 0 and 1 */
		share_label: '', /* Text displayed when a user rollover an item */
		share_on_label: 'Share on ', /* Text displayed when a user rollover a website to share */
		hideflash: false, /* Hides all the flash object on a page, set to TRUE if flash appears over prettySociable */
		hover_padding: 0,
		websites: {
			facebook : {
				'active': true,
				'encode':true, // If sharing is not working, try to turn to false
				'title': 'Facebook',
				'url': 'http://www.facebook.com/share.php?u=',
				'icon':'/survey/res/img/prettySociable/large_icons/facebook.png',
				'sizes':{'width':70,'height':70}
			},
			twitter : {
				'active': true,
				'encode':true, // If sharing is not working, try to turn to false
				'title': 'Twitter',
				'url': 'http://twitter.com/home?status=',
				'icon':'/survey/res/img/prettySociable/large_icons/twitter.png',
				'sizes':{'width':70,'height':70}
			},
			delicious : {
				'active': true,
				'encode':true, // If sharing is not working, try to turn to false
				'title': 'Delicious',
				'url': 'http://del.icio.us/post?url=',
				'icon':'/survey/res/img/prettySociable/large_icons/delicious.png',
				'sizes':{'width':70,'height':70}
			},
			digg : {
				'active': true,
				'encode':true, // If sharing is not working, try to turn to false
				'title': 'Digg',
				'url': 'http://digg.com/submit?phase=2&url=',
				'icon':'/survey/res/img/prettySociable/large_icons/digg.png',
				'sizes':{'width':70,'height':70}
			},
			linkedin : {
				'active': true,
				'encode':true, // If sharing is not working, try to turn to false
				'title': 'LinkedIn',
				'url': 'http://www.linkedin.com/shareArticle?mini=true&ro=true&url=',
				'icon':'/survey/res/img/prettySociable/large_icons/linkedin.png',
				'sizes':{'width':70,'height':70}
			},
			reddit : {
				'active': true,
				'encode':true, // If sharing is not working, try to turn to false
				'title': 'Reddit',
				'url': 'http://reddit.com/submit?url=',
				'icon':'/survey/res/img/prettySociable/large_icons/reddit.png',
				'sizes':{'width':70,'height':70}
			},
			stumbleupon : {
				'active': true,
				'encode':false, // If sharing is not working, try to turn to false
				'title': 'StumbleUpon',
				'url': 'http://stumbleupon.com/submit?url=',
				'icon':'/survey/res/img/prettySociable/large_icons/stumbleupon.png',
				'sizes':{'width':70,'height':70}
			},
			tumblr : {
				'active': true,
				'encode':true, // If sharing is not working, try to turn to false
				'title': 'tumblr',
				'url': 'http://www.tumblr.com/share?v=3&u=',
				'icon':'/survey/res/img/prettySociable/large_icons/tumblr.png',
				'sizes':{'width':70,'height':70}
			}
		},
		urlshortener : {
			/*
				To get started you'll need a free bit.ly user account and API key - sign up at:

http://bit.ly/account/register?rd=/

				Quickly access your private API key once you are signed in at:

http://bit.ly/account/your_api_key

			*/
			bitly : {
				'active' : false
			}
		},
		tooltip: {
			offsetTop:0,
			offsetLeft: 15
		},
		popup: {
			width: 900,
			height: 500
		},
		callback: function(){} /* Called when prettySociable is closed */
	});



/* ----- FORMS ----- */

	/* add/remove .focus class on submit button */
	$("#subscribe input[type='text']").focus(function(){
		$(this).next("input[type='submit']").addClass("focused");
	}).blur(function(){
		$(this).next("input[type='submit']").removeClass("focused");		
	});

	// set default input value
	$('input[alt]').each(function(){
		$(this).val( $(this).attr("alt") ).addClass("input-off");
	});

	// clear input value
	$('input[alt]').focus(function () {
		if ($(this).val() == $(this).attr("alt")) {
			$(this).val("").removeClass("input-off");
		}
	}).blur(function () {
		if ($(this).val() == "") {
			$(this).val($(this).attr("alt")).addClass("input-off");
		}
	});

	/* validate contact form */
	$("#contact, #commentform").validate();
	
	
	
/* ----- MAIN MENU ----- */

	$('#main-nav > ul').superfish({
		delay:       0,                // one second delay on mouseout 
		animation:   {height:'show'},  // fade-in and slide-down animation 
		speed:       'fast',           // faster animation speed 
		autoArrows:  false,            // disable generation of arrow mark-up 
		dropShadows: false    	
	});
	
	
	
/* ----- PORTFOLIO ----- */

	$("#p-filter a").click(function(){
		$("#p-filter a.act").removeClass("act");
		$(this).addClass("act");

		p_filter = $(this).attr("href").substr(1);

		$(".p-item").each(function(){
			p_property = $(this).attr("rel");
			if(p_property == p_filter && p_filter != "all") {
				$(this).fadeTo(300, 1);
				$("img", this).fadeTo(300, 1);
				$(this).removeClass("item-off");
			} else if(p_filter != "all") {
				$(this).fadeTo(300, 0.3);
				$("img", this).fadeTo(300, 0.2);
				$(this).addClass("item-off");
			} else {
				$(this).fadeTo(300, 1);
				$("img", this).fadeTo(300, 1);
				$(this).removeClass("item-off");
			}
		});
	});
	
	

/* ----- HOVER/OPACITY EFFECT & OTHER ----- */

	$("a img").not("#logo img, .p-item a img").hover(function(){
		$(this).fadeTo(300, 0.85);
	}, function(){
		$(this).fadeTo(300, 1);
	});
		
});