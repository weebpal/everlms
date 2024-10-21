(function (Drupal, $) {

  $.fn.setValue = function (argument) {
    var $zoomData = $('.field--name-field-zoom-class-data textarea');
    if ($zoomData.length) {
      $zoomData.val(JSON.stringify(argument, null, 2));
    }
    var $zoomLink = $('.field--name-field-zoom-class-link input');
    if ($zoomLink.length) {
      $zoomLink.val(argument.join_url);
    }

  };

})(Drupal, jQuery);
