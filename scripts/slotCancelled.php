<?php
include "library.php";
session_start();
if(isset($_SESSION['user_id'])){
  $profpicpath = "/home/yusufghodiwala/public_html/www_data/3420project_images/profile-pic" . $_SESSION['user_id'] . ".jpg";
  $profpic_url = "https://loki.trentu.ca/~yusufghodiwala/www_data/3420project_images/profile-pic" . $_SESSION['user_id'] . ".jpg";
  
  }
$pdo = connectDB();
if(!isset($_SESSION['user_id']))
{
  header("location:login.php");
  exit;
}
$SlotID = $_GET['SlotID'];

$query1 = "select Sheet_ID from Slots where Slot_ID = ?"; 
$stmt= $pdo->prepare($query1);
$stmt->execute([$SlotID]);
$result = $stmt->fetch();

$Sheet_ID=$result['Sheet_ID'];

  $query4 = "UPDATE Signup_sheets SET No_of_Signups=No_of_Signups-1 where ID=?"; 
  $stmt4 = $pdo->prepare($query4);
  $stmt4->execute([$Sheet_ID]);

  $query5 = "UPDATE Slots SET User_ID=NULL where Slot_ID = ?";  
  $stmt5 = $pdo->prepare($query5);
  $stmt5->execute([$SlotID]);


?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Slot-It</title>
    <link rel="stylesheet" href="../styles/cancelslot.css"/>
    <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
  </head>

<body>

    <header>
      <nav>
    <ul>
   
          <div>
          
          <a href="../index.php"><li>Home</li></a>
          
          <?php if(isset($_SESSION['user_id'])):?>
            <a href="../create.php"><li>Create</li></a>
            <a href="./mystuff.php"><li>Search</li></a>
            <a href="./mystuff.php"><li>View</li></a>
            <a href="./edit_account.php"><li>My Account</li></a>
               <?php if(file_exists($profpicpath)):?>
              <img src="<?=$profpic_url?>">
            
            
            <?php else:?>
            <i class="fa fa-user" aria-hidden="true"></i></li></a>
            <?php endif?>
            
          <?php endif ?>

        </div>
        </ul>
      </nav>
      </header>

      <main>
        <section>
          <h1>Slot Cancelled.</h1>
        <div>
            <a href="mystuff.php">Continue</a>
        </div>
        </section>
      </main>

      <footer>
        <ul>
          <li><a href="../index.php">Home</a></li>
          <li><a href="mailto:slot-it@gmail.com">Contact</a></li>
          <li><i class="fas fa-phone-square-alt"></i> : +1(705)-123-1234</li>
          <li> <img src="../img/logo.png" alt="Slot-it logo"></li>
        </ul>
        <p>&copy; 2021 - Slot-It - All rights reserved</p>
        
      </footer>
      
</body>