<?php 
function pointelle_global_posts_processor( $posts, $pointelle_slider_curr,$out_echo,$set ){
	global $pointelle_slider;
	$pointelle_slider_css = pointelle_get_inline_css($set);
	$html = '';
	$pointelle_sldr_j = 0;
	
	$timthumb='1';
	if($pointelle_slider_curr['timthumb']=='1'){
		$timthumb='0';
	}
	
	foreach($posts as $post) {
		$id = $post->ID;	
		$post_title = stripslashes($post->post_title);
		$post_title = str_replace('"', '', $post_title);
		$post_id = $post->ID;
		//filter hook
		$post_title=apply_filters('pointelle_post_title',$post_title,$post_id,$pointelle_slider_curr,$pointelle_slider_css);	
		$slider_content = $post->post_content;
	
		$pointelle_slide_redirect_url = get_post_meta($post_id, 'pointelle_slide_redirect_url', true);
		$pointelle_sslider_nolink = get_post_meta($post_id,'pointelle_sslider_nolink',true);
		trim($pointelle_slide_redirect_url);
		if(!empty($pointelle_slide_redirect_url) and isset($pointelle_slide_redirect_url)) {
		   $permalink = $pointelle_slide_redirect_url;
		}
		else{
		   $permalink = get_permalink($post_id);
		}
		if($pointelle_sslider_nolink=='1'){
		  $permalink='';
		}
		
		$pointelle_sldr_j++;
		
		if( $pointelle_slider_curr['navpos']=='0' ) $activeclass = 'pointelle-active-lt' ;
		else $activeclass = 'pointelle-active' ;
		
		if($pointelle_sldr_j == '1'){$nav_active = $activeclass;$div_slides='<div class="pointelle_slides" '.$pointelle_slider_css['pointelle_slides'].'>';$div_control='<div class="pointelle-slider-control" '.$pointelle_slider_css['pointelle_slider_control'].'> ';}
		else{$nav_active = '';$div_slides='';$div_control='';}
		$pointelle_slide_nav = get_post_meta($post_id,'slide_nav',true);
		
		if(!empty($pointelle_slide_nav) and isset($pointelle_slide_nav)) {
			   $slide_nav = $pointelle_slide_nav;
		}
		else{
		       $slide_nav = $post_title;
		}
		
		$slide_nav = pointelle_slider_word_limiter( $slide_nav, $limit = $pointelle_slider_curr['slide_nav_limit'] );
		//filter hook
		$slide_nav=apply_filters('pointelle_nav_title',$slide_nav,$post_id,$pointelle_slider_curr,$pointelle_slider_css);
			
		//meta1
		$meta1_parms=$pointelle_slider_curr['meta1_parms'];
		if(function_exists($pointelle_slider_curr['meta1_fn'])){
	    	$fn_name=$pointelle_slider_curr['meta1_fn'];
		    $meta1_value=$fn_name($post,$meta1_parms);
		}
		//meta2
		$meta2_parms=$pointelle_slider_curr['meta2_parms'];
		if(function_exists($pointelle_slider_curr['meta2_fn'])){
	    	$fn_name=$pointelle_slider_curr['meta2_fn'];
		    $meta2_value=$fn_name($post,$meta2_parms);
		}

//All Images 
		$pointelle_media = get_post_meta($post_id,'pointelle_media',true);
		   	
		if($pointelle_slider_curr['img_pick'][0] == '1'){
		 $custom_key = array($pointelle_slider_curr['img_pick'][1]);
		}
		else {
		 $custom_key = '';
		}
		
		$nav_thumb_value = get_post_meta($post_id,'nav_thumb',true);
		if( !$nav_thumb_value or empty($nav_thumb_value) ){
			$nav_custom_key=$custom_key;
		}
		else{
			$nav_custom_key='nav_thumb';
		}
		
		if($pointelle_slider_curr['img_pick'][2] == '1'){
		 $the_post_thumbnail = true;
		}
		else {
		 $the_post_thumbnail = false;
		}
		
		if($pointelle_slider_curr['img_pick'][3] == '1'){
		 $attachment = true;
		 $order_of_image = $pointelle_slider_curr['img_pick'][4];
		}
		else{
		 $attachment = false;
		 $order_of_image = '1';
		}
		
		if($pointelle_slider_curr['img_pick'][5] == '1'){
			 $image_scan = true;
		}
		else {
			 $image_scan = false;
		}
		
       $gti_width = $pointelle_slider_curr['img_width'];
	   $gti_height = $pointelle_slider_curr['img_height'];
	   $nav_thumb_width = $pointelle_slider_curr['nav_img_width'];
	   $nav_thumb_height = $pointelle_slider_curr['nav_img_height'];
		
		if($pointelle_slider_curr['crop'] == '0'){
		 $extract_size = 'full';
		}
		elseif($pointelle_slider_curr['crop'] == '1'){
		 $extract_size = 'large';
		}
		elseif($pointelle_slider_curr['crop'] == '2'){
		 $extract_size = 'medium';
		}
		else{
		 $extract_size = 'thumbnail';
		}
		
		$img_args = array(
			'custom_key' => $nav_custom_key,
			'post_id' => $post_id,
			'attachment' => $attachment,
			'size' => 'thumbnail',
			'the_post_thumbnail' => $the_post_thumbnail,
			'default_image' => false,
			'order_of_image' => $order_of_image,
			'link_to_post' => false,
			'image_class' => 'pointelle_nav_thumb',
			'image_scan' => $image_scan,
			'width' => $nav_thumb_width,
			'height' => $nav_thumb_height,
			'echo' => false,
			'permalink' => '',
			'timthumb'=>$timthumb,
			'style'=> $pointelle_slider_css['pointelle_slider_nav_thumb']
		);		
		
		//on hover
		$anav='';$anav_close='';
		if( $pointelle_slider_curr['onhover'] == '1' ) { 
			$anav='<a style="display:block;" href="'.$permalink.'">';
			$anav_close='</a>';
		}
		
		$navigation .=$div_control.'<div class="pointelle-slider-nav '.$nav_active.'" '.$pointelle_slider_css['pointelle_slider_nav'].'>'.$anav;
		if($pointelle_slider_curr['disable_thumbs'] != '1')	{
			$navigation_image=pointelle_sslider_get_the_image($img_args);
			//filter hook
			$navigation_image=apply_filters('pointelle_nav_thumb',$navigation_image,$post_id,$pointelle_slider_curr,$pointelle_slider_css);
			$navigation .=  $navigation_image;
		}
		
		$pointelle_meta='<span class="pointelle-meta" '.$pointelle_slider_css['pointelle_meta'].'><span class="pointelle-meta1">'.$pointelle_slider_curr['meta1_before'].'<span class="pointelle-meta1-value">'.$meta1_value.'</span>'.$pointelle_slider_curr['meta1_after'].'</span><span class="pointelle-meta2">'.$pointelle_slider_curr['meta2_before'].'<span class="pointelle-meta2-value">'.$meta2_value.'</span>'.$pointelle_slider_curr['meta2_after']. '</span></span>';	
		//filter hook
		$pointelle_meta=apply_filters('pointelle_meta_html',$pointelle_meta,$post_id,$pointelle_slider_curr,$pointelle_slider_css);
		if($pointelle_slider_curr['disable_navtext'] == '1') $navtext='';
		else $navtext =  '<h2 '.$pointelle_slider_css['pointelle_slider_nav_h2'].'>'.$slide_nav.'</h2> 
						'.$pointelle_meta;
		
		$navigation .= $navtext.'<span class="pointelle-order">'.$pointelle_sldr_j.'</span> 
					<div class="sldr_clearlt"></div>'.$anav_close.'</div>';
		
		$html .= $div_slides.'<div class="pointelle_slideri" '.$pointelle_slider_css['pointelle_slideri'].'>
			<!-- pointelle_slide -->';
					
		$more_html='';					
		if($pointelle_slider_curr['show_title']=='1'){
		   if($permalink!='') { $slide_title = '<h4 '.$pointelle_slider_css['pointelle_slider_h4'].'><a href="'.$permalink.'" '.$pointelle_slider_css['pointelle_slider_h4_a'].'>'.$post_title.'</a></h4>';  
				$morefield=$pointelle_slider_curr['readmore'];
				if( !empty($morefield) and $morefield ){
					$more_html= '<p class="more"><a href="'.$permalink.'" '.$pointelle_slider_css['pointelle_slider_p_more'].'>'.$morefield.'</a></p>';
				} 
		   }
		   else{ $slide_title = '<h4 '.$pointelle_slider_css['pointelle_slider_h4'].'>'.$post_title.'</h4>';  }
		}
		else{
		   $slide_title = '';
		}
		//filter hook
		$slide_title=apply_filters('pointelle_slide_title_html',$slide_title,$post_id,$pointelle_slider_curr,$pointelle_slider_css);
		
		if ($pointelle_slider_curr['content_from'] == "slider_content") {
			$slider_content = get_post_meta($post_id, 'slider_content', true);
		}
		if ($pointelle_slider_curr['content_from'] == "excerpt") {
			$slider_content = $post->post_excerpt;
		}

		$slider_content = strip_shortcodes( $slider_content );

		$slider_content = stripslashes($slider_content);
		$slider_content = str_replace(']]>', ']]&gt;', $slider_content);

		$slider_content = str_replace("\n","<br />",$slider_content);
		$slider_content = strip_tags($slider_content, $pointelle_slider_curr['allowable_tags']);
		
		if(!$pointelle_slider_curr['content_limit'] or $pointelle_slider_curr['content_limit'] == '' or $pointelle_slider_curr['content_limit'] == ' ') 
		  $slider_excerpt = substr($slider_content,0,$pointelle_slider_curr['content_chars']);
		else 
		  $slider_excerpt = pointelle_slider_word_limiter( $slider_content, $limit = $pointelle_slider_curr['content_limit'], $dots = '...' );
		
		//filter hook
		$slider_excerpt=apply_filters('pointelle_slide_excerpt',$slider_excerpt,$post_id,$pointelle_slider_curr,$pointelle_slider_css);
		$trimmed=trim($slider_excerpt);
		if( $pointelle_slider_curr['show_content']=='1' and !empty($trimmed) )
			$slider_excerpt='<p '.$pointelle_slider_css['pointelle_excerpt_p'].'> '.$slider_excerpt.'</p>';
		else
			$slider_excerpt='';
		
		//filter hook
		$slider_excerpt=apply_filters('pointelle_slide_excerpt_html',$slider_excerpt,$post_id,$pointelle_slider_curr,$pointelle_slider_css);
				
		$img_args = array(
			'custom_key' => $custom_key,
			'post_id' => $post_id,
			'attachment' => $attachment,
			'size' => $extract_size,
			'the_post_thumbnail' => $the_post_thumbnail,
			'default_image' => false,
			'order_of_image' => $order_of_image,
			'link_to_post' => false,
			'image_class' => 'pointelle_slider_thumbnail',
			'image_scan' => $image_scan,
			'width' => $gti_width,
			'height' => $gti_height,
			'echo' => false,
			'permalink' => $permalink,
			'timthumb'=>$timthumb,
			'style'=> $pointelle_slider_css['pointelle_slider_thumbnail']
		);
		
		if( empty($pointelle_media) or $pointelle_media=='' or !($pointelle_media) ) {  
			$pointelle_large_image=pointelle_sslider_get_the_image($img_args);
		}
		else{
			$pointelle_large_image=$pointelle_media;
		}
		//filter hook
		$pointelle_large_image=apply_filters('pointelle_large_image',$pointelle_large_image,$post_id,$pointelle_slider_curr,$pointelle_slider_css);
		$html .= $pointelle_large_image;
		
		$protect='';
		if($pointelle_slider_curr['copyprotect'] == '1'){
		  if($permalink!='')
			$protect= '<a href="'.$permalink.'" ><span class="pointelle_overlay"></span></a>';	
		  else
		    $protect= '<span class="pointelle_overlay"></span>';	
		} 		
		if ($pointelle_slider_curr['image_only'] == '1') { 
			$html .= $protect.'</div>';
		}
		else {
		   if( !empty($slide_title) or !empty($slider_excerpt) )  $excerpt='<div class="pointelle-excerpt" '.$pointelle_slider_css['pointelle-excerpt'].'>'.$slide_title.$slider_excerpt.$more_html.'</div>';
		   else $excerpt='';
		   
		   $html .= $protect.$excerpt.'</div>';
		}
	}
	if($posts){
		$html .='<div id="pointelle_nav_next" class="pointelle_slide_arrow pointelle_nav_next"></div><div id="pointelle_nav_prev" class="pointelle_slide_arrow pointelle_nav_prev"></div></div>';
		$navigation .='</div>';
	}
	//If disable navigation is set true
	if($pointelle_slider_curr['disable_nav'] == '1')	$navigation =  '';
	
	if( $pointelle_slider_curr['navpos']=='0' ) $html = $navigation . $html ;
	else $html = $html . $navigation ; 
	
	$html=apply_filters('pointelle_extract_html',$html,$pointelle_sldr_j,$posts,$pointelle_slider_curr);
	
	if($out_echo == '1') {
	   echo $html;
	}
	$r_array = array( $pointelle_sldr_j, $html);
	$r_array=apply_filters('pointelle_r_array',$r_array,$posts, $pointelle_slider_curr,$set);
	return $r_array;
}

