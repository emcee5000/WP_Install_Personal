<?php
/*
Plugin Name: Green Card Deals
Plugin URI: http://kickpointstudios.com
Description: WP Plugin for Green Card deals
Author: Avinash Kadaji
Version: 1.0
Author URI: http://kickpointstudios.com
*/

class KPS_GreenCard {

	function __construct() {
		$labels = array(
			'name' => _x('GC Deals', 'post type general name'),
			'singular_name' => _x('GC Deal', 'post type singular name'),
			'add_new' => _x('Add New', 'kpsgcdeal item'),
			'add_new_item' => __('Add New Deal'),
			'edit_item' => __('Edit Deal'),
			'new_item' => __('New Deal'),
			'view_item' => __('View Deal '),
			'search_items' => __('Search Deals'),
			'not_found' =>  __('Nothing found'),
			'not_found_in_trash' => __('Nothing found in Trash'),
			'parent_item_colon' => ''
		);
	 
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			//'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title','editor','thumbnail'),
			'taxonomies' => array( 'post_tag' )
		  ); 
	 
		register_post_type( 'kpsgcdeal' , $args );
		flush_rewrite_rules();
		
		/**
		 * Register courses as taxonomies
		 */
		$labels = array(
			'name' => _x( 'Courses', 'taxonomy general name' ),
			'singular_name' => _x( 'Course', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Courses' ),
			'all_items' => __( 'All Courses' ),
			'parent_item' => __( 'Parent Course' ),
			'parent_item_colon' => __( 'Parent Course:' ),
			'edit_item' => __( 'Edit Course' ), 
			'update_item' => __( 'Update Course' ),
			'add_new_item' => __( 'Add New Course' ),
			'new_item_name' => __( 'New Course Name' ),
			'menu_name' => __( 'Courses' ),
		  ); 	 
	    register_taxonomy( "courses", array("kpsgcdeal"), 
	   						array("hierarchical" => true, 'labels' => $labels, "singular_label" => "Course", 
							"rewrite" => true));
							

		//add custom fields
		add_action("admin_init", array( &$this, "init_fields" ));
		 					
		
		//save custom fields
		add_action('save_post', array( &$this, 'save_details' ));
		
		//Custom UI view
		add_action("manage_posts_custom_column",  array( &$this, "kpsgcdeal_custom_columns" ));
		add_filter("manage_edit-kpsgcdeal_columns", array( &$this, "kpsgcdeal_edit_columns" ));
 
 		add_action( "template_redirect", array( &$this, "template_redirect" ));
	}

	function template_redirect() {
		global $wp_query;		
		if( $wp_query->query_vars['post_type'] == 'kpsgcdeal' ) {
			include TEMPLATEPATH . "/golfdeal.php";
			exit();
		}
	}
	function kpsgcdeal_edit_columns( $columns ) {		
		$columns = array( 
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Title",
			"gc_deal_start" => "Valid From",
			"gc_deal_end" => "Valid Until",
			"gc_deal_cost" => "Cost",
			"gc_deal_cart" => "Cart Incl."
			
		);
		
		return $columns;
	}
	
	function kpsgcdeal_custom_columns( $column ) {
		global $post;
		
		switch( $column ) {
			case 'gc_deal_end':  case 'gc_deal_start':
				$custom = get_post_custom();
				echo $custom[ $column ][ 0 ];
				break;		
			case 'gc_deal_cost': 
				$custom = get_post_custom();
				echo "$" . $custom[ $column ][ 0 ];
				break;		
			case 'gc_deal_cart': 
				$custom = get_post_custom();
				echo $custom[ $column ][ 0 ] == 1 ? "Yes" : "No";
				break;										
		}
	}
	
	function init_fields(){
	  add_meta_box("gc_expiration", "Valid From", array(&$this, "gc_deal_expiration"), "kpsgcdeal", "side", "low");
	  add_meta_box("gc_deal_details", "Details", array(&$this, "gc_deal_details"), "kpsgcdeal", "normal", "high");
	}
	 
	function save_details() {
		global $post;
 
    	update_post_meta($post->ID, "gc_deal_start", $_POST["gc_deal_start"]);
    	update_post_meta($post->ID, "gc_deal_end", $_POST["gc_deal_end"]);		
		
    	update_post_meta($post->ID, "gc_deal_holes", $_POST["gc_deal_holes"]);
    	update_post_meta($post->ID, "gc_deal_cart", $_POST["gc_deal_cart"]);		
    	update_post_meta($post->ID, "gc_deal_cost", $_POST["gc_deal_cost"]);		
	}
	 
