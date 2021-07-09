<?php


include 'library.php';
$pdo = connectDB();
$email  = $_POST['email'] ?? null;
$sent = false;
if(isset($_POST['submit'])){
    



    $selector = bin2hex(random_bytes(8));
    $token = random_bytes(32);

    $link = "https://loki.trentu.ca/~yusufghodiwala/3420/project/scripts/createNewPw.php?selector=" . $selector . "&validator=" . bin2hex($token);
    

    // 30 mins
    $linkExpiry = date("U") + 1800; 

    $query = "DELETE FROM `reset_password` where resetEmail=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);

    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
     
    $query = "INSERT INTO `reset_password` (resetEmail, resetSelector, resetToken, expiryDate) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email,$selector,$hashedToken,$linkExpiry]);
    
    
      require_once "Mail.php";  //this includes the pear SMTP mail library
      $from = "Slot-It <yusufghodiwala@trentu.ca>"; // change this.
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
      } else {
        echo ("<p>Message successfully sent!</p>");
      }

            
    // $emailTo = $email;

    // $subject = "Reset your password for Slot-it";
    // $message = '<p>Request for Passord Reset was received. Click on the link below
    // to Reset your password. If you did not request for one, ignore this email.</p>';
    // $message .= '<p>Password Link: <br>';
    // $message .= '<a href="' . $link . '">' . $link . '</a></p>';

    // $headers = "From Slot-it <yusufghodiwala@trentu.ca>\r\n"; 
    // $headers .= "Content-type: text/html";

    // mail($emailTo, $subject, $message, $headers);
    $sent = true;
    //header("Refresh: 3; url=https://loki.trentu.ca/~yusufghodiwala/3420/project/scripts/resetpw.php?reset=success");


    



   
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
    <p>You'll be sent a link to your registered email, follow instructions in the mail to reset your password</p>
    <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="false">
      <div>
        <input type="text" name="email" id="email" placeholder="derekpope666"  autocomplete="off">
        <label for="email">Email</label>
        <span class="error <?= !isset($errors['username']) ? 'hidden' : "errors"; ?>">*Username was empty</span>
      </div>

      <div id="links-login">
        <a href="../index.html"><button type="button">Back</button></a>
        <a href=""><button type="submit" name="submit">Reset</button></a>
      
      
    </form>

    <?php
    
   if($sent){
       echo '<h2>Please check your email</h2>';
   }
    
    ?>
  </main>
  <footer>
    <p>&copy; 2021 - Slot-It</p>
  </footer>

</body>

</html>