<?php 
include "library.php";
session_start();
$pdo = connectDB();
if(!isset($_SESSION['user_id']))
{
  header("location:login.php");
  exit;
}
$user=$_SESSION['user_id'];   //need this from yusuf's page
$Sheet_ID = $_GET['SheetID'];

$query1 = "select ID,Description,Title from Signup_sheets where ID=?&& Owner_ID=?"; 
$stmt1 = $pdo->prepare($query1);
$stmt1->execute([$Sheet_ID,$user]);
$result = $stmt1->fetch();
if($result==false)
{
   echo "<h2>Bad Request </h2>";
   die;
}
$Sheet_id = $result['ID'];

$query2 = "select Scheduled_slots,Guest_ID,user_ID from Slots where Sheet_ID=?"; 
$stmt2 = $pdo->prepare($query2);
$stmt2->execute([$Sheet_id]);
$list1 = $stmt2->fetchAll();

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
            <li><img src="../img/logo.png" alt="Slot-it logo"></li>
          </div>
          <div>
          <a href="../index.html"><li>Home</li></a>
          <a href="../create.html"><li>Create</li></a>          
          <a href="./mystuff.php"><li>View</li></a>
          <a href="derek.html"><li>My Account<i class="fa fa-user" aria-hidden="true"></i></li></a>
        </div>
        </ul>
      </nav>
</header>

<main>
<p> Title: <?=$result['Title']?></p>
    <table>
          <thead>
            <tr>
              <th scope="col">Purpose</th>
              <th scope="col">Date</th>
              <th scope="col">Time</th>
              <th scope="col">Name</th>
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
                <?php if($list1!=null) : foreach ($list1 as $row):?>
                    <tr>
              <td><?=$result['Description']?></td>
              <td><?=$row['Scheduled_slots']?></td>
              <td><?=$row['Scheduled_slots']?></td>

              <?php if(!isset($row['user_ID'])&&!isset($row['Guest_ID'])): ?>
                <td></td>
              <?php  elseif(!isset($row['Guest_ID'])): 
                $query3 = "select fname from users where ID=?"; 
                $stmt3 = $pdo->prepare($query3);
                $stmt3->execute([$row['user_ID']]);

                $result1 = $stmt3->fetch(); 

?>
              <td><?=$result1['fname']?></td>

              <?php else: ?>
               <?php $query4 = "select Name from Guest_users where ID=?"; 
                $stmt4 = $pdo->prepare($query4);
                $stmt4->execute([$row['Guest_ID']]);
                $result2 = $stmt4->fetch();
?>
              <td><?=$result2['Name']?></td>
              <?php endif ?>
            </tr>
              <?php endforeach; endif?>
          </tbody>
        </table>
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