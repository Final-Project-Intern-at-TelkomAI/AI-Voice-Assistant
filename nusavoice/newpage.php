<?php
include 'config.php';
session_start();

if (isset($_SESSION["user_id"])) {
  $first_name = $_SESSION["first_name"];
} else {
  header("Location: ./login.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<title>NusaTalk</title>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, width=device-width" />
    <link rel="icon" type="image/ico" href="public/assets--website---mini-project-8-2@2x.png">
    <meta name="Author" content="Mark Jivko">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="Author" content="Mark Jivko">
    <meta name="Keywords" content="js,audio recorder">
    <meta name="Description" content="Recording audio with HTML 5 and JavaScript">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="stylesheet" href="./global.css" />
    <link rel="stylesheet" href="./home.css" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Nunito Sans:wght@400;700&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,700;1,400&display=swap"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap"
    />
    <link rel="canonical" href="https://markjivko.com/tutorials/B3wWIsNHPk4/">
    <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
    <script src="https://markjivko.com/dist/recorder.js"></script>
    <div class="desktop-frame-1440-x-1024">
        <div class="holder">
            <div data-role="controls">
                <button>Record</button>
            </div>
            <div data-role="recordings"></div>
        </div>
      <div class="desktop-frame-1440-x-1024-child"></div>
      <div class="new-chat">
        <div class="new-chat-child"></div>
        <img
          class="material-symbolsadd-icon"
          alt=""
          src="./public/materialsymbolsadd.svg"
        />

      <div class="new-chat1">New Chat</div>
      <script>
        var newChatElement = document.querySelector(".new-chat1");
        newChatElement.addEventListener("click", function () {
          window.location.href = "newpage.php";
        });
      </script>

      </div>
      <div class="coba-asisten-suara"style="margin-left: 15px;">
        <div class="coba-asisten-suara1">Histori 1</div>
        <img
          class="ant-designaudio-filled-icon"
          alt=""
          src="./public/antdesignaudiofilled.svg"
        />
        <img class="vector-icon" alt="" src="./public/vector.svg" style="margin-left: -15px;"/>

        <img class="vector-icon1" alt="" src="./public/vector1.svg" style="margin-left: -15px;"/>

        <img class="vector-icon2" alt="" src="./public/vector2.svg" style="margin-left: -15px;"/>
      </div>
      <div class="histori"style="margin-top: 50px;">
        <div class="histori-child"></div>
        <div class="histori1">Histori 2</div>
        <img
          class="ant-designaudio-filled-icon2"
          alt=""
          src="./public/antdesignaudiofilled.svg"
        />
        <img class="vector-icon" alt="" src="./public/vector.svg" />

        <img class="vector-icon1" alt="" src="./public/vector1.svg" />

        <img class="vector-icon2" alt="" src="./public/vector2.svg" />
      </div>
      <div class="header">
        <div class="header-child"></div>
        <img
          class="iconoirprofile-circle"
          alt=""
          src="./public/iconoirprofilecircle.svg"
          id="iconoirprofileCircle"
          style="cursor: pointer;"
        />

        <div class="nama-pengguna"><?php echo $first_name; ?></div>
      </div>
    </div>

    <div id="frameContainer" class="popup-overlay" style="display: none">
      <img id="logout-icon" class="log-out-icon" alt="" src="./public/logoutt.png" style="width: 150px; height: 100px; cursor: pointer;" />
    </div>

    <script>
      document.querySelector(".coba-asisten-suara").addEventListener("click", function () {
        window.location.href = "home.php";
      });
    </script>

    <script>
      document.getElementById("logout-icon").addEventListener("click", function () {
        window.location.href = "logout.php";
      });
    </script>

    <script>
      var iconoirprofileCircle = document.getElementById("iconoirprofileCircle");
      if (iconoirprofileCircle) {
        iconoirprofileCircle.addEventListener("click", function () {
          var popup = document.getElementById("frameContainer");
          if (!popup) return;
          var popupStyle = popup.style;
          if (popupStyle) {
            popupStyle.display = "flex";
            popupStyle.zIndex = 100;
            popupStyle.backgroundColor = "rgba(113, 113, 113, 0.3)";
            popupStyle.alignItems = "center";
            popupStyle.justifyContent = "center";
          }
          popup.setAttribute("closable", "");
      
          var onClick =
            popup.onClick ||
            function (e) {
              if (e.target === popup && popup.hasAttribute("closable")) {
                popupStyle.display = "none";
              }
            };
          popup.addEventListener("click", onClick);
        });
      }
      </script>
      <script>
            jQuery(document).ready(function () {
                var $ = jQuery;
                var myRecorder = {
                    objects: {
                        context: null,
                        stream: null,
                        recorder: null
                    },
                    init: function () {
                        if (null === myRecorder.objects.context) {
                            myRecorder.objects.context = new (
                                    window.AudioContext || window.webkitAudioContext
                                    );
                        }
                    },
                    start: function () {
                        var options = {audio: true, video: false};
                        navigator.mediaDevices.getUserMedia(options).then(function (stream) {
                            myRecorder.objects.stream = stream;
                            myRecorder.objects.recorder = new Recorder(
                                    myRecorder.objects.context.createMediaStreamSource(stream),
                                    {numChannels: 1}
                            );
                            myRecorder.objects.recorder.record();
                        }).catch(function (err) {});
                    },
                    stop: function (listObject) {
                        if (null !== myRecorder.objects.stream) {
                            myRecorder.objects.stream.getAudioTracks()[0].stop();
                        }
                        if (null !== myRecorder.objects.recorder) {
                            myRecorder.objects.recorder.stop();

                            // Validate object
                            if (null !== listObject
                                    && 'object' === typeof listObject
                                    && listObject.length > 0) {
                                // Export the WAV file
                                myRecorder.objects.recorder.exportWAV(function (blob) {
                                    var url = (window.URL || window.webkitURL)
                                            .createObjectURL(blob);

                                    // Prepare the playback
                                    var audioObject = $('<audio controls></audio>')
                                            .attr('src', url);

                                    // Prepare the download link
                                    var downloadObject = $('<a>&#9660;</a>')
                                            .attr('href', url)
                                            .attr('download', new Date().toUTCString() + '.wav');

                                    // Wrap everything in a row
                                    var holderObject = $('<div class="row"></div>')
                                            .append(audioObject)
                                            .append(downloadObject);

                                    // Append to the list
                                    listObject.append(holderObject);
                                });
                            }
                        }
                    }
                };

                // Prepare the recordings list
                var listObject = $('[data-role="recordings"]');

                // Prepare the record button
                $('[data-role="controls"] > button').click(function () {
                    // Initialize the recorder
                    myRecorder.init();

                    // Get the button state 
                    var buttonState = !!$(this).attr('data-recording');

                    // Toggle
                    if (!buttonState) {
                        $(this).attr('data-recording', 'true');
                        myRecorder.start();
                    } else {
                        $(this).attr('data-recording', '');
                        myRecorder.stop(listObject);
                    }
                });
            });
      </script>
  </body>
</html>
