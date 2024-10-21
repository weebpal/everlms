(function ($, Drupal) {
  'use strict';

  Drupal.main = Drupal.main || {};
  Drupal.behaviors.main = {
	 attach: function (context) {
		Drupal.main.toggleMobile(context);
	 },
  };

  Drupal.main.toggleMobile = function (context) {
	 const $header = $(once('MobileMenuToggle', '.header', context));
	 if ($header.length > 0) {
		const $section = $header.find('.section-mobile-menu');
		const $btn_toggle = $section.find('.toggle-mobile-menu');
		$btn_toggle.click(function () {
		  $section.toggleClass('active');
		  $('body').toggleClass('no-scrollable');
		})

	 }
  }

})(jQuery, Drupal, once);