
<?php

/* Two ways to continue as a guest. Google Sign In or Registering as normal with providing info */
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$errors = array();
include "library.php";
session_start();
$pdo = connectDB();




// The JS file for google sign will post data which contains info to verify if google
//  sign in was chosen

if(isset($_POST['submit-from-js'])){
  
  // store the google details in session
  $_SESSION['googleName'] = $_POST['name'];
  $_SESSION['googleEmail'] = $_POST['email'];
}

// inserting the details of the guest onto the guest table in the database
if(isset($_SESSION['googleName'])){

  // check if the user already exists in our database
  $checkQuery = "SELECT * FROM `Guest_users` WHERE Name=? AND Email=?";
  $stmt = $pdo->prepare($checkQuery);
  $stmt->execute([$_SESSION['googleName'],$_SESSION['googleEmail']]);
  $row = $stmt->fetch();
 

  // insert them if they don't
  if($row == false){
  $query = "INSERT INTO `Guest_users` (Name, Email) values(?,?)";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_SESSION['googleName'],$_SESSION['googleEmail']]);
  }


// this is the case when the user tried to slot in for a sheet and was redirected here(from login)
  if(isset($_SESSION['SlotID'])){  
    header("Location:slot_in.php"); // send them back to slot_in.php
  }

  // if they didn't come from slot_in.php, grab the guest_id and redirect to search.php
  else{
  $query1 = "SELECT * FROM `Guest_users` WHERE Name=? && Email=?";
  $stmt1 = $pdo->prepare($query1);
  $stmt1->execute([$_SESSION['googleName'],$_SESSION['googleEmail']]);
  $GuestID = $stmt1->fetch();
  $_SESSION['Guest_ID']=$GuestID['ID'];
  header("Location:search.php");
  exit();
}}


// if the other option to continue as guest was used, i.e registering normally
if (isset($_POST['submit'])) {


  // validate name
  if (!isset($name) || strlen($name) === 0) {
    $errors['name'] = true;
  }

  // validate email
  if (!isset($email) || strlen($email) == 0 || filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
    $errors['email'] = true;
  }


  // if no errors
if(count($errors) == 0){


  // insert them in the database
    $query = "INSERT INTO `Guest_users` (Name, Email) values(?,?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$name,$email]);

    // this is the case when the user tried to slot in for a sheet and was redirected here(from login)
    if(isset($_SESSION['SlotID'])){
    $query1 = "SELECT * FROM `Guest_users` WHERE Name=? && Email=?";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute([$name,$email]);
    $GuestID = $stmt1->fetch();
    $_SESSION['Guest_ID']=$GuestID['ID'];

    header("Location:slot_in.php"); // send them back to slot_in.php
    exit();
    }
// else get the guest_id and redirect back to search.php
    $query1 = "SELECT * FROM `Guest_users` WHERE Name=? && Email=?";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute([$name,$email]);
    $GuestID = $stmt1->fetch();
    $_SESSION['Guest_ID']=$GuestID['ID'];
    header("Location:search.php");
    exit();
    }
  }

?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Google API Key -->
  <meta name="google-signin-client_id" content="661589590202-us1vtruq46mvta14t83d4fekdmtrp4mf.apps.googleusercontent.com">
  <title>Guest</title>
  <link rel="stylesheet" href="../styles/login.css" />
  <link rel="stylesheet" href="../styles/errors.css" />
  
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
  <script src="./googleSignIn.js" async defer></script>
</head>

<body>
  <main>

    <h1>Guest</h1>
    <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="off">
      <div>
        <input type="text" name="name" id="name" placeholder="derekpope666" value="<?=$name?>" autocomplete="off">
        <label for="name">Name</label>
        <span class="error <?= !isset($errors['name']) ? 'hidden' : "errors"; ?>">*Name was empty</span>
      </div>

      <div>
        <input type="text" name="email" id="email" placeholder="derekpope666" autocomplete="off" value="<?=$email?>">
        <label for="email">Email</label>
        <span class="error <?=!isset($errors['email']) ? 'hidden' : ""; ?>">*Email Invalid</span>
      </div>

      

      <div id="links-login">
        <a href="../index.html"><button type="button">Back</button></a>
        <a href=""><button type="submit" name="submit">Continue</button></a>
      </div>
      
      <!-- Added a div from Google's API which automatically styles a button for sign in -->
      <p>Continue with Google</p>
      <div class="g-signin2" data-onsuccess="onSignIn">
      </div>
      
    </form>
  </main>
  <footer>
    <p>&copy; 2021 - Slot-It</p>
  </footer>

</body>

</html>
