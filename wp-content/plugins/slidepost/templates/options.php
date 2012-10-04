<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Slidepost: select a resolution</title>
<link rel="stylesheet" href="<?php echo $plugin_url;?>css/options.css" type="text/css" media="screen" />
</head>
<body class="home-page" id="bp-default">
	<div align="center">
		<div id="container">
			<div id="content">
					<h1>Slidepost</h1>
					<h2>Select a screen resolution</h2>
					<ul>
						<li><a href='<?php echo get_permalink( get_option('slidepost_page_slug') ); ?>?res=800-600'>800 x 600 pixels</a></li>
						<li><a href='<?php echo get_permalink( get_option('slidepost_page_slug') ); ?>?res=1024-768'>1024 by 768 pixels</a></li>
						<li><a href='<?php echo get_permalink( get_option('slidepost_page_slug') ); ?>?res=1280-768'>1280 by 768 pixels</a></li>
						<li><a href='<?php echo get_permalink( get_option('slidepost_page_slug') ); ?>?res=1360-768'>1360 by 768 pixels</a></li>
						<li><a href='<?php echo get_permalink( get_option('slidepost_page_slug') ); ?>?res=1280-800'>1280 by 800 pixels</a></li>
						<li><a href='<?php echo get_permalink( get_option('slidepost_page_slug') ); ?>?res=1680-1050'>1680 by 1050 pixels</a></li>
						<li><a href='<?php echo get_permalink( get_option('slidepost_page_slug') ); ?>?res=1920-1080'>1920 by 1080 pixels (HD)</a></li>
					</ul>
			</div>
		</div>
	</div>
</body>
</html>