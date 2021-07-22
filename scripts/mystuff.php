<?php

// $errors = array();   //declare empty array to add errors too
session_start();
if(!isset($_SESSION['user_id']))
{
  header("location:login.php");
  exit;
}
$user=$_SESSION['user_id'];   
include "library.php";
// CONNECT TO DATABASE
$pdo = connectDB();
$query = "select * from Signup_sheets where Owner_ID=?"; 
$stmt = $pdo->prepare($query);
$stmt->execute([$user]);
$list1 = $stmt->fetchAll();

$query1 = "select Slots.Slot_ID,Signup_sheets.Title, Slots.Scheduled_slots from Signup_sheets INNER JOIN Slots ON Slots.Sheet_ID=Signup_sheets.ID where User_ID=?"; 
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
        <nav>
            <ul>
                <div>
                    <img src="../img/logo.png" alt="Slot-it logo" width="60px" height="60px">
                </div>
                <div>
                    <a href="./search.php">
                        <li>Search</li>
                    </a>
                    <a href="../create.php">
                        <li>Create</li>
                    </a>

                    <a href="./edit_account.php">
                        <li>My Account<i class="fa fa-user" aria-hidden="true"></i></li>
                    </a>
                </div>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <!-- add dialog box when slot deleted -->
            <h2>My Sign-up sheets</h2>

            <table>
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Number of slots</th>
                        <th scope="col">Total sign-ups</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($list1==null) : ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                <?php if($list1!=null) : foreach ($list1 as $row): ?>
                    <tr>
                        <td><?=$row['Title']?></td>
                        <td><?=$row['No_of_slots']?></td>
                        <td><?=$row['No_of_signups']?></td>
                       
                        <td> <a href="./viewing_owner.php?SheetID=<?php echo $row['ID']?> " ><i class="fas fa-info-circle">Details</i></a></td>
                        <td> <a href="./edit_sheet.php?SheetID=<?php echo $row['ID']?>"><i class="fas fa-edit"> Edit</i></a></td>
                        <td> <a href="./edit_sheet.php?SheetID=<?php echo $row['ID']?>"><i class="fas fa-trash"> Delete</i></a></td>
                        <td> <a href="./copying.php?SheetID=<?php echo $row['ID']?>"><i class="fas fa-copy"> Copy</i></a></td>
                        <td> <button class="button" value="/viewing_user.php?SheetID=<?php echo $row['ID']?> "><i class="fa fa-link" >CopyURL</i></button></td>
                    </tr>
                    <?php endforeach; endif; ?>

                </tbody>
            </table>

            <h2>Slots I have signed-up for</h2>

            <table>
                <?php if($list2==null)
                echo "<h3> You don't have any slots signed up. </h3>"
                ?>
                <?php if($list2 !=null):?>
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>    
                <?php foreach ($list2 as $row): ?>

                    <tr>
                        <td><?=$row['Title']?></td>
                        <td><?=$row['Scheduled_slots']?></td>
                        <td><?=$row['Scheduled_slots']?></td>
                        <td> <a href="./cancelSlot.php?SlotID=<?php echo $row['Slot_ID']?>"><i class="fas fa-window-close"> Cancel</i></a></td>
                    </tr>
                    <?php endforeach; endif;?>

                </tbody>
            </table>
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