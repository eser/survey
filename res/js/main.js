$(function() {

/* ----- CONTENT ----- */

	// Accordion
	$('.accordionToggle').click(function() {
		$('.accordionTitle').removeClass('a-open');
		$('.accordionContent').slideUp(200);
		if($(this).next().is(':hidden') == true) {
			$(this).addClass('a-open');
			$(this).next().slideDown(200);
		}
	});
	$('.hidden').hide();
	
	// Tabs
	$('.panes div').hide();
	if($('.tabs .selected').length <= 0) {
		$('.tabs a:first').addClass('selected');
	}
	$('.tabs_table').each(function(){
		var selectedElement = $(this).find('.selected');
		if(selectedElement.length > 0) {
			$('#' + selectedElement.attr('rel')).show();
		}
		else {
			$(this).find('.panes div:first').show();
			$(this).find('a:first').addClass('selected');
		}
	});
	$('.tabs a').click(function(){
		var which = $(this).attr('rel');
		$(this).parents('.tabs_table').find('.selected').removeClass('selected');
		$(this).addClass('selected');
		$(this).parents('.tabs_table').find('.panes').find('div').hide();
		$(this).parents('.tabs_table').find('.panes').find('#' + which).fadeIn(800);
	});


/* ----- MAIN NAVIGATION ----- */
	
	$('#main-nav ul li').each(function(){
		if( $('ul', this).size() ) $(this).addClass('dd');
	});


	
/* ----- FORMS ----- */

	/* add/remove .focus class on submit button */
	$('#subscribe input[type=text]').focus(function(){
		$(this).next('input[type=submit]').addClass('focused');
	}).blur(function(){
		$(this).next('input[type=submit]').removeClass('focused');
	});

	// set default input value
	$('input[alt]').each(function(){
		$(this).val( $(this).attr('alt') ).addClass('input-off');
	});

	// clear input value
	$('input[alt]').focus(function () {
		if ($(this).val() == $(this).attr('alt')) {
			$(this).val('').removeClass('input-off');
		}
	}).blur(function () {
		if ($(this).val() == '') {
			$(this).val($(this).attr('alt')).addClass('input-off');
		}
	});

	/* validate contact form */
	$('#contact, #commentform').validate();
	
	
	
/* ----- MAIN MENU ----- */

	$('#main-nav > ul').superfish({
		delay:       0,                // one second delay on mouseout 
		animation:   {height:'show'},  // fade-in and slide-down animation 
		speed:       'fast',           // faster animation speed 
		autoArrows:  false,            // disable generation of arrow mark-up 
		dropShadows: false    	
	});
	
/* ----- NIVO SLIDER ----- */

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

/* ----- PORTFOLIO ----- */

	$('#p-filter a').click(function(){
		$('#p-filter a.act').removeClass('act');
		$(this).addClass('act');

		p_filter = $(this).attr('href').substr(1);

		$('.p-item').each(function(){
			p_property = $(this).attr('rel');
			if(p_property == p_filter && p_filter != 'all') {
				$(this).fadeTo(300, 1);
				$('img', this).fadeTo(300, 1);
				$(this).removeClass('item-off');
			} else if(p_filter != 'all') {
				$(this).fadeTo(300, 0.3);
				$('img', this).fadeTo(300, 0.2);
				$(this).addClass('item-off');
			} else {
				$(this).fadeTo(300, 1);
				$('img', this).fadeTo(300, 1);
				$(this).removeClass('item-off');
			}
		});
	});
	
	

/* ----- HOVER/OPACITY EFFECT & OTHER ----- */

	$('a img').not('#logo img, .p-item a img').hover(function(){
		$(this).fadeTo(300, 0.85);
	}, function(){
		$(this).fadeTo(300, 1);
	});

});

$l.ready(function() {
	var loginform = $l.dom.selectSingle('#loginform');

	if(loginform != null) {
		$l.forms.ajaxForm(
			loginform,
			function(data) {
				if(data['user']) {
					$l.ui.msgbox(5, 'logged in, redirecting...');
					location.reload(true);
					return;
				}

				$l.ui.msgbox(5, 'nothing...');
			}
		);

		$l.dom.setEvent(
			$l.dom.selectSingle('#fblogin'),
			'click',
			function() {
				window.location = $l.baseLocation + '/user/fblogin';
				return false;
			}
		);
	}

	var userlogout = $l.dom.selectSingle('#userlogout');
	if(userlogout != null) {
		$l.dom.setEvent(
			userlogout,
			'click',
			function() {
				if(!confirm('Are you sure to log out?')) {
					return false;
				}

				window.location = $l.baseLocation + '/user/login';
				return false;
			}
		);
	}
});