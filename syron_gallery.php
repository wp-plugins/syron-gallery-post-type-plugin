<?php
/*
Plugin Name: Syron.se Post Gallery Plugin
Plugin URI: http://syron.se/
Description: Adds the ability to handle a post-type as a gallery!
Version: 0.2a
Author: Robert "syron" Mayer
Author URI: http://syron.se/
License: GPL2
*/
include('syron_gallery_settings.php');
include('syron_gallery_media_categories.php');
include('syron_gallery_media_categories_page.php');
include('syron_gallery_functions.php');

function call_syron_gallery() 
{
  return new syron_gallery();
}
if (is_admin()) {
  add_action('admin_init', 'call_syron_gallery');
  add_action('admin_init', 'syron_gallery_register_settings');
  add_action('admin_menu', 'syron_gallery_option_submenu_page');
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
    ?>
    <?php $this->get_media_categories(); ?>
    
    <div class="syron_gallery_categories">
      Choose your album:
      <select name="syron_gallery_media_category_select" id="syron_gallery_media_category_select" onchange="" size="1">
        <option value="-1" selected>** ALL **</option>
        <?php 
          $terms = $this->get_media_categories(); 
          foreach ($terms as $term): 
        ?>
        <option value="<?=$term->slug;?>"><?=$term->name;?></option> 
        <?php 
          endforeach; 
        ?>  
      </select>
      and select your images!
    </div>
    
    <div id="syron_gallery_image_thumbs">      
      <?php foreach($images as $image): $selected = ""; $checked = ""; ?>
        <?php if ($image["selected"]) { $selected = "selected"; $checked = 'checked="checked"'; } ?>
        <div class="syron_gallery_image <?=$selected;?>">
          <label>
            <input type="checkbox" name="syron_gallery_images[]" value="<?=$image["ID"];?>" <?=$checked;?>>
            <?=wp_get_attachment_image($image["ID"], "thumbnail");?>
          </label>
        </div>  
      <?php endforeach; ?>
      <div class="clearfix"></div>
    </div>
    
    <div id="syron_gallery_images_hidden_fields">
      <?php foreach($images as $image): $selected = ""; $checked = ""; ?>
        <?php if ($image["selected"]) { $checked = 'checked="checked"'; } ?>
        <input type="checkbox" name="syron_gallery_images_hidden[]" value="<?=$image["ID"];?>" <?=$checked;?>>
      <?php endforeach; ?>
    </div>
  
    <?php
    // end of function
  }
  
  
  public function meta_box_save($post_id) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
          return;
    
    update_post_meta($post_id, 'syron_gallery_images', $_POST['syron_gallery_images_hidden']);
  }
  
  private function get_media_categories() {
    global $wpdb;
    $terms = get_terms("media_category", array("hide_empty"=>0));
    return $terms;
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
      if ($selected != null && in_array($image->ID, $selected))
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