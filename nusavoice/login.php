<?php

include 'config.php';
session_start();
error_reporting(0);

if (isset($_POST["signin"])) {
  $email = mysqli_real_escape_string($conn, $_POST["email"]);
  $password = mysqli_real_escape_string($conn, md5($_POST["password"]));
  $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' AND password='$password'");

  if (mysqli_num_rows($check_email) > 0) {
    $row = mysqli_fetch_assoc($check_email);
    $_SESSION["user_id"] = $row['id'];

    $user_data = mysqli_query($conn, "SELECT first_name FROM users WHERE id = " . $row['id']);
    $user = mysqli_fetch_assoc($user_data);
    $_SESSION["first_name"] = $user['first_name'];

    header("Location: ./homepage.php"); 
  } else {
    echo "<script>alert('Login details are incorrect. Please try again.');</script>";
  }
}
?>

<!DOCTYPE html>
<html>
<title>NusaTalk</title>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="initial-scale=1, width=device-width" />
  <link rel="icon"type="image/ico"href="public/assets--website---mini-project-8-2@2x.png">
  <link rel="stylesheet" href="./global.css" />
  <link rel="stylesheet" href="./login.css" />
  <link rel="stylesheet" href="./signup.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito Sans:wght@400;700&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,700;1,400&display=swap" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&display=swap" />
</head>
<body>
  <div class="login-desktop-frame-1440-x">
    <img class="login-desktop-frame-1440-x-child" alt="" src="./public/rectangle-104@2x.png"/>
    <form action="" method="post">
      <div class="rectangle-parent">
        <div class="group-child"></div>
        <b class="log-in1">Log in</b>
        <div class="group-inner"></div>
        <div class="rectangle-div"></div>
        <div class="email1">
          <input type="text" placeholder="Email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required />
        </div>
        <div class="password1">
          <input type="password" placeholder="Password" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>" required />
        </div>
        <input type="submit" class="group-child4" id="rectangle1" name="signin" value="Log in" style="cursor: pointer;"/>
      </div>
    </form>
    <script>
      var rectangle1 = document.getElementById("rectangle1");
      if (rectangle1) {
        rectangle1.addEventListener("click", function (e) {
          window.location.href = "./homepage.php";
        });
      }
    </script>
  </div>
</body>
</html>