function get_global_pointelle_slider($slider_handle,$r_array,$pointelle_slider_curr,$set){
	global $pointelle_slider; 
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
		$pointelle_wrapper.hover(	function () {$pointelle_slide_arrow.css("display","block");},
								function () {$pointelle_slide_arrow.css("display","none");} );
		$pointelle_slide_arrow.hover(	function () {pause_scroll= true;},function () {pause_scroll= false;} );';
	}

	$js_scroll_nav='';$html_scroll_nav='';
	if( $pointelle_slider_curr['scroll_nav_posts'] < $pointelle_slider_curr['no_posts'] and $pointelle_slider_curr['disable_nav'] != '1' ){
		wp_enqueue_script( 'jquery.carouFredSel', pointelle_slider_plugin_url( 'js/carouFredSel.js' ),
					array('jquery'), POINTELLE_SLIDER_VER, false); 
		$js_scroll_nav='jQuery("#'.$slider_handle.'  .pointelle-slider-control").carouFredSel({items:'. $pointelle_slider_curr['scroll_nav_posts'].',auto:false,direction:"up",next:{button:"#'. $slider_handle.' .pointelle_nav_down",items:'.$pointelle_slider_curr['scroll_nav_posts'].'}, prev:{button:"#'. $slider_handle.' .pointelle_nav_up",items:'.$pointelle_slider_curr['scroll_nav_posts'].'}}, { wrapper:{classname:"pointelle_nav_wrapper"} }	);
			jQuery("#'.$slider_handle.' .pointelle_nav_arrows").hover( function () {pause_scroll= true;},function () {pause_scroll= false;} );';
		$html_scroll_nav='<div class="pointelle_nav_arrows" '.$pointelle_slider_css['pointelle_nav_arrows'].'><div id="pointelle_nav_down" class="pointelle_nav_down"></div><div id="pointelle_nav_up" class="pointelle_nav_up"></div></div>';
	}
