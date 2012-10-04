<?php
/*
	Plugin Name: Slidepost
	Plugin URI: http://www.skyrocketonlinemarketing.com/2011/03/14/slidepost-1-0/
	Description: A simple plugin to display posts as a slideshow.
	Author: Sky Rocket Inc.
	Version: 1.0
	Author URI: http://www.skyrocketonlinemarketing.com/category/wordpress-plugins
*/
?>
<?php 
// create custom plugin settings menu
add_action('admin_menu', 'slidepost_create_menu');

function slidepost_create_menu() {
	//create new top-level menu
	add_options_page(__('Slidepost Settings','menu-slidepost'), __('Slidepost Settings','menu-slidepost'), 'manage_options', 'slidepostsettings', 'slidepost_settings_page');
	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}

function register_mysettings() {
	//register our settings
	register_setting( 'slidepost-settings-group', 'slidepost_categories' );
	register_setting( 'slidepost-settings-group', 'slidepost_order' );
	register_setting( 'slidepost-settings-group', 'slidepost_autoplay' );
	register_setting( 'slidepost-settings-group', 'slidepost_css' );
	register_setting( 'slidepost-settings-group', 'slidepost_template' );
	register_setting( 'slidepost-settings-group', 'slidepost_page_slug' );
	register_setting( 'slidepost-settings-group', 'slidepost_plugin_url' );
	register_setting( 'slidepost-settings-group', 'slidepost_custom_name' );
	register_setting( 'slidepost-settings-group', 'slidepost_custom_logo_url' );
	register_setting( 'slidepost-settings-group', 'slidepost_custom_footer' );
	register_setting( 'slidepost-settings-group', 'slidepost_random_vertical' );
}

function slidepost_settings_page() {
	// build the slide post settings page
	global $page_slug;
	$page_slug = get_option('slidepost_page_slug');
	$plugin_url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
?>
<div class="wrap">
<h2>Slidepost</h2>
<h3><?php if ( get_option('slidepost_page_slug') ) { echo '<a href="'.get_permalink( get_option('slidepost_page_slug') ).'" target="_blank">View slideshow</a></h3>'; } ?>
<form method="post" action="options.php">
    <?php settings_fields( 'slidepost-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><strong>Name</strong><br /><small>Give your slideshow a name</small></th>
        <td><input type="text" size="35" name="slidepost_custom_name" value="<?php echo get_option('slidepost_custom_name'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Logo URL</strong><br /><small>Add a URL to display a custom logo, this will replace the slideshow name. (PNG files with transparency look best.)</small></th>
        <td><input type="text" size="100" name="slidepost_custom_logo_url" value="<?php echo get_option('slidepost_custom_logo_url'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Description text</strong><br /><small>Add text here to describe the slideshow</small></th>
        <td><input type="text" size="100" name="slidepost_custom_footer" value="<?php echo get_option('slidepost_custom_footer'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Category IDs</strong><br /><small>(separate multiples IDs with a comma)</small></th>
        <td><input type="text" size="35" name="slidepost_categories" value="<?php echo get_option('slidepost_categories'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Post order</strong><br /><small>(Ascending, Descending or Random)</small></th>
        <td>
        	<select name="slidepost_order"/>
				<?php 
                $values = array('ASC'=>'Ascending', 'DESC'=>'Decending', 'rand'=>'Random');
                $val = get_option('slidepost_order');
                foreach($values as $v => $n) {
                    $s = ($val == $v) ? " selected='selected'" : null;
                    echo "<option value='$v'$s>$n</option>\n";
                }
                ?>
            </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Delay</strong><br /><small>Select the amount of time (in seconds) each slide is displayed for</small></th>
        <td><input type="text" size="5" name="slidepost_autoplay" value="<?php echo get_option('slidepost_autoplay'); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Page</strong><br /><small>Choose a page for the slideshow display</small></th>
        <td><?php wp_dropdown_pages('name=slidepost_page_slug&selected='.get_option('slidepost_page_slug')); ?></td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Template</strong><br /><small>Choose a template for the slideshow</small></th>
        <td>
        	<select name="slidepost_template"/>
				<?php 
                //$values = array( '/templates/template-normal.php' => 'Normal', '/templates/template-title.php' => 'Title Only', '/templates/template-bpl.php' => 'BPL');
                $values = array( '/templates/template-normal.php' => 'Normal');
                $val = get_option( 'slidepost_template' );
                foreach($values as $v => $n) {
                    $s = ($val == $v) ? " selected='selected'" : null;
                    echo "<option value='$v'$s>$n</option>\n";
                }
                ?>
            </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Style</strong><br /><small>Choose a layout style for the slideshow</small></th>
        <td>
        	<select name="slidepost_css"/>
				<?php 
                //$values = array( 'paper.css' => 'Paper', 'screen.css' => 'Screen',  'bpl.css' => 'BPL');
                $values = array( 'paper.css' => 'Paper', 'screen.css' => 'Screen');
                $val = get_option( 'slidepost_css' );
                foreach($values as $v => $n) {
                    $s = ($val == $v) ? " selected='selected'" : null;
                    echo "<option value='$v'$s>$n</option>\n";
                }
                ?>
            </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Random vertical position</strong><br /><small>If on, this will make the slideshow track along the vertical axis for random posts. It adds a little more interest to the animation. This setting doesn't work so well with the paper style.</small></th>
        <td>
        	<label></label><input <?php if ( get_option('slidepost_random_vertical') == 'on' ) { echo 'checked="checked"'; } ?> type="radio" name="slidepost_random_vertical" value="on"> On</label><br />
        	<label></label><input <?php if ( get_option('slidepost_random_vertical') == 'off' ) { echo 'checked="checked"'; } ?> type="radio" name="slidepost_random_vertical" value="off"> Off</label
        </td>
        </tr>
	</table>
    <input type="hidden" name="slidepost_plugin_url" value="<?php echo $plugin_url; ?>" />
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Settings') ?>" />
    </p>
</form>
<p><a href="http://www.skyrocketonlinemarketing.com/2011/03/14/slidepost-1-0/">Click here </a> to report bugs or request features...</p>
<p>And if you feel like donating to support free software, <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WSVM264W5YDNS">click here...</a></p>
</div>
<?php 
}

add_filter( 'page_template', 'slidepost_page_template' );
function slidepost_page_template( $page_template )
{
	$page_slug = get_option('slidepost_page_slug');
	if ( is_page( $page_slug ) ) {
		$page_template = dirname( __FILE__ ) . get_option('slidepost_template');
	}
	return $page_template;
}
?>
