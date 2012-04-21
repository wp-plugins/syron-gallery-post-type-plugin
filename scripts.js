$(document).ready(function() {
  
  $(".syron_gallery_image input[type='checkbox']").click(function() {
    if ($(this).is(":checked")) $(this).parent().parent().addClass("selected");
    else $(this).parent().parent().removeClass("selected");
  });
  
});