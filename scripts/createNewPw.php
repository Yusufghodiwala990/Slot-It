<?php

/* This page is loaded when the user clicks on the link in their inbox for forgot password */
include 'library.php';
$errors = array();
$password = $_POST['password'] ?? null;
$confirmpass = $_POST['conpass'] ?? null;
$formSelector = $_POST['selector'] ?? null;
$formValidator = $_POST['validator'] ?? null; // this is grabbed from the hidden form field for
                                                // the token
if (isset($_POST['submit'])) {
$pdo = connectDB();

// validate confirm pass
if($password != $confirmpass){
    $errors['match'] = true;
  }

  
  // validate password
   function checkPassword($password){
     $flag = false;
    if(!isset($password) || strlen($password) == 0 || strlen($password) < 8){
    $flag = true;
      
    }


    // no digits
    if(!preg_match('/[0-9]+/',$password)){
      $flag = true;
      
    }
    // at least one letter
    if(!preg_match('/[a-zA-Z]+/',$password)){
      $flag = true;
     
    }
    // uppercse
    if(!preg_match('/[A-Z]+/',$password)){
     $flag  = true;

    }
    // at least one special character
    if(!preg_match('/[-@_#$%^&+=]/',$password)){
      $flag  = true;   
    }
    return $flag;
  }



  // function to hash the password
  function hashPass($password){
    $hashedpass = password_hash($password,PASSWORD_DEFAULT);
    return $hashedpass;
  }

  if(checkPassword($password)){
    $errors['password'] = true;
  }
  


  // get the current date to compare with the link Expiry
  $currentDate = date("U");

  if(count($errors) === 0){

    // fetch the details from the resetSelector given the link hasn't expired
    $query = "SELECT * FROM `reset_password` WHERE resetSelector=? AND expiryDate >=? ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$formSelector,$currentDate]);

    $result = $stmt->fetch();

    if($result == false){
      echo "<h2>Could not reset, submit another request</h2>";
      exit();
    }
    else{
      // convert the token from hex to binary
      $binToken = hex2bin($formValidator);

      // verify the token. (The token was fetched from the hidden input)
      $tokenCheck = password_verify($binToken, $result['resetToken']);

      // token is not the same as in the database and the hidden input
      if($tokenCheck === false){
        echo "<h2>Could not reset, submit another request</h2>";
        exit();
        
      }

      // else, grab the email from the reset_pw table 
      else{
        $tokenEmail = $result['resetEmail'];


        // verify that the request came from a valid user
        $query = "SELECT * FROM `users` WHERE email=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$tokenEmail]);
        $result = $stmt->fetch();

        // verification of the email failed, user is not found in the users table with
        // the given email


        if($result === false){
          echo "<h2>Could not reset, please try again</h2>";
        }
        else{

          // finally hash the new password and update it since all verfications did not fail
          $hashedpass = hashPass($password);
          $query = "UPDATE `users` SET password=? WHERE email=?";
          $stmt = $pdo->prepare($query);
          $stmt->execute([$hashedpass,$tokenEmail]);


          // deleting the token after pwd update

          $query = "DELETE FROM `reset_password` WHERE resetEmail=?";
          $stmt = $pdo->prepare($query);
          $stmt->execute([$tokenEmail]);

          // redirect to login
          header("Location:login.php");
          exit();
   

        }


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
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
  <script src="./googleSignIn.js" async defer></script>
</head>

<body>
  <main>

    <?php

    // get the selector and validator(token with selector from GET)
    //  because it is on the url that was sent to the email of the user
    $selector = $_GET['selector'] ?? null;
    $validator = $_GET['validator'] ?? null;

    // verify the selector and validator
    if(empty($selector) || empty($validator)){
        echo "<h2>Could not track your reset password request</h2>";
    }
    else{

        // if the selector and validator are valid hex digits
        if((ctype_xdigit($selector)) === true && (ctype_xdigit($validator)) === true){
        
         ?>

         <!-- All checks passed, allow them to enter a new password -->
            <h1>Enter New Password</h1>
    <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="off">

    <!-- Hidden inputs to store the selector and validator from GET,
    They are used to verify the legitimacy of the user in the php script above -->

    <input type="hidden" name="selector" value="<?=$selector?>">
    <input type="hidden" name="validator" value="<?=$validator?>">
    <div>
        <input type="password" name="password" id="password" placeholder="inbaepn" autocomplete="off">
        <label for="password">Password</label>
        <span class="error <?=!isset($errors['password']) ? 'hidden' : ""; ?>">
        *Invalid Password. Atleast 1 number, 1 special character, 1 uppercase letter and >=8</span>
      </div>

      <div>
        <input type="password" name="conpass" id="conpass" placeholder="inbaepn" autocomplete="off">
        <label for="conpass">Confirm Password</label>
        <span class="error <?=!isset($errors['match']) ? 'hidden' : ""; ?>">*Passwords do not match</span>
      </div>
     
      
      <div id="links-login">
        <a href="../index.html"><button type="button">Back</button></a>
        <a href=""><button type="submit" name="submit">Continue</button></a>
      </div>
    </form>
        <?php
        }
      }
?>
    
    
  </main>
  <footer>
    <p>&copy; 2021 - Slot-It</p>
  </footer>
</body>
</html>