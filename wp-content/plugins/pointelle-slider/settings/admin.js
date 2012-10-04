jQuery(document).ready(function() {
	 jQuery("#slider_tabs").tabs({ cookie: { expires: 30 } }); 
	//getter
	var cookie = jQuery("#slider_tabs").tabs( "option", "cookie" );
	//setter
	jQuery("#slider_tabs").tabs( "option", "cookie", { expires: 30 } );
	
		jQuery('#colorbox_1').farbtastic('#color_value_1');
		jQuery('#color_picker_1').click(function () {
           if (jQuery('#colorbox_1').css('display') == "block") {
		      jQuery('#colorbox_1').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_1').fadeIn("slow"); }
        });
		var colorpick_1 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_1 == true) {
    			return; }
				jQuery('#colorbox_1').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_1 = false;
		});
//for second color box
		jQuery('#colorbox_2').farbtastic('#color_value_2');
		jQuery('#color_picker_2').click(function () {
           if (jQuery('#colorbox_2').css('display') == "block") {
		      jQuery('#colorbox_2').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_2').fadeIn("slow"); }
        });
		var colorpick_2 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_2 == true) {
    			return; }
				jQuery('#colorbox_2').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_2 = false;
		});
//for third color box
		jQuery('#colorbox_3').farbtastic('#color_value_3');
		jQuery('#color_picker_3').click(function () {
           if (jQuery('#colorbox_3').css('display') == "block") {
		      jQuery('#colorbox_3').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_3').fadeIn("slow"); }
        });
		var colorpick_3 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_3 == true) {
    			return; }
				jQuery('#colorbox_3').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_3 = false;
		});
//for fourth color box
		jQuery('#colorbox_4').farbtastic('#color_value_4');
		jQuery('#color_picker_4').click(function () {
           if (jQuery('#colorbox_4').css('display') == "block") {
		      jQuery('#colorbox_4').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_4').fadeIn("slow"); }
        });
		var colorpick_4 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_4 == true) {
    			return; }
				jQuery('#colorbox_4').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_4 = false;
		});
//for fifth color box
		jQuery('#colorbox_5').farbtastic('#color_value_5');
		jQuery('#color_picker_5').click(function () {
           if (jQuery('#colorbox_5').css('display') == "block") {
		      jQuery('#colorbox_5').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_5').fadeIn("slow"); }
        });
		var colorpick_5 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_5 == true) {
    			return; }
				jQuery('#colorbox_5').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_5 = false;
		});
//for sixth color box
		jQuery('#colorbox_6').farbtastic('#color_value_6');
		jQuery('#color_picker_6').click(function () {
           if (jQuery('#colorbox_6').css('display') == "block") {
		      jQuery('#colorbox_6').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_6').fadeIn("slow"); }
        });
		var colorpick_6 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_6 == true) {
    			return; }
				jQuery('#colorbox_6').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_6 = false;
		});
//for seventh color box
		jQuery('#colorbox_7').farbtastic('#color_value_7');
		jQuery('#color_picker_7').click(function () {
           if (jQuery('#colorbox_7').css('display') == "block") {
		      jQuery('#colorbox_7').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_7').fadeIn("slow"); }
        });
		var colorpick_7 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_7 == true) {
    			return; }
				jQuery('#colorbox_7').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_7 = false;
		});
//for eighth color box
		jQuery('#colorbox_8').farbtastic('#color_value_8');
		jQuery('#color_picker_8').click(function () {
           if (jQuery('#colorbox_8').css('display') == "block") {
		      jQuery('#colorbox_8').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_8').fadeIn("slow"); }
        });
		var colorpick_8 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_8 == true) {
    			return; }
				jQuery('#colorbox_8').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_8 = false;
		});
//for ninth color box
		jQuery('#colorbox_9').farbtastic('#color_value_9');
		jQuery('#color_picker_9').click(function () {
           if (jQuery('#colorbox_9').css('display') == "block") {
		      jQuery('#colorbox_9').fadeOut("slow"); }
		   else {
		      jQuery('#colorbox_9').fadeIn("slow"); }
        });
		var colorpick_9 = false;
		jQuery(document).mousedown(function(){
		    if (colorpick_9 == true) {
    			return; }
				jQuery('#colorbox_9').fadeOut("slow");
		});
		jQuery(document).mouseup(function(){
		    colorpick_9 = false;
		});
});
function confirmSettingsCreate()
        {
            var agree=confirm("Create New Settings Set??");
            if (agree)
            return true ;
            else
            return false ;
}
function confirmSettingsDelete()
        {
            var agree=confirm("Delete this Settings Set??");
            if (agree)
            return true ;
            else
            return false ;
}
