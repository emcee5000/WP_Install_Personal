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
<title><?php $page_data = get_page ( get_option('slidepost_page_slug') ); echo $page_data->post_title; echo ' &#8212; '; bloginfo('name'); ?></title>
<link rel="stylesheet" href="<?php echo $plugin_url;?>css/<?php echo get_option('slidepost_css'); ?>" type="text/css" media="screen" title="no title">
<link rel="stylesheet" href="<?php echo $plugin_url;?>css/slidepost.css" type="text/css" media="screen" title="no title">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
<script src="http://flesler-plugins.googlecode.com/files/jquery.scrollTo-1.4.2-min.js" type="text/javascript"></script>
<script src="http://flesler-plugins.googlecode.com/files/jquery.serialScroll-1.2.2-min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $plugin_url;?>js/init.js" type="text/javascript" charset="utf-8"></script>
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
										<div class="title">
											<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title() ?></a></h2>
										</div>
										<div style="clear: both"></div>
										<div class="separator"></div>
									</div>
									<div class="block_inside_content">
										<?php the_content(); ?>
									</div>
								</div>
								<div style="clear: both;"></div>
								<div class="block_end"></div>
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