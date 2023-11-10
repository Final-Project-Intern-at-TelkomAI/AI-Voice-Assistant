<?php
include 'config.php';
session_start();
error_reporting(0);

$registrationSuccess = false; 

if (isset($_POST["signup"])) {
  $first_name = mysqli_real_escape_string($conn, $_POST["signup_first_name"]);
  $last_name = mysqli_real_escape_string($conn, $_POST["signup_last_name"]);
  $email = mysqli_real_escape_string($conn, $_POST["signup_email"]);
  $password = mysqli_real_escape_string($conn, md5($_POST["signup_password"]));
  $cpassword = mysqli_real_escape_string($conn, md5($_POST["signup_cpassword"]));
  $check_email = mysqli_num_rows(mysqli_query($conn, "SELECT email FROM users WHERE email='$email'"));

  if ($password !== $cpassword) {
    echo "<script>alert('Password did not match.');</script>";
  } elseif ($check_email > 0) {
    echo "<script>alert('Email already exists in our database.');</script>";
  } else {
    $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $registrationSuccess = true; 
      $_POST["signup_first_name"] = "";
      $_POST["signup_last_name"] = "";
      $_POST["signup_email"] = "";
      $_POST["signup_password"] = "";
      $_POST["signup_cpassword"] = "";
    } else {
      echo "<script>alert('User registration failed.');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html>
<title>NusaTalk</title>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="initial-scale=1, width=device-width" />
  <link rel="icon" type="image/ico" href="public/assets--website---mini-project-8-2@2x.png">
  <link rel="stylesheet" href="./global.css" />
  <link rel="stylesheet" href="./signup.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito Sans:wght@400;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,700;1,400&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" />
</head>
<body>
  <div class="sign-up-desktop-frame-1440">
    <img class="sign-up-desktop-frame-1440-child" alt="" src="./public/rectangle-103@2x.png"/>
    <form action="" method="post">
      <div class="group-div">
        <div class="group-child3"></div>
        <b class="sign-up1">Sign Up</b>
        <div class="group-child5"></div>
        <div class="group-child6"></div>
        <div class="group-child7"></div>
        <div class="first-name">
          <input type="text" class="first-name1" placeholder="First Name" name="signup_first_name" value="<?php echo $_POST["signup_first_name"]; ?>" required />
        </div>
        <div class="group-child8"></div>
        <div class="group-child9"></div>
        <div class="last-name">
          <input type="text" class="first-name1" placeholder="Last Name" name="signup_last_name" value="<?php echo $_POST["signup_last_name"]; ?>" required />
        </div>
        <div class="email1">
          <input type="email" placeholder="Email" name="signup_email" value="<?php echo $_POST["signup_email"]; ?>" required />
        </div>
        <div class="password1">
          <input type="password" placeholder="Password" name="signup_password" value="<?php echo $_POST["signup_password"]; ?>" required />
        </div>
        <div class="retype-password">
          <input type="password" placeholder="Retype Password" name="signup_cpassword" value="<?php echo $_POST["signup_cpassword"]; ?>" required />
        </div>
        <input type="submit" class="group-child4" id="rectangle1" name="signup" value="Register" style="cursor: pointer;"/>
      </div>
    </form>
  </div>

  <?php
  if ($registrationSuccess) {
  ?>
  <div id="modalContainer" class="popup-overlay">
    <div class="modal">
      <div class="base">
        <div class="master-modal-header">
          <div class="title-description">
            <div class="card-header">
              <img class="avatar-icon" alt="" src="./public/avatar.svg" />
              <img class="arrow-up-circle-icon" alt="" src="./public/arrowupcircle.svg"/>
              <b class="card-title">Message has been sent</b>
              <img class="arrow-up-circle-icon" alt="" src="./public/x.svg" />
            </div>
            <div class="mauris-turpis-augue">
              Thank you for contacting us, and we will respond to your message as soon as possible.
            </div>
          </div>
        </div>
      </div>
      <div class="frame-parent">
        <div class="wrapper">
          <img class="icon" alt="" src="./public/5707664-1@2x.png" />
        </div>
        <div class="title-description-parent">
          <div class="title-description1">
            <div class="card-header">
              <img class="avatar-icon" alt="" src="./public/avatar.svg" />
              <img class="arrow-up-circle-icon" alt="" src="./public/arrowupcircle.svg"/>
              <b class="message-has-been">Registrasi Akun Sukses</b>
              <img class="arrow-up-circle-icon" alt="" src="./public/x.svg" />
            </div>
            <div class="thank-you-for">
              Terima kasih telah mendaftar. Silakan periksa email Anda untuk konfirmasi dan masuk ke akun Anda.
            </div>
          </div>
        </div>
      </div>
      <div class="base1">
        <div class="master-modal-footer">
          <div class="top-button">
            <div class="button">
              <div class="base2">
                <div class="master-anchor-text">
                  <img class="icon1" alt="" src="./public/icon.svg" />
                  <b class="text">Button</b>
                  <img class="icon1" alt="" src="./public/icon1.svg" />
                </div>
              </div>
            </div>
            <div class="button-set" id="popupbuttonSetContainer">
              <div class="button1">
                <div class="base3">
                  <div class="master-button">
                    <img class="icon1" alt="" src="./public/icon2.svg" />
                    <b class="text">Button</b>
                    <img class="icon1" alt="" src="./public/icon3.svg" />
                  </div>
                </div>
              </div>
              <div class="button2">
                <div class="base3">
                  <div class="master-button1">
                    <img class="icon1" alt="" src="./public/icon4.svg" />
                    <b class="text2">Back to Log In</b>
                    <img class="icon1" alt="" src="./public/icon5.svg" />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bottom-button">
            <div class="button1">
              <div class="base3">
                <div class="master-button">
                  <img class="icon1" alt="" src="./public/icon.svg" />
                  <b class="text">Button</b>
                  <img class="icon8" alt="" src="./public/icon3.svg" />
                </div>
              </div>
            </div>
            <div class="button4">
              <div class="base3">
                <div class="master-button">
                  <img class="icon1" alt="" src="./public/icon.svg" />
                  <b class="text">Button</b>
                  <img class="icon1" alt="" src="./public/icon3.svg" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  }
  ?>

  <script>
    <?php
    if ($registrationSuccess) {
    ?>
    var popupbuttonSetContainer = document.getElementById("popupbuttonSetContainer");
    if (popupbuttonSetContainer) {
      popupbuttonSetContainer.addEventListener("click", function (e) {
        window.location.href = "./login.php";
      });
    }
    var rectangle1 = document.getElementById("rectangle1");
    if (rectangle1) {
      rectangle1.addEventListener("click", function () {
        var popup = document.getElementById("modalContainer");
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

        var onClick = popup.onClick || function (e) {
          if (e.target === popup && popup.hasAttribute("closable")) {
            popupStyle.display = "none";
          }
        };
        popup.addEventListener("click", onClick);
      });
    }
    <?php
    }
    ?>
  </script>
</body>
</html>
