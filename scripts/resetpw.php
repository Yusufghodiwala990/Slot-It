<?php

/* Explaination of the resetpw mechanism retreived 
     from https://www.youtube.com/watch?v=wUkKCMEYj9M */
include 'library.php';
$pdo = connectDB();
$email  = $_POST['email'] ?? null;
$sent = false;
if(isset($_POST['submit'])){
    
  // generate a hex selector an a bytes token
    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $link = "https://loki.trentu.ca/~yusufghodiwala/3420/project/scripts/createNewPw.php?selector=" . $selector . "&validator=" . bin2hex($token);
    

    // 30 mins
    $linkExpiry = date("U") + 1800; 

    // delete previous password requests before initializing a new one
    $query = "DELETE FROM `reset_password` where resetEmail=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);

    // hashing the bytes token
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
     
    // inserting the token with the selector into the reset_password table
    $query = "INSERT INTO `reset_password` (resetEmail, resetSelector, resetToken, expiryDate) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email,$selector,$hashedToken,$linkExpiry]);
    

    // Sending Email example grabbed from Blackboard
      require_once "Mail.php";  //this includes the pear SMTP mail library
      $from = "Slot-It <yusufghodiwala@trentu.ca>"; 
      $to = $email;  //put user's email here
      $subject = "Reset your password for Slot-it";
      $message = '<p>Request for Passord Reset was received. Click on the link below
        to Reset your password. If you did not request for one, ignore this email.</p>';
      $message .= '<p>Password Link: <br>';
      $message .= '<a href="' . $link . '">' . $link . '</a></p>';

      $host = "smtp.trentu.ca";
      $headers = array(
        'From' => $from,
        'To' => $to,
        'Subject' => $subject, 'Content-type' => "text/html"
      );
      $smtp = Mail::factory(
          'smtp',
          array('host' => $host)
        );

      $mail = $smtp->send($to, $headers, $message);
      if (PEAR::isError($mail)) {
        echo ("<p>" . $mail->getMessage() . "</p>");
      }

    $sent = true;   
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="google-signin-client_id" content="661589590202-us1vtruq46mvta14t83d4fekdmtrp4mf.apps.googleusercontent.com">
  <title>Reset Password</title>
  <link rel="stylesheet" href="../styles/login.css" />
  <link rel="stylesheet" href="../styles/errors.css" />
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
  <script src="./googleSignIn.js" async defer></script>
</head>

<body>
  <main>

    <h1>Reset</h1>
    <p style="margin-bottom: 1.5em;">You'll be sent a link to your registered email, follow instructions in the mail to reset your password</p>
    
    <!-- Form to get the email of the user -->
    <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="false">
      <div>
        <input type="text" name="email" id="email" placeholder="derekpope666"  autocomplete="off">
        <label for="email">Email</label>
        <span class="error <?= !isset($errors['username']) ? 'hidden' : "errors"; ?>">*Username was empty</span>
      </div>

      <div id="links-login">
        <a href="../index.html"><button type="button">Back</button></a>
        <a href=""><button type="submit" name="submit">Reset</button></a>
      </div>
      
      <?php
    
    // if email was sent
    if($sent):?>
    <span style="color:#FF007F">* Please check your email</span>
    <?php endif?>
    

    </form>

   
  </main>
  <footer>
    <p>&copy; 2021 - Slot-It</p>
  </footer>

</body>

</html>