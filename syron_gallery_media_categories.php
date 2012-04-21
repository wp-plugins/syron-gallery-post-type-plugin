<?php
  function register_custom_taxonomies() {
      $labels = array(
          'name' => _x( 'Albums', 'taxonomy general name' ),
          'singular_name' => _x( 'Album', 'taxonomy singular name' ),
          'search_items' =>  __( 'Search Albums' ),
          'all_items' => __( 'All Albums' ),
          'parent_item' => __( 'Parent Album' ),
          'parent_item_colon' => __( 'Parent Album:' ),
          'edit_item' => __( 'Edit Album' ), 
          'update_item' => __( 'Update Album' ),
          'add_new_item' => __( 'Add New Album' ),
          'new_item_name' => __( 'New Album Name' ),
          'menu_name' => __( 'Album' )
      );
      $capabilities = array(
          'manage_terms' => 'nobody',
          'edit_terms' => 'nobody',
          'delete_terms' => 'nobody'
      );
      $args = array(
          'public' => false,
          'hierarchical' => true,
          'labels' => $labels,
          'capabilities' => $capabilities,
          'show_ui' => false,
          'query_var' => 'album',
          'rewrite' => false
      );
      register_taxonomy('media_category', array('attachment'), $args);
  }
  add_action( 'init', 'register_custom_taxonomies', 1);

  function add_media_categories($fields, $post) {
      $categories = get_categories(array('taxonomy' => 'media_category', 'hide_empty' => 0));
      $post_categories = wp_get_object_terms($post->ID, 'media_category', array('fields' => 'ids'));
      $all_cats .= '<ul id="media-categories-list" style="width:500px;">'; 
      foreach ($categories as $category) {
          if (in_array($category->term_id, $post_categories)) {
              $checked = ' checked="checked"';
          } else {
              $checked = '';  
          }
          $option = '<li style="width:240px;float:left;"><input type="checkbox" value="'.$category->category_nicename.'" id="'.$post->ID.'-'.$category->category_nicename.'" name="'.$post->ID.'-'.$category->category_nicename.'"'.$checked.'> ';
          $option .= '<label for="'.$post->ID.'-'.$category->category_nicename.'">'.$category->cat_name.'</label>';
          $option .= '</li>';
          $all_cats .= $option;
      }
      $all_cats .= '</ul>';

      $categories = array('all_categories' => array (
              'label' => __('Album'),
              'input' => 'html',
              'html' => $all_cats
      ));
      return array_merge($fields, $categories);
  }
  add_filter('attachment_fields_to_edit', 'add_media_categories', null, 2);

  function add_image_attachment_fields_to_save($post, $attachment) {
      $categories = get_categories(array('taxonomy' => 'media_category', 'hide_empty' => 0));
      $terms = array();
      foreach($categories as $category) {
          if (isset($_POST[$post['ID'].'-'.$category->category_nicename])) {
              $terms[] = $_POST[$post['ID'].'-'.$category->category_nicename];        
          }
      }
      wp_set_object_terms( $post['ID'], $terms, 'media_category' );
      return $post;
  }
  add_filter('attachment_fields_to_save', 'add_image_attachment_fields_to_save', null , 2);
?>