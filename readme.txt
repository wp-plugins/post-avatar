==== Post Avatar ====
Contributors: garinungkadol, DMKE
Donate link: 
Tags: post, avatars, images
Requires at least: 2.0.11
Tested up to: 2.7 beta 3
Stable tag: 1.2.5

Choose an avatar from a pre-defined list to include in a post. 

== Description == 
This plugin simplifies including a picture when writing posts by allowing the user to choose from a predefined list of images. The image can be automatically shown on the page or output customized with the use of a template tag in themes. This plugin is similar to Livejournal userpics. Developed with [Dominik Menke](http://WordPress.gaw2006.de).

= Features =
* Easy selection of images in the Write Post screen.

* Scans images in sub-directories of the image option folder.

* Allows the following file types: .jpg, .jpeg, .gif and .png.

* Settings display avatars automatically or through the use of template tags.

* Customize html output of avatars.

* Does not display missing images.


= Changelog =
* Fixed: Incorrect display of css class

* Fixed: Bugs in image display (height/width switched up)

* Fixed: "Cannot modify header information" errors when saving posts when plugin is used in conjunction with search unleashed plugin
	
* Added: Theme developer override option for automatic avatar display
	
* Added: template tag to return post avatar data in an array. 

== Installation ==
1. Download the plugin.

2. Unzip.

3. Upload "post-avatar" directory to your plugin folder (/wp-content/plugins).


4. Activate the plugin from the Plugin Management screen.


5. Set plugin options in Options - Post Avatar. 
	* **Path to Images Folder** - location of your images folder in relation to your WordPress installation.

	* **Show image in Write Post Page** - Place a tick mark if you want to see a thumbnail of the post avatar in the Write Post screen.

	* **Scan the images directory and its sub-directories** - Place a tick mark if you want to list all images including those in sub-directories of the image folder.

	* **Show avatar in post** - Place a tick mark to show avatar automatically on your blog post. Disable to use the template tag.

	These options help you further customize the display of your post avatar

	* **Customize HTML/CSS** - These options help you customize the look of your post avatar
			1. **Before and After HTML** - enter the HTML you want to display before and after the post avatar. 
				Example: Before: <div class="myimage"> / After: </div>
				Output: <div class="myimage"><img src="http://mydomain.com/images/image.jpg" style="border:0" alt="post-title" /></div>

			2. **CSS Class** - enter the name of the css class that you would like to associate with the post avatar image. Can be left blank. 
				Example: The class name is: postimage
				Output: <img class="postimage" src="http://mydomain.com/images/image.jpg" style="border:0" alt="post-title" />


			If you use both the css class and the before and after html you will get the following output:
				<div class="myimage"><img class="postimage" src="http://mydomain.com/images/image.jpg" style="border:0" alt="post-title" /></div>

	* **Others**
			1. **Get image size?** - Turned on by default to determine the image's width and height. If you encounter any getimagesize errors, turn this feature off.

			2. **Show in feeds?** - Turned off by default. Check this option to display post avatars in your RSS feeds.


= Upgrading =

From versions lower than 1.2 down:
If you are upgrading from previous versions of Post Avatar, deactivate and activate the plugin to enable role capabilities.
If you don't do this, even administrators will not be able to select avatars.


= Usage =
**A. UPLOAD IMAGES**
	
Upload the images that you intend to use to the folder defined in the Post Avatar options.


**B. ADDING AN AVATAR TO A POST**

1. To add an image to a post, go to the Post Avatar section.
   For versions older than WordPress 2.5, the post avatar selection box is below the Custom Fields.
   For WordPress 2.5 and 2.6, the selection box is below the Categories.
   In WordPress 2.7 the selection box is below the Excerpt but you can move it to a different location.

2. Select the image name from the list. 

3. Save your entry.


= For Theme Developers =

For improved integration with third-party WordPress themes, Post Avatar has two additional tags to help producing custom output in additional functions. 

**OVERRIDE AUTOMATIC DISPLAY OF POST AVATARS

In case users automatic display of avatars set to on, use the tag:
        &lt;?php gkl&#95;dev&#95;override(true); ?&gt;

Place at the start of your theme's functions.php

**CUSTOM OUTPUT

To produce your own output with post avatar data, use the function:
	&lt;?php gkl&#95;get&#95;postavatar(); ?&gt;

This lets you create a array containing the url to the avatar, image height and width, post title, post id and boolean value to let you know if the getimagesize option has been turned on or not.


Please visit the [Post Avatar Page](http://www.garinungkadol.com/downloads/post-avatar/) for details on customizing the avatar display.