	function gc_deal_expiration(){
	  global $post;
	  $custom = get_post_custom($post->ID);
	  $deal_start = $custom["gc_deal_start"][0];
	  $deal_end = $custom["gc_deal_end"][0];		  		  
	  ?>
      <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
  	  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
      <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>  
      <script>
      	$(function() {
	      $( "#gc_deal_start" ).datepicker();
		  $( "#gc_deal_end" ).datepicker();
    	});
      </script>
      
	  <div style="float: left; width: 75px; padding: 8px 0 0 0;">
		  <label >Start Date:</label>
	  </div>
	  <div style="float: left; width: 155px; ">
		  <input name="gc_deal_start" id="gc_deal_start" size="10" value="<?php echo $deal_start; ?>" />
	  </div>  
	  <div style="clear: both; line-height: 8px; height: 8px;"></div>
	  
	  <div style="float: left; width: 75px; padding: 8px 0 0 0;">
			<label>End Date:</label>
	  </div>
	  <div style="float: left; width: 155px; ">
			<input name="gc_deal_end" id="gc_deal_end" size="10" value="<?php echo $deal_end; ?>" />
	  </div>
	  <div style="clear: both; line-height: 3px; height: 3px;"></div>
	  <?php
	}
	
	 
	function gc_deal_details(){
	  global $post;
	  $custom = get_post_custom($post->ID);
	  
	  $deal_holes = $custom["gc_deal_holes"][0];
	  if( empty( $deal_holes )) $deal_holes = 18;
	  
	  $deal_cart = $custom["gc_deal_cart"][0];	
	  if( empty( $deal_cart )) $deal_cart = 0;
				  
	  $deal_cost = $custom["gc_deal_cost"][0];		  		  		  
	  ?>
	  <div style="float: left; width: 155px; padding: 1px 0 0 0;">
		  <label >Holes:</label>
	  </div>
	  <div style="float: left; width: 155px; ">
		<?php $checked = $deal_holes == 18 ? " checked " : " "; ?>
		<input type="radio" name="gc_deal_holes" id="18_holes" <?php echo $checked; ?> value="18" /> 
			<label for="18_holes">18</label>&nbsp;&nbsp;&nbsp;
		<?php $checked = $deal_holes == 9 ? " checked " : " "; ?>    
		<input type="radio" name="gc_deal_holes" id="9_holes" <?php echo $checked; ?> value="9" /> <label for="9_holes">9</label>
	  </div>  
	  <div style="clear: both; line-height: 3px; height: 8px;"></div>
	  
	  <div style="float: left; width: 155px; padding: 1px 0 0 0;">
			<label>Cart Included:</label>
	  </div>
	  <div style="float: left; width: 155px; ">
		<?php $checked = (( $deal_cart == 1 ) ? " checked " : " " ); ?>
		<input type="radio" name="gc_deal_cart" id="with_cart" <?php echo $checked; ?> value="1" /> 
			<label for="with_cart">Yes</label>&nbsp;&nbsp;&nbsp;
		<?php $checked = (( intval( $deal_cart ) == 0 ) ? " checked " : " " ); ?>    
		<input type="radio" name="gc_deal_cart" id="without_cart" <?php echo $checked; ?> value="0" /> <label for="without_cart">No</label>
	  </div>
	  <div style="clear: both; line-height: 3px; height: 8px;"></div>
	  
	  <div style="float: left; width: 155px; padding: 8px 0 0 0;">
			<label>Cost</label>
	  </div>
	  <div style="float: left; width: 155px; ">
			$<input name="gc_deal_cost" size="3" value="<?php echo $deal_cost; ?>" />
	  </div>
	  <div style="clear: both; line-height: 3px; height: 3px;"></div>
	  <?php
	}		
}

/*
add_action("manage_posts_custom_column",  "kpsgcdeal_custom_columns" );
		add_filter("manage_edit-kpsgcdeal_columns", "kpsgcdeal_edit_columns" );
 
	

	function kpsgcdeal_edit_columns( $column ) {
		$columns = array( 
			"cb" => "<input type=\"checkbox\" />",
			"title" => "Title",
			"gc_deal_end" => "Valid Until",
			"gc_deal_cost" => "Cost"
		);
		
		return $columns;	
	}
	
	function kpsgcdeal_custom_columns( $columns ) {
			
		global $post;
		
		switch( $column ) {			
			case 'gc_deal_end': 
				echo $column;
				break;				
		}
	}
*/	
	
// Initiate the plugin
add_action("init", "KPS_GreenCardInit");
function KPS_GreenCardInit() { global $ksp_greencard; $ksp_greencard = new KPS_GreenCard(); }