?>
    <script type="text/javascript"> 
<?php if(!isset($pointelle_slider_curr['fouc']) or $pointelle_slider_curr['fouc']=='0' ){?>
	jQuery('html').addClass('pointelle_slider_fouc');
	jQuery(document).ready(function() {   jQuery(".pointelle_slider_fouc #<?php echo $slider_handle;?>").css({'display' : 'block'}); <?php echo $textstatus;?> });
<?php } ?>
	jQuery(document).ready(function(){
		jQuery('#<?php echo $slider_handle;?> .pointelle_slides').cycle({	timeout: <?php echo $timeout;?>, speed: <?php  echo ( $pointelle_slider_curr['speed'] * 100);?>,	fx: '<?php  echo $pointelle_slider_curr['transition'];?>',<?php echo $nextprev_arrows;?> slideExpr: 'div.pointelle_slideri' });
		var manual_transition=false;
		var $pointelle_wrapper = jQuery('#<?php echo $slider_handle;?>');var $pointelle_item = jQuery('#<?php echo $slider_handle;?> div.pointelle-slider-nav');var $slider_control = jQuery('#<?php echo $slider_handle;?>  .pointelle-slider-control');var $image_container = jQuery('#<?php echo $slider_handle;?> .pointelle_slideri');var ordernumber;var pause_scroll = false;$image_container.css("height","<?php  echo $pointelle_slider_curr['height'];?>px");
		<?php echo $js_slide_arrows;?>
		$image_container.hover(	function () {jQuery(this).find("img").stop(true,true).fadeTo("fast", 0.7);pause_scroll= true;<?php echo $texthoveron;?>}, 
							 function () {jQuery(this).find("img").stop(true,true).fadeTo("fast", 1);pause_scroll= false;<?php echo $texthoveroff;?>});
<?php if($pointelle_slider_curr['disable_nav'] != '1') {?>
		function pointelle_manual_transition(isNext){
			var manual_number = $slider_control.find(".<?php echo $activeclass;?> span.pointelle-order").html();
			if(!isNext) manual_number=manual_number-2;;
			if ( (manual_number == $pointelle_item.length) && isNext ) manual_number = 0;
			if ( (manual_number < 0) && !isNext ) manual_number = $pointelle_item.length-1;
			manual_transition=true;
			$pointelle_item.eq(manual_number).trigger("pointelle_autonext");
		}
		<?php echo $js_scroll_nav;?>
		$pointelle_item.find('img').fadeTo("fast", 0.7);$slider_control.find(".<?php echo $activeclass;?> img").fadeTo("fast", 1);
		function pointelle_gonext(this_element){
			$slider_control.find(".<?php echo $activeclass;?> img").stop(true,true).fadeTo("fast", 0.7);
			$slider_control.find(".<?php echo $activeclass;?>").removeClass('<?php echo $activeclass;?>');
			this_element.addClass("<?php echo $activeclass;?>");
			$slider_control.find(".<?php echo $activeclass;?> img").stop(true,true).fadeTo("fast", 1);
			ordernumber = this_element.find("span.pointelle-order").html();
			jQuery('#<?php echo $slider_handle;?>  .pointelle-slider-control').trigger("slideTo", ordernumber - 1);
			if(!manual_transition)	jQuery('#<?php echo $slider_handle;?> .pointelle_slides').cycle(ordernumber - 1);
			manual_transition=false;
		} 
		<?php echo $transitionon; ?>	
		var auto_number;var interval;
		$pointelle_item.bind('pointelle_autonext', function pointelle_autonext(){ 
			if (!(pause_scroll) || manual_transition) {  pointelle_gonext(jQuery(this))}; 
			return false;
		});
		<?php echo $autoslide; ?>
	<?php } else {?>
		function pointelle_manual_transition(){	}
	<?php } ?>
	});
	<?php do_action('pointelle_global_script',$slider_handle,$pointelle_slider_curr);?>
	</script> 
	<noscript><p><strong><?php echo $pointelle_slider['noscript'];?></strong></p></noscript>
	<?php 
	$slider_html='<div id="'.$slider_handle.'" class="pointelle_slider pointelle_slider_'.$set.'" '.$pointelle_slider_css['pointelle_slider'].'>
			'. $r_array[1].'
			'.$html_scroll_nav.'
	<div class="sldr_clearlt"></div><div class="sldr_clearrt"></div></div>';
	$slider_html=apply_filters('pointelle_slider_html',$slider_html,$r_array,$pointelle_slider_curr,$set);
	echo $slider_html;
}

