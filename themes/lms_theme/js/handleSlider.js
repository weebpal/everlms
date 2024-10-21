(function ($, Drupal) {
  'use strict';

  Drupal.handleSilder = Drupal.handleSilder || {};
  Drupal.behaviors.handleSilder = {
	 attach: function (context) {
		Drupal.handleSilder.categorySlider(context);
	 },
  };

  Drupal.handleSilder.categorySlider = function (context) {
	 $(once('handleCategorySlider', '.view-category.view-display-id-block_1', context)).each(function () {
		const _this = $(this);
		const $splide = _this.find('.splide');
		if ($splide.length > 0) {
		  let splide = new Splide('.view-category.view-display-id-block_1 .splide', {
			 type: 'loop',
			 perMove: 1,
			 arrows: true,
			 pagination: false,
			 autoWidth: true,
			 padding: {
				top: 20,
				left: 20,
				bottom: 20,
				right: 20,
			 },
			 gap: 8,
			 autoplay: true,
			 pauseOnHover: false,
		  });
		  splide.mount();
		}
	 });
  }

})(jQuery, Drupal, once);