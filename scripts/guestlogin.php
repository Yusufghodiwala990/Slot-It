<?php
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$sheet_id = $_GET['SheetID'] ?? null ;
$Slot_ID = $_GET['Slot_ID'] ?? null ;
$errors = array();
include "library.php";
$pdo = connectDB();


// two options to store the details of guest, use form or google sign in.
// $_POST['submit-from-js] is a check to verify that details were sent through google sign in
// $_POST['submit] is a check to verify that details were sent through the Form on the page


session_start();
if(isset($_POST['submit-from-js'])){

  // use this details for google sign in on other pages (if applicable)
  $_SESSION['googleName'] = $_POST['name'];
  $_SESSION['googleEmail'] = $_POST['email'];
}

// inserting the details of the guest onto the guest table in the database
if(isset($_SESSION['googleName'])){
  $query = "INSERT INTO `Guest_users` (Name, email) values(?,?)";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_SESSION['googleName'],$_SESSION['googleEmail']]);

  // **TO DAUD** - REDIRECT WHERE NECESSARY after the google sign in.
  header("Location:search.php");

}
if (isset($_POST['submit'])) {


  if (!isset($name) || strlen($name) === 0) {
    $errors['name'] = true;
  }

  if (!isset($email) || strlen($email) == 0 || filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
    $errors['email'] = true;
  }

if(count($errors) == 0){

    $query = "INSERT INTO `Guest_users` (Name, email) values(?,?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$name,$email]);

    $query1 = "SELECT * FROM `Guest_users` WHERE Name=? && email=?";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute([$name,$email]);
    $GuestID = $stmt1->fetch();

    $query2 = "UPDATE `Slots` SET Guest_ID=? WHERE Sheet_ID=? && Slot_ID=? ";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute([$GuestID['ID'],$sheet_id,$Slot_ID]);

    $query3 = "SELECT No_of_signups from `Signup_sheets` where ID=? ";
    $stmt3 = $pdo->prepare($query3);
    $stmt3->execute([$sheet_id]);
    $result = $stmt3->fetch();
    $No_of_slots = $result['No_of_signups'] + 1;

    $query4 = "UPDATE `Signup_sheets` SET No_of_signups=? WHERE ID=?";
    $stmt4 = $pdo->prepare($query4);
    $stmt4->execute([$No_of_slots,$sheet_id]);
    echo "You have been slotted-in successfully!";
  //header("Refresh:3 url=search.php");
}
    }

?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
    <form action="" method="post" novalidate autocomplete="false">
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
      

     
      <div class="g-signin2" data-onsuccess="onSignIn">
      </div>
      
    </form>
  </main>
  <footer>
    <p>&copy; 2021 - Slot-It</p>
  </footer>

</body>

</html>