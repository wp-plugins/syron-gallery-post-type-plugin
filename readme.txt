=== SYRON Post Gallery Plugin ===
Contributors: syron1988 
Tags: post,gallery,simple
Requires at least: 3.0.0
Tested up to: 3.3.1
Stable tag: trunk

Adds the abbility to use a post-type as a gallery. Basically it adds the possibility to add multiple pictures to a post.

== Description ==

= Official Page =
http://syron.se/

= Usage Example Video =
http://syron.se/

= What it does =

After installing the plugin, a new page will be added to the settings on 'wp-admin'. There you have the choice to which post-types you will add the plugin. It currently will even let you add the plugin to attachments (to your media library), but it will not work there!

After you have chosen the post-types, to which you will add the plugin, a new meta box will be visible when adding/editing a post where you can choose the images, which you want to use! 

== Screenshots ==
1. `http://syron.se/files/wordpress/plugins/syron_gallery/pics/image_categories.png`
2. `http://syron.se/files/wordpress/plugins/syron_gallery/pics/media_albums.png`
3. `http://syron.se/files/wordpress/plugins/syron_gallery/pics/plugin_settings.png`
4. `http://syron.se/files/wordpress/plugins/syron_gallery/pics/post.png`

== Change Log ==

= 0.2a =
- Added media categories
- Added a select box when adding a post (it lets you search easier through all your media images)

== Installation ==

1.  Upload the plugin folder to the \'/wp-content/plugins/\' directory, 
2.  Activate the plugin through the \'Plugins\' menu in WordPress.

After setting all up, you can just simple put the following code into The Loop of your template:
`<?php 
  $syron_gallery_images = get_post_meta(get_the_ID(), 'syron_gallery_images', true); 
  foreach ($syron_gallery_images as $image_id) {
    $myimage = get_post($image_id);            
    $att = wp_get_attachment_image($image_id, "thumbnail");
    echo '<a class="sg_img" href="' . $myimage->guid . '" target="_blank" style="margin: 10px; padding: 5px;">' . $att . '</a>';
  }          
?>`