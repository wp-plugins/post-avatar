==== Post Avatar ====
Contributors: garinungkadol, DMKE
Donate link: 
Tags: post, avatars, images
Requires at least: 2.0
Tested up to: 2.3
Stable tag: 1.2.3

Choose an avatar from a pre-defined list to include in a post. 

== Description == 
This plugin simplifies including a picture when writing posts by allowing the user to choose from a predefined list of images. The image can be automatically shown on the page or output customized with the use of a template tag in themes. This plugin is similar to Livejournal userpics. Developed with [Dominik Menke](http://wordpress.gaw2006.de).

= Features =
* Easy selection of images in the Write Post screen.

* Scans images in sub-directories of the image option folder.

* Allows the following file types: .jpg, .jpeg, .gif and .png.

* Settings display avatars automatically or through the use of template tags.

* Customize html output of avatars.

* Does not display missing images.


= Changelog =
* Added: Role capabilities to allow only Administrators, Editors and Authors to post avatars

* Added: Additional parameters to set default avatar styles in options page

* Added: Display avatar in post excerpts

* Added: Option to enable/disable getimagesize

* Fixed: Improved user input validation

* Fixed: Removed post avatar display from feeds

* Fixed: Bug that displayed avatar twice in excerpts


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

	* **Advanced Options** - These options help you customize the look of your post avatar
			1. **Before and After HTML** - enter the HTML you want to display before and after the post avatar. 
				Example: Before: <div class="myimage"> / After: </div>
				Output: <div class="myimage"><img src="http://mydomain.com/images/image.jpg" style="border:0" alt="post-title" /></div>

			2. **CSS Class** - enter the name of the css class that you would like to associate with the post avatar image. Can be left blank. 
				Example: The class name is: postimage
				Output: <img class="postimage" src="http://mydomain.com/images/image.jpg" style="border:0" alt="post-title" />

			If you use both the css class and the before and after html you will get the following output:
				<div class="myimage"><img class="postimage" src="http://mydomain.com/images/image.jpg" style="border:0" alt="post-title" /></div>
				
			3. **Get image size?** - Turned on by default to determine the image's width and height. If you encounter any getimagesize errors, turn this feature off.


= Upgrading =

If you are upgrading from previous versions of Post Avatar, deactivate and activate the plugin to enable role capabilities.
If you don't do this, even administrators will not be able to select avatars.


= Usage =
**A. UPLOAD IMAGES**
	
Upload the images that you intend to use to the folder defined in the Post Avatar options.


**B. ADDING AN AVATAR TO A POST**

1. To add an image to a post, go to the Post Avatar section (just below the Save button).

2. Select the image name from the list. 

3. Save your entry.

Please visit the [Post Avatar Page](http://www.garinungkadol.com/downloads/post-avatar/) for details on customizing the avatar display.