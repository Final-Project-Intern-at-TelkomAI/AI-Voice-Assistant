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
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow: auto;
        }
    </style>
    <link rel="icon" type="image/ico" href="public/assets--website---mini-project-8-2@2x.png">
    <link rel="stylesheet" href="./global.css" />
    <link rel="stylesheet" href="./home.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito Sans:wght@400;700&display=swap"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,700;1,400&display=swap"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
    <script src="https://markjivko.com/dist/recorder.js"></script>
  </head>
  <body>
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
        <img class="material-symbolsadd-icon"alt=""src="./public/materialsymbolsadd.svg"/>
      <div class="new-chat1">New Chat</div>
      <div class="coba-asisten-suara">
        <div class="coba-asisten-suara-child"></div>
        <div class="coba-asisten-suara1" id="historiText">Histori 1</div>
        <img class="ant-designaudio-filled-icon" alt="" src="./public/antdesignaudiofilled.svg"/>
        <img class="vector-icon" alt="" src="./public/vector.svg" />
        <img class="vector-icon1" alt="" src="./public/vector1.svg" id="editIcon"/>
        <img class="vector-icon2" alt="" src="./public/vector2.svg" />
        <button id="saveButton" style="display: none;">Simpan</button>
      </div>
      </div>
      <div class="header">
        <div class="header-child"></div>
        <img class="iconoirprofile-circle" alt="" src="./public/iconoirprofilecircle.svg"id="iconoirprofileCircle" style="cursor: pointer;"/>
        <div class="nama-pengguna"><?php echo $first_name; ?></div>
      </div>
      </div>
      <div id="frameContainer" class="popup-overlay" style="display: none">
        <img id="logout-icon" class="log-out-icon" alt="" src="./public/logoutt.png" style="width: 150px; height: 100px; cursor: pointer;" />
      </div>

      <script>
        var newChatElement = document.querySelector(".new-chat1");
        newChatElement.addEventListener("click", function () {
          window.location.href = "newpage.php";
        });

        const historiText = document.getElementById("historiText");
        const editIcon = document.getElementById("editIcon");
        const saveButton = document.getElementById("saveButton");
        editIcon.addEventListener("click", function() {
          // Buat elemen input untuk mengedit teks
          const inputElement = document.createElement("input");
          inputElement.type = "text";
          inputElement.value = historiText.innerText;
          historiText.replaceWith(inputElement);
          saveButton.style.display = "inline";
          inputElement.focus();
          saveButton.addEventListener("click", function() {
            historiText.innerText = inputElement.value;
            inputElement.replaceWith(historiText);
            saveButton.style.display = "none";
          });
          inputElement.addEventListener("blur", function() {
            historiText.innerText = inputElement.value;
            inputElement.replaceWith(historiText);
            saveButton.style.display = "none";
          });
        });

      document.querySelector(".vector-icon2").addEventListener("click", function () {
        var cobaAsistenSuara = document.querySelector(".coba-asisten-suara");
        if (cobaAsistenSuara) {
          cobaAsistenSuara.remove();
        }
      });

      document.getElementById("logout-icon").addEventListener("click", function () {
        window.location.href = "logout.php";
      });

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
                if (null !== listObject
                    && 'object' === typeof listObject
                    && listObject.length > 0) {
                    myRecorder.objects.recorder.exportWAV(function (blob) {
                        var url = (window.URL || window.webkitURL)
                                  .createObjectURL(blob);
                        var audioObject = $('<audio controls></audio>')
                            .attr('src', url);
                        var downloadObject = $('<a>&#9660;</a>')
                            .attr('href', url)
                            .attr('download', new Date().toUTCString() + '.wav');
                        var holderObject = $('<div class="row"></div>')
                            .append(audioObject)
                            .append(downloadObject);
                        listObject.append(holderObject);

                        // Call function to send audio to API
                        sendAudioToAPI(blob);
                    });
                }
            }
        }
    };

    var listObject = $('[data-role="recordings"]');
    $('[data-role="controls"] > button').click(function () {
        myRecorder.init();
        var buttonState = !!$(this).attr('data-recording');
        if (!buttonState) {
            $(this).attr('data-recording', 'true');
            myRecorder.start();
        } else {
            $(this).attr('data-recording', '');
            myRecorder.stop(listObject);
        }
    });

    function sendAudioToAPI(blob) {
        var formData = new FormData();
        formData.append('audio', blob, 'recording.wav');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'upload.php', true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                var response = JSON.parse(xhr.responseText);
                var textResult = response.text;
                var resultElement = $('<div></div>').text('API Result: ' + textResult);
                listObject.append(resultElement);
            } else {
                console.error('Error in API request:', xhr.status, xhr.statusText);
            }
        };

        xhr.onerror = function () {
            console.error('Network error during API request');
        };

        xhr.send(formData);
    }
});
</script>
</body>
</html>