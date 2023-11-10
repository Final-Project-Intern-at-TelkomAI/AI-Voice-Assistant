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
    <body style="background-color: #1A1841;">
    <link rel="stylesheet" href="./global.css" />
    <link rel="stylesheet" href="./homepage.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap"/>
  </head>
  <body>
    <div class="desktop-frame-1440-x-10241">
      <img class="desktop-frame-1440-x-1024-inner"alt=""src="./public/vector-4.svg"/>
      <img class="assets-website-mini-project1"alt=""src="./public/assets--website---mini-project-26-1@2x.png"/>
      <b class="halo-teman-ayo-mulai-container1"style="color: #fff;">
        <p class="halo-teman1">Halo, teman!</p>
        <p class="halo-teman1">Ayo mulai percakapan.</p>
      </b>
      <div class="landing-page-1-desktop-fram-inner" id="groupContainer1">
        <div class="sign-up-wrapper">
          <b class="sign-up"style="color: #fff;">Mulai</b>
        </div>
      </div>
      <div class="header1">
        <div class="header-item"></div>
        <img class="iconoirprofile-circle1"alt=""src="./public/iconoirprofilecircle.svg" id="iconoirprofileCircle"style="cursor: pointer;"/>
        <div class="nama-pengguna1" style="color: #fff;"><?php echo $first_name; ?></div>
      </div>
      <img class="footer-icon1" alt="" src="./public/footer1.svg" />
    </div>
    <div id="frameContainer" class="popup-overlay" style="display: none">
      <img id="logout-icon" class="log-out-icon" alt="" src="./public/logoutt.png" style="width: 150px; height: 100px; cursor: pointer;" />
    </div>

    <script>
      document.getElementById("logout-icon").addEventListener("click", function () {
        window.location.href = "logout.php";
      });
      var groupContainer1 = document.getElementById("groupContainer1");
      if (groupContainer1) {
        groupContainer1.addEventListener("click", function (e) {
          window.location.href = "./home.php";
        });
      }
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
  </body>
</html>
