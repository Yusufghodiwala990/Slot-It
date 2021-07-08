<?php
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;


$errors = array();
include "library.php";

if (isset($_POST['submit'])) {
    $pdo = connectDB();
    var_dump($pdo);

  
 


  if (!isset($name) || strlen($name) === 0) {
    $errors['name'] = true;
  }


  if (!isset($email) || strlen($email) == 0 || filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
    $errors['email'] = true;
  }




 
}



if(count($errors) ==- 0){
    
    $query = "INSERT INTO `Guest_users` (Name, email) values(?,?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$name,$email]);
    header("Location:search.php");
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
    <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="false">
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