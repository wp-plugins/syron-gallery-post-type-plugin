<?php
  if (is_admin()) {
    add_action('admin_menu', 'register_syron_media_submenu_page');
  }

  $custom_post_type = "media_category";

  function register_syron_media_submenu_page() {
  	add_submenu_page( 'upload.php', 'Media Albums', 'Media Albums', 'manage_options', 'syron-media-submenu-page', 'syron_media_submenu_page_callback' ); 
  }
  function syron_media_submenu_page_callback() {
    global $wpdb, $custom_post_type;
    $categories = get_categories(array('taxonomy' => $custom_post_type, 'hide_empty' => 0));
  ?>

  <div class="wrap">
    <h2>SYRON Gallery Plugin - Media Upload Categories</h2>
  
    <form class="sg_media_category_form" action="action" method="post">
      <div>
        To edit an album, just simply click the albums title, write your new album name and click edit. (<strong>JavaScript has to be activated!</strong>)
        <hr />
        <div class="cats">
          <?php foreach($categories as $category): ?>
            <?php //print_r($category); ?>
            <div class="cat">
              <input type="text" size="12" class="current_syron_media_category" value="<?=$category->name;?>" />
              <input type="button" termid="<?=$category->term_id?>" class="edit_syron_media_category" value="Edit" />
              <input type="button" termid="<?=$category->term_id?>" class="delete_syron_media_category" value="Delete" />
            </div>
          <?php endforeach; ?>
        </div>
        <div class="clearfix"></div>
        <hr />
        <input type="text" class="new_syron_media_category" /><input type="submit" onclick="return false;" class="add_syron_media_category" value="Add Album" />
      </div>
    </form>
  </div>

  <?php  
  }
?>