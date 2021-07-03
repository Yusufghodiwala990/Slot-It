<?php

session_start();
if(!isset($_SESSION['user_id'])){
  header("Location:login.php");
  exit();
}
$userID = $_SESSION['user_id'];
include 'library.php';
$errors = array();

$pdo = connectDB();
$query = "SELECT * FROM `users` where ID=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userID]);

$userInfo = $stmt->fetch();

if (isset($_POST['submit'])) {
$name = $_POST['name'] ?? null;
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;                                              
$password = $_POST['password'] ?? null;
$confirmpass = $_POST['conpass'] ?? null;

  if (!isset($name) || strlen($name) == 0 || is_numeric($name) == true) {
    $errors['name'] = true;
  }

  if (!isset($username) || strlen($username) == 0) {
    $errors['username'] = true;
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
    if(strlen($password) == 0 || strlen($password) < 8){
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



  function hashPass($password){
    $hashedpass = password_hash($password,PASSWORD_DEFAULT);
    return $hashedpass;



  }
  

  // NOTE : Not displaying the password in the field for edit account. Security issue.
  // only check if requirements are met if a password was submitted
  //  otherwise allow the user to leave it blank

  if(strlen($_POST['password']) !== 0){
  if(checkPassword($password)){
    $errors['password'] = true;
  }
}
 

  if(count($errors) ==- 0){
    if(strlen($_POST['password'])!==0){
    $hashedpass = hashPass($password);
    $query = "UPDATE `users` SET username=?, fname=?, email=?, password=? WHERE ID=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username,$name,$email,$hashedpass,$userID]);
    $update = true;
    echo "UPDATE WITH PASS DONE";
    }
    else{
    $query = "UPDATE `users` SET username=?, fname=?, email=? WHERE ID=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username,$name,$email,$userID]);
    $update = true;    
    }
    
    header("Refresh:0 url=edit_account.php");
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Account</title>
  <link rel="stylesheet" href="../styles/edit_account.css" />
  <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../styles/errors.css" />
</head>

<body>
  <header>
    <nav>
      <ul>
        <div>
          <img src="../img/logo.png" alt="Slot-it logo" width="60px" height="60px">
        </div>
        <div>
          <a href="../index.html">
            <li>Home</li>
          </a>
          <a href="../create.html">
            <li>Create</li>
          </a>
          <a href="../mystuff.php">
            <li>View</li>
          </a>
          <a href="./login.php">
            <li>Login <i class="fa fa-sign-in" aria-hidden="true"></i></li>
          </a>
          <a href="./edit_account.php">
            <li>My Account <i class="fa fa-user" aria-hidden="true"></i></li>
          </a>
        </div>
      </ul>
    </nav>
  </header>

  <main>
    <section>
      <h2>Account Information</h2>
      <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="false" enctype="multipart/form-data">

        <div>
          <input type="text" name="name" placeholder="John Smith" id="name" value="<?=$userInfo['fname']?>">
          <label for="name">Full Name</label>
          <span class="error <?=!isset($errors['name']) ? 'hidden' : ""; ?>">*Name was empty</span>
        </div>

        <div>
          <input type="text" name="username" id="username" placeholder="derekpope666" autocomplete="off" value="<?=$userInfo['username']?>">
          <label for="username">Username</label>
          <span class="error <?=!isset($errors['username']) ? 'hidden' : ""; ?>">*Username was empty</span>
        </div>

        <div>
          <input type="text" name="email" id="email" placeholder="derekpope666" autocomplete="off" value="<?=$userInfo['email']?>">
          <label for="email">Email</label>
          <span class="error <?=!isset($errors['email']) ? 'hidden' : ""; ?>">*Email Invalid</span>
        </div>
        <div>
          <input type="password" name="password" id="password" placeholder="inbaepn" autocomplete="off" value="">
          <label for="password">Password</label>
          <span class="error <?=!isset($errors['password']) ? 'hidden' : ""; ?>">
        *Invalid Password. Atleast 1 number, 1 special character, 1 uppercase letter and >=8</span>
        </div>
        <div>
          <input type="password" name="conpass" id="conpass" placeholder="inbaepn" autocomplete="off" value="">
          <label for="conpass">Confirm Password</label>
          <span class="error <?=!isset($errors['match']) ? 'hidden' : ""; ?>">*Passwords do not match</span>
        </div>

        <div>
          <a href="../index.html"><button type="button">Back</button></a>
          <a href=""><button type="submit" name="submit">Update</button></a>
        </div>
      </form>

     
    </section>


    <form id="profpic" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="false" enctype="multipart/form-data">
      <aside>
      <label id="special" class="profpic" id="profilepic" for="profilepic">Change Profile Picture<i class="far fa-user-circle"></i></label>
          <input class="profpic" type="file" id="profilepic" name="profilepic">
         
        </aside>
        </form>
  </main>
  
  <div class="deletebutton">
      <a href=""><button>Delete Account</button></a>
    </div>
</body>
</html>