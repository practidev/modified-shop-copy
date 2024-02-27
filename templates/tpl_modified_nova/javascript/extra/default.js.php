<?php
  /* --------------------------------------------------------------
   $Id: default.js.php 15352 2023-07-19 06:29:48Z Markus $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2019 [www.modified-shop.org]
   --------------------------------------------------------------
   Released under the GNU General Public License
   --------------------------------------------------------------*/
?>
<script>
  $('.field_eye').on('click', '.fa-eye, .fa-eye-slash', function() {
    var pass_name = $(this).data('name');
    var pass_state = $("input[name='"+pass_name+"']").attr('type');
    $("input[name='"+pass_name+"']").attr('type', (pass_state == 'text') ? 'password' : 'text');
    $(this).toggleClass("fa-eye fa-eye-slash");    
  });

  $(document).ready(function() {
    <?php if ($_SESSION['customers_status']['customers_status'] == '0') { ?>
    $('body').addClass('admin_mode');
    <?php } ?>
    
    $(".topscroll").click(function(event) {
      event.preventDefault();
      $("html, body").animate({ scrollTop: 0 }, "slow");
      return false;
    });
    
    $(".listing_topscroll").click(function(event) {
      event.preventDefault();
      $("html, body").animate({ scrollTop: $('.listing').offset().top - 120}, "slow");
      return false;
    });

    $(".listing_bottomscroll").click(function(event) {
      event.preventDefault();
      $("html, body").animate({ scrollTop: $('.listing').offset().top + $(".listing").outerHeight() - $(window).height() + 80}, "slow");
      return false;
    });
  });

  $(window).on('load',function () {
    $('.show_rating input').change(function () {
      var $radio = $(this);
      $('.show_rating .selected').removeClass('selected');
      $radio.closest('label').addClass('selected');
    });
    $('.show_rating :radio').each(function() {
      if($(this).attr("checked")){
        $(this).closest('label').addClass('selected');
      }
    });
  });

  function alert(message, title) {
    title = title || "<?php echo TEXT_LINK_TITLE_INFORMATION; ?>";
    $.alertable.alert('<span id="alertable-title"></span><span id="alertable-content"></span>', { 
      html: true 
    });
    $('#alertable-content').html(message);
    $('#alertable-title').html(title);
  }

  $(function() {
    $('#toggle_account').click(function() {
      $('body').addClass('no_scroll');
      $('.toggle_account').addClass('active');
      $('.toggle_overlay').fadeIn('slow');
      $('.toggle_cart').removeClass('active');
      $('.toggle_wishlist').removeClass('active');
      $('.toggle_settings').removeClass('active');
      ac_closing();
      return false;
    });

    $('#toggle_settings').click(function() {
      $('body').addClass('no_scroll');
      $('.toggle_settings').addClass('active');
      $('.toggle_overlay').fadeIn('slow');
      $('.toggle_cart').removeClass('active');
      $('.toggle_wishlist').removeClass('active');
      $('.toggle_account').removeClass('active');
      ac_closing();
      return false;
    });
    
    $('#toggle_filter').click(function() {
      $('body').addClass('no_scroll');
      $('.toggle_filter').addClass('active');
      $('.toggle_overlay').fadeIn('slow');
      $('.toggle_settings').removeClass('active');
      $('.toggle_cart').removeClass('active');
      $('.toggle_wishlist').removeClass('active');
      $('.toggle_account').removeClass('active');
      ac_closing();
      return false;
    });

    $('html').click(function(e){
      var target = $(e.target);
      var parents = target.parents().map(function(){return $(this).attr("class")}).get().join(',');
      parents = ","+parents+",";
      
      if (parents.indexOf(',col_account,') > -1
          || parents.indexOf(',col_cart,') > -1
          || parents.indexOf(',col_wishlist,') > -1
          || parents.indexOf(',col_settings,') > -1
          || parents.indexOf(',listing_filter,') > -1
          )
      {    

      } else {
        $('body').removeClass('no_scroll');
        $('.toggle_account').removeClass('active');
        $('.toggle_overlay').fadeOut('slow');
        $('.toggle_cart').removeClass('active');
        $('.toggle_wishlist').removeClass('active');
        $('.toggle_settings').removeClass('active');
        $('.toggle_filter').removeClass('active');
      }
    });

  });
  <?php if (basename($PHP_SELF) != FILENAME_SHOPPING_CART && !strpos($PHP_SELF, 'checkout')) { ?>
    $(function() {
      $('body').on('click', '#toggle_cart', function() {
        $('body').addClass('no_scroll');
        $('.toggle_cart').addClass('active');
        $('.toggle_overlay').fadeIn('slow');
        $('.toggle_wishlist').removeClass('active');
        $('.toggle_account').removeClass('active');
        $('.toggle_settings').removeClass('active');
        ac_closing();
        return false;
      });
      <?php if (DISPLAY_CART == 'false' && isset($_SESSION['new_products_id_in_cart'])) {
        unset($_SESSION['new_products_id_in_cart']); ?>
        $('body').addClass('no_scroll');
        $('.toggle_cart').addClass('active');
        $('.toggle_overlay').fadeIn('slow');
        timer = setTimeout(function(){
          $('body').removeClass('no_scroll');
          $('.toggle_cart').removeClass('active');
          $('.toggle_overlay').fadeOut('slow');
        }, 3000);
        $('.toggle_cart').mouseover(function() {clearTimeout(timer);});
      <?php } ?>
    });     

    $(function() {
      $('body').on('click', '#toggle_wishlist', function() {
        $('body').addClass('no_scroll');
        $('.toggle_wishlist').addClass('active');
        $('.toggle_overlay').fadeIn('slow');
        $('.toggle_cart').removeClass('active');
        $('.toggle_account').removeClass('active');
        $('.toggle_settings').removeClass('active');
        ac_closing();
        return false;
      });
      <?php if (DISPLAY_CART == 'false' && isset($_SESSION['new_products_id_in_wishlist'])) {
        unset($_SESSION['new_products_id_in_wishlist']); ?>
        $('body').addClass('no_scroll');
        $('.toggle_wishlist').addClass('active');
        $('.toggle_overlay').fadeIn('slow');
        timer = setTimeout(function(){
          $('body').removeClass('no_scroll');
          $('.toggle_wishlist').removeClass('active');
          $('.toggle_overlay').fadeOut('slow');
        }, 3000);
        $('.toggle_wishlist').mouseover(function() {clearTimeout(timer);});
      <?php } ?>
    });     
  <?php } else {
    unset($_SESSION['new_products_id_in_cart']);
    unset($_SESSION['new_products_id_in_wishlist']);
  } ?>
  
  $(function() {
    $('body').on('click', '.toggle_closer', function(event) {
      close_toggle_panel(event);
    });
  });   
  
  $(function() {
      $('#search_short').click(function(event) {
        show_search_field(event);
        $('#inputString').focus();
      });

      $(".toggle_search").on("click", function (event) {
        show_search_field(event);
      });

      $('#search_closer').click(function(event) {
        close_search_field(event);
      });

      var globalWidth = window.innerWidth;

      $(window).resize(function() {
        if (window.innerWidth <= 920) {
          if(globalWidth != window.innerWidth){
            globalWidth = window.innerWidth;
            $(".toggle_search").hide();
          }
        } else {
          $(".toggle_search").show();
        }
      });
  });
 
  function close_search_field(event) {
    event.stopPropagation();
    $(".toggle_search").fadeOut("slow");
    return false;
  }

  function show_search_field(event) {
    $(".toggle_search").fadeIn("slow");
    $('.toggle_account').removeClass('active');
    $('.toggle_settings').removeClass('active');
    $('.toggle_cart').removeClass('active');
    $('.toggle_wishlist').removeClass('active');
    return false;
  }  
  
  function close_toggle_panel(event) {
    $('body').removeClass('no_scroll');
    $('.toggle_cart').removeClass('active');
    $('.toggle_wishlist').removeClass('active');
    $('.toggle_account').removeClass('active');
    $('.toggle_settings').removeClass('active');
    $('.toggle_filter').removeClass('active');
    $('.toggle_overlay').fadeOut('slow');
    return false;
  }    
  
  var keyName = "<?php echo basename($PHP_SELF); ?>";
  var scrollPos = localStorage.getItem(keyName);
  if (parseInt(scrollPos) > 0) {
    localStorage.removeItem(keyName);
    $(window).scrollTop(scrollPos);
  }
  $('body').on('submit', '#gift_coupon, #cart_quantity', function() {
    localStorage.setItem(keyName, $(window).scrollTop());
  });
</script> 
