(function (Drupal, $, drupalSettings) {
  Drupal.lmsLesson = Drupal.lmsLesson || {};

  Drupal.behaviors.lmsLesson = {
    attach: function (context, settings) {
      Drupal.lmsLesson.embedZoom(context);
    },
  };

  Drupal.lmsLesson.embedZoom = function (context) {
    $(once("meetingSDKElement", "#meetingSDKElement", context)).each(
      function () {
        var $zoomMeetingSdk = $('.zoom-meeting-sdk--wrapper');
        let meetingSDKElement = document.getElementById("meetingSDKElement");
        console.log(drupalSettings)
        if (drupalSettings.zoom.role) {
          const client = ZoomMtgEmbedded.createClient();
          var width = $zoomMeetingSdk.width();
          var height = $zoomMeetingSdk.height();
          if (drupalSettings.zoom.role === 'teacher') {
            client.init({
              zoomAppRoot: meetingSDKElement, language: 'en-US',
              isResizable: true,
              customize: {
                video: {
                  viewSizes: {
                    default: {
                      width: width,
                      height: height
                    },
                  }
                }
              }
            }).then(() => {
              client.join({
                sdkKey: drupalSettings.zoom.sdkKey,
                signature: drupalSettings.zoom.signature,
                meetingNumber: drupalSettings.zoom.meetingNumber,
                password: drupalSettings.zoom.password,
                userName: drupalSettings.zoom.userName,
                zak: drupalSettings.zoom.zak,
                success: function (res) {
                  console.log("Meeting joined successfully", res);
                },
                error: function (res) {
                  console.error("Failed to join the meeting", res);
                },
              }).then(() => {
                console.log('Joined successfully')
              }).catch((error) => {
                console.log(error)
              })
            }).catch((error) => {
              console.log(error)
            })
          }
          else if (drupalSettings.zoom.role === 'student') {
            client.init({
              zoomAppRoot: meetingSDKElement, language: 'en-US',
              isResizable: true,
              customize: {
                video: {
                  viewSizes: {
                    default: {
                      width: width,
                      height: height
                    },
                  }
                }
              }
            }).then(() => {
              client.join({
                sdkKey: drupalSettings.zoom.sdkKey,
                signature: drupalSettings.zoom.signature,
                meetingNumber: drupalSettings.zoom.meetingNumber,
                password: drupalSettings.zoom.password,
                userName: drupalSettings.zoom.userName,
                success: function (res) {
                  console.log("Meeting joined successfully", res);
                },
                error: function (res) {
                  console.error("Failed to join the meeting", res);
                },
              }).then(() => {
                console.log('Joined successfully')
              }).catch((error) => {
                console.log(error)
              })
            }).catch((error) => {
              console.log(error)
            })
          }
        }
      }
    );
  };
})(Drupal, jQuery, drupalSettings);
