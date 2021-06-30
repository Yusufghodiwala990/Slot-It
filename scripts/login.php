<?php

session_start();
// commenting this out so that we can test login.php
// no need to login if session variables are set.
/*
if(isset($_SESSION['username'])){
  header("Location:mystuff.php");
  exit();
}*/
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;


$errors = array();

if (isset($_POST['submit'])) {
  include "library.php";
  $pdo = connectDB();


  if (!isset($username) || strlen($username) === 0) {
    $errors['username'] = true;
  }

  if (!isset($password) || strlen($password) === 0) {
    $errors['password'] = true;
  }
  if (count($errors) === 0) {
    $query = "select * from `users` where username=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);

    if (($row = $stmt->fetch()) === false) {
      $errors['login'] = true;
    } else {


      if (password_verify($_POST['password'], $row['password']) == true) {

        $_SESSION['user_id'] = $row['ID'];
        $_SESSION['username'] = $username;

        if (isset($_POST['rememberme'])) {
          setcookie("slot-it", $username, time() + 60 * 60 * 24);
        }

        header("Location:mystuff.php");
        exit();
      } else {
        $errors['login'] = true;
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="stylesheet" href="../styles/login.css" />
  <link rel="stylesheet" href="../styles/errors.css" />
</head>

<body>
  <main>

    <h1>Login</h1>
    <form method="POST" novalidate autocomplete="false">
      <div>
        <input type="text" name="username" id="username" placeholder="derekpope666" value="<?= isset($_COOKIE['slot-it']) ? $_COOKIE['slot-it'] : "" ?>" autocomplete="off">
        <label for="username">Username</label>
        <span class="error <?= !isset($errors['username']) ? 'hidden' : "errors"; ?>">*Username was empty</span>



      </div>
      <div>
        <input type="password" name="password" id="password" placeholder="inbaepn" autocomplete="off">
        <label for="password">Password</label>
        <span class="error <?= !isset($errors['password']) ? 'hidden' : "errors"; ?>">*Password was empty</span>
        <span class="error <?= !isset($errors['login']) ? 'hidden' : "errors"; ?>">*Incorrect login information</span>
      </div>
      <div>
        <input type="checkbox" id="rememberme" name="rememberme" value="true">
        <label id="special" for="rememberme">Remember Me?</label>
      </div>
      <div id="links-login">
        <a href="../index.html"><button type="button">Back</button></a>
        <a href=""><button type="submit" name="submit">Login</button></a>
      </div>
      <div id="forgotpass">
        <a href=""><button>Forgot Password</button></a>
      </div>
    </form>
  </main>
  <footer>
    <p>&copy; 2021 - Slot-It</p>
  </footer>

</body>

</html>