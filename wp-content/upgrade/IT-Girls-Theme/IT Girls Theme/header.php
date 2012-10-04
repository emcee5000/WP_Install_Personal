<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php wp_title(' | ', true, 'right'); ?><?php bloginfo('name'); ?></title>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" />
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body>
<div id="container">
<div id="header">
<a href="http://itgirls.ischool.syr.edu"><img src="<?php bloginfo('template_directory') ?>/images/logo.png"/></a>
<div id="nav">
<ul>
<?php wp_list_pages("title_li=&depth=1&sort_column=menu_order&sort_order=DESC"); ?>
</ul>
</div><!--/nav-->
</div><!--/header-->
<br class="reset"/>

<?php
global $wp_query;
if( empty($wp_query->post->post_parent) ) {
$parent = $wp_query->post->ID;
} else {
$parent = $wp_query->post->post_parent;
} ?>