=== SYRON Post Gallery Plugin ===
Contributors: syron1988
Donate link:
Tags: post, gallery, post-types, simple
Requires at least: 3.0.0
Tested up to: 3.3.1
Stable tag: 0.1

Adds the abbility to use a post-type as a gallery. Basically it adds the possibility to add multiple pictures to a post.

== Description ==

** Official Page **
http://syron.se/

** Usage Example Video **
http://syron.se/

** What it does **
After installing the plugin, a new page will be added to the settings on /wp-admin/. There you have the choice to which post-types you will add the plugin. It currently will even let you add the plugin to attachments (to your media library), but it will not work there!

After you have chosen the post-types, to which you will add the plugin, a new meta box will be visible when adding/editing a post where you can choose the images, which you want to use! 

** How to use it **
Add the following code to your template and adjust it for your needs.
`<?php 
  $syron_gallery_images = get_post_meta(get_the_ID(), 'syron_gallery_images', true); 
  foreach ($syron_gallery_images as $image_id) {
    $myimage = get_post($image_id);            
    $att = wp_get_attachment_image($image_id, "thumbnail");
    echo '<a class="sg_img" href="' . $myimage->guid . '" target="_blank" style="margin: 10px; padding: 5px;">' . $att . '</a>';
  }          
?>`

== Installation ==
1. Upload the plugin folder to the '/wp-content/plugins/' directory, 
2. Activate the plugin through the 'Plugins' menu in WordPress.

You will then find SYRON Gallery under the Settings section, where you can choose your post-types to which you want to add the plugin.