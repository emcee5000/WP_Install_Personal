<?php
function return_global_pointelle_slider($slider_handle,$r_array,$pointelle_slider_curr,$set){
	global $pointelle_slider; 
	$slider_html='';
	$pointelle_sldr_j = $r_array[0];
	$pointelle_slider_css = pointelle_get_inline_css($set);
	
	if( $pointelle_slider_curr['navpos']=='0' ) $activeclass = 'pointelle-active-lt' ;
	else $activeclass = 'pointelle-active' ;

	$texthoveron='';
	$texthoveroff='';
	$textstatus='';
	if ($pointelle_slider_curr['hovercontent'] == '1') {
	   $texthoveron='jQuery(this).find(".pointelle-excerpt").stop(true,true).slideDown(400);';
	   $texthoveroff='jQuery(this).find(".pointelle-excerpt").stop(true,true).slideUp(550);';
	   $textstatus='jQuery(".pointelle-excerpt").hide();';
	}
	
	//Transition - on hover or on click
	if( $pointelle_slider_curr['onhover'] == '1' ) {
		$transitionon='$pointelle_item.hover( function() { pause_scroll= true;pointelle_gonext(jQuery(this)); return false;}, 
							   function() { pause_scroll= false;});	';
	}
	else{
		if(! defined('POINTELLE_CONTINUE_ONCLICK')) $stoponclick='clearInterval(interval);';
		else $stoponclick='';
		$transitionon='$pointelle_item.click(function() {'.$stoponclick.'pointelle_gonext(jQuery(this)); return false;	});
				$pointelle_item.hover(function () {	pause_scroll= true;	}, 
								function () { pause_scroll= false;});';
	}
	
	//Autoslide - On or Off
	 if( $pointelle_slider_curr['autoslide'] != '0' ) {
		$autoslide='interval = setInterval(function () {
				var auto_number = $slider_control.find(".'.$activeclass.' span.pointelle-order").html();
				if (auto_number == $pointelle_item.length) auto_number = 0;
				$pointelle_item.eq(auto_number).trigger("pointelle_autonext");
			}, '. ( $pointelle_slider_curr["pause"] * 1000) .');';
	} 
	else{
		$autoslide='';
	}
	
	if($pointelle_slider_curr['disable_nav'] == '1' and $pointelle_slider_curr['autoslide'] != '0' ) $timeout= $pointelle_slider_curr['pause'] * 1000 ;
	else $timeout = 0;
	//Next Prev Arrows 
	$nextprev_arrows='';$js_slide_arrows='';
	if( $pointelle_slider_curr['nextprev'] != '0' ){
		$nextprev_arrows='next:   "#'.$slider_handle.' .pointelle_nav_next", prev:   "#'.$slider_handle.' .pointelle_nav_prev",onPrevNextEvent:pointelle_manual_transition,';
		$js_slide_arrows='var $pointelle_slide_arrow=jQuery("#'.$slider_handle.' .pointelle_slide_arrow");
		$pointelle_wrapper.hover(	function () {$pointelle_slide_arrow.stop(true,true).css("display","block");},
								function () {$pointelle_slide_arrow.stop(true,true).css("display","none");} );
		$pointelle_slide_arrow.hover(	function () {pause_scroll= true;},function () {pause_scroll= false;} );';
	}

	$js_scroll_nav='';$html_scroll_nav='';$js_scroll_advance='';
	if( $pointelle_slider_curr['scroll_nav_posts'] < $pointelle_slider_curr['no_posts'] ){
		wp_enqueue_script( 'jquery.carouFredSel', pointelle_slider_plugin_url( 'js/carouFredSel.js' ),
					array('jquery'), POINTELLE_SLIDER_VER, false); 
		$js_scroll_nav='jQuery("#'.$slider_handle.'  .pointelle-slider-control").carouFredSel({items:'. $pointelle_slider_curr['scroll_nav_posts'].',auto:false,direction:"up",next:{button:"#'. $slider_handle.' .pointelle_nav_down",items:'.$pointelle_slider_curr['scroll_nav_posts'].'}, prev:{button:"#'. $slider_handle.' .pointelle_nav_up",items:'.$pointelle_slider_curr['scroll_nav_posts'].'} }, { wrapper:{classname:"pointelle_nav_wrapper"} }	);
			jQuery("#'.$slider_handle.' .pointelle_nav_arrows").hover( function () {pause_scroll= true;},function () {pause_scroll= false;} );';
		$html_scroll_nav='<div class="pointelle_nav_arrows"><div id="pointelle_nav_down" class="pointelle_nav_down"></div><div id="pointelle_nav_up" class="pointelle_nav_up"></div></div>';
		$js_scroll_advance='jQuery("#'. $slider_handle.'  .pointelle-slider-control").trigger("slideTo", ordernumber - 1);';
	}
	
	if(!isset($pointelle_slider_curr['fouc']) or $pointelle_slider_curr['fouc']=='0' ){
		$fouc='jQuery("html").addClass("pointelle_slider_fouc");
	jQuery(document).ready(function() {   jQuery(".pointelle_slider_fouc #'.$slider_handle.'").css({"display" : "block"}); '.$textstatus.'});';
    }	
	else{
	    $fouc='';
	}	
	
	if($pointelle_slider_curr['disable_nav'] != '1') {
		$pointellenavjs='function pointelle_manual_transition(isNext){
			var manual_number = $slider_control.find(".'.$activeclass.' span.pointelle-order").html();
			if(!isNext) manual_number=manual_number-2;;
			if ( (manual_number == $pointelle_item.length) && isNext ) manual_number = 0;
			if ( (manual_number < 0) && !isNext ) manual_number = $pointelle_item.length-1;
			manual_transition=true;
			$pointelle_item.eq(manual_number).trigger("pointelle_autonext");
		}
		'.$js_scroll_nav.'
		$pointelle_item.find("img").fadeTo("fast", 0.7);$slider_control.find(".'.$activeclass.' img").fadeTo("fast", 1);
		function pointelle_gonext(this_element){
			$slider_control.find(".'.$activeclass.' img").stop(true,true).fadeTo("fast", 0.7);
			$slider_control.find(".'.$activeclass.'").removeClass("'.$activeclass.'");
			this_element.addClass("'.$activeclass.'");
			$slider_control.find(".'.$activeclass.' img").stop(true,true).fadeTo("fast", 1);
			ordernumber = this_element.find("span.pointelle-order").html();
			'.$js_scroll_advance.'
			if(!manual_transition) jQuery("#'.$slider_handle.' .pointelle_slides").cycle(ordernumber - 1);
			manual_transition=false;
		} 
		'.$transitionon.'
		var auto_number;var interval;
		$pointelle_item.bind("pointelle_autonext", function pointelle_autonext(){
			if (!(pause_scroll)  || manual_transition) pointelle_gonext(jQuery(this)); 
			return false;
		});
		'.$autoslide;
	}
	else{
		$pointellenavjs='function pointelle_manual_transition(){}';
	}
	
	$slider_html=$slider_html.'<script type="text/javascript"> '.$fouc.'
	jQuery(document).ready(function(){
		jQuery("#'.$slider_handle.' .pointelle_slides").cycle({	timeout: '. $timeout.', speed: '. ( $pointelle_slider_curr['speed'] * 100).',	fx: "'.$pointelle_slider_curr['transition'].'",'.$nextprev_arrows.' slideExpr: "div.pointelle_slideri" });	var manual_transition=false;
		var $pointelle_wrapper = jQuery("#'.$slider_handle.'");var $pointelle_item = jQuery("#'.$slider_handle.' div.pointelle-slider-nav");var $slider_control = jQuery("#'.$slider_handle.'  .pointelle-slider-control");var $image_container = jQuery("#'.$slider_handle.' .pointelle_slideri");var ordernumber;var pause_scroll = false;$image_container.css("height","'.$pointelle_slider_curr['height'].'px");
		'.$js_slide_arrows.'
		$image_container.hover(	function () {jQuery(this).find("img").stop(true,true).fadeTo("fast", 0.7);pause_scroll= true;'.$texthoveron.'}, 
							 function () {jQuery(this).find("img").stop(true,true).fadeTo("fast", 1);pause_scroll= false;'. $texthoveroff.'});
		'.$pointellenavjs.'
	});	</script> 
		<noscript><p><strong>'. $pointelle_slider['noscript'] .'</strong></p></noscript>
	<div id="'.$slider_handle.'" class="pointelle_slider pointelle_slider_'.$set.'" '.$pointelle_slider_css['pointelle_slider'].'>
			'. $r_array[1].'
			'.$html_scroll_nav.'
	<div class="sldr_clearlt"></div><div class="sldr_clearrt"></div></div>';
	return $slider_html;
}
function return_pointelle_slider($slider_id='',$set='') {
	global $pointelle_slider; 
 	$pointelle_slider_options='pointelle_slider_options'.$set;
    $pointelle_slider_curr=get_option($pointelle_slider_options);
	if(!isset($pointelle_slider_curr) or !is_array($pointelle_slider_curr) or empty($pointelle_slider_curr)){$pointelle_slider_curr=$pointelle_slider;$set='';}
 
	if($pointelle_slider['multiple_sliders'] == '1' and is_singular() and (empty($slider_id) or !isset($slider_id))){
		global $post;
		$post_id = $post->ID;
		$slider_id = get_pointelle_slider_for_the_post($post_id);
	}
	if(empty($slider_id) or !isset($slider_id)){
	  $slider_id = '1';
	}
	$slider_handle='pointelle_slider_'.$slider_id;
	$slider_html='';
	if(!empty($slider_id)){
		$r_array = pointelle_carousel_posts_on_slider($pointelle_slider_curr['no_posts'], $offset=0, $slider_id, $echo = '0', $set); 
		$slider_html=return_global_pointelle_slider($slider_handle,$r_array,$pointelle_slider_curr,$set);
	} //end of not empty slider_id condition
	
	return $slider_html;
}

