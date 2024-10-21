(function($, Drupal) {

  Drupal.mobile_menu = Drupal.mobile_menu || {};

  $(document).ready(function () {
      Drupal.mobile_menu.initilizeMobileMenu();
      Drupal.mobile_menu.toggleMobileMenu();
      Drupal.mobile_menu.closeMobileMenuToggle();
  });

  $(window).on("load", function () {
  });

  Drupal.mobile_menu.initilizeMobileMenu = function() {
    if (!$(".navigation.menu--main").find('span.btn-close').length){
      $(".navigation.menu--main").append('<span class="btn-close">x</span>');
    }
    if ($(window).width() > 991)
    {
      $("span.btn-close").css('display', 'none');
    }
  }

  Drupal.mobile_menu.toggleMobileMenu = function(){
    $('.navbar-toggle').click(function(){
      $("body").addClass("mobile-menu-toggled");
      $("#mobile-menu").addClass("show-menu");
    });
  }

  Drupal.mobile_menu.closeMobileMenuToggle = function(){
    $(".close-menu").click(function(){
      $("body").removeClass("mobile-menu-toggled");
      $("#mobile-menu").removeClass("show-menu");

    });

    $(".navigation.menu--main").click(function(e){
      if ($("body").find(".mobile-menu-toggled").length){
        e.stopPropagation();
      }
    });

    $("body").click(function(){
      // console.log(123);
      if ($("body").find(".mobile-menu-toggled").length){
        $("span.btn-close").trigger('click');
      };
    });
  }

})(jQuery, Drupal);
