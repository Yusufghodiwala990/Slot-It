<?php

session_start();
include 'library.php';

if(isset($_SESSION['username'])){
  header("Location:mystuff.php");
  exit();
}
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

if(isset($_GET['Slot_ID'])){
$_SESSION['SheetID'] = $_GET['SheetID'] ??null;
$_SESSION['SlotID'] = $_GET['Slot_ID'] ??null;
}

// if(isset($_SESSION['Guest_ID' && 'SlotID'])){   //try this again
//   header("Location:slot_in.php");
//   exit();
//}

$errors = array();

if (isset($_POST['submit'])) {

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


      if (password_verify($_POST['password'], $row['password'])) {

        $_SESSION['user_id'] = $row['ID'];
        $_SESSION['username'] = $username;

        if (isset($_POST['rememberme'])) {
          setcookie("slot-it", $username, time() + 60 * 60 * 24);
        }

        if(isset($_SESSION['SlotID'])){
          header("Location:slot_in.php");
          exit();
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
  <meta name="google-signin-client_id" content="661589590202-us1vtruq46mvta14t83d4fekdmtrp4mf.apps.googleusercontent.com">
  <title>Login</title>
  <link rel="stylesheet" href="../styles/login.css" />
  <link rel="stylesheet" href="../styles/errors.css" />
  <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
  <script src="./login.js" defer></script>

</head>

<body>
  <main>

    <h1>Login</h1>
    <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="false">
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
        <a href=""><button type="submit" id="submit" name="submit">Login</button></a>
      </div>
      <div id="forgotpass">
        <a href="resetpw.php"><button type="button">Forgot Password?</button></a>
      </div>

      <div id="forgotpass">
        <button><a href="guestlogin.php">Continue as Guest <i class="fa fa-sign-in" aria-hidden="true"></i></a></button>
      </div>
      
      
    </form>
  </main>
  <footer>
    <p>&copy; 2021 - Slot-It</p>
  </footer>

</body>

</html>