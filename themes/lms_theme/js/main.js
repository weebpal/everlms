(function ($, Drupal) {
  'use strict';

  Drupal.main = Drupal.main || {};
  Drupal.behaviors.main = {
	 attach: function (context) {
		Drupal.main.toggleMobile(context);
		 Drupal.main.addClassMenuHeader(context);
	 },
  };
	Drupal.main.addClassMenuHeader = function (context) {
		if ($('#block-lms-theme-account-menu').length > 0) {
			if ($('[data-drupal-link-system-path="user/logout"]').length >0) {
				$('[data-drupal-link-system-path="user/logout"]').addClass('user-logout');
			}
			if ($('[data-drupal-link-system-path="user/loin"]').length >0) {
				$('[data-drupal-link-system-path="user/login"]').addClass('user-login');
			}
		}
	}
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