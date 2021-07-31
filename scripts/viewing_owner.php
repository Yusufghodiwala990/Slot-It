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
    $profpic_url = $profpic_url . $url[sizeof($url)-1]; // build URL
  }
  
  }
$pdo = connectDB();  //Connect to database
//Redirect if not logged in.
if(!isset($_SESSION['user_id']))
{
  header("location:login.php");
  exit();
}
$user=$_SESSION['user_id']; 
$Sheet_ID = $_GET['SheetID'] ?? null;   // get the sheet id if exist, else assign null.

//selecting details of the specific signup sheet owned.
$query1 = "select ID,Description,Title,StartDate from Signup_sheets where ID=? && Owner_ID=?"; 
$stmt1 = $pdo->prepare($query1);
$stmt1->execute([$Sheet_ID,$user]);
$result = $stmt1->fetch();

//if no result is returned
if($result==false)
{
   echo "<h2>'Sorry! You are not the owner of the sign-up sheet.'</h2>";
}
//if the sheet details are returned
else{
$Sheet_id = $result['ID']; //storing the sheetID
//selecting the slots of the sheet.
$query2 = "select StartTime,Guest_ID,user_ID from Slots where Sheet_ID=?"; 
$stmt2 = $pdo->prepare($query2);
$stmt2->execute([$Sheet_id]);
$list1 = $stmt2->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Viewing Sign-ups</title>
  <link rel="stylesheet" href="../styles/viewing.css" />
  <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
</head>

<body>

<header>
<nav>
            <!-- navigational bar -->
        <ul>
          <div>
            <img src="../img/logo.png" alt="Slot-it logo" width="60px" height="60px">
          </div>
          <div>
            <a href="../index.php"><li>Home</li></a>
            <a href="./search.php"><li>Search</li></a>
            <a href="../create.php"><li>Create</li></a>
            <a href="./mystuff.php"><li>View</li></a>
            <a href="./edit_account.php"><li>My Account</li></a>
            <a href="./logout.php"><li>Logout</li></a>
            <!-- if a profile picture is uploaded -->
               <?php if($picExists):?>
              <img src="<?=$profpic_url?>" alt="Profile picture">
            
            <!-- if a profile picture is not uploaded, show the user icon -->
            <?php else:?>
            <i class="fa fa-user" aria-hidden="true"></i>
            <?php endif?>
            
        <ul>
      </nav>
</header>

<main>
<section>
<p> Title: <?=$result['Title']?? '*****' ?></p>
    <table>
      <!-- Table for the signup sheet details -->
          <thead>
            <tr>
              <th scope="col">Purpose</th>
              <th scope="col">Date</th>
              <th scope="col">Time</th>
              <th scope="col">Name</th>
            </tr>
          </thead>
          <tbody>
            <!-- Showing an empty table if the table details are not returned -->
          <?php if($result==false) : ?>
            <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
          <?php else: ?>
            <!-- if no slots exist in the signup sheet -->
          <?php if($list1==null) : ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                    <!-- foreach signup if the slots exist, show all the details of the sheet -->
                <?php if($list1!=null) : foreach ($list1 as $row):?>
                    <tr>
              <td><?=$result['Description']?></td>
              <td><?=$result['StartDate']?></td>
              <td><?=$row['StartTime']?></td>
              <!-- if noone has signed up for the slot, print nothing -->
              <?php if(!isset($row['user_ID'])&&!isset($row['Guest_ID'])): ?>
                <td></td> 
              <!-- if the user is logged into their account but not as a guest -->
              <?php  elseif(!isset($row['Guest_ID'])): 
                $query3 = "select fname from users where ID=?"; //select the name of the user
                $stmt3 = $pdo->prepare($query3);
                $stmt3->execute([$row['user_ID']]);
                $result1 = $stmt3->fetch(); ?>

              <td><?=$result1['fname']?></td>
                  <!-- if a guest is logged in -->
              <?php else: ?>
               <?php $query4 = "select Name from Guest_users where ID=?"; //select the name of the guest
                $stmt4 = $pdo->prepare($query4);
                $stmt4->execute([$row['Guest_ID']]);
                $result2 = $stmt4->fetch();
?>
              <td><?=$result2['Name']?></td>
              <?php endif ?>
            </tr>
              <?php endforeach; endif; endif;?>
          </tbody>
        </table>
</main>
</section>
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
