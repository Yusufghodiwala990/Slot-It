<?php

session_start();

if(!isset($_SESSION['user_id']))
{
  header("location:login.php");
  exit;
}

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
   $url = explode("/",$result[sizeof($result) - 1]);  // get the latest pic the user uploaded
   $profpic_url = $profpic_url . $url[sizeof($url)-1]; // build URL
 }


$user=$_SESSION['user_id'];   
include "library.php";
// CONNECT TO DATABASE
$pdo = connectDB();
$query = "select * from Signup_sheets where Owner_ID=?"; //selecting sheets owned by the user
$stmt = $pdo->prepare($query);
$stmt->execute([$user]);
$list1 = $stmt->fetchAll();

//selecting data of slots that the user has signed up for
$query1 = "select Slots.Slot_ID,Signup_sheets.Title, Slots.StartTime, `Signup_sheets`.`StartDate` from Signup_sheets INNER JOIN Slots ON Slots.Sheet_ID=Signup_sheets.ID where User_ID=?"; 
$stmt1 = $pdo->prepare($query1);
$stmt1->execute([$user]);
$list2 = $stmt1->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My-View</title>
    <link rel="stylesheet" href="../styles/mystuff.css" />
    <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
    <script defer src="copyURL.js"></script> 


</head>

<body>
    <header>
    <!-- navigational bar -->
        <nav>
            <ul>
                <div>
                    <img src="../img/logo.png" alt="Slot-it logo" width="60px" height="60px">
                </div>
                <div>
                <!-- navigate to the search page -->
                    <a href="./search.php">
                        <li>Search</li>
                    </a>
                    <!-- navigate to creaing a sheet page -->
                    <a href="../create.php"> 
                        <li>Create</li>
                    </a>
                    <!-- navigate to the edit account page -->
                    <a href="./edit_account.php"><li>My Account</li></a> 
                    <a href="./logout.php"><li>Logout</li></a> 
                    <!-- if a picture is uploaded -->
               <?php if($picExists):?>
              <img src="<?=$profpic_url?>"alt="Profile picture">
              <!-- if a picture is not uploaded, show th user icon -->
              <?php else:?>
            <i class="fa fa-user" aria-hidden="true"></i>
            <?php endif?>

                </div>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>My Sign-up sheets</h2>

            <table>
                <thead>
                    <tr>
                    <!-- column headers -->
                        <th scope="col">Title</th>
                        <th scope="col">Number of slots</th>
                        <th scope="col">Total sign-ups</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- show an empty table if sheets are owned by the user -->
                <?php if($list1==null) : ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                    <!-- foreach signup-sheet owned by the user, show the details with icon-based links -->
                <?php if($list1!=null) : foreach ($list1 as $row): ?>
                    <tr>
                        <td><?=$row['Title']?></td>
                        <td><?=$row['No_of_slots']?></td>
                        <td><?=$row['No_of_signups']?></td>
                       
                        <td> <a href="./viewing_owner.php?SheetID=<?php echo $row['ID']?> "><i class="fas fa-info-circle"></i>Details</a></td>
                        <td> <a href="./edit_sheet.php?SheetID=<?php echo $row['ID']?>"><i class="fas fa-edit"></i>Edit</a></td>
                        <td> <a href="./edit_sheet.php?SheetID=<?php echo $row['ID']?>"><i class="fas fa-trash"></i>Delete</a></td>
                        <td> <a href="./copying.php?SheetID=<?php echo $row['ID']?>"><i class="fas fa-copy"> </i>Copy</a></td>
                        <td> <button class="button" value="/viewing_user.php?SheetID=<?php echo $row['ID']?> "><i class="fa fa-link" ></i>CopyURL</button></td>
                    </tr>
                    <?php endforeach; endif; ?>

                </tbody>
            </table>

            <h2>Slots I have signed-up for</h2>

            <table>
                <?php if($list2==null) //if the user has not signed up for slots...
                echo "<h3> You don't have any slots signed up. </h3>"
                ?>
                <!-- if user has signed up for slots -->
                <?php if($list2 !=null):?> 
                <thead>
                    <tr>
                        <!-- column headers -->
                        <th scope="col">Title</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>    
                    <!--foreach slot, show the details with an option to cancel the slot-->
                <?php foreach ($list2 as $row): ?>

                    <tr>
                        <td><?=$row['Title']?></td>
                        <td><?=$row['StartDate']?></td>
                        <td><?=$row['StartTime']?></td>
                        <td> <a href="./cancelSlot.php?SlotID=<?php echo $row['Slot_ID']?>"><i class="fas fa-window-close"> Cancel</i></a></td>
                    </tr>
                    <?php endforeach; endif;?>

                </tbody>
            </table>
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