<?php
/*
Plugin Name: LZ Scroll Bar
Plugin URI: http://nazmulislam.xyz/plugins/scrollbar-wordpress
Description: This plugin will add a custom scrollbar in your site. You can change your own color and other setting using Option Panel from your Dashboard.
Author: Nazmul Islam
Author URI: http://nazmulislam.xyz/
Version: 1.0
*/


// jQuery from Wordpress
function lz_scrollbar_wp_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'lz_scrollbar_wp_jquery');



// Some Set-up
define('LZ_SCROLL_BAR_WP', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );



// Adding Plugin javascript file
wp_enqueue_script('lz-scrollbar-main', LZ_SCROLL_BAR_WP.'js/jquery.nicescroll.min.js', array('jquery'), '1.0', false);
wp_enqueue_style('lz-scrollbar-css', LZ_SCROLL_BAR_WP.'css/lz-scroll.css');


// Adding menu in wordpress dashboard
function add_lzscrollbar_options_framwrork() {
	add_options_page('Lz Scrollbar Options', 'Lz Scrollbar Options', 'manage_options', 'lzscrollbar-settings','lzscrollbar_options_framwrork');

}
add_action('admin_menu', 'add_lzscrollbar_options_framwrork');


function lz_scrollbar_color_pickr_function( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'lz-script-handle', plugins_url('js/color-pickr.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'lz_scrollbar_color_pickr_function' );


// Default options values
$lzscrollbar_options = array(
	'cursor_color' => '#cc0000',
	'cursor_width' => '10px',
	'cursor_border' => '0px solid #fff',
	'border_radius' => '0px',
	'scroll_speed' => '60',
	'auto_hide_mode' => 'true'
);




if ( is_admin() ) : // Load only if we are viewing an admin page


function lzscrollbar_register_settings() {
	// Register settings and call sanitation functions
	register_setting( 'lzscrollbar_p_options', 'lzscrollbar_options', 'lzscrollbar_validate_options' );
}
add_action( 'admin_init', 'lzscrollbar_register_settings' );


// Store layouts views in array
$auto_hide_mode = array(
	'auto_hide_yes' => array(
		'value' => 'true',
		'label' => 'Active auto hide'
	),
	'auto_hide_no' => array(
		'value' => 'false',
		'label' => 'Deactive auto hide'
	),
);


// Function to generate options page
function lzscrollbar_options_framwrork() {
	global $lzscrollbar_options, $auto_hide_mode;

	if ( ! isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>

	<div class="wrap">

	
	<h2>LZ Scrollbar Option</h2>

	<?php if ( false !== $_REQUEST['updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
	<?php endif; // If the form has just been submitted, this shows the notification ?>

	<form method="post" action="options.php">

	<?php $settings = get_option( 'lzscrollbar_options', $lzscrollbar_options ); ?>
	
	<?php settings_fields( 'lzscrollbar_p_options' ); ?>

	
	<table class="form-table"><!-- Grab a hot cup of coffee, yes we're using tables! -->

		<tr valign="top">
			<th scope="row"><label for="cursor_color">Scroll Bar color</label></th>
			<td>
				<input id="cursor_color" type="text" name="lzscrollbar_options[cursor_color]" value="<?php echo stripslashes($settings['cursor_color']); ?>" class="lz-color-field" /><p class="description">Choice a Scroll bar color for your Scroll bar. You can use here HTML HEX color code.</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="cursor_width">Scroll Bar Width</label></th>
			<td>
				<input id="cursor_width" type="text" name="lzscrollbar_options[cursor_width]" value="<?php echo stripslashes($settings['cursor_width']); ?>" /><p class="description">Enter Scroll bar with here. Please use here px like : 5px</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="cursor_border">Scroll Bar border</label></th>
			<td>
				<input id="cursor_border" type="text" name="lzscrollbar_options[cursor_border]" value="<?php echo stripslashes($settings['cursor_border']); ?>" /><p class="description">Enter your Scroll Bar border here. Border Style should be three part. Make sure you have used correct format. Example: 1px solid #cc0000 or 1px solid blue</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="border_radius">Scroll Bar border radius</label></th>
			<td>
				<input id="border_radius" type="text" name="lzscrollbar_options[border_radius]" value="<?php echo stripslashes($settings['border_radius']); ?>" /><p class="description">You can adjast here your Scroll bar border radius using px. Like: 5px</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="scroll_speed">Scroll Bar Scroll Speed</label></th>
			<td>
				<input id="scroll_speed" type="text" name="lzscrollbar_options[scroll_speed]" value="<?php echo stripslashes($settings['scroll_speed']); ?>" /><p class="description">You can manage your Scroll bar Speed here. default Speed: 300. If you increase your value, the scrolling speed will slower and you decrease value, scrolling speed will faster.</p>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="scroll_speed">Scroll Bar visibility settings</label></th>
			<td>
				<?php foreach($auto_hide_mode as $activate) : ?>
				<input type="radio" id="<?php echo $activate['value']; ?>" name="lzscrollbar_options[auto_hide_mode]" value="<?php esc_attr_e($activate['value']); ?>" <?php checked($settings['auto_hide_mode'], $activate['value']); ?> />
				<lable for="<?php echo $activate['value']; ?>"><?php echo $activate['label']; ?></lable> <br />
				<?php endforeach; ?><p class="description">You can change scroll bar visibily settings here. There are two options for it. Lets learn!
				<ul>
					<li><strong>Activate auto hide:</strong> If you select this option, the scroll bar will hide automaically.</li>
					<li><strong>Deactivate auto hide:</strong> If you select this option, the scroll bar will stay on right.</li>
				</ul></p>
			</td>
		</tr>
		
	</table>

	<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>
	
	
	</form>

	</div>

	<?php
}

function lzscrollbar_validate_options( $input ) {
	global $lzscrollbar_options, $auto_hide_mode;

	$settings = get_option( 'lzscrollbar_options', $lzscrollbar_options );
	
	// We strip all tags from the text field, to avoid vulnerablilties like XSS

	$input['cursor_color'] = wp_filter_post_kses( $input['cursor_color'] );
	$input['cursor_width'] = wp_filter_post_kses( $input['cursor_width'] );
	$input['cursor_border'] = wp_filter_post_kses( $input['cursor_border'] );
	$input['border_radius'] = wp_filter_post_kses( $input['border_radius'] );
	$input['scroll_speed'] = wp_filter_post_kses( $input['scroll_speed'] );
	
	// We select the previous value of the field, to restore it in case an invalid entry has been given
	$prev = $settings['layout_only'];
	// We verify if the given value exists in the layouts array
	if ( !array_key_exists( $input['layout_only'], $auto_hide_mode ) )
		$input['layout_only'] = $prev;

	return $input;
}

endif;  // EndIf is_admin()



function lz_scrollbar_wp_active(){?>
<?php global $lzscrollbar_options; $lzscrollbar_settings = get_option( 'lzscrollbar_options', $lzscrollbar_options ); ?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			  jQuery("html").niceScroll({
				cursorcolor : "<?php echo $lzscrollbar_settings['cursor_color']; ?>",
				cursorwidth : "<?php echo $lzscrollbar_settings['cursor_width']; ?>",
				cursorborder : "<?php echo $lzscrollbar_settings['cursor_border']; ?>",
				cursorborderradius : "<?php echo $lzscrollbar_settings['border_radius']; ?>",
				scrollspeed : "<?php echo $lzscrollbar_settings['scroll_speed']; ?>",
				autohidemode : <?php echo $lzscrollbar_settings['auto_hide_mode']; ?>,
				bouncescroll: true,
				horizrailenabled: false
			  });
		  
		  }); 
	</script>
<?php
}
add_action('wp_head', 'lz_scrollbar_wp_active');

?>