function pointelle_carousel_posts_on_slider($max_posts, $offset=0, $slider_id = '1',$out_echo = '1',$set='') {
    global $pointelle_slider;
	$pointelle_slider_options='pointelle_slider_options'.$set;
    $pointelle_slider_curr=get_option($pointelle_slider_options);
	if(!isset($pointelle_slider_curr) or !is_array($pointelle_slider_curr) or empty($pointelle_slider_curr)){$pointelle_slider_curr=$pointelle_slider;$set='';}
		
	global $wpdb, $table_prefix;
	$table_name = $table_prefix.POINTELLE_SLIDER_TABLE;
	$post_table = $table_prefix."posts";
	$rand = $pointelle_slider_curr['rand'];
	if(isset($rand) and $rand=='1'){
	  $orderby = 'RAND()';
	}
	else {
	  $orderby = 'a.slide_order ASC, a.date DESC';
	}
	
	$posts = $wpdb->get_results("SELECT b.* FROM 
	                             $table_name a LEFT OUTER JOIN $post_table b 
								 ON a.post_id = b.ID 
								 WHERE (b.post_status = 'publish' OR (b.post_type='attachment' AND b.post_status = 'inherit')) AND a.slider_id = '$slider_id' 
	                             ORDER BY ".$orderby." LIMIT $offset, $max_posts", OBJECT);
	
	$r_array=pointelle_global_posts_processor( $posts, $pointelle_slider_curr, $out_echo,$set );
	return $r_array;
}

function get_pointelle_slider($slider_id='',$set='') {
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
	if(!empty($slider_id)){
		$r_array = pointelle_carousel_posts_on_slider($pointelle_slider_curr['no_posts'], $offset=0, $slider_id, '0', $set); 
		$slider_handle='pointelle_slider_'.$slider_id;
		get_global_pointelle_slider($slider_handle,$r_array,$pointelle_slider_curr,$set);
	} //end of not empty slider_id condition
}

//For displaying category specific posts in chronologically reverse order
function pointelle_carousel_posts_on_slider_category($max_posts='5', $catg_slug='', $offset=0, $out_echo = '1', $set='') {
    global $pointelle_slider;
	$pointelle_slider_options='pointelle_slider_options'.$set;
    $pointelle_slider_curr=get_option($pointelle_slider_options);
	if(!isset($pointelle_slider_curr) or !is_array($pointelle_slider_curr) or empty($pointelle_slider_curr)){$pointelle_slider_curr=$pointelle_slider;$set='';}

	global $wpdb, $table_prefix;
	
	if (!empty($catg_slug)) {
		$category = get_category_by_slug($catg_slug); 
		$slider_cat = $category->term_id;
	}
	else {
		$category = get_the_category();
		$slider_cat = $category[0]->cat_ID;
	}
	
	$rand = $pointelle_slider_curr['rand'];
	if(isset($rand) and $rand=='1'){
	  $orderby = '&orderby=rand';
	}
	else {
	  $orderby = '';
	}
	
	//extract the posts
	$posts = get_posts('numberposts='.$max_posts.'&offset='.$offset.'&category='.$slider_cat.$orderby);
	
	$r_array=pointelle_global_posts_processor( $posts, $pointelle_slider_curr, $out_echo,$set );
	return $r_array;
}

function get_pointelle_slider_category($catg_slug='', $set='') {
    global $pointelle_slider; 
 	$pointelle_slider_options='pointelle_slider_options'.$set;
    $pointelle_slider_curr=get_option($pointelle_slider_options);
	if(!isset($pointelle_slider_curr) or !is_array($pointelle_slider_curr) or empty($pointelle_slider_curr)){$pointelle_slider_curr=$pointelle_slider;$set='';}
    $r_array = pointelle_carousel_posts_on_slider_category($pointelle_slider_curr['no_posts'], $catg_slug, '0', '0', $set); 
	$slider_handle='pointelle_slider_'.$catg_slug;
	get_global_pointelle_slider($slider_handle,$r_array,$pointelle_slider_curr,$set);
} 

//For displaying recent posts in chronologically reverse order
function pointelle_carousel_posts_on_slider_recent($max_posts='5', $offset=0, $out_echo = '1', $set='') {
    global $pointelle_slider;
	$pointelle_slider_options='pointelle_slider_options'.$set;
    $pointelle_slider_curr=get_option($pointelle_slider_options);
	if(!isset($pointelle_slider_curr) or !is_array($pointelle_slider_curr) or empty($pointelle_slider_curr)){$pointelle_slider_curr=$pointelle_slider;$set='';}
	//extract posts data
	$posts = get_posts('numberposts='.$max_posts.'&offset='.$offset);
	//randomize the slides
	$rand = $pointelle_slider_curr['rand'];
	if(isset($rand) and $rand=='1'){
	  shuffle($posts);
	}
	
	$r_array=pointelle_global_posts_processor( $posts, $pointelle_slider_curr, $out_echo,$set );
	return $r_array;
}

function get_pointelle_slider_recent($set='') {
	global $pointelle_slider; 
 	$pointelle_slider_options='pointelle_slider_options'.$set;
    $pointelle_slider_curr=get_option($pointelle_slider_options);
	if(!isset($pointelle_slider_curr) or !is_array($pointelle_slider_curr) or empty($pointelle_slider_curr)){$pointelle_slider_curr=$pointelle_slider;$set='';}
	$r_array = pointelle_carousel_posts_on_slider_recent($pointelle_slider_curr['no_posts'], '0', '0', $set);
	$slider_handle='pointelle_slider_recent';
	get_global_pointelle_slider($slider_handle,$r_array,$pointelle_slider_curr,$set);
}
 
require_once (dirname (__FILE__) . '/shortcodes_1.php');
require_once (dirname (__FILE__) . '/widgets_1.php');

function pointelle_slider_enqueue_scripts() {
	wp_enqueue_script( 'jquery.cycle', pointelle_slider_plugin_url( 'js/jquery.cycle.js' ),
		array('jquery'), POINTELLE_SLIDER_VER, false);
}

add_action( 'init', 'pointelle_slider_enqueue_scripts' );

function pointelle_slider_enqueue_styles() {	
  global $post, $pointelle_slider, $wp_registered_widgets,$wp_widget_factory;
  if(is_singular()) {
	 $pointelle_slider_style = get_post_meta($post->ID,'_pointelle_slider_style',true);
	 if((is_active_widget(false, false, 'pointelle_sslider_wid', true) or isset($pointelle_slider['shortcode']) ) and (!isset($pointelle_slider_style) or empty($pointelle_slider_style))){
	   $pointelle_slider_style='default';
	 }
	 if (!isset($pointelle_slider_style) or empty($pointelle_slider_style) ) {
	     wp_enqueue_style( 'pointelle_slider_headcss', pointelle_slider_plugin_url( 'css/skins/'.$pointelle_slider['stylesheet'].'/style.css' ),
		false, POINTELLE_SLIDER_VER, 'all');
	 }
     else {
	     wp_enqueue_style( 'pointelle_slider_headcss', pointelle_slider_plugin_url( 'css/skins/'.$pointelle_slider_style.'/style.css' ),
		false, POINTELLE_SLIDER_VER, 'all');
	}
  }
  else {
     $pointelle_slider_style = $pointelle_slider['stylesheet'];
	wp_enqueue_style( 'pointelle_slider_headcss', pointelle_slider_plugin_url( 'css/skins/'.$pointelle_slider_style.'/style.css' ),
		false, POINTELLE_SLIDER_VER, 'all');
  }
}
add_action( 'wp', 'pointelle_slider_enqueue_styles' );

//admin settings
function pointelle_slider_admin_scripts() {
global $pointelle_slider;
  if ( is_admin() ){ // admin actions
  // Settings page only
	if ( isset($_GET['page']) && ('pointelle-slider-admin' == $_GET['page'] or 'pointelle-slider-settings' == $_GET['page'] )  ) {
	wp_register_script('jquery', false, false, false, false);
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'jquery-ui-core' );
    wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'jquery.cookie', pointelle_slider_plugin_url( 'js/jquery.cookie.js' ),
		array('jquery-ui-core','jquery-ui-tabs'), POINTELLE_SLIDER_VER, false);
	wp_enqueue_script( 'jquery.cycle', pointelle_slider_plugin_url( 'js/jquery.cycle.js' ),
		array('jquery'), POINTELLE_SLIDER_VER, false);
	wp_enqueue_style( 'pointelle_slider_admin_head_css', pointelle_slider_plugin_url( 'css/skins/'.$pointelle_slider['stylesheet'].'/style.css' ),
		false, POINTELLE_SLIDER_VER, 'all');
	}
  }
}

