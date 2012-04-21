<?php
/*
Plugin Name: Syron.se Post Gallery Plugin
Plugin URI: http://syron.se/
Description: Adds the ability to handle a post-type as a gallery!
Version: 0.1a
Author: Robert "syron" Mayer
Author URI: http://syron.se/
License: GPL2
*/
function call_syron_gallery() 
{
    return new syron_gallery();
}
if (is_admin()) {
  add_action('admin_init', 'call_syron_gallery');
  add_action('admin_init', 'syron_gallery_register_settings');
  add_action('admin_menu', 'syron_gallery_option_submenu_page');
}

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
    if (in_array($post_type, $sg_post_types))
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










class syron_gallery {
  private $PREFIX = "syron_gallery";
  private $post_types = array();
  
  public function __construct() {
    $this->post_types = get_option("sg_post_types");
    
    // meta box
    $this->add_styles();
    $this->add_scripts();
    add_action("add_meta_boxes", array(&$this, 'add_meta_boxes'));
    add_action('save_post', array(&$this, 'meta_box_save'));
  } 
  
  public function add_styles() {
    // add style
    wp_register_style($this->PREFIX . '_sheet', plugins_url('style.css', __FILE__));
    wp_enqueue_style($this->PREFIX . '_sheet');
  }
  
  public function add_scripts() {
    // add jquery
    wp_enqueue_script($this->PREFIX . '_jquery', "https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js");
    // add plugin script
    wp_register_script($this->PREFIX . '_script', plugins_url('scripts.js', __FILE__));
    wp_enqueue_script($this->PREFIX . '_script');
  }
  
  public function add_meta_boxes() {
    foreach ($this->post_types as $post_type)
      add_meta_box($this->PREFIX . '_images', __("SYRON GALLERY PLUGIN"), array(&$this, "show_meta_box"), $post_type, "normal", "high");
  }
  
  public function show_meta_box($post) {
    wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );
    
    $myvar = get_post_meta($post->ID, "syron_gallery_images", true);
    $images = $this->get_media($myvar);
    echo '<div id="syron_gallery_images">';
    foreach ($images as $image) {
      if ($image["selected"]) {
        echo '<div class="syron_gallery_image selected">';
        echo '<label>';
        echo '<input type="checkbox" name="syron_gallery_images[]" value="' . $image["ID"] . '" checked>';  
        echo wp_get_attachment_image($image["ID"], "thumbnail");
        echo '</label>';
        echo '</div>';
      }
      else {
          echo '<div class="syron_gallery_image">';
          echo '<label>';
          echo '<input type="checkbox" name="syron_gallery_images[]" value="' . $image["ID"] . '">';  
          //echo '<img src="' . $image["guid"] . '">';
          echo wp_get_attachment_image($image["ID"], "thumbnail");
          echo '</label>';
          echo '</div>';
      }
    }
    echo '<div class="clearfix"></div>';
    echo '</div>';
  }
  
  
  public function meta_box_save($post_id) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
          return;
    
    update_post_meta($post_id, 'syron_gallery_images', $_POST['syron_gallery_images']);
  }

  /*
   *  Is getting all the media files where mime_type = image!
   *  Could be extended with videos!
   */
  private function get_media($selected) {
    global $wpdb;
    
    $query_images_args = array(
        'post_type' => 'attachment', 
        'post_mime_type' =>'image', 
        'post_status' => 'inherit', 
        'posts_per_page' => -1
    );
    
    $query_images = new WP_Query( $query_images_args );
    $images = array();
    foreach ( $query_images->posts as $image) {
      if (in_array($image->ID, $selected))
        $images[] = array("ID" => $image->ID, "guid" => $image->guid, "selected" => 1);
      else
        $images[] = array("ID" => $image->ID, "guid" => $image->guid, "selected" => 0);
    }
    return array_reverse($this->subval_sort($images, "selected"));
  }
  
  /*
   *  Sorting the array!
   */
  private function subval_sort($a,$subkey) {
  	foreach($a as $k=>$v) {
  		$b[$k] = strtolower($v[$subkey]);
  	}
  	asort($b);
  	foreach($b as $key=>$val) {
  		$c[] = $a[$key];
  	}
  	return $c;
  }
}
?>