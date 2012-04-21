<?php
function syron_gallery_register_settings() {
  register_setting( 'syron_gallery-group', 'sg_post_types' );
}

function syron_gallery_option_submenu_page() {
	add_submenu_page( 'options-general.php', 'SYRON Gallery', 'SYRON Gallery', 'manage_options', 'syron-gallery-options', 'syron_gallery_option_page' ); 
}

function syron_gallery_option_page() {
	
	echo '<div class="wrap">';
  echo '<h2>SYRON Gallery Plugin Options</h2>';
  
  echo '<div class="sg_information">';
  echo 'Choose in which post types the plugin should work in!';
  echo '</div>';
  
  echo '<form method="post" action="options.php">';
  echo '<div class="sg_form">';
  
  settings_fields( 'syron_gallery-group' );
  do_settings_fields( 'syron_gallery-group' );
  
  /*
  echo '<input type="text" name="post_types" value="' . get_option('post_types') . '"/>';
  */
  $sg_post_types = get_option('sg_post_types');
  $post_types = get_post_types(array("public"=>true));
  foreach ($post_types as $post_type) {
    if ($sg_post_types != null && in_array($post_type, $sg_post_types))
      echo '<label><input name="sg_post_types[]" type="checkbox" value="' . $post_type . '" checked/>' . $post_type . '</label><br />';
    else
      echo '<label><input name="sg_post_types[]" type="checkbox" value="' . $post_type . '" />' . $post_type . '</label><br />';
  }
  
  echo '<p class="submit">';
  echo '<input type="submit" class="button-primary" value="Save Changes" />';
  echo '</p>';
  echo '</div>';
  echo '</form>';
  
  echo '</div>';
}

?>