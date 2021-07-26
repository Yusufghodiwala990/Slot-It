<?php

session_start();
if (isset($_SESSION['user_id'])) {
  // fetching the profile picture if available.

  /* FORMAT OF THE PROFILE PICTURE STORED ON LOKI : profile-pic{ID}.extension stored
   in 3420project_images folder in www_data on yusufghodiwala account  */

  $filename = "profile-pic" . $_SESSION['user_id'];
  $profpicpath = "/home/yusufghodiwala/public_html/www_data/3420project_images/";


  // glob function to run a search with a wildcard to return all matching filenames.
  $result = glob($profpicpath . $filename . ".*");

  // if array is empty, no match, else build URL.
  if (empty($result))
    $picExists = false;
  else {
    $picExists = true;
    $profpic_url = "https://loki.trentu.ca/~yusufghodiwala/www_data/3420project_images/";
    $url = explode("/", $result[sizeof($result) - 1]);  // get the latest pic the user uploaded
    $profpic_url = $profpic_url . $url[sizeof($url) - 1]; // build URL
  }
}

// if the user somehow ended up here without a session set, send them to login
if (!isset($_SESSION['user_id'])) {
  header("Location:login.php");
  exit();
}

// get the user ID
$userID = $_SESSION['user_id'];
include 'library.php';

$errors = array();  // array to store all errors
$pdo = connectDB();   // creating an instance of a pdo object


// load the users details
$query = "SELECT * FROM `users` where ID=?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userID]);
$userInfo = $stmt->fetch();


// on Submit (update)
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

  if ($password != $confirmpass) {
    $errors['match'] = true;
  }


  // void functions in 7.1
  //https://www.php.net/manual/en/migration71.new-features.php
  function checkPassword($password)
  {
    $flag = false;
    if (strlen($password) == 0 || strlen($password) < 8) {
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
    // uppercse
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






  // NOTE : Not displaying the password in the field for edit account. Security issue.
  // only check if requirements are met if a password was submitted
  //  otherwise allow the user to leave it blank

  if (strlen($_POST['password']) !== 0) {
    if (checkPassword($password)) {
      $errors['password'] = true;
    }
  }


  if (count($errors) == 0) {
    if (strlen($_POST['password']) !== 0) {
      $hashedpass = hashPass($password);
      $query = "UPDATE `users` SET username=?, fname=?, email=?, password=? WHERE ID=?";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$username, $name, $email, $hashedpass, $userID]);
      $update = true;
      echo "UPDATE WITH PASS DONE";
    } else {
      $query = "UPDATE `users` SET username=?, fname=?, email=? WHERE ID=?";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$username, $name, $email, $userID]);
      $update = true;
    }

    header("Refresh:0 url=edit_account.php");
    exit();
  }
}


// for the profile change form
// Uploading a file
// from php notes on blackboard

if (isset($_POST['submit2'])) {
  // from notes
  function checkAndMoveFile($filekey, $sizelimit, $newname, $fileError)
  {
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
        $fileError = true;          // bool variable to store if there was an error



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

    // Check the File type
    if (
      exif_imagetype($_FILES[$filekey]['tmp_name']) != IMAGETYPE_GIF
      and exif_imagetype($_FILES[$filekey]['tmp_name']) != IMAGETYPE_JPEG
      and exif_imagetype($_FILES[$filekey]['tmp_name']) != IMAGETYPE_PNG
    ) {
      $fileError = true;
    }


    if ($fileError == false) {
      if (!move_uploaded_file($_FILES[$filekey]['tmp_name'], $newname)) {
        throw new RuntimeException('Failed to move uploaded file.');
      }
    }
    return $fileError;
  }


  // if a file was uploaded
  if (is_uploaded_file($_FILES['profilepic']['tmp_name'])) {

    // build filename
    $query = "SELECT ID AS user_ID FROM `users` where ID=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userID]);
    $row = $stmt->fetch();
    $uniqueid = $row['user_ID']; // filename built with the user's ID.
    $path = "/home/yusufghodiwala/public_html/www_data/3420project_images/";
    $fileroot = "profile-pic";

    $filename = $_FILES['profilepic']['name'];
    $exts = explode(".", $filename);
    $ext = $exts[count($exts) - 1];
    $filename = $fileroot . $uniqueid . "." . $ext;
    $newname = $path . $filename;
    $fileError = false;

    // upload it with the specified limit; 2MB.
    if (checkAndMoveFile('profilepic', 2000000, $newname, $fileError)) {
      $errors['file'] = true;
    } else {
      echo "Profile-Picture was updated.";
      header("Refresh:0 url=edit_account.php");
    }
  }
}


