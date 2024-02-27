<?php
  /* --------------------------------------------------------------
   $Id: colorbox.js.php 13648 2021-07-29 08:21:12Z Markus $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2019 [www.modified-shop.org]
   --------------------------------------------------------------
   Released under the GNU General Public License
   --------------------------------------------------------------*/
?>
<script>
  $(document).ready(function(){
    $(".cbimages").colorbox({rel:'cbimages', scalePhotos:true, maxWidth: "90%", maxHeight: "90%", fixed: true, close: '<i class="fas fa-times"></i>', next: '<i class="fas fa-chevron-right"></i>', previous: '<i class="fas fa-chevron-left"></i>'});
    $(".iframe").colorbox({iframe:true, width:"780", height:"560", maxWidth: "90%", maxHeight: "90%", fixed: true, close: '<i class="fas fa-times"></i>'});
    $("#print_order_layer").on('submit', function(event) {
      $.colorbox({iframe:true, width:"780", height:"560", maxWidth: "90%", maxHeight: "90%", close: '<i class="fas fa-times"></i>', href:$(this).attr("action") + '&' + $(this).serialize()});
      return false;
    });
  });
  
  jQuery.extend(jQuery.colorbox.settings, {
    current: "<?php echo TEXT_COLORBOX_CURRENT; ?>",
    previous: "<?php echo TEXT_COLORBOX_PREVIOUS; ?>",
    next: "<?php echo TEXT_COLORBOX_NEXT; ?>",
    close: "<?php echo TEXT_COLORBOX_CLOSE; ?>",
    xhrError: "<?php echo TEXT_COLORBOX_XHRERROR; ?>",
    imgError: "<?php echo TEXT_COLORBOX_IMGERROR; ?>",
    slideshowStart: "<?php echo TEXT_COLORBOX_SLIDESHOWSTART; ?>",
    slideshowStop: "<?php echo TEXT_COLORBOX_SLIDESHOWSTOP; ?>"
  });
</script>