<?php
/*
Plugin Name: (Un)offical Twitter Widget
Plugin URI: http://blog.artesea.co.uk/2010/01/unofficial-twitter-widget.html
Description: Adds a sidebar widget to display Twitter updates (uses the Javascript <a href="http://twitter.com/goodies/widget_profile">Twitter Profile Widget</a>)
Version: 0.3
Author: Ryan Cullen
Author URI: http://blog.artesea.co.uk/

Based on old twitter code from
http://seanys.com/2007/10/12/twitter-wordpress-widget/

Version 0.3
+ Added the option to set the HEX values for the colours
*/

function widget_artesea_twidget_init() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	function widget_artesea_twidget($args) {

		// "$args is an array of strings that help widgets to conform to
		// the active theme: before_widget, before_title, after_widget,
		// and after_title are the array keys." - These are set up by the theme
		extract($args);

		// These are our own options
		$options         = get_option('widget_artesea_twidget');
		$account         = $options['account']; // Your Twitter account name
		$title           = $options['title'];   // Title in sidebar for widget
		$show            = $options['show'];    // # of Updates to show
		$shellbackground = $options['shellbackground'];
		$shelltext       = $options['shelltext'];
		$tweetbackground = $options['tweetbackground'];
		$tweettext       = $options['tweettext'];
		$links           = $options['links'];
		
		// If any empty use defaults
		if(!$account)         $account         = 'artesea';
		if(!$title)           $title           = 'Twitter Updates';
		if(!$show)            $show            = 5;
		if(!$shellbackground) $shellbackground = '333333';
		if(!$shelltext)       $shelltext       = 'ffffff';
		if(!$tweetbackground) $tweetbackground = '000000';
		if(!$tweettext)       $tweettext       = 'ffffff';
		if(!$links)           $links           = '4aed05';

        // Output
		echo $before_widget ;

		// start
		echo "<div id=\"twitter_div\">".$before_title.$title.$after_title."
<script src=\"http://widgets.twimg.com/j/2/widget.js\" type=\"text/javascript\"></script>
<script type=\"text/javascript\">
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: ".$show.",
  interval: 6000,
  width: 'auto',
  height: 300,
  theme: {
    shell: {
      background: '#".$shellbackground."',
      color: '#".$shelltext."'
    },
    tweets: {
      background: '#".$tweetbackground."',
      color: '#".$tweettext."',
      links: '#".$links."'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: false,
    hashtags: true,
    timestamp: true,
    avatars: false,
    behavior: 'all'
  }
}).render().setUser('".$account."').start();
</script>
</div>";

		// echo widget closing tag
		echo $after_widget;
	}

	// Settings form
	function widget_artesea_twidget_control() {

		// Get options
		$options = get_option('widget_artesea_twidget');
		// options exist? if not set defaults
		if(!$options['account'])         $options['account']         = 'artesea';
		if(!$options['title'])           $options['title']           = 'Twitter Updates';
		if(!$options['show'])            $options['show']            = 5;
		if(!$options['shellbackground']) $options['shellbackground'] = '333333';
		if(!$options['shelltext'])       $options['shelltext']       = 'ffffff';
		if(!$options['tweetbackground']) $options['tweetbackground'] = '000000';
		if(!$options['tweettext'])       $options['tweettext']       = 'ffffff';
		if(!$options['links'])           $options['links']           = '4aed05';		
		if ( !is_array($options) )
			$options = array('account'=>'artesea', 'title'=>'Twitter Updates', 'show'=>'5');

        // form posted?
		if ( $_POST['Twitter-submit'] ) {

			// Remember to sanitize and format use input appropriately.
			$options['account']         = strip_tags(stripslashes($_POST['Twitter-account']));
			$options['title']           = strip_tags(stripslashes($_POST['Twitter-title']));
			$options['show']            = strip_tags(stripslashes($_POST['Twitter-show']));
			$options['shellbackground'] = substr(str_replace('#','',strip_tags(stripslashes($_POST['Twitter-shellbackground']))), 0, 6);
			$options['shelltext']       = substr(str_replace('#','',strip_tags(stripslashes($_POST['Twitter-shelltext']))), 0, 6);
			$options['tweetbackground'] = substr(str_replace('#','',strip_tags(stripslashes($_POST['Twitter-tweetbackground']))), 0, 6);
			$options['tweettext']       = substr(str_replace('#','',strip_tags(stripslashes($_POST['Twitter-tweettext']))), 0, 6);
			$options['links']           = substr(str_replace('#','',strip_tags(stripslashes($_POST['Twitter-links']))), 0, 6);
			update_option('widget_artesea_twidget', $options);
		}

		// Get options for form fields to show
		$account         = htmlspecialchars($options['account'], ENT_QUOTES);
		$title           = htmlspecialchars($options['title'], ENT_QUOTES);
		$show            = htmlspecialchars($options['show'], ENT_QUOTES);
		$shellbackground = htmlspecialchars($options['shellbackground'], ENT_QUOTES);
		$shelltext       = htmlspecialchars($options['shelltext'], ENT_QUOTES);
		$tweetbackground = htmlspecialchars($options['tweetbackground'], ENT_QUOTES);
		$tweettext       = htmlspecialchars($options['tweettext'], ENT_QUOTES);
		$links           = htmlspecialchars($options['links'], ENT_QUOTES);

		// The form fields
		echo '<p><strong>Basic Settings:</strong></p>
<p><label for="Twitter-account">' . __('Account:') . '<br />
	<input id="Twitter-account" name="Twitter-account" type="text" value="'.$account.'" />
</label></p>
<p><label for="Twitter-title">' . __('Title:') . '<br />
	<input id="Twitter-title" name="Twitter-title" type="text" value="'.$title.'" />
</label></p>
<p><label for="Twitter-show">' . __('Show:') . '<br />
	<input id="Twitter-show" name="Twitter-show" type="text" value="'.$show.'" />
</label></p>
<p><strong>' . __('Colours:') . '</strong></p>
<p><label for="Twitter-shellbackground">' . __('Shell Background:') . '<br />
	#<input id="Twitter-shellbackground" name="Twitter-shellbackground" type="text" value="'.$shellbackground.'" />
</label></p>
<p><label for="Twitter-shelltext">' . __('Shell Text:') . '<br />
	#<input id="Twitter-shelltext" name="Twitter-shelltext" type="text" value="'.$shelltext.'" />
</label></p>
<p><label for="Twitter-tweetbackground">' . __('Tweet Background:') . '<br />
	#<input id="Twitter-tweetbackground" name="Twitter-tweetbackground" type="text" value="'.$tweetbackground.'" />
</label></p>
<p><label for="Twitter-tweettext">' . __('Tweet Text:') . '<br />
	#<input id="Twitter-tweettext" name="Twitter-tweettext" type="text" value="'.$tweettext.'" />
</label></p>
<p><label for="Twitter-links">' . __('Links:') . '<br />
	#<input id="Twitter-links" name="Twitter-links" type="text" value="'.$links.'" />
</label></p>
<input type="hidden" id="Twitter-submit" name="Twitter-submit" value="1" />';
	}


	// Register widget for use
	register_sidebar_widget(array('Twitter', 'widgets'), 'widget_artesea_twidget');

	// Register settings for use
	register_widget_control(array('Twitter', 'widgets'), 'widget_artesea_twidget_control');
}

// Run code and init
add_action('widgets_init', 'widget_artesea_twidget_init');
?>