// Easing equation, borrowed from jQuery easing plugin
// http://gsgd.co.uk/sandbox/jquery/easing/
jQuery.easing.easeOutQuart = function (x, t, b, c, d) {
	return -c * ((t=t/d-1)*t*t*t - 1) + b;
};

/*jQuery(function( $ ){
	$('#screen').serialScroll({
		target:'#sections',
		items:'li.slidepost', // Selector to the items ( relative to the matched elements, '#sections' in this case )
		axis:'x',// The default is 'y' scroll on both ways
		easing:'easeOutQuart',
		duration:1000,// Length of the animation (if you scroll 2 axes and use queue, then each axis take half this time)
		force:true, // Force a scroll to the element specified by 'start' (some browsers don't reset on refreshes)		
		interval:10000, // It's the number of milliseconds to automatically go to the next
		step:1, // How many items to scroll each time ( 1 is the default, no need to specify )		
		onBefore:function( e, elem, $pane, $items, pos ){
			e.preventDefault();
			if ( this.blur )
				this.blur();
		},
		onAfter:function( elem ){
		}
	});	
});*/