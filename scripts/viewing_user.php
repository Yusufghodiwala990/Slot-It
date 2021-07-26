 <?php 
include "library.php";
$pdo = connectDB(); // CONNECT TO DATABASE
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

$Sheet_ID = $_GET['SheetID']??null; // get the sheet id if exist, else assign null.
//selecting details of the specific signup sheet.
$query1 = "select StartDate,ID,Description,Title,Owner_ID from Signup_sheets where ID=?"; 
$stmt1 = $pdo->prepare($query1);
$stmt1->execute([$Sheet_ID]);
$result = $stmt1->fetch();

//if nothing gets selected, then the signup sheet does not exist.
if($result==false)
{
   echo "<h2>'Sorry! There is no such signup sheet.'</h2>";
}

//if result was returned
else{
//selecting the owner's name
$query2 = "select fname from users where ID=?"; 
$stmt2 = $pdo->prepare($query2);
$stmt2->execute([$result['Owner_ID']]);
$result2 = $stmt2->fetch();

//selecting the slots within the signup sheet
$query3 = "select StartTime,Guest_ID,user_ID,Slot_ID from Slots where Sheet_ID=?"; 
$stmt3 = $pdo->prepare($query3);
$stmt3->execute([$Sheet_ID]);
$list1 = $stmt3->fetchAll();
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
        <ul>
          <div>
            <img src="../img/logo.png" alt="Slot-it logo" width="60px" height="60px">
          </div>
          <div>
          
          <a href="../index.php"><li>Home</li></a>
          <a href="./search.php"><li>Search</li></a>
          
          <!-- if the user is logged in -->
          <?php if(isset($_SESSION['user_id'])):?>
            <!-- navigational bar links -->
            <a href="../create.php"><li>Create</li></a>
            <a href="./mystuff.php"><li>View</li></a>
            <a href="./edit_account.php"><li>My Account</li></a>
            <a href="./logout.php"><li>Logout</li></a>
            <!-- if a profile picture is uploaded -->
               <?php if($picExists):?> 
              <img src="<?=$profpic_url?>" alt="Profile picture">
            
            <!-- if a profile picture is not uploaded -->
            <?php else:?>
            <i class="fa fa-user" aria-hidden="true"></i>
            <?php endif?>
            
          <?php endif ?>

          <!-- if the user is not logged in -->
          <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="./registration.php"><li>Sign-up<i class="fa fa-sign-in" aria-hidden="true"></i></li></a>
            <a href="./login.php"><li>Login<i class="fa fa-sign-in" aria-hidden="true"></i></li></a>
          <?php endif ?>

        </div>
        </ul>
      </nav>      

</header>

<main>
<section>
<!-- if the details of the signup sheet are not returned -->
<?php if($result==false):?>
<p> Title: *** </p>
<p> Owner: *** </p>

<!-- if the details of the sheet are returned -->
<?php else :?>
<p> Title: <?=$result['Title']?></p>
<p> Owner: <?=$result2['fname']?></p> 
<p> Description: <?=$result['Description']?></p> 

<?php endif?>

<!-- form sending the post array to slot_in.php -->
<form action="./slot_in.php" method="post" novalidate >
    <table>
          <thead>
            <tr>
              <th scope="col">Date</th>
              <th scope="col">Time</th>
            </tr>
          </thead>
          <tbody>
            <!-- If the signup sheet doesnt exist or if no slots existed, show an empty table -->
          <?php if($result==false||$list1==null) : ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    
                    <!-- if the signup exists and has slots  -->
                    <?php else : ?>
                      <!-- foreach slots,show the details and a button for signing up -->
                      <?php foreach ($list1 as $row):?>
                    <tr>

              <td><?=$result['StartDate']?></td>
              <td><?=$row['StartTime']?></td>
              <!-- if noone has signed up for the slot  -->
              <?php if(!isset($row['user_ID'])&&!isset($row['Guest_ID'])): ?>
                <!-- if a user is logged in -->
                <?php if(isset($_SESSION['user_id'])):?>
                  <!-- passing the sheetID and slotID with the value attribute -->
                  <td><button name="submit" type="submit" value="<?=$result['ID'] . "-" . $row['Slot_ID']?>">SLOT ME IN</button></td></tr>
                  <!-- if a guest is logged in -->
             <?php elseif(isset($_SESSION['Guest_ID'])): ?>   
              <td><a href="./slot_in.php?SheetID=<?php echo $result['ID']?>& Slot_ID=<?php echo $row['Slot_ID']?>">SLOT ME IN</a></td></tr>
              <!-- if the user is not logged in at all -->
              <?php else: ?>   
                <td><a href="./login.php?SheetID=<?php echo $result['ID']?>& Slot_ID=<?php echo $row['Slot_ID']?>">SLOT ME IN</a></td></tr>
              <?php endif; ?>
              <!-- if the user is logged into their account but not as a guest -->
              <?php  elseif(!isset($row['Guest_ID'])): 
                $query4 = "select fname from users where ID=?"; //selecting the user's name
                $stmt4 = $pdo->prepare($query4);
                $stmt4->execute([$row['user_ID']]);
                $result1 = $stmt4->fetch(); 
                ?>
                <td><?=$result1['fname']?></td></tr>
                <!-- if the user is logged in as guest -->
              <?php else: ?>
               <?php $query4 = "select Name from Guest_users where ID=?"; // selecting the guest's name
                $stmt4 = $pdo->prepare($query4);
                $stmt4->execute([$row['Guest_ID']]);
                $result2 = $stmt4->fetch();
              ?>
              <td><?=$result2['Name']?></td></tr>
              <?php endif ?>
              
                <?php endforeach; endif?>

          </tbody>
        </table>
</form>
</section>
</main>
<footer>
      <ul>
        <li><a href="../index.html">Home</a></li>
        <li><a href="mailto:slot-it@gmail.com">Contact</a></li>
        <li><i class="fas fa-phone-square-alt"></i> : +1(705)-123-1234</li>
        <li> <img src="../img/logo.png" alt="Slot-it logo"></li>
      </ul>
      <p>&copy; 2021 - Slot-It - All rights reserved</p>
      
    </footer>
  </body>
</html>
