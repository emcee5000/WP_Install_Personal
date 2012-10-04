<?php
// Variables
$plugin_url = get_option('slidepost_plugin_url');
// Screen resolution options page
$screenres = $_GET['res'];
if ( !isset( $screenres ) ) {
	include_once( 'options.php' );
} else {
// Variables
$cats = get_option('slidepost_categories');
$order = get_option('slidepost_order');
if( $order == 'rand') {
	$rand = 'rand';
	$order = '';
}
$autoplay = get_option('slidepost_autoplay');
$autoplay = $autoplay*1000;
$custom_name = get_option('slidepost_custom_name');
$args = array(
	'posts_per_page' => -1,
	'post_type'  => 'post',
	'order'  => $order,
	'orderby'  => $rand,
	'cat' => $cats
);
// Query posts
query_posts($args);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title('--',true,'right'); bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php echo $plugin_url;?>css/<?php echo get_option('slidepost_css'); ?>" type="text/css" media="screen" title="no title">
<link rel="stylesheet" href="<?php echo $plugin_url;?>css/slidepost.css" type="text/css" media="screen" title="no title">
<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
<script src="http://flesler-plugins.googlecode.com/files/jquery.scrollTo-1.4.2-min.js" type="text/javascript"></script>
<script src="http://flesler-plugins.googlecode.com/files/jquery.serialScroll-1.2.2-min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $plugin_url;?>/js/init.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
jQuery(function( $ ){
	$('#screen').serialScroll({
		target:'#sections',
		items:'li.slidepost',
		axis:'xy',
		easing:'easeOutQuart',
		duration:1000,
		force:true,
		interval:<?php echo $autoplay; ?>,
		step:1,
		onBefore:function( e, elem, $pane, $items, pos ){
			e.preventDefault();
			if ( this.blur )
				this.blur();
		},
		onAfter:function( elem ){
		}
	});	
});
</script>
</head>
<body>
<div id="main">
	<div class="container res-<?php echo $screenres; ?>">
		<?php if ( get_option('slidepost_custom_logo_url') ) { ?>
		<div class="logo-float">
			<img src='<?php echo get_option('slidepost_custom_logo_url'); ?>' />
		</div>
		<?php } else { ?>
		<div class="name-float">
			<h1><?php echo get_option('slidepost_custom_name'); ?></h1>
		</div>
		<?php } ?>
		<div class="footer">
			<h1><?php echo get_option('slidepost_custom_footer'); ?></h1>
		</div>
		<div id="screen">
			<div id="sections">
				<ul class="slidepost">
					<?php if(have_posts()) : while(have_posts()) : the_post();?>
						<li class="slidepost <?php $fallen = rand (0,3); if ( get_option('slidepost_random_vertical') == 'on' ) { if ( $fallen == 1 ) { echo 'fallen1'; } elseif ( $fallen == 2 ) { echo 'fallen2'; } elseif ( $fallen == 3 ) { echo 'fallen3'; } } ?>">
							<div class="block_content">
								<div class="content_area block">
									<div class="block_inside_title">
										<small><?php echo get_the_time('d M Y'); ?><span style="float: right"><?php if ( in_category( "sales" ) ) { echo "Property For Sale"; } else if ( in_category( "rentals-long-term" ) ) { echo "Long Term Rental"; } else if ( in_category( "rentals-short-term" ) ) { echo "Short Term Rental"; } else if ( in_category( "timeshare" ) ) {  echo "Timeshare"; } else { echo "Blog"; } ?></span></small>
										<div class="titleaddress">
											<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php $pltitlearr = explode( ",", get_the_title() ); echo $pltitlearr[0]; ?></a></h2>
											<h3><?php $pladdressarr = array_slice($pltitlearr, 1); foreach ( $pladdressarr as &$value) { $pladdress = $pladdress.$value.','; } echo substr( $pladdress, 1, -1 ); $pladdress = "";?></h3>
										</div>
										<div class="pricedetailsviewing">
											<h2><?php echo "$".number_format( get_post_meta( $post->ID, 'price', true),0 ); ?></h2>
											<h3><?php if (get_post_meta($post->ID, 'rentalprice', true)) { /* Short-term rentals 1 daily rate */ echo "USD per day"; } else if (get_post_meta($post->ID, 'timeshare', true)) { /* Timeshare */ } else if (get_post_meta($post->ID, 'longtermrental', true)) { /* Long-term rentals */ echo "USD per month"; } else 	if (get_post_meta($post->ID, 'summerrental', true)) { /* Short-term rentals with summer and winter rates */ $summerrental = get_post_meta($post->ID, 'summerrental', true); $summerrental = number_format($summerrental, 0); $winterrental = get_post_meta($post->ID, 'winterrental', true); $winterrental = number_format($winterrental, 0); echo "USD per day<br />(Summer: $$summerrental)"; } else if (get_post_meta($post->ID, 'price', true)) { echo "USD"; } ?></h3>
										</div>
										<div style="clear: both"></div>
										<div class="separator"></div>
									</div>
									<div class="block_inside_content">
										<?php the_content(); ?>
									</div>
								</div>
							</div>
						</li>
					<?php endwhile ?>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<?php } ?>