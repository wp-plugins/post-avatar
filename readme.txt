==== Post Avatar ====
Contributors: garinungkadol
Donate link: 
Tags: post, avatars, images
Requires at least: 2.0
Tested up to: 2.1.2
Stable tag: 1.2.2

Choose an avatar from a pre-defined list to include in a post. 

== Description == 
This plugin simplifies including a picture when writing posts by allowing the user to choose from a predefined list of images. The image can be automatically shown on the page or output customized with the use of a template tag in themes. This plugin is similar to Livejournal userpics.

= Features =
* Easy selection of images in the Write Post screen.

* Scans images in sub-directories of the image option folder.

* Allows the following file types: .jpg, .jpeg, .gif and .png.

* Settings display avatars automatically or through the use of template tags.

* Does not display missing images.


= Changelog =

* Fixed: Wordpress 2.1 incompatibility where posting comments deletes the avatar in the function: gkl_avatar_edit



= Installation =
1. Download the plugin.

2. Unzip.

3. Upload "post-avatar" directory to your plugin folder (/wp-content/plugins).


4. Activate the plugin from the Plugin Management screen.


5. Set plugin options in Options - Post Avatar. 
	* **Path to Images Folder** - location of your images folder in relation to your WordPress installation.

	* **Show image in Write Post Page** - Place a tick mark if you want to see a thumbnail of the post avatar in the Write Post screen.

	* **Scan the images directory and its sub-directories** - Place a tick mark if you want to list all images including those in sub-directories of the image folder.

	* **Show avatar in post** - Place a tick mark to show avatar automatically on your blog post. Disable to use the template tag.


= Usage =
**A. UPLOAD IMAGES**
	Upload the images that you intend to use to the folder defined in the Post Avatar options.

**B. ADDING AN AVATAR TO A POST**
	1. To add an image to a post, go to the Post Avatar section (just below the Save button).

	2. Select the image name from the list. 

	3. Save your entry.

Please visit the [Post Avatar Page] http://www.garinungkadol.com/downloads/post-avatar/ for details on customizing the avatar display.
**C. CUSTOMIZING AVATAR DISPLAY(Optional)**
	**USING CSS** 

	If you set the option "Show avatar in post", images will automatically appear in your blog post. Customize the stylesheet inside "wp-content/plugins/post-avatar/head/" if necessary.


	**USING THE TEMPLATE TAG "gkl_postavatar"**

	To customize the display of the avatar further, be sure to uncheck the option, "Show avatar in post". You can use the template tag: gkl_postavatar. 

	Open up your templates and add the following within The Loop (where the post is displayed):

		<?php if (function_exists('gkl_postavatar')) gkl_postavatar(); ?>

	By default, the plugin will show your image encased within a <div> with the CSS class, "postavatar". You may want to add a style for this in your stylesheet.

	Example:
		<div class="postavatar"><img src="http://www.garinungkadol.com/wp-content/images/icons/messer01.jpg" alt="title of post" style="width:100px; height:100px; border:none;" /></div>


	OTHER OPTIONS:

	You can also define your own CSS-classes by using a query string. By adding this to The Loop:
		<?php if (function_exists('gkl_postavatar')) gkl_postavatar('example', '', ''); ?>

	the result will be this:

		<img class="example" src="http://www.garinungkadol.com/wp-content/images/icons/messer01.jpg" alt="title of post" style="width:100px; height:100px; border:none;" />


	It is also possible to define your own HTML. Then you can use the string-parameters "before" and "after":
		If before = <p class="mypic"> and after = </p> in this example:

		<?php if (function_exists('gkl_postavatar')) gkl_postavatar('', '<p class="mypic">', '</p>'); ?>

	will result in:

		<p class="mypic"><img src="http://www.garinungkadol.com/wp-content/images/icons/messer01.jpg" alt="title of post" style="width:100px; height:100px; border:none;" /></p>

	To show the image without any divs place this in The Loop: 

		<?php if (function_exists('gkl_postavatar')) gkl_postavatar('', '', ''); ?>

	to get:

		<img src="http://www.garinungkadol.com/wp-content/images/icons/messer01.jpg" alt="title of post" style="width:100px; height:100px; border:none;" />



= Support =
Please visit http://www.garinungkadol.com/downloads/post-avatar/ to submit bugs, get help or leave feedback.
