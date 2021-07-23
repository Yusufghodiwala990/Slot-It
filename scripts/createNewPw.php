<?php
include 'library.php';
$errors = array();


$password = $_POST['password'] ?? null;
$confirmpass = $_POST['conpass'] ?? null;
$formSelector = $_POST['selector'] ?? null;
$formValidator = $_POST['validator'] ?? null;
if (isset($_POST['submit'])) {
$pdo = connectDB();




if($password != $confirmpass){
    $errors['match'] = true;
  }

  
  // void functions in 7.1
  //https://www.php.net/manual/en/migration71.new-features.php
   function checkPassword($password){
     $flag = false;
    if(!isset($password) || strlen($password) == 0 || strlen($password) < 8){
    $flag = true;
      
      echo "<h2>length short</h2>";
    }


    // no digits
    if(!preg_match('/[0-9]+/',$password)){
      echo "<h2>no</h2>";
      $flag = true;
      
    }
    // at least one letter
    if(!preg_match('/[a-zA-Z]+/',$password)){
      echo "<h2>no letter</h2>";
      $flag = true;
     
    }
    // uppercse
    if(!preg_match('/[A-Z]+/',$password)){
      echo "no uppercase";
     $flag  = true;

    }
    // at least one special character
    if(!preg_match('/[-@_#$%^&+=]/',$password)){
      echo"<h2>no special</h2>";
      $flag  = true;
      
    }
    return $flag;
  }



  function hashPass($password){
    $hashedpass = password_hash($password,PASSWORD_DEFAULT);
    return $hashedpass;



  }

  if(checkPassword($password)){
    $errors['password'] = true;
  }
  

  $currentDate = date("U");

  if(count($errors) === 0){
    $query = "SELECT * FROM `reset_password` WHERE resetSelector=? AND expiryDate >=? ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$formSelector,$currentDate]);

    $result = $stmt->fetch();

    if($result == false){
      echo "Could not reset, submit another submit request";
      exit();
    }
    else{
      $binToken = hex2bin($formValidator);
      $tokenCheck = password_verify($binToken, $result['resetToken']);

      if($tokenCheck === false){
        echo "Could not reset, submit another request";
        exit();
        
      }
      else{
        $tokenEmail = $result['resetEmail'];

        $query = "SELECT * FROM `users` WHERE email=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$tokenEmail]);
        $result = $stmt->fetch();

        if($result === false){
          echo "Could not reset, please try again";
        }
        else{
          $hashedpass = hashPass($password);
          $query = "UPDATE `users` SET password=? WHERE email=?";
          $stmt = $pdo->prepare($query);
          $stmt->execute([$hashedpass,$tokenEmail]);


          // deleting the token after pwd update

          $query = "DELETE FROM `reset_password` WHERE resetEmail=?";
          $stmt = $pdo->prepare($query);
          $stmt->execute([$tokenEmail]);

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
    $selector = $_GET['selector'];
    $validator = $_GET['validator'];
    if(empty($selector) || empty($validator)){
        echo "Could not track your reset password request";
    }
    else{

        if((ctype_xdigit($selector)) === true && (ctype_xdigit($validator)) === true){
          
           
         ?>
            <h1>Enter New Password</h1>
    <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="false">
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