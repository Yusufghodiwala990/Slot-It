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

  if ($row['numusers'] !== 0) {
    $errors['unique'] = true;
  }


  if (!isset($email) || strlen($email) == 0 || filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
    $errors['email'] = true;
  }

  if ($password != $confirmpass) {
    $errors['match'] = true;
  }


  // void functions in 7.1
  //https://www.php.net/manual/en/migration71.new-features.php
  function checkPassword($password)
  {
    $flag = false;
    if (!isset($password) || strlen($password) == 0 || strlen($password) < 8) {
      $flag = true;
    }


    // no digits
    if (!preg_match('/[0-9]+/', $password)) {
      $flag = true;
    }
    // at least one letter
    if (!preg_match('/[a-zA-Z]+/', $password)) {
      $flag = true;
    }
    // uppercase
    if (!preg_match('/[A-Z]+/', $password)) {
      $flag  = true;
    }
    // at least one special character
    if (!preg_match('/[-@_#$%^&+=]/', $password)) {
      $flag  = true;
    }
    return $flag;
  }



  function hashPass($password)
  {
    $hashedpass = password_hash($password, PASSWORD_DEFAULT);
    return $hashedpass;
  }
  // from php notes on blackboard

  function checkAndMoveFile($filekey, $sizelimit, $newname, $fileError){
    //modified from http://www.php.net/manual/en/features.file-upload.php

    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (!isset($_FILES[$filekey]['error']) || is_array($_FILES[$filekey]['error'])) {
    }

    // Check Error value.
    switch ($_FILES[$filekey]['error']) {
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        $fileError = true;



      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:

        $fileError = true;
      default:
        $fileError = true;
    }

    // You should also check filesize here.
    if ($_FILES[$filekey]['size'] > $sizelimit) {
      $fileError = true;
      throw new RuntimeException('Exceeded filesize limit.');
    }

    // Check the File type  Note: this example assumes image upload
    if (
      exif_imagetype($_FILES[$filekey]['tmp_name']) != IMAGETYPE_GIF
      and exif_imagetype($_FILES[$filekey]['tmp_name']) != IMAGETYPE_JPEG
      and exif_imagetype($_FILES[$filekey]['tmp_name']) != IMAGETYPE_PNG
    ) {
      $fileError = true;
    }

    // $newname should be unique and tested
    if($fileError == false){
    if (!move_uploaded_file($_FILES[$filekey]['tmp_name'], $newname)) {
      throw new RuntimeException('Failed to move uploaded file.');
    }
  }
    return $fileError;
 }


  if (checkPassword($password)) {
    $errors['password'] = true;
  }






if (count($errors) == 0) {
    // from notes
  
    if (is_uploaded_file($_FILES['profilepic']['tmp_name'])) {
      $query = "SELECT MAX(ID) AS latest FROM `users`";
      $stmt = $pdo->prepare($query);
      $stmt->execute();
      $row = $stmt->fetch();

      $uniqueid = $row['latest'] + 1;
      $path = "/home/yusufghodiwala/public_html/www_data/3420project_images/";
      $fileroot = "profile-pic";

      $filename = $_FILES['profilepic']['name'];
      $exts = explode(".", $filename);
      $ext = $exts[count($exts) - 1];
      $filename = $fileroot . $uniqueid . "." . $ext;
      $newname = $path . $filename;
      $fileError = false;


      if (checkAndMoveFile('profilepic', 1972864, $newname, $fileError)) {
        $errors['file'] = true;
      }
    }

    if (!isset($errors['file'])) {
      $hashedpass = hashPass($password);
      $query = "INSERT INTO `users` (username, fname, email, password) values(?,?,?,?)";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$username, $name, $email, $hashedpass]);
    }
    
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
  <script src="./registration.js" defer></script>
  <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
</head>

<body>
  <main>

    <h1>Create Account</h1>
    <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post" novalidate autocomplete="false" enctype="multipart/form-data">

    <aside>
        <!-- soft limit -->
        <input type="hidden" name="MAX_FILE_SIZE" value="1972864" />

        <input class="profpic" type="file" id="file" name="profilepic">
        <label class="profpic" id="profilepic" for="profilepic">Choose Profile Picture(optional)<i class="far fa-user-circle"></i></label>
        <span class="error <?= !isset($errors['file']) ? 'hidden' : ""; ?>">Invalid Format/Size</span>

      </aside>
      <div>
        <input type="text" name="name" placeholder="John Smith" id="name" value="<?=$name ?>">
        <label for="name">Full Name</label>
        <span class="error <?= !isset($errors['name']) ? 'hidden' : ""; ?>">*Name was empty</span>
      </div>
      <div>
        <input type="text" name="username" id="username" placeholder="derekpope666" autocomplete="off" value="<?=$username ?>">
        <label for="username">Username</label>
        <span class="error <?= !isset($errors['unique']) ? 'hidden' : ""; ?>">*Username already taken</span>
        <span class="error <?= !isset($errors['username']) ? 'hidden' : ""; ?>">*Username was empty</span>

      </div>

      <div>
        <input type="text" name="email" id="email" placeholder="derekpope666" autocomplete="off" value="<?=$email ?>">
        <label for="email">Email</label>
        <span class="error <?= !isset($errors['email']) ? 'hidden' : ""; ?>">*Email Invalid</span>
      </div>

      <div>
        <input type="password" name="password" id="password" placeholder="inbaepn" autocomplete="off">
        <label for="password">Password</label>
        <span class="error <?= !isset($errors['password']) ? 'hidden' : ""; ?>">
          *Invalid Password. Atleast 1 number, 1 special character, 1 uppercase letter and >=8</span>
          <span class="rating"></span>
      </div>

      <div>
        <input type="password" name="conpass" id="conpass" placeholder="inbaepn" autocomplete="off">
        <label for="conpass">Confirm Password</label>
        <span class="error <?= !isset($errors['match']) ? 'hidden' : ""; ?>">*Passwords do not match</span>
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