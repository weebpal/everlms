(function ($, Drupal, drupalSettings) {


  'use strict';

  Drupal.quizNotification = Drupal.quizNotification || {};

  Drupal.quizNotification.runOne = true;
  Drupal.quizNotification.redirect_url = '';

  Drupal.behaviors.quizNotification = {
    attach: function (context, settings) {
    }
  };

  $(document).ready(function () {
    Drupal.quizNotification.showConfirm();
    Drupal.quizNotification.countDown();
  })

  Drupal.quizNotification.countDown = function () {
    var $countDownWrapper = $('.count-down-label--wrapper .timer');
    // Update the countdown every second
    if (drupalSettings.start_time && drupalSettings.end_time && $countDownWrapper.length) {
      var start_date = drupalSettings.start_time * 1000;
      var end_date = drupalSettings.end_time * 1000;
      var count_down_time = end_date - start_date;
      var countdown = setInterval(function () {
        if (!Drupal.quizNotification.redirect_url) {
          count_down_time = count_down_time - 1000;
          if (count_down_time <= 0) {
            clearInterval(countdown);
            $countDownWrapper.html(Drupal.t('Countdown expired!'));
            $('.submit-btn').click();
          } else {
            var hours = Math.floor((count_down_time % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((count_down_time % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((count_down_time % (1000 * 60)) / 1000);
            $countDownWrapper.html(hours + "h " + minutes + "m " + seconds + "s");
          }
        } else {
          clearInterval(countdown);
          $countDownWrapper.html(0 + "h " + 0 + "m " + 0 + "s");
        }
      }, 1000); // Update the countdown every second
    }
  }

  $(document).on('ajaxComplete', function () {
    Drupal.quizNotification.showConfirm();
  })

  Drupal.quizNotification.showConfirm = function () {
    var $confirm_submit = $('#confirm_submit input');
    if ($confirm_submit.length) {
      $confirm_submit.click(function (e) {
        e.preventDefault();
        Drupal.quizNotification.confirmPopup();
      })
    }
  }

  Drupal.quizNotification.confirmPopup = function () {
    var data = '<div class="confirm-message"><p>' + Drupal.t('By proceeding you won\'t be able to go back and edit your answers.') + '</p></div>';
    var formDialog = Drupal.dialog(data, {
      dialogClass: 'quiz-confirm-dialog',
      resizable: true,
      closeOnEscape: false,
      width: 500,
      title: Drupal.t('Confirmation'),
      buttons: [
        {
          text: Drupal.t('Confirm'),
          class: 'button--primary button',
          click: function () {
            $('.submit-btn').click();
            $(this).dialog('close');
            Drupal.quizNotification.checkPermission();
          }
        },

        {
          text: Drupal.t('Close'),
          click: function () {
            $(this).dialog('close');
          }
        }
      ],
    });
    formDialog.showModal();
  }

  Drupal.quizNotification.checkPermission = function () {
    // Update the countdown every second
    if (drupalSettings.quiz_id && drupalSettings.uid && drupalSettings.redirect_take_quiz_callback) {
      var permission = setInterval(function () {
        var content = {
          'quiz_id': drupalSettings.quiz_id,
          'uid': drupalSettings.uid,
        };
        $.ajax({
          url: drupalSettings.redirect_take_quiz_callback,
          type: 'POST', // HTTP request method
          contentType: 'application/json', // Set the appropriate content type
          data: JSON.stringify(content), // Data to send (must be a string)
          success: function (response) {
            // Handle the successful response here
            if (response.status == 200) {
              Drupal.quizNotification.redirect_url = response.redirect_url;
              $('#confirm_submit input').attr('disabled', 'disabled');
              clearInterval(permission);
            }
          },
        });
      }, 1000); // Update the countdown every second
    }
  }

})(jQuery, Drupal, drupalSettings);
