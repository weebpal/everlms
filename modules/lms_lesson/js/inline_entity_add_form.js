(function (Drupal, $) {
	Drupal.lmsLessonInline = Drupal.lmsLessonInline || {};

	Drupal.behaviors.lmsLessonInline = {
		attach: function (context, settings) {

		},
	};

	$(document).ready(function () {
		Drupal.lmsLessonInline.setCreateClassCallback();
	});

	$(document).ajaxComplete(function () {
		Drupal.lmsLessonInline.setCreateClassCallback();
	});

	Drupal.lmsLessonInline.setCreateClassCallback = function () {
		var $buttonCreateZoom = $('div.button-create-zoom-class');
		$buttonCreateZoom.each(function () {
			var $button = $(this);
			$button.click(function () {
				var $iefForm = $button.parents('.ief-form');
				if ($iefForm.length) {
					var room_id = '';
					var dateValue = '';
					var dateEndValue = '';
					var $roomClass = $iefForm.find('.zoom-class--wrapper');
					if ($roomClass.length && $roomClass.val()) {
						if($roomClass.val() !== '_none'){
							room_id = $roomClass.val();
						}
					}
					var $dateTimeValueDate = $iefForm.find('input.date-time-value--wrapper.form-date');
					var $dateTimeValueTime = $iefForm.find('input.date-time-value--wrapper.form-time');
					if ($dateTimeValueDate.length && $dateTimeValueTime.length && $dateTimeValueDate.val() && $dateTimeValueTime.val()) {
						dateValue = $dateTimeValueDate.val() + 'T' + $dateTimeValueTime.val();
					}
					var $dateTimeEndValueDate = $iefForm.find('input.date-time-end-value--wrapper.form-date');
					var $dateTimeEndValueTime = $iefForm.find('input.date-time-end-value--wrapper.form-time');
					if ($dateTimeEndValueDate.length && $dateTimeEndValueTime.length && $dateTimeEndValueDate.val() && $dateTimeEndValueTime.val()) {
						dateEndValue = $dateTimeEndValueDate.val() + 'T' + $dateTimeEndValueTime.val();
					}
					if (dateValue && room_id) {
						var ajaxContent = {
							'start_time': dateValue,
							'room_id': room_id,
						};
						$.ajax({
							url: '/lms/lms-lesson/zoom-api-callback',
							type: 'POST', // HTTP request method
							contentType: 'application/json', // Set the appropriate content type
							data: JSON.stringify(ajaxContent), // Data to send (must be a string)
							success: function (response) {
								// Handle the successful response here
								if (response.status == 200) {
									var $zoomData = $iefForm.find('.field--name-field-zoom-class-data textarea');
									if ($zoomData.length) {
										$zoomData.val(JSON.stringify(response.zoom_data, null, 2));
									}
									var $zoomLink = $iefForm.find('.field--name-field-zoom-class-link input');
									if ($zoomLink.length) {
										$zoomLink.val(response.zoom_data.join_url);
									}
								}
							}
						})
					}
				}
			})
		})
	};

})(Drupal, jQuery);
