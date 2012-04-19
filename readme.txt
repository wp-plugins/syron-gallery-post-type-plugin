=== SYRON Post-Type Gallery Plugin  ===

Contributors: syron1988
Tags: post,post-types,gallery
Requires at least: 3.0
Tested up to: 3.3.1
Stable tag: 0.1a

This plugin will add the ability to use post-types as galleries. Not only as galleries, you can add multiple images to a post.


== Description ==

Coming soon...


== Installation ==

You can either go into your /wp-admin/plugins.php, search for the plugin (SYRON Post-Type Gallery Plugin) and install it. Or you can just simply download it and install it by uploading it via /wp-admin/.


== Code Example ==

Place this inside your loop and everything should be going :)

` <?php 
  $syron_gallery_images = get_post_meta(get_the_ID(), 'syron_gallery_images', true); 
  foreach ($syron_gallery_images as $image_id) {
    $myimage = get_post($image_id);            
    $att = wp_get_attachment_image($image_id, "thumbnail");
    echo '<a class="sg_img" href="' . $myimage->guid . '" target="_blank" style="margin: 10px; padding: 5px;">' . $att . '</a>';
  }          
?>`