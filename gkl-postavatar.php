<?php
/*
	Plugin Name: Post Avatar
	Plugin URI: http://www.garinungkadol.com/downloads/post-avatar/
	Description: Attach a picture to posts easily by selecting from a list of uploaded images. Similar to Livejournal Userpics. Developed with <a href="http://wordpress.gaw2006.de">Dominik Menke</a>.
	Author: Vicky Arulsingam
	Version: 1.3.2
	Author URI: http://garinungkadol.com
*/

// Avoid calling page directly
if (eregi(basename(__FILE__),$_SERVER['PHP_SELF'])) {
	echo "Whoops! You shouldn't be doing that.";
	exit;
}

/* PLUGIN and WP-CONTENT directory constants if not already defined */
if ( ! defined( 'WP_PLUGIN_URL' ) )
	define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
if ( ! defined( 'WP_CONTENT_URL' ) )
	define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	
	
/**
 * Load Text-Domain
 */
load_plugin_textdomain( 'gklpa', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/**
 * OPTIONS
 */
$gklpa_siteurl = get_option('siteurl');
$gkl_myAvatarDir = str_replace('/', DIRECTORY_SEPARATOR, ABSPATH . get_option('gklpa_mydir')); // Updated absolute path to images folder (takes into account Win servers)
$gkl_AvatarURL = trailingslashit($gklpa_siteurl) . get_option('gklpa_mydir'); // URL to images folder
$gkl_ShowAvatarInPost = get_option('gklpa_showinwritepage'); // Show image in Write Page?
$gkl_ScanRecursive = get_option('gklpa_scanrecursive'); // Recursive scan of the images?
$gkl_ShowInContent = get_option('gklpa_showincontent'); // Show avatar automatically in content?
$gkl_getsize = get_option('gklpa_getsize'); // Use getimagesize?
$gkl_dev_override = false;

/**
 * Display post avatar within The Loop
 *
 * @param string $class
 * @param string $before
 * @param string $after
 */
function gkl_postavatar($class='', $before='', $after='', $do_what= 'echo') {
	global $post, $allowedposttags;
	
	if (empty($class)) $class  = get_option('gklpa_class');
	if (empty($before)) $before = gkl_unescape_html(stripslashes(get_option('gklpa_before'))); 
	if (empty($after)) $after = gkl_unescape_html(stripslashes(get_option('gklpa_after')));

	# Validation & Sanitization
	$possible_values = array( 'echo', 'return' );
	if ( !in_array( $do_what, $possible_values ) )
		die( 'Invalid value in gkl_postavatar template tag');
	
	$class = wp_kses( $class, $allowedposttags );
	$before = wp_kses( $before, $allowedposttags );
	$after = wp_kses( $after, $allowedposttags );
	if (!empty($class)) $class = ' class="' . $class . '"';
	
	$post_avatar = gkl_get_postavatar($post);
	$avatar_dim = '';

	if (!is_null($post_avatar)) {
		if ($post_avatar['show_image_dim']) {
			$avatar_dim = 'width="' . $post_avatar['image_width'] .'" height="'. $post_avatar['image_height'] .'"';
		}
		
		$post_avatar_text = $before .'<img' .$class . ' src="'. $post_avatar['avatar_url'] .'" '. $avatar_dim . ' alt="'. $post_avatar['post_title']. '" />'. $after ."\n";
		// Show post avatar	
		if( $do_what == 'echo' ) echo $post_avatar_text;
		elseif( $do_what == 'return' ) return $post_avatar_text;
	}
	
}


/**
 * Get post avatar data
 *
 */
function gkl_get_postavatar($post) {
	global $gkl_AvatarURL, $gkl_myAvatarDir, $gkl_getsize;

	$post_avatar = array();
	$post_id = $post->ID;
	$CurrAvatar = get_post_meta($post_id,'postuserpic');
	$CheckAvatar = $gkl_myAvatarDir . $CurrAvatar[0];

	// Return nothing if value is empty or file does not exist
	if ( !empty($CurrAvatar) && file_exists($CheckAvatar) ) {
		$post_title = sanitize_title($post->post_title);
		$CurrAvatarLoc = $gkl_AvatarURL . $CurrAvatar[0];
		

		if ( $CurrAvatarLoc != $gkl_AvatarURL ) {
			$CurrAvatarLoc = str_replace('/', DIRECTORY_SEPARATOR, $gkl_myAvatarDir . ltrim($CurrAvatar[0],'/'));
			if($gkl_getsize) {
				$dim = @getimagesize($CheckAvatar);
			 } else {
			 	$dim[0] = null;
			 	$dim[1] = null;
			 }
			$CurrAvatarLoc = $gkl_AvatarURL . ltrim($CurrAvatar[0],'/');

			// create array of post avatar values			
			$post_avatar = array("avatar_url"=>$CurrAvatarLoc, "show_image_dim"=>$gkl_getsize, "image_height"=>$dim[1], "image_width"=>$dim[0], "post_id"=>$post_id, "post_title"=>$post_title, "image_name"=>ltrim($CurrAvatar[0],'/'));

		}
	} else {
			$post_avatar = null;
	}
	
	return $post_avatar;
}


/**
 * Get list of directory
 *
 * @param string $dir
 * @param boolean $recursive
 * @return array
 */
function gkl_readdir($dir, $recursive = true) {
	global $gkl_myAvatarDir;
	
	// Cut of the myAvatarDir from the output
	$dir2 = $gkl_myAvatarDir .'/';

	// Init
	$array_items = array();

	$handle = @opendir($dir);

	while (false !== ($file = @readdir($handle))) {
		// Bad for recursive to scan the current folder again and again and again...
		// ...also bad to scan the parent folder
		if ( $file != '.' && $file != ".." ) {
			// if is_file
			if (!is_dir($dir .'/'. $file)) {
				$file = $dir .'/'. $file;
				// Cut of the myAvatarDir from the output
				$array_items[] = str_replace($dir2, '', $file);
			} else {
				// if (is_dir && recusive scan) scan dir
				if ($recursive) {
					$array_items = array_merge($array_items, gkl_readdir($dir .'/'. $file, $recursive));
				}
				$file = $dir .'/'. $file;
				// Cut of the myAvatarDir from the output
				$array_items[] = str_replace($dir2, '', $file);
			}
		}
	}
	@closedir($handle);

	// Limit list to only images
	$array_items = preg_grep('/.jpg$|.jpeg$|.gif$|.png$/', $array_items);
	asort($array_items);
	return $array_items;
}


/** 
 * Write Post display for WP 2.3 and below
 *
 */
function gkl_postavatar_dbx_admin() {

	 if ( function_exists('current_user_can') && current_user_can('post_avatars')) {
		global $gkl_myAvatarDir, $gkl_AvatarURL, $gkl_ShowAvatarInPost, $gkl_ScanRecursive;
			
		// Get current post's avatar
		$post_id = esc_attr($_GET['post']);
		$CurrAvatar = get_post_meta($post_id, 'postuserpic');
		$selected = ltrim( $CurrAvatar[0], '/' );
	
		//! Get AvatarList
		if ($gkl_ScanRecursive == 1)
			$recursive = true;
		else
			$recursive = false;
		$AvatarList = gkl_readdir($gkl_myAvatarDir, $recursive);
	?>

		<fieldset id="postavatarfield" class="dbx-box">
			<div class="dbx-handle-wrapper">
				<h3 class="dbx-handle"><?php _e('Post Avatar', 'gklpa'); ?></h3>
			</div>
			<div class="dbx-content-wrapper">
				<div class="dbx-content">
						<?php  gkl_avatar_html($AvatarList, $CurrAvatar, $selected); ?>		
				</div>
			</div>
			</fieldset>
	<?php
	}
}

/** 
 * Write Post display for WP 2.5+
 *
 */
function gkl_postavatar_metabox_admin() {
	 if ( function_exists('current_user_can') && current_user_can('post_avatars')) {
		global $gkl_myAvatarDir, $gkl_AvatarURL, $gkl_ShowAvatarInPost, $gkl_ScanRecursive;
			
		// Get current post's avatar
		$post_id = esc_attr($_GET['post']);
		$CurrAvatar = get_post_meta($post_id, 'postuserpic');
		$selected = ltrim( $CurrAvatar[0], '/' );
	
		//! Get AvatarList
		if ($gkl_ScanRecursive == 1)
			$recursive = true;
		else
			$recursive = false;
		$AvatarList = gkl_readdir($gkl_myAvatarDir, $recursive);
	?>
		<fieldset id="postavatarfield">
			<?php  gkl_avatar_html($AvatarList, $CurrAvatar, $selected); ?>		
		</fieldset>
	<?php
	}
}

/** 
 * Generate html for post avatar display
 *
 */
function gkl_avatar_html($AvatarList, $CurrAvatar, $selected) {
	global $gkl_ShowAvatarInPost, $gklpa_siteurl, $gkl_myAvatarDir, $gkl_AvatarURL
	
?>
				<table cellspacing="3" cellpadding="3" width="100%" align="left">
						<tr valign="top">
							<th width="20%"><?php _e('Select an avatar', 'gklpa'); ?></th>
							<td align="center">
							<?php if ($gkl_ShowAvatarInPost) { ?>
							<a href="#prev" onclick="prevPostAvatar();return false" class="pa"><img src="<?php echo WP_PLUGIN_URL . '/post-avatar/images/prev.png'; ?>" alt="prev" title="" /></a>
	<?php } ?>
		
								<select name="postuserpic" id="postuserpic" onchange="chPostAvatar(this)">
									<option value="no_avatar.png" onclick="chPostAvatar(this)"><?php _e('No Avatar selected', 'gklpa'); ?></option>
	<?php
		foreach ($AvatarList as $file) {
			if ($file == 'no_avatar.png')
				continue;
	
			$checked = ( $file == $selected ) ? ' selected="selected"' : '';
			$oncklick = ( $gkl_ShowAvatarInPost == 1 ) ? ' onclick="chPostAvatar(this)"' : '';
			echo '<option value="/'. $file .'"'. $checked . $oncklick .'>'. $file .'</option>'."\n";
		}
	?>
								</select>
	<?php if ($gkl_ShowAvatarInPost) { ?>
							
							<a href="#next" onclick="nextPostAvatar();return false" class="pa"><img src="<?php echo WP_PLUGIN_URL . '/post-avatar/images/next.png'; ?>" alt="next" title="" /></a>
	<?php } ?>
							</td>
						</tr>
						<?php
		// Display current avatar in Write Post page
		if ( $gkl_ShowAvatarInPost == 1 ) {
			?><tr>
				<th width="20%" align="center">
					<?php _e('Preview', 'gklpa'); ?>
				</th>
				<td align="center">
			<?php
	
				if ( !empty($CurrAvatar) ) {
					if ( file_exists($gkl_myAvatarDir . $CurrAvatar[0]) ) {
						$CurrAvatarLoc = $gkl_AvatarURL . $CurrAvatar[0];
						echo '<img id="postavatar" src="'. $CurrAvatarLoc .'" alt="Avatar" />';
					} else {
						echo '<img id="postavatar" src="'. WP_PLUGIN_URL . '/post-avatar/images/missing_avatar.png" alt="'. __('Avatar Does Not Exist', 'gklpa') .'" />';
					}
				} else {
					echo '<img id="postavatar" src="'. WP_PLUGIN_URL . '/post-avatar/images/no_avatar.png" alt="'. __('No Avatar selected', 'gklpa') .'" />';
				}
	
			?></td>
			</tr><?php
		}
		?>
					</table>
		<input type="hidden" name="postuserpic-key" id="postuserpic-key" value="<?php echo wp_create_nonce('postuserpic') ; ?>" />
<?php
}

/**
 * Update post avatar
 *
 * @param integer $postid
 */
function gkl_avatar_edit($postid) {
	global $gkl_myAvatarDir;

	if( !isset($postid) )
		$postid = ((int) $_POST['post_ID']);

	// authorization
	if ( !current_user_can('edit_post', $postid) )
		return $postid;
		
	// origination and intention: Are we in Write Post?
		if ( !wp_verify_nonce($_POST['postuserpic-key'], 'postuserpic') )
			return $postid;
	
	if(function_exists('esc_attr'))	$meta_value =  esc_attr($_POST['postuserpic']) ;
	else  $meta_value = wp_specialchars($_POST['postuserpic']) ;
	$CheckAvatar = $gkl_myAvatarDir . $meta_value;

	// Verify avatar exists
	if ( !empty($meta_value) && !file_exists($CheckAvatar) ) unset($meta_value);

	if( isset($meta_value) && !empty($meta_value) && $meta_value != 'no_avatar.png' ) {
		delete_post_meta($postid, 'postuserpic');
		add_post_meta($postid, 'postuserpic', $meta_value);
	} else {
		delete_post_meta($postid, 'postuserpic');
	}
}

/**
 * Create Options Page
 *
 */
function postavatar_admin() {
	if ( function_exists('add_options_page') ) {
		add_options_page(__('Post Avatar Options', 'gklpa'), 'Post Avatar', 'manage_options', basename(__FILE__), 'postavatar_admin_form');
	}
}

/**
 * Manage Options Form
 *
 */
function postavatar_admin_form() {
	
	// Add default Post Avatar settings
	add_option('gklpa_mydir', 'wp-content/uploads/icons/', '', 'yes');
	add_option('gklpa_showinwritepage', 1, '', 'yes');
	add_option('gklpa_scanrecursive', 1, '', 'yes');
	add_option('gklpa_showincontent', 1, '', 'yes');
	add_option('gklpa_class', '', '', 'yes');
	add_option('gklpa_before', '&lt;div class=\&quot;postavatar\&quot;&gt;', '', 'yes');
	add_option('gklpa_after', '&lt;/div&gt;', '', 'yes');
	add_option('gklpa_getsize', 1 , '', 'yes');
	add_option('gklpa_showinfeeds', 0, '', 'yes');
	

	$gklpa_mydir = get_option('gklpa_mydir');
	$gklpa_showinwritepage = get_option('gklpa_showinwritepage');
	$gklpa_scanrecursive = get_option('gklpa_scanrecursive');
	$gklpa_showincontent = get_option('gklpa_showincontent');
	$gklpa_class = get_option('gklpa_class');
	$gklpa_before = get_option('gklpa_before');
	$gklpa_after = get_option('gklpa_after');
	$gklpa_getsize = get_option('gklpa_getsize');
	$gklpa_showinfeeds = get_option('gklpa_showinfeeds');

	// Update Post Avatar settings
	if ( isset($_POST['submit']) ) {
	
	   if ( function_exists('current_user_can') && !current_user_can('manage_options') )
	      die(__('Cheatin uh?', 'gklpa'));
	      
		check_admin_referer('gkl_postavatar_form');

		$gklpa_mydir = esc_attr(trailingslashit(rtrim($_POST['gklpa_mydir'], '/')));
		$gklpa_showinwritepage = gkl_validate_checked($_POST['gklpa_showinwritepage']);
		$gklpa_scanrecursive = gkl_validate_checked($_POST['gklpa_scanrecursive']);
		$gklpa_showincontent = gkl_validate_checked($_POST['gklpa_showincontent']);		
		$gklpa_class = sanitize_user($_POST['gklpa_class']); // allow alphanumeric characters only
		$gklpa_before = htmlentities($_POST['gklpa_before']);
		$gklpa_after = htmlentities($_POST['gklpa_after']);
		$gklpa_getsize = gkl_validate_checked($_POST['gklpa_getsize']);
		$gklpa_showinfeeds = gkl_validate_checked($_POST['gklpa_showinfeeds']);
		

		update_option('gklpa_mydir', $gklpa_mydir);
		update_option('gklpa_showinwritepage', $gklpa_showinwritepage);
		update_option('gklpa_scanrecursive', $gklpa_scanrecursive);
		update_option('gklpa_showincontent', $gklpa_showincontent);
		update_option('gklpa_class', $gklpa_class);
		update_option('gklpa_before', $gklpa_before);
		update_option('gklpa_after', $gklpa_after);
		update_option('gklpa_getsize', $gklpa_getsize);
		update_option('gklpa_showinfeeds', $gklpa_showinfeeds);		
	
	}
?>
<div class=wrap>
	<h2><?php _e('Post Avatar Settings', 'gklpa'); ?></h2>
	<form name="gkl_postavatar" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=gkl-postavatar.php&amp;updated=true">
		<input type="hidden" name="gkl_postavatar_options" value="1" /> <?php if ( function_exists('wp_nonce_field') ) wp_nonce_field('gkl_postavatar_form'); ?>

		<h3><?php _e('Default options', 'gklpa'); ?></h3>
		<table class="form-table">
		<tr valign="top">
		<th scope="row"><?php _e('Path to Images Folder:', 'gklpa'); ?></th>
		<td><input name="gklpa_mydir" type="text" id="gklpa_mydir" value="<?php echo $gklpa_mydir; ?>" size="45" /><br />
		<?php _e('You must not leave this field blank. The directory also must exist.', 'gklpa'); ?>
		</td>
		</tr>
		<tr valign="top">
		<th scope="row"><?php _e('Display', 'gklpa'); ?></th>
		<td><input name="gklpa_showinwritepage" type="checkbox" value="1" <?php checked('1', get_option('gklpa_showinwritepage')); ?> /> <?php _e('Show image in Write Post Page?', 'gklpa'); ?><br />
		
		<input name="gklpa_showincontent" type="checkbox" value="1" <?php checked('1', get_option('gklpa_showincontent')); ?> /> <?php _e('Show avatar in post? Disable to use template tag', 'gklpa'); ?>
		
		</td>
		</tr>
		
		
		<tr valign="top">
		<th scope="row"><?php _e('Others', 'gklpa'); ?></th>
		<td><input name="gklpa_scanrecursive" type="checkbox" value="1" <?php checked('1', get_option('gklpa_scanrecursive')); ?> /> <?php _e('Scan the images directory and its sub-directories?', 'gklpa'); ?><br />
		
		<input name="gklpa_getsize" type="checkbox" value="1" <?php checked('1', get_option('gklpa_getsize')); ?> /> <?php _e('Get image dimensions. Disable this feature if you encounter getimagesize errors', 'gklpa'); ?><br />
		
		<input name="gklpa_showinfeeds" type="checkbox" value="1" <?php checked('1', get_option('gklpa_showinfeeds')); ?> /> <?php _e('Check here to display post avatars in your rss feeds', 'gklpa'); ?>
		
		</td>
		</tr>
		</table>
		
		<h3><?php _e('Customize HTML/CSS', 'gklpa'); ?></h3>

		<table class="form-table">
		<tr valign="top">
		<th scope="row"><?php _e('HTML', 'gklpa'); ?></th>
		<td><?php _e('Use this HTML before/after the post avatar image', 'gklpa'); ?><br />
		<input name="gklpa_before" type="text" value="<?php echo stripslashes($gklpa_before); ?>" /> / <input name="gklpa_after" type="text" value="<?php echo stripslashes($gklpa_after); ?>" /><br />
		<?php _e('You can leave this field blank.', 'gklpa'); ?>
		</td>
		</tr>
		
		<tr valign="top">
		<th scope="row"><?php _e('CSS', 'gklpa'); ?></th>
		<td><?php _e('Use this CSS class for the post avatar image', 'gklpa'); ?><br />
		<input name="gklpa_class" type="text" value="<?php echo $gklpa_class; ?>" /><br />
		<?php _e('You can leave this field blank.', 'gklpa'); ?>
		</td>
		</tr>
		</table>
		
		<p class="submit"><input type="submit" name="submit" value="<?php _e('Save Changes', 'gklpa') ?> &raquo;" /></p>

		</form>
</div><?php
}


/**
 * Display html characters
 *
 * @param string $value
 * @return $value
 */
function gkl_unescape_html($value) {
	return str_replace(
		array("&lt;", "&gt;", "&quot;", "&amp;"),
		array("<", ">", "\"", "&"),
		$value);

}

/**
 * Validate checked options
 *
 * @param string $option
 * @return $value
 */
function gkl_validate_checked($option) {

	$value = esc_attr($option);
	if (!empty($value)) 
		$value = 1;
	
	return $value;
}


/**
 * Set capabality "post_avatars" for administrators, editors and authors
 *
 */
function gkl_setcap() {
	
	if (isset($_GET['activate']) && $_GET['activate'] == 'true') {
		$role = get_role('administrator');
		if(!$role->has_cap('post_avatars'))
			$role->add_cap('post_avatars');

		$role = get_role('editor');
		if(!$role->has_cap('post_avatars'))
			$role->add_cap('post_avatars');
			
		$role = get_role('author');
		if(!$role->has_cap('post_avatars'))
			$role->add_cap('post_avatars');
	}
}

/**
 * Allows overriding of automatic template display
 * mainly for theme integration
 */
function gkl_dev_override($override = false) {
	global $gkl_dev_override;

	$gkl_dev_override = $override;
	
}

/**
 * Checks, whether one of two strings are substrings of PHP_SELF
 *
 * @return boolean
 */
function gkl_check_phpself() {
	if (substr_count($_SERVER['PHP_SELF'], '/wp-admin/post.php') == 1 
		|| substr_count($_SERVER['PHP_SELF'], '/wp-admin/page.php') == 1 
		|| substr_count($_SERVER['PHP_SELF'], '/wp-admin/page-new.php') == 1 || substr_count($_SERVER['PHP_SELF'], '/wp-admin/post-new.php') == 1 
		|| substr_count($_SERVER['PHP_SELF'], '/wp-admin/edit.php') == 1)
		return true;
	else
		return false;
}

/**
 * Prints js- and css-code in the admin-head-area
 *
 */
function gkl_admin_head() {
	global $gkl_AvatarURL, $gkl_ShowAvatarInPost, $gklpa_siteurl;

	//! $_SERVER['PHP_SELF'] - only works if installed in root of domain/subdomain. If installed in sub-folder - does not work because $_SERVER['PHP_SELF'] shows up as /foldername/wp-admin/post.php;
	//! Fix: check if PHP_SELF contains substring
	if ( $gkl_ShowAvatarInPost == 1 && gkl_check_phpself() ) {

	//! Created external scriptfile -> so it's easier to extend the script, e.g. with slideshow-effects
?><script type="text/javascript">
//<![CDATA[
var gkl_site = "<?php echo $gklpa_siteurl; ?>";
var gkl_avatar = "<?php echo $gkl_AvatarURL; ?>";
var gkl_avatar_img = "<?php echo WP_PLUGIN_URL . '/post-avatar/images'; ?>";
//]]>
</script>
<script type="text/javascript" src="<?php echo WP_PLUGIN_URL ;?>/post-avatar/head/gkl-postavatar.js"></script>
<?php
	}

	//! Lets design the module a little bit, let it look like the other modules
	if ( gkl_check_phpself() )
		gkl_postavatar_showcss();
}

/*
 * Display css where needed
 */
function gkl_postavatar_showcss() {
	echo '<link rel="stylesheet" type="text/css" href="'. WP_PLUGIN_URL . '/post-avatar/head/gkl-postavatar.css" />';
}


/*
 * Filter to include post avatar in the_content()
 *
 * @param text $content
 * @return text $content
 */
function gkl_postavatar_filter($content) {
	global $post, $gkl_AvatarURL, $gkl_myAvatarDir, $wp_query, $gkl_dev_override;
	
	// Using this to determine if we're in wp-admin or in the site
	// For compatibility with search unleashed plugin
	// or plugins that call add_filter after saving a post
	if (is_null($wp_query->posts)){ 
		return $content; 
	} else {
		if (!$wp_query->is_feed && $gkl_dev_override == 0){
				$post_avatar = gkl_postavatar('', '', '', 'return');
				// Show post avatar		
		}
		$new_content = $post_avatar . $content;
		return $new_content;
	}
}


/*
 * Filter to include post avatar in feeds
 *
 * @param text $content
 * @return text $content
 */
function gkl_postavatar_feed_filter($content) {
	global $post, $wp_query;
	
	$showinfeeds = get_option('gklpa_showinfeeds');
	if($showinfeeds == 1 && $wp_query->is_feed) 
		$post_avatar = gkl_postavatar('', '', '', 'return');

	return $post_avatar . $content;
}

// Hook it up
add_action('admin_menu', 'postavatar_admin');

// Add meta box
add_action('admin_menu', 'postavatar_add_meta_box');
function postavatar_add_meta_box() {
	add_meta_box('postavatardiv', __('Post Avatar', 'gklpa'), 'gkl_postavatar_metabox_admin', 'post');

}

add_action('edit_post', 'gkl_avatar_edit');
add_action('save_post', 'gkl_avatar_edit');
add_action('publish_post', 'gkl_avatar_edit');
add_action('admin_head', 'gkl_admin_head');
add_action('init', 'gkl_setcap');
// Feed filter
add_filter('the_content', 'gkl_postavatar_feed_filter');
// Content filter (automatic display)
if ($gkl_ShowInContent == 1){
	add_filter('the_content', 'gkl_postavatar_filter', 99);
	add_filter('the_excerpt', 'gkl_postavatar_filter', 99);
	add_filter('wp_head', 'gkl_postavatar_showcss', 99);
}
?>