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