function pointelle_slider_simple_shortcode($atts) {
	extract(shortcode_atts(array(
		'id' => '',
		'set' => '',
	), $atts));

	return return_pointelle_slider($id,$set);
}
add_shortcode('pointelleslider', 'pointelle_slider_simple_shortcode');

function return_pointelle_slider_category($catg_slug='',$set='') {
	global $pointelle_slider; 
 	$pointelle_slider_options='pointelle_slider_options'.$set;
    $pointelle_slider_curr=get_option($pointelle_slider_options);
	if(!isset($pointelle_slider_curr) or !is_array($pointelle_slider_curr) or empty($pointelle_slider_curr)){$pointelle_slider_curr=$pointelle_slider;$set='';}
    $r_array = pointelle_carousel_posts_on_slider_category($pointelle_slider_curr['no_posts'], $catg_slug, '0', '0', $set); $pointelle_sldr_j = $r_array[0];
	$slider_handle='pointelle_slider_'.$catg_slug;
	//get slider 
	$slider_html=return_global_pointelle_slider($slider_handle,$r_array,$pointelle_slider_curr,$set);
	
	return $slider_html;
}

function pointelle_slider_category_shortcode($atts) {
	extract(shortcode_atts(array(
		'catg_slug' => '',
		'set' => '',
	), $atts));

	return return_pointelle_slider_category($catg_slug,$set,$offset);
}
add_shortcode('pointellecategory', 'pointelle_slider_category_shortcode');

function return_pointelle_slider_recent($set='') {
	global $pointelle_slider; 
 	$pointelle_slider_options='pointelle_slider_options'.$set;
    $pointelle_slider_curr=get_option($pointelle_slider_options);
	if(!isset($pointelle_slider_curr) or !is_array($pointelle_slider_curr) or empty($pointelle_slider_curr)){$pointelle_slider_curr=$pointelle_slider;$set='';}
	$r_array = pointelle_carousel_posts_on_slider_recent($pointelle_slider_curr['no_posts'], '0', '0', $set); 
	$slider_handle='pointelle_slider_recent';
	
	//get slider 
	$slider_html=return_global_pointelle_slider($slider_handle,$r_array,$pointelle_slider_curr,$set);
	
	return $slider_html;
}

function pointelle_slider_recent_shortcode($atts) {
	extract(shortcode_atts(array(
		'set' => '',
	), $atts));
	return return_pointelle_slider_recent($set,$offset);
}
add_shortcode('pointellerecent', 'pointelle_slider_recent_shortcode');
?>