// for deleting an account
if (isset($_POST['yes'])) {

  // delete the users info from all the tables

  // update slots the user signed up and set it to null
  $query1 = "UPDATE Slots set User_ID=null where User_ID=?";
  $stmt1 = $pdo->prepare($query1);
  $stmt1->execute([$userID]);


  // fetch all the users sign up sheets where they are an owner
  $query4 = "select ID from Signup_sheets where Owner_ID=?";
  $stmt4 = $pdo->prepare($query4);
  $stmt4->execute([$userID]);
  $table_IDs = $stmt4->fetchAll();

  // delete all the slots of the sheet the user owned
  foreach ($table_IDs as $row) :
    $query2 = "DELETE from Slots where Sheet_ID = ?";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute(array($row['ID']));
  endforeach;

  // delete all sheets the user owned
  $query5 = "DELETE from Signup_sheets where Owner_ID = ?";
  $stmt5 = $pdo->prepare($query5);
  $stmt5->execute(array($userID));

  // finally, delete the user from the users table
  $query3 = "DELETE from users where ID = ?";
  $stmt3 = $pdo->prepare($query3);
  $stmt3->execute([$userID]);

  foreach($result as $pic){
    unlink($pic);
  }
  // send the user to logout.php which will destroy session and redirect to index.php
  header("Location:logout.php");
  exit();
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
  <script defer src="deleteModal.js"></script>
  <script defer src="./edit_account.js"></script>
</head>

<body>
  <header>
    <nav>
      <ul>
        <div>
          <img src="../img/logo.png" alt="Slot-it logo" width="60px" height="60px">
        </div>
        <div>
          <a href="../index.php">
            <li>Home</li>
          </a>
          <a href="../create.php">
            <li>Create</li>
          </a>
          <a href="mystuff.php">
            <li>View</li>
          </a>
          <a href="./edit_account.php">
            <li>My Account</li>
          </a>
          <!-- display the profile picture if it exists, otherwise display an icon -->
          <?php if ($picExists) : ?>
            <img src="<?= $profpic_url ?>">


          <?php else : ?>
            <i class="fa fa-user" aria-hidden="true"></i></li></a>
          <?php endif ?>
        </div>
      </ul>
    </nav>
  </header>

  <main>
    <section>
      <h2>Account Information</h2>
      <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post" novalidate autocomplete="false">
        <!-- All inputs are populated from the database -->
        <div>
          <input type="text" name="name" placeholder="John Smith" id="name" value="<?= $userInfo['fname'] ?>">
          <label for="name">Full Name</label>
          <span class="error <?= !isset($errors['name']) ? 'hidden' : ""; ?>">*Name was empty</span>
        </div>

        <div>
          <input type="text" name="username" id="username" placeholder="derekpope666" autocomplete="off" value="<?= $userInfo['username'] ?>">
          <label for="username">Username</label>
          <span class="error <?= !isset($errors['username']) ? 'hidden' : ""; ?>">*Username was empty</span>
        </div>

        <div>
          <input type="text" name="email" id="email" placeholder="derekpope666" autocomplete="off" value="<?= $userInfo['email'] ?>">
          <label for="email">Email</label>
          <span class="error <?= !isset($errors['email']) ? 'hidden' : ""; ?>">*Email Invalid</span>
        </div>
        <div>
          <input type="password" name="password" id="password" placeholder="inbaepn" autocomplete="off" value="">
          <label for="password">Password</label>
          <span class="error <?= !isset($errors['password']) ? 'hidden' : ""; ?>">
            *Invalid Password. Atleast 1 number, 1 special character, 1 uppercase letter and >=8</span>
          <span class="rating"></span>
        </div>
        <div>
          <input type="password" name="conpass" id="conpass" placeholder="inbaepn" autocomplete="off" value="">
          <label for="conpass">Confirm Password</label>
          <span class="error <?= !isset($errors['match']) ? 'hidden' : ""; ?>">*Passwords do not match</span>
        </div>

        <div>
          <a href="../index.html"><button type="button">Back</button></a>
          <a href=""><button type="submit" id="submit1" name="submit">Update</button></a>
        </div>
      </form>


    </section>

    <!-- Optional form to change profile picture -->
    <form id="profpic" action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post" novalidate autocomplete="false" enctype="multipart/form-data">
      <aside>
        <label id="special" class="profpic" id="profilepic" for="profilepic">Change Profile Picture<i class="far fa-user-circle"></i></label>
        <input class="profpic" type="file" id="profilepic" name="profilepic">
        <span class="error <?= !isset($errors['file']) ? 'hidden' : ""; ?>">*Invalid format/size. Size has to be less than 2MB</span>
        <div>
          <a href=""><button type="submit" id="submit2" name="submit2">Change</button></a>
        </div>
      </aside>
    </form>

  </main>

  <!-- Modal on Delete -->
  <div class="deletebutton">
    <button id="delete">Delete Account</button>
  </div>

  <!-- form for deleting the account -->
  <form id="delete" action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post" novalidate autocomplete="false" enctype="multipart/form-data">
    
  <!-- Modal Window on Delete -->
    <div id="ModalWindow" class="modal">
      <div class="content">
        <p>Are you sure you want to continue?</p>
        <div>
          <button type="submit" name="no" id="no">No</button>
          <button type="submit" name="yes" id="yes">Yes</button>
        </div>
      </div>
    </div>
  </form>
</body>

</html>