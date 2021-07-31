<?php
include "library.php";
session_start();
if(isset($_SESSION['user_id'])){
  // fetching the profile picture if available.

/* FORMAT OF THE PROFILE PICTURE STORED ON LOKI : profile-pic{ID}.extension stored
   in 3420project_images folder in www_data on yusufghodiwala account  */
  $filename = "profile-pic" . $_SESSION['user_id'];
  $profpicpath = "/home/yusufghodiwala/public_html/www_data/3420project_images/";
 
  // glob function to run a search with a wildcard to return all matching filenames.
   $result = glob ($profpicpath . $filename . ".*" );

    // if array is empty, no match, else build URL.
   if(empty($result))
     $picExists = false;
   else{
     $picExists = true;
     $profpic_url = "https://loki.trentu.ca/~yusufghodiwala/www_data/3420project_images/";
     $url = explode("/",$result[sizeof($result) - 1]); // get the latest pic the user uploaded
     $profpic_url = $profpic_url . $url[sizeof($url)-1];  // build URL
   }
  
}
$pdo = connectDB(); //Connect to the database
//Redirect if not logged in.
if(!isset($_SESSION['user_id']))
{
  header("location:login.php");
  exit();
}
$SlotID = $_GET['SlotID']; //Get the slotID for the slot to be cancelled

//Selecting the slot details
$query1 = "select * from Slots where Slot_ID = ?"; 
$stmt= $pdo->prepare($query1);
$stmt->execute([$SlotID]);
$result = $stmt->fetch();

$Sheet_ID=$result['Sheet_ID']; //storing the details returned

//selecting the title and startdate of the target sheet
$query2 = "select Title,StartDate from Signup_sheets where ID = ?"; 
$stmt2 = $pdo->prepare($query2);
$stmt2->execute([$Sheet_ID]);
$Title = $stmt2->fetch();

$userID =$result['User_ID'];

//Selecting the users name
$query3 = "select fname from users where ID= ?"; 
$stmt3 = $pdo->prepare($query3);
$stmt3->execute([$userID]);
$slotUsr = $stmt3->fetch();

?>

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
          <!-- navigational bar -->
          <ul>
            <div>
            <a href="../index.php"><li>Home</li></a>
            <a href="../create.php"><li>Create</li></a> 
            <a href="./mystuff.php"><li>View</li></a>
            <a href="./edit_account.php"><li>My Account</li></a>
               <!-- if a profile picture is uploaded -->
               <?php if($picExists):?>
              <img src="<?=$profpic_url?>"alt="Profile picture">
            
            <!-- if a profile picture is not uploaded, show the user icon -->
            <?php else:?>
            <i class="fa fa-user" aria-hidden="true"></i>
            <?php endif?>         
           </div>
          </ul>
        </nav>      
      </header>

      <main>
        <!-- Details of slot being cancelled  -->
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
            <!-- Alerting the user -->
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
</html>