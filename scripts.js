$(document).ready(function() {
  $("body").delegate(".syron_gallery_image input[type='checkbox']", "click", function() {
    var value = $(this).val();
    if ($(this).is(":checked")) {
      $(this).parent().parent().addClass("selected");
      
      // check in hidden
      $("#syron_gallery_images_hidden_fields input").each(function() {
        if (value == $(this).val()) $(this).attr("checked", "checked");
      });
    }
    else {
      $(this).parent().parent().removeClass("selected");
      // check in hidden
      $("#syron_gallery_images_hidden_fields input").each(function() {
        if (value == $(this).val()) $(this).removeAttr("checked");
      });
    }
  });
  
  $("body").delegate(".delete_syron_media_category", "click", function() {
    var data = {
      action: "delete_media_category",
      term_id: $(this).attr("termid")
    };
    var _parent = $(this).parent();
    $.post(ajaxurl, data, function(response) {
      _parent.fadeOut("fast", function() {
        $(this).remove();
      });
    });
  });
  $("body").delegate(".edit_syron_media_category", "click", function() {
    var my_value = $(this).parent().find("input[type='text']").val();
    var id = $(this).attr("termid");
        
    var data = {
      action: "edit_media_category",
      name: my_value,
      term_id: id
    };
    $.post(ajaxurl, data, function(response) {
      var id = response;
      if (id == -1) {
        alert('This album does already exist!');
      } else {
        alert('Updated...');
      }
    });
  });
  $("body").delegate(".add_syron_media_category", "click", function() {
    var my_value = $(".new_syron_media_category").val();
    if (my_value != "") {
      var data = {
        action: "add_media_category",
        name: my_value
      };
      $.post(ajaxurl, data, function(response) {
        var id = response;
        if (id != -1) {
          var string = '<div class="cat">\n';
          string += '<input type="text" size="12" class="current_syron_media_category" value="' + my_value + '" />'
          string += '<input type="button" termid="' + id + '" class="edit_syron_media_category" value="Edit" />'
          string += '<input type="button" termid="' + id + '" class="delete_syron_media_category" value="Delete" />';
          string += '</div>';
          $(".sg_media_category_form .cats").append(string);
          $(".new_syron_media_category").val("");
        } else {
          alert("Exists...");
        }
      });
    }
  });
  
  
  $("#syron_gallery_media_category_select").change(function() {
    var value = $(this).val();

      var data = {
        action: "get_images_from_media_category",
        slug: value
      };
      $.post(ajaxurl, data, function(response) {
        $("#syron_gallery_image_thumbs").html(response);
        
          $("#syron_gallery_images_hidden_fields input:checked").each(function() {
            var value = $(this).val();
            $(".syron_gallery_image input[type='checkbox']").each(function() {
              if ($(this).val() == value) {
                $(this).attr("checked", "checked")
                $(this).parent().parent().addClass("selected"); 
              };
            });
          });
      });
  });
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
});