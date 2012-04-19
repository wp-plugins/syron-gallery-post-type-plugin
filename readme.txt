=== SYRON Post Gallery Plugin ===
Contributors: syron1988
Tags: post,gallery,post-types
Requires at least: 3.0.0
Tested up to: 3.3.1
Stable tag: 0.1

This plugin will add the abbility to use a post-type as a gallery. Basically it adds the possibility to add multiple pictures to a post.

== Description ==

= What it does =
After installing the plugin, a new page will be added to the settings on /wp-admin/. There you have the choice to which post-types you will add the plugin. It currently will even let you add the plugin to attachments (to your media library), but it will not work there!

After you have chosen the post-types, to which you will add the plugin, a new meta box will be visible when adding/editing a post where you can choose the images, which you want to use! 

== Installation ==
You can either search for the plugin in /wp-admin/ and install it from there or you can just simply download the plugin and install it by uploading it via /wp-admin/

== Frequently Asked Questions ==

None yet!

== Changelog ==

None yet!

== Upgrade Notice ==

None yet!

== How to implement it ==
Add the following code to your template and adjust it for your needs.
`
<?php 
  $syron_gallery_images = get_post_meta(get_the_ID(), 'syron_gallery_images', true); 
  foreach ($syron_gallery_images as $image_id) {
    $myimage = get_post($image_id);            
    $att = wp_get_attachment_image($image_id, "thumbnail");
    echo '<a class="sg_img" href="' . $myimage->guid . '" target="_blank" style="margin: 10px; padding: 5px;">' . $att . '</a>';
  }          
?>
`