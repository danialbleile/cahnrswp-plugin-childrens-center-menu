if (typeof jQuery != 'undefined') {
	jQuery('#ccmenu').on('click', '#ccmenu-tabs a' , function( event ){
		event.preventDefault();
		var c = jQuery( this );
		var index = c.index();
		c.addClass('active').siblings().removeClass('active');
		jQuery('#ccmenu .ccmenu-set').eq( index ).addClass('active').siblings().removeClass('active');
	});
}