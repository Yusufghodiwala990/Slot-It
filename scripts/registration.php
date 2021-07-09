<?php

include 'library.php';
$errors = array();


//get values from post or set to NULL if doesn't exist
$name = $_POST['name'] ?? null;
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;                                              
$password = $_POST['password'] ?? null;
$confirmpass = $_POST['conpass'] ?? null;


//NOTES : STRING LENGTH VALIDATION MAY BE DONE THROUGH JAVASCRIPT SO I'M SKIPPING FOR NOW
//        ALTHOUGH IT'S JUST A STRLEN() CALL


if (isset($_POST['submit'])) {
  $pdo = connectDB();

  if (!isset($name) || strlen($name) == 0 || is_numeric($name) == true) {
    $errors['name'] = true;
  }

  if (!isset($username) || strlen($username) == 0) {
    $errors['username'] = true;
  }


  //. unique username
  $query = "SELECT COUNT(*) as numusers from `users` where username=?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$username]);
  $row = $stmt->fetch();
  
  if($row['numusers']!==0){
    $errors['unique'] = true;
  }


  if (!isset($email) || strlen($email) == 0 || filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
    $errors['email'] = true;
  }

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
 





  if(count($errors) ==- 0){
    $hashedpass = hashPass($password);
    $query = "INSERT INTO `users` (username, fname, email, password) values(?,?,?,?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username,$name,$email,$hashedpass]);
    echo "bye!";
    header("Location:login.php");
  }


}
    

//At some point in an application sessions should be destroyed (i.e. when a user logs out). To destroy a session call session_destroy(), which cleans up the session variables and removes the session file.
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration</title>
  <link rel="stylesheet" href="../styles/registration.css" />
  <link rel="stylesheet" href="../styles/errors.css" />
  <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
</head>

<body>
  <main>

    <h1>Create Account</h1>
    <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="false" enctype="multipart/form-data">
      <aside>
        <input class="profpic" type="file" id="profilepic" name="profilepic">
        <label class="profpic" id="profilepic" for="profilepic">Choose Profile Picture(optional)<i class="far fa-user-circle"></i></label>
      </aside>
      <div>
        <input type="text" name="name" placeholder="John Smith" id="name" value="<?=$name?>">
        <label for="name">Full Name</label>
        <span class="error <?=!isset($errors['name']) ? 'hidden' : ""; ?>">*Name was empty</span>
      </div>
      <div>
        <input type="text" name="username" id="username" placeholder="derekpope666" autocomplete="off" value="<?=$username?>">
        <label for="username">Username</label>
        <span class="error <?=!isset($errors['unique']) ? 'hidden' : ""; ?>">*Username already taken</span>
        <span class="error <?=!isset($errors['username']) ? 'hidden' : ""; ?>">*Username was empty</span>
        
      </div>

      <div>
        <input type="text" name="email" id="email" placeholder="derekpope666" autocomplete="off" value="<?=$email?>">
        <label for="email">Email</label>
        <span class="error <?=!isset($errors['email']) ? 'hidden' : ""; ?>">*Email Invalid</span>
      </div>

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
      <div id="links-reg">
        <a href="../index.html"><button type="button">Back</button></a>
        <a href=""><button name="submit" id="submit">Sign-up</button></a>
      </div>
    </form>
  </main>
  <footer>

    <p>&copy; 2021 - Slot-It</p>
  </footer>

</body>
</html>