add_shortcode( 'kp_gcdeals', 'kp_gcdeals_shortcode' );
function kp_gcdeals_shortcode( $atts, $content=null ) {
	shortcode_atts( array( ), $atts );
	
	$result = "";
	
	$order = "asc";
	if( $_GET['sortby'] == 'course' && $_GET['order'] == "asc" )
		$order = "desc"; 
	
	$result .= "<div class='$order' style='float: left; width: 550px; font-family:bebas; font-size:2em; color:#8cc63f; padding-bottom:20px;'>" . 
		"<a href='?sortby=course&order=$order'>" . "What's the deal?" . "</a>" . 
		"</div>";
		
	$order = "asc";
	if( $_GET['sortby'] == 'dateposted' && $_GET['order'] == "asc" )
		$order = "desc"; 	
		
	$result .= "<div class='$order' style='float: left; width: 150px; font-family:bebas; font-size:2em; color:#8cc63f; padding-bottom:20px;'>" . 
		"<a href='?sortby=dateposted&order=$order'>" . "Date Posted" . "</a>" . 
		"</div>";	
	
	$order = "asc";
	if( $_GET['sortby'] == 'expiration' && $_GET['order'] == "asc" )
		$order = "desc"; 	
	
	$result .= "<div class='$order' style='float: left; width: 150px; font-family:bebas; font-size:2em; color:#ec1a29; padding-bottom:20px;'>" . 
		"<a href='?sortby=expiration&order=$order'>" . "Expires" . "</a>" . 
		"</div>";		
	
	$deals = query_posts( 'post_type=kpsgcdeal&posts_per_page=-1' );
	$crsList = array(); $temp = array();
	
	switch( $_GET['sortby'] ) {
		case 'course':
			foreach( $deals as $deal ) {
				$temp[ $deal->ID ] = $deal;
				$custom = get_post_custom( $deal->ID );
				$crs = get_the_terms( $deal->ID, 'courses' );
				$course = "";
				foreach( $crs as $id => $course )
					$c = 1; 
				
				$crsList[$deal->ID] = $course->name;					
			}
			$_GET['order'] == 'asc' ? asort( $crsList ) : arsort( $crsList );
			break;	
		case 'dateposted':
			foreach( $deals as $deal ) {
				$temp[ $deal->ID ] = $deal;
				$custom = get_post_custom( $deal->ID );
			
				$crsList[$deal->ID] = strtotime( $custom[ 'gc_deal_start' ][ 0 ] );
			}			
			$_GET['order'] == 'asc' ? asort( $crsList ) : arsort( $crsList );		
			break;
		case 'expiration':
			foreach( $deals as $deal ) {
				$temp[ $deal->ID ] = $deal;
				$custom = get_post_custom( $deal->ID );
			
				$crsList[$deal->ID] = strtotime( $custom[ 'gc_deal_end' ][ 0 ] );
			}			
			$_GET['order'] == 'asc' ? asort( $crsList ) : arsort( $crsList );		
			break;			
		default:
			foreach( $deals as $deal ) {
				$temp[ $deal->ID ] = $deal;				
				$crsList[$deal->ID] = '';
			}
	}
	
	//redo the array indexed by dealid
	$deals = $temp;
	
	foreach( $crsList as $dealid => $val ) {
	//foreach( $deals as $deal ) {
		$deal = $deals[$dealid];
		
		$custom = get_post_custom( $deal->ID );
		
		$image = get_the_post_thumbnail( $deal->ID );
     	$image_url = wp_get_attachment_image_src( get_post_thumbnail_id($deal->ID), array( 100, 100 ) );      
		
		$crs = get_the_terms( $deal->ID, 'courses' );
		$course = "";
		foreach( $crs as $id => $course )
			$c = 1; 
		
		$deal_cart = "With Cart";
		if( empty( $custom["gc_deal_cart"][0] )) $deal_cart = " ";

		//$course = $course[ 0 ];
		$result .= "<div style='float: left; width: 550px; margin-bottom:20px;'>" . 
			"<div style='float: left; width: 210px; margin:right:10px;'>" .
				"<img src='" . $image_url[0] . "' align='top'>" . 
			"</div>" . 
			"<div style='float: left; width: 340px; magin-bottom:10px; '><span style='line-height: 0.95em;font-family:bebas; font-size:2em; color:#666699;'>" .
				"<a href='" . get_permalink( $deal->ID ) . "'>" . $course->name . "</a></span>" . 
				"<span style='font-family:bebas; font-size:2em; color:#8cc63f;'>" . 
					//
					//$custom["gc_deal_holes"][0] . " holes " . $deal_cart . 
					//" $" . $custom["gc_deal_cost"][0] .
					//
				"</span><br><br>" . "<div style=''>" . 
				$deal->post_content . "</div>" . 
			"</div>" . 
			"</div>";
			
		$result .= 	"<div style='float: left; width: 150px; color:#006600; font-family:bebas; font-size:2em;'>" .
			$custom[ 'gc_deal_start' ][ 0 ] .
			"</div>";
		
		$result .= "<div style='float: left; width: 150px; color:#ec1a29; font-family:bebas; font-size:2em;'>" . 
			$custom[ 'gc_deal_end' ][0] .
			"</div>";
		
		$result .= "<div style='clear:both'></div>";		
	}
	return $result;
}