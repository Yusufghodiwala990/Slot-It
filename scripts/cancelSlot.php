<?php
include "library.php";
session_start();
if(isset($_SESSION['user_id'])){
  $filename = "profile-pic" . $_SESSION['user_id'];
  $profpicpath = "/home/yusufghodiwala/public_html/www_data/3420project_images/";
 
   $result = glob ($profpicpath . $filename . ".*" );
   
   if(empty($result))
     $picExists = false;
   else{
     $picExists = true;
     $profpic_url = "https://loki.trentu.ca/~yusufghodiwala/www_data/3420project_images/";
     $url = explode("/",$result[sizeof($result) - 1]);
     $profpic_url = $profpic_url . $url[sizeof($url)-1]; 
   }
  
}
$pdo = connectDB();
if(!isset($_SESSION['user_id']))
{
  header("location:login.php");
  exit;
}
$SlotID = $_GET['SlotID'];

$query1 = "select * from Slots where Slot_ID = ?"; 
$stmt= $pdo->prepare($query1);
$stmt->execute([$SlotID]);
$result = $stmt->fetch();

$Sheet_ID=$result['Sheet_ID'];

$query2 = "select Title,StartDate from Signup_sheets where ID = ?"; 
$stmt2 = $pdo->prepare($query2);
$stmt2->execute([$Sheet_ID]);
$Title = $stmt2->fetch();

$userID =$result['User_ID'];

$query3 = "select fname from users where ID= ?"; 
$stmt3 = $pdo->prepare($query3);
$stmt3->execute([$userID]);
$slotUsr = $stmt3->fetch();


if(isset($_POST['Cancel']))
{
  header("Location: mystuff.php");
}

if(isset($_POST['deleteSlot']))
{
  $query4 = "UPDATE Signup_sheets SET No_of_Signups=No_of_Signups-1 where ID=?"; 
  $stmt4 = $pdo->prepare($query4);
  $stmt4->execute([$Sheet_ID]);

  $query5 = "UPDATE Slots SET Slots.Guest_ID=NULL, Slots.User_ID=NULL WHERE Slots.Slot_ID=?"; 
  $stmt5 = $pdo->prepare($query5);
  $stmt5->execute([$SlotID]);

  header("refresh:0.5; url=mystuff.php");


}

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
            <a href="../create.php"><li>Create</li></a> 
            <a href="./mystuff.php"><li>View</li></a>
            <a href="./edit_account.php"><li>My Account</li></a>
               <?php if($picExists):?>
              <img src="<?=$profpic_url?>">
            
            
            <?php else:?>
            <i class="fa fa-user" aria-hidden="true"></i></li></a>
            <?php endif?>          </div>
          </ul>
        </nav>      
      </header>

      <main>
        <section>
          <h1>You are about to cancel the following slot :</h1>
          <table>
            <thead>
              <tr>
                <th scope="col">Title</th>
                <th scope="col">Date</th>
                <th scope="col">Time</th>
                <th scope="col">Name</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $Title['Title']?></td>
                <td><?php echo $Title['StartDate']?></td>
                <td><?php echo $result['StartTime']?></td>
                <td><?php echo $slotUsr['fname']?></td>
              </tr>
            </tbody>
          </table>
          <p>Are you sure you want to continue?</p>
          <div>
            <a href="slotCancelled.php?SlotID=<?php echo $SlotID?>">Yes</a>
            <a href="mystuff.php">No</a>
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