add_action( 'admin_init', 'pointelle_slider_admin_scripts' );

function pointelle_slider_admin_head() {
global $pointelle_slider;
if ( is_admin() ){ // admin actions
   
  // Sliders page only
    if ( isset($_GET['page']) && ('pointelle-slider-admin' == $_GET['page'] ) ) {
	  $sliders = pointelle_ss_get_sliders(); 
	?>
		<script type="text/javascript">
            // <![CDATA[
        jQuery(document).ready(function() {
                jQuery(function() {
                    jQuery("#slider_tabs").tabs({ cookie: { expires: 30 } }); 
					//getter
					var cookie = jQuery("#slider_tabs").tabs( "option", "cookie" );
					//setter
					jQuery("#slider_tabs").tabs( "option", "cookie", { expires: 30 } );
					
				<?php foreach($sliders as $slider){?>
                    jQuery("#sslider_sortable_<?php echo $slider['slider_id'];?>").sortable();
                    jQuery("#sslider_sortable_<?php echo $slider['slider_id'];?>").disableSelection();
			    <?php } ?>
                });
        });
        function confirmRemove()
        {
            var agree=confirm("This will remove selected Posts/Pages from Slider.");
            if (agree)
            return true ;
            else
            return false ;
        }
        function confirmRemoveAll()
        {
            var agree=confirm("Remove all Posts/Pages from Pointelle Slider??");
            if (agree)
            return true ;
            else
            return false ;
        }
        function confirmSliderDelete()
        {
            var agree=confirm("Delete this Slider??");
            if (agree)
            return true ;
            else
            return false ;
        }
        function slider_checkform ( form )
        {
          if (form.new_slider_name.value == "") {
            alert( "Please enter the New Slider name." );
            form.new_slider_name.focus();
            return false ;
          }
          return true ;
        }
        </script>
        <style type="text/css">
        /************************************************
        *	ui-tabs  									*
        ************************************************/
        .ui-tabs { padding: .2em; zoom: 1; }
        .ui-tabs .ui-tabs-nav { list-style: none; position: relative; padding: .2em .2em 0; }
        .ui-tabs .ui-tabs-nav li { position: relative; float: left; border-bottom-width: 0 !important; margin: 0 .2em -1px 0; padding: 0;  background-color:#B9B9B9;}
        .ui-tabs .ui-tabs-nav li a { float: left; text-decoration: none; padding: .5em 1em; color:#FFFFFF;}
        .ui-tabs .ui-tabs-nav li.ui-tabs-selected { border-bottom-width: 0; background-color:#ABD37E;}
        .ui-tabs .ui-tabs-nav li.ui-tabs-selected a, .ui-tabs .ui-tabs-nav li.ui-state-disabled a, .ui-tabs .ui-tabs-nav li.ui-state-processing a { cursor: text; color:#FFF;}
        .ui-tabs .ui-tabs-nav li a, .ui-tabs.ui-tabs-collapsible .ui-tabs-nav li.ui-tabs-selected a { cursor: pointer; } /* first selector in group seems obsolete, but required to overcome bug in Opera applying cursor: text overall if defined elsewhere... */
        .ui-tabs .ui-tabs-panel { padding: 1em 1.4em; display: block; border-width: 0; background: none; }
        .ui-tabs .ui-tabs-hide { display: none !important; }
        /*tabs complete*/
        #sldr_message {background-color:#FEF7DA;clear:both;width:72%;}
        #sldr_close {float:right;} 
        </style>
<?php
   } //Sliders page only
 }//only for admin
}
add_action('admin_head', 'pointelle_slider_admin_head');

function pointelle_slider_admin_enqueue(){
	global $pointelle_slider;
	if ( is_admin() ){ // admin actions
		if ( isset($_GET['page']) && 'pointelle-slider-settings' == $_GET['page']  ) {
			wp_enqueue_style( 'farbtastic' );
			wp_register_style( 'pointelle_admin_css', pointelle_slider_plugin_url( 'settings/admin.css' ), false, POINTELLE_SLIDER_VER, 'all' );
			wp_enqueue_style( 'pointelle_admin_css' );
			
			wp_enqueue_script( 'farbtastic' );
			wp_enqueue_script( 'pointelle_admin_js', pointelle_slider_plugin_url( 'settings/admin.js' ),array('jquery'), POINTELLE_SLIDER_VER, false);
			
		}
	}
}
add_action( 'admin_enqueue_scripts', 'pointelle_slider_admin_enqueue' );

//get direct css rules
function pointelle_get_inline_css_rules($set=''){
    global $pointelle_slider;
	$pointelle_slider_options='pointelle_slider_options'.$set;
    $pointelle_slider_curr=get_option($pointelle_slider_options);
	if(!isset($pointelle_slider_curr) or !is_array($pointelle_slider_curr) or empty($pointelle_slider_curr)){$pointelle_slider_curr=$pointelle_slider;$set='';}
	
	global $post;
	if(is_singular()) {	$pointelle_slider_style = get_post_meta($post->ID,'_pointelle_slider_style',true);}
	if((is_singular() and ($pointelle_slider_style == 'default' or empty($pointelle_slider_style) or !$pointelle_slider_style)) or (!is_singular() and $pointelle_slider['stylesheet'] == 'default')  )	{ $default=true;	}
	else{ $default=false;}
	
	$pointelle_slider_css=array();
	if($default){
		//pointelle_slider
		$width='';
		if(isset($pointelle_slider_curr['width']) and $pointelle_slider_curr['width']!=0) {
		    $width='width:'. $pointelle_slider_curr['width'].'px;';
		}		
		$pointelle_slider_css['pointelle_slider'] = ''.$width.'height:'. $pointelle_slider_curr['height'].'px;';

		//pointelle_slides	
		if( $pointelle_slider_curr['navpos']=='0' ) {$slidespos = 'right:0;left:inherit;' ;
		$pointelle_slider_css['pointelle_slides'] = ''.$slidespos.'';}
		
		//pointelle_slider_control	
		if( $pointelle_slider_curr['navpos']=='0' ) $controlpos = 'left:0;right:inherit;' ;
		$pointelle_slider_css['pointelle_slider_control'] = 'width:'.$pointelle_slider_curr['nav_control_w'].'px; height: '. ( $pointelle_slider_curr['nav_control_h'] - 2 ).'px;border: 1px solid '.$pointelle_slider_curr['nav_brcolor'].'; '.$controlpos.'';
		
		//pointelle_slider_nav_thumb
		$pointelle_slider_css['pointelle_slider_nav_thumb'] = 'border:'.$pointelle_slider_curr['nav_img_border'].'px solid '.$pointelle_slider_curr['nav_img_brcolor'].';width:'.$pointelle_slider_curr['nav_img_width'].'px;height:'.$pointelle_slider_curr['nav_img_height'].'px;';
		
		//pointelle_slider_nav
		if ($pointelle_slider_curr['nav_bg'] == '1') { $pointelle_slider_nav_bg = "transparent";} else { $pointelle_slider_nav_bg = $pointelle_slider_curr['nav_bg_color']; }
		$pointelle_slider_css['pointelle_slider_nav'] = 'height: '.( ( $pointelle_slider_curr['nav_control_h'] / $pointelle_slider_curr['scroll_nav_posts'] ) - 20 ).'px;background-color:'.$pointelle_slider_nav_bg.'; border-bottom:'.$pointelle_slider_curr['nav_img_border'].'px solid '.$pointelle_slider_curr['nav_brcolor'].'';
		
		//pointelle_slider_nav_h2
		if ($pointelle_slider_curr['nav_title_fstyle'] == "bold" or $pointelle_slider_curr['nav_title_fstyle'] == "bold italic" ){$nav_title_font_weight = "bold";} else { $nav_title_font_weight = "normal"; }
		if ($pointelle_slider_curr['nav_title_fstyle'] == "italic" or $pointelle_slider_curr['nav_title_fstyle'] == "bold italic" ){$nav_title_font_style = "italic";} else {$nav_title_font_style = "normal";}
		if($pointelle_slider_curr['disable_thumbs'] != '1')	$nav_img_width =  ( $pointelle_slider_curr['nav_img_width'] + 55 );
		else $nav_img_width =  30;
		$pointelle_slider_css['pointelle_slider_nav_h2'] = 'width:'.($pointelle_slider_curr['nav_control_w'] - $nav_img_width ) .'px;font-family:'.$pointelle_slider_curr['nav_title_font'].', Arial, Helvetica, sans-serif; font-weight:'.$nav_title_font_weight.';font-style:'.$nav_title_font_style.'; font-size: '.$pointelle_slider_curr['nav_title_fsize'].'px; color: '.$pointelle_slider_curr['nav_title_fcolor'].';';
		
		//pointelle_meta
	    if ($pointelle_slider_curr['meta_title_fstyle'] == "bold" or $pointelle_slider_curr['meta_title_fstyle'] == "bold italic" ){$meta_title_font_weight = "bold";} else { $meta_title_font_weight = "normal"; }
		if ($pointelle_slider_curr['meta_title_fstyle'] == "italic" or $pointelle_slider_curr['meta_title_fstyle'] == "bold italic" ){$meta_title_font_style = "italic";} else {$meta_title_font_style = "normal";}
		$pointelle_slider_css['pointelle_meta'] = 'width:'.($pointelle_slider_curr['nav_control_w'] - $nav_img_width ) .'px;font-family:'.$pointelle_slider_curr['meta_title_font'].', Arial, Helvetica, sans-serif; font-weight:'.$meta_title_font_weight.';font-style:'.$meta_title_font_style.'; font-size: '.$pointelle_slider_curr['meta_title_fsize'].'px; color: '.$pointelle_slider_curr['meta_title_fcolor'].';border-top:1px solid '.$pointelle_slider_curr['nav_brcolor'].';';
		 
	//pointelle_slideri
	   	if ($pointelle_slider_curr['bg'] == '1') { $pointelle_slideri_bg = "transparent";} else { $pointelle_slideri_bg = $pointelle_slider_curr['bg_color']; }
		$pointelle_slider_css['pointelle_slideri']='background-color:'.$pointelle_slideri_bg.';border:'.$pointelle_slider_curr['border'].'px solid '.$pointelle_slider_curr['brcolor'].';height:'. $pointelle_slider_curr['height'].'px;';
		
		//pointelle_slider_h4
		if ($pointelle_slider_curr['ptitle_fstyle'] == "bold" or $pointelle_slider_curr['ptitle_fstyle'] == "bold italic" ){$ptitle_fweight = "bold";} else {$ptitle_fweight = "normal";}
		if ($pointelle_slider_curr['ptitle_fstyle'] == "italic" or $pointelle_slider_curr['ptitle_fstyle'] == "bold italic"){$ptitle_fstyle = "italic";} else {$ptitle_fstyle = "normal";}
		$pointelle_slider_css['pointelle_slider_h4']='clear:none;line-height:'. ($pointelle_slider_curr['ptitle_fsize'] + 3) .'px;font-family:'. $pointelle_slider_curr['ptitle_font'].', Arial, Helvetica, sans-serif;font-size:'.$pointelle_slider_curr['ptitle_fsize'].'px;font-weight:'.$ptitle_fweight.';font-style:'.$ptitle_fstyle.';color:'.$pointelle_slider_curr['ptitle_fcolor'].';margin:0px 0 5px 0;';
		
	//pointelle_slider_h4_a
		$pointelle_slider_css['pointelle_slider_h4_a']='color:'.$pointelle_slider_curr['ptitle_fcolor'].';';
	
	//pointelle_excerpt_p
		if ($pointelle_slider_curr['content_fstyle'] == "bold" or $pointelle_slider_curr['content_fstyle'] == "bold italic" ){$content_fweight= "bold";} else {$content_fweight= "normal";}
		if ($pointelle_slider_curr['content_fstyle']=="italic" or $pointelle_slider_curr['content_fstyle'] == "bold italic"){$content_fstyle= "italic";} else {$content_fstyle= "normal";}
		$pointelle_slider_css['pointelle_excerpt_p']='font-family:'.$pointelle_slider_curr['content_font'].', Arial, Helvetica, sans-serif;font-size:'.$pointelle_slider_curr['content_fsize'].'px;font-weight:'.$content_fweight.';font-style:'.$content_fstyle.';color:'. $pointelle_slider_curr['content_fcolor'].';';
		
	//pointelle_slider_thumbnail
		$pointelle_slider_css['pointelle_slider_thumbnail']='height:'.$pointelle_slider_curr['img_height'].'px;border:'.$pointelle_slider_curr['img_border'].'px solid '.$pointelle_slider_curr['img_brcolor'].';width:'.$pointelle_slider_curr['img_width'].'px;margin:0;padding:0;';
	
	//pointelle_slider_p_more
		$pointelle_slider_css['pointelle_slider_p_more']='color:'.$pointelle_slider_curr['ptitle_fcolor'].';font-family:'.$pointelle_slider_curr['content_font'].';font-size:'.$pointelle_slider_curr['content_fsize'].'px;';
	
	//pointelle_nav_arrows
		$navarrowpos='';
		if( $pointelle_slider_curr['navpos']=='0' ) $navarrowpos = 'left:0;right:inherit;' ;
		$pointelle_slider_css['pointelle_nav_arrows']=$navarrowpos;	
	}
	return $pointelle_slider_css;
}

function pointelle_get_inline_css($set=''){
    global $pointelle_slider;
	$pointelle_slider_options='pointelle_slider_options'.$set;
    $pointelle_slider_curr=get_option($pointelle_slider_options);
	if(!isset($pointelle_slider_curr) or !is_array($pointelle_slider_curr) or empty($pointelle_slider_curr)){$pointelle_slider_curr=$pointelle_slider;$set='';}
	
	global $post;
	if(is_singular()) {	$pointelle_slider_style = get_post_meta($post->ID,'_pointelle_slider_style',true);}
	if((is_singular() and ($pointelle_slider_style == 'default' or empty($pointelle_slider_style) or !$pointelle_slider_style)) or (!is_singular() and $pointelle_slider['stylesheet'] == 'default')  )	{ $default=true;	}
	else{ $default=false;}
	
	$pointelle_slider_css=array();
	if($default){
		//pointelle_slider
		$width='';
		if(isset($pointelle_slider_curr['width']) and $pointelle_slider_curr['width']!=0) {
		    $width='width:'. $pointelle_slider_curr['width'].'px;';
		}		
		$pointelle_slider_css['pointelle_slider'] = 'style="'.$width.'height:'. $pointelle_slider_curr['height'].'px;"';

		//pointelle_slides	
		if( $pointelle_slider_curr['navpos']=='0' ) {$slidespos = 'right:0;left:inherit;' ;
		$pointelle_slider_css['pointelle_slides'] = 'style="'.$slidespos.'"';}
		
		//pointelle_slider_control	
		if( $pointelle_slider_curr['navpos']=='0' ) $controlpos = 'left:0;right:inherit;' ;
		$pointelle_slider_css['pointelle_slider_control'] = 'style="width:'.$pointelle_slider_curr['nav_control_w'].'px; height: '. ( $pointelle_slider_curr['nav_control_h'] - 2 ).'px;border: 1px solid '.$pointelle_slider_curr['nav_brcolor'].'; '.$controlpos.'"';
		
		//pointelle_slider_nav_thumb
		$pointelle_slider_css['pointelle_slider_nav_thumb'] = 'style="border:'.$pointelle_slider_curr['nav_img_border'].'px solid '.$pointelle_slider_curr['nav_img_brcolor'].';width:'.$pointelle_slider_curr['nav_img_width'].'px;height:'.$pointelle_slider_curr['nav_img_height'].'px;"';
		
		//pointelle_slider_nav
		if ($pointelle_slider_curr['nav_bg'] == '1') { $pointelle_slider_nav_bg = "transparent";} else { $pointelle_slider_nav_bg = $pointelle_slider_curr['nav_bg_color']; }
		$pointelle_slider_css['pointelle_slider_nav'] = 'style="height: '.( ( $pointelle_slider_curr['nav_control_h'] / $pointelle_slider_curr['scroll_nav_posts'] ) - 20 ).'px;background-color:'.$pointelle_slider_nav_bg.'; border-bottom:'.$pointelle_slider_curr['nav_img_border'].'px solid '.$pointelle_slider_curr['nav_brcolor'].'"';
		
		//pointelle_slider_nav_h2
		if ($pointelle_slider_curr['nav_title_fstyle'] == "bold" or $pointelle_slider_curr['nav_title_fstyle'] == "bold italic" ){$nav_title_font_weight = "bold";} else { $nav_title_font_weight = "normal"; }
		if ($pointelle_slider_curr['nav_title_fstyle'] == "italic" or $pointelle_slider_curr['nav_title_fstyle'] == "bold italic" ){$nav_title_font_style = "italic";} else {$nav_title_font_style = "normal";}
		if($pointelle_slider_curr['disable_thumbs'] != '1')	$nav_img_width =  ( $pointelle_slider_curr['nav_img_width'] + 55 );
		else $nav_img_width =  30;
		$pointelle_slider_css['pointelle_slider_nav_h2'] = 'style="width:'.($pointelle_slider_curr['nav_control_w'] - $nav_img_width ) .'px;font-family:'.$pointelle_slider_curr['nav_title_font'].', Arial, Helvetica, sans-serif; font-weight:'.$nav_title_font_weight.';font-style:'.$nav_title_font_style.'; font-size: '.$pointelle_slider_curr['nav_title_fsize'].'px; color: '.$pointelle_slider_curr['nav_title_fcolor'].';"';
		
		//pointelle_meta
	    if ($pointelle_slider_curr['meta_title_fstyle'] == "bold" or $pointelle_slider_curr['meta_title_fstyle'] == "bold italic" ){$meta_title_font_weight = "bold";} else { $meta_title_font_weight = "normal"; }
		if ($pointelle_slider_curr['meta_title_fstyle'] == "italic" or $pointelle_slider_curr['meta_title_fstyle'] == "bold italic" ){$meta_title_font_style = "italic";} else {$meta_title_font_style = "normal";}
		$pointelle_slider_css['pointelle_meta'] = 'style="width:'.($pointelle_slider_curr['nav_control_w'] - $nav_img_width ) .'px;font-family:'.$pointelle_slider_curr['meta_title_font'].', Arial, Helvetica, sans-serif; font-weight:'.$meta_title_font_weight.';font-style:'.$meta_title_font_style.'; font-size: '.$pointelle_slider_curr['meta_title_fsize'].'px; color: '.$pointelle_slider_curr['meta_title_fcolor'].';border-top:1px solid '.$pointelle_slider_curr['nav_brcolor'].';"';
		 
	//pointelle_slideri
	   	if ($pointelle_slider_curr['bg'] == '1') { $pointelle_slideri_bg = "transparent";} else { $pointelle_slideri_bg = $pointelle_slider_curr['bg_color']; }
		$pointelle_slider_css['pointelle_slideri']='style="background-color:'.$pointelle_slideri_bg.';border:'.$pointelle_slider_curr['border'].'px solid '.$pointelle_slider_curr['brcolor'].';height:'. $pointelle_slider_curr['height'].'px;"';
		
		//pointelle_slider_h4
		if ($pointelle_slider_curr['ptitle_fstyle'] == "bold" or $pointelle_slider_curr['ptitle_fstyle'] == "bold italic" ){$ptitle_fweight = "bold";} else {$ptitle_fweight = "normal";}
		if ($pointelle_slider_curr['ptitle_fstyle'] == "italic" or $pointelle_slider_curr['ptitle_fstyle'] == "bold italic"){$ptitle_fstyle = "italic";} else {$ptitle_fstyle = "normal";}
		$pointelle_slider_css['pointelle_slider_h4']='style="clear:none;line-height:'. ($pointelle_slider_curr['ptitle_fsize'] + 3) .'px;font-family:'. $pointelle_slider_curr['ptitle_font'].', Arial, Helvetica, sans-serif;font-size:'.$pointelle_slider_curr['ptitle_fsize'].'px;font-weight:'.$ptitle_fweight.';font-style:'.$ptitle_fstyle.';color:'.$pointelle_slider_curr['ptitle_fcolor'].';margin:0px 0 5px 0;"';
		
	//pointelle_slider_h4_a
		$pointelle_slider_css['pointelle_slider_h4_a']='style="color:'.$pointelle_slider_curr['ptitle_fcolor'].';"';
	
	//pointelle_excerpt_p
		if ($pointelle_slider_curr['content_fstyle'] == "bold" or $pointelle_slider_curr['content_fstyle'] == "bold italic" ){$content_fweight= "bold";} else {$content_fweight= "normal";}
		if ($pointelle_slider_curr['content_fstyle']=="italic" or $pointelle_slider_curr['content_fstyle'] == "bold italic"){$content_fstyle= "italic";} else {$content_fstyle= "normal";}
		$pointelle_slider_css['pointelle_excerpt_p']='style="font-family:'.$pointelle_slider_curr['content_font'].', Arial, Helvetica, sans-serif;font-size:'.$pointelle_slider_curr['content_fsize'].'px;font-weight:'.$content_fweight.';font-style:'.$content_fstyle.';color:'. $pointelle_slider_curr['content_fcolor'].';"';
		
	//pointelle_slider_thumbnail
		$pointelle_slider_css['pointelle_slider_thumbnail']='style="height:'.$pointelle_slider_curr['img_height'].'px;border:'.$pointelle_slider_curr['img_border'].'px solid '.$pointelle_slider_curr['img_brcolor'].';width:'.$pointelle_slider_curr['img_width'].'px;margin:0;padding:0;"';
	
	//pointelle_slider_p_more
		$pointelle_slider_css['pointelle_slider_p_more']='style="color:'.$pointelle_slider_curr['ptitle_fcolor'].';font-family:'.$pointelle_slider_curr['content_font'].';font-size:'.$pointelle_slider_curr['content_fsize'].'px;"';
	
	//pointelle_nav_arrows
		$navarrowpos='';
		if( $pointelle_slider_curr['navpos']=='0' ) $navarrowpos = 'left:0;right:inherit;' ;
		$pointelle_slider_css['pointelle_nav_arrows']='style="'.$navarrowpos.'"';	
	}
	$pointelle_slider_css=apply_filters('pointelle_inline_css',$pointelle_slider_css,$pointelle_slider_curr,$default);
	return $pointelle_slider_css;
}
function pointelle_slider_css() {
global $pointelle_slider;
$css=$pointelle_slider['css'];
if($css and !empty($css)){
?>
 <style type="text/css"><?php echo $css;?></style>
<?php
}
}
add_action('wp_head', 'pointelle_slider_css');
add_action('admin_head', 'pointelle_slider_css');

function pointelle_custom_scripts(){
}
add_action('wp_head', 'pointelle_custom_scripts');
add_action('admin_head', 'pointelle_custom_scripts');
?>