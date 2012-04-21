<?php
  if (is_admin()) {
    add_action('wp_ajax_add_media_category', 'add_media_category');
    add_action('wp_ajax_delete_media_category', 'delete_media_category');
    add_action('wp_ajax_edit_media_category', 'edit_media_category');
    add_action('wp_ajax_get_images_from_media_category', 'get_images_from_media_category');
  }
  
  $custom_post_type = "media_category";


  function get_images_from_media_category() {
    global $wpdb, $custom_post_type;
    
    $slug = $_POST['slug'];
    $args = array(
      'post_type' => 'attachment',
      'post_mime_type' => 'image',
      'post_status' => 'inherit',
      'posts_per_page' => -1
    );
    
    if ($slug != "-1") {
      $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'post_status' => 'inherit',
        'posts_per_page' => -1,
      	'tax_query' => array(
      		array(
      			'taxonomy' => $custom_post_type,
      			'field' => 'slug',
      			'terms' => $slug
      		)
      	)
      );
    }
    
    $query_images = new WP_Query( $args );
    $images = array();
    foreach ( $query_images->posts as $image ) {
      $images[] = array("ID" => $image->ID, "guid" => $image->guid, "selected" => 0);
    }
    
    foreach ($images as $image) {
      echo '<div class="syron_gallery_image">';
      echo '<label>';
      echo '<input type="checkbox" name="syron_gallery_image[]" value="' . $image["ID"] . '" />';
      echo wp_get_attachment_image($image["ID"], "thumbnail");
      echo '</label>';
      echo '</div>';
    }
    echo '<div class="clearfix"></div>';
    /*
    <?php foreach($images as $image): $selected = ""; $checked = ""; ?>
      <?php if ($image["selected"]) { $selected = "selected"; $checked = 'checked="checked"'; } ?>
      <div class="syron_gallery_image <?=$selected;?>">
        <label>
          <input type="checkbox" name="syron_gallery_images[]" value="<?=$image["ID"];?>" <?=$checked;?>>
          <?=wp_get_attachment_image($image["ID"], "thumbnail");?>
        </label>
      </div>  
    <?php endforeach; ?>
    */
    die();
  }
  
  function get_media($selected) {
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
    return array_reverse(subval_sort($images, "selected"));
  }
  
  /*
   *  Sorting the array!
   */
  function subval_sort($a,$subkey) {
  	foreach($a as $k=>$v) {
  		$b[$k] = strtolower($v[$subkey]);
  	}
  	asort($b);
  	foreach($b as $key=>$val) {
  		$c[] = $a[$key];
  	}
  	return $c;
  }

  

  function edit_media_category() {
    global $wpdb, $custom_post_type;
    $term_id = $_POST['term_id'];
    $name = $_POST['name'];
    
    if (term_exists(create_slug($name), $custom_post_type)) {
      echo -1;
      die();
    }
    
    wp_update_term($term_id, $custom_post_type, array("name"=>$name, "slug"=>create_slug($name)));
    echo 1;
  
    die();
  }
  function delete_media_category() {
    global $wpdb, $custom_post_type;
    $term_id = $_POST['term_id'];
    wp_delete_term( $term_id, $custom_post_type );
  
    die();
  }
  function add_media_category() {
  	global $wpdb, $custom_post_type;

  	$name = $_POST['name'];	  
    // update db & checkboxes
    if (term_exists($name, $custom_post_type)) { 
      echo -1; 
      die(); 
    }
    
    wp_insert_term($name, $custom_post_type);
  
    $terms = get_terms($custom_post_type, array("hide_empty"=>0));
    foreach ( $terms as $term ) {
      if ($term->name == $name) echo $term->term_id;
    }
  
  	die();
  }

  function remove_accent($str)
  {
  $a = array('À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','ß','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','ÿ','Ā','ā','Ă','ă','Ą','ą','Ć','ć','Ĉ','ĉ','Ċ','ċ','Č','č','Ď','ď','Đ','đ','Ē','ē','Ĕ','ĕ','Ė','ė','Ę','ę','Ě','ě','Ĝ','ĝ','Ğ','ğ','Ġ','ġ','Ģ','ģ','Ĥ','ĥ','Ħ','ħ','Ĩ','ĩ','Ī','ī','Ĭ','ĭ','Į','į','İ','ı','Ĳ','ĳ','Ĵ','ĵ','Ķ','ķ','Ĺ','ĺ','Ļ','ļ','Ľ','ľ','Ŀ','ŀ','Ł','ł','Ń','ń','Ņ','ņ','Ň','ň','ŉ','Ō','ō','Ŏ','ŏ','Ő','ő','Œ','œ','Ŕ','ŕ','Ŗ','ŗ','Ř','ř','Ś','ś','Ŝ','ŝ','Ş','ş','Š','š','Ţ','ţ','Ť','ť','Ŧ','ŧ','Ũ','ũ','Ū','ū','Ŭ','ŭ','Ů','ů','Ű','ű','Ų','ų','Ŵ','ŵ','Ŷ','ŷ','Ÿ','Ź','ź','Ż','ż','Ž','ž','ſ','ƒ','Ơ','ơ','Ư','ư','Ǎ','ǎ','Ǐ','ǐ','Ǒ','ǒ','Ǔ','ǔ','Ǖ','ǖ','Ǘ','ǘ','Ǚ','ǚ','Ǜ','ǜ','Ǻ','ǻ','Ǽ','ǽ','Ǿ','ǿ');
  $b = array('A','A','A','A','A','A','AE','C','E','E','E','E','I','I','I','I','D','N','O','O','O','O','O','O','U','U','U','U','Y','s','a','a','a','a','a','a','ae','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','o','u','u','u','u','y','y','A','a','A','a','A','a','C','c','C','c','C','c','C','c','D','d','D','d','E','e','E','e','E','e','E','e','E','e','G','g','G','g','G','g','G','g','H','h','H','h','I','i','I','i','I','i','I','i','I','i','IJ','ij','J','j','K','k','L','l','L','l','L','l','L','l','l','l','N','n','N','n','N','n','n','O','o','O','o','O','o','OE','oe','R','r','R','r','R','r','S','s','S','s','S','s','S','s','T','t','T','t','T','t','U','u','U','u','U','u','U','u','U','u','U','u','W','w','Y','y','Y','Z','z','Z','z','Z','z','s','f','O','o','U','u','A','a','I','i','O','o','U','u','U','u','U','u','U','u','U','u','A','a','AE','ae','O','o');
  return str_replace($a, $b, $str);
  }

  function create_slug($str)
  {
  return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), remove_accent($str)));
